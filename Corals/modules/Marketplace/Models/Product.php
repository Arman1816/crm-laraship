<?php

namespace Corals\Modules\Marketplace\Models;

use Bkwld\Cloner\Cloneable;
use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Search\{Indexable, IndexedRecord};
use Corals\Foundation\Traits\GatewayStatusTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\CMS\Models\Content;
use Corals\Modules\Marketplace\{Facades\Marketplace, Traits\DownloadableModel};
use Corals\Modules\Payment\Common\{Models\TaxClass};
use Corals\Modules\Payment\Facades\Payments;
use Corals\Modules\Utility\Traits\{Comment\ModelHasComments, Gallery\ModelHasGallery, Wishlist\Wishlistable};
use Corals\Modules\Utility\Traits\Rating\ReviewRateable as ReviewRateableTrait;
use Corals\Settings\Facades\Settings;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Filesystem;
use Spatie\TemporaryDirectory\TemporaryDirectory;


class Product extends BaseModel implements HasMedia
{
    use Indexable, Sluggable, PresentableTrait, LogsActivity, ModelHasGallery,
        InteractsWithMedia, GatewayStatusTrait, DownloadableModel, ReviewRateableTrait, Wishlistable, ModelHasComments, Cloneable;

    /**
     * @var string[]
     */
    protected $cloneable_relations = [
        'sku',
        'attributes',
        'attributeSets',
        'options',
        'categories',
        'tags',
        'tax_classes'
    ];


    public $galleryMediaCollection = 'marketplace-product-gallery';
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.product';

    protected $casts = [
        'properties' => 'array',
        'shipping' => 'array',
        'is_featured' => 'boolean',
        'classification_price' => 'array',
        'price_per_quantity' => 'json',
        'offers' => 'json'
    ];

    protected $guarded = [];
    protected $indexContentColumns = ['description', 'caption'];
    protected $indexTitleColumns = [
        'brand.name',
        'store.name',
        'name',
        'skus.code',
        'tags.name',
        'tags.slug',
        'categories.name'
    ];

    protected $table = 'marketplace_products';


    protected static $logAttributes = ['name', 'description', 'caption', 'properties'];

    protected static function boot()
    {
        parent::boot();
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function hasProperty($key)
    {
        if (!empty($this->properties) && isset($this->properties[$key])) {
            return true;
        }

        return false;
    }

    public function getDisplayReference()
    {
        return $this->name;
    }


    public function scopeMyProducts($query)
    {
        return $query->whereIn('store_id', user()->id);
    }

    public function getNonFeaturedImageAttribute()
    {
        $image = asset(config($this->config . '.default_image'));

        $gallery = $this->getMedia($this->galleryMediaCollection);

        foreach ($gallery as $item) {
            if (!$item->hasCustomProperty('featured')) {
                $image = $item->getFullUrl();
                break;
            }
        }

        return $image;
    }

    public function sku()
    {
        return $this->hasMany(SKU::class);
    }

    public function AggregatedRatingParentModel()
    {
        return $this->store;
    }


    public function getDiscountAttribute()
    {
        $type = $this->attributes['type'] ?? null;

        $discount = 0;

        if ($type === 'simple') {
            $sku = $this->sku->first();
            $discount = optional($sku)->discount;
        }

        return $discount;
    }

    public function getRegularPriceAttribute()
    {
        $type = $this->attributes['type'] ?? null;

        $regularPrice = 0;

        if ($type === 'simple') {
            $sku = $this->sku->first();
            $regularPrice = optional($sku)->regular_price;
        }

        return $regularPrice;
    }

    public function getSystemPriceAttribute()
    {
        $type = $this->attributes['type'] ?? null;

        $price = '-';

        if ($type === 'simple') {
            $sku = $this->sku->first();
            $price = optional($sku)->price;
        } elseif ($type === 'variable') {
            $price = $this->sku->min('price');
        }

        if (empty($price)) {
            $price = '-';
        }

        if ($price != '-') {
            $price = \Payments::admin_currency($price);
        }

        if ($type === 'variable' && $price != '-') {
            $price = '<small style="font-size: 9px">' . trans('Marketplace::attributes.product.starts_at') . ' </small>' . $price;
        }

        if ($price === '0') {
            $price = trans('Marketplace::attributes.product.free');
        }

        if ($price != '-' && $this->isTaxIncluded()) {
            $price .= ' <small style="font-size: 9px">' . trans('Marketplace::attributes.product.tax_included') . ' </small>';
        }

        return $price;
    }

    public function getTax($address = [])
    {
        $taxes = Payments::calculateTax($this, $address);

        $tax_rate = 0;

        foreach ($taxes as $tax) {
            $tax_rate += $tax['rate'];
        }

        return $tax_rate;
    }

    public function isTaxIncluded()
    {
        return Settings::get('marketplace_tax_tax_included_in_price') && $this->tax_classes()->exists();
    }

    public function getPriceAttribute()
    {
        $type = $this->attributes['type'] ?? null;

        $price = '-';

        if ($type === 'simple') {
            $sku = $this->sku->first();
            $price = optional($sku)->price;
        } elseif ($type === 'variable') {
            $price = $this->sku->min('price');
        }

        if (empty($price)) {
            $price = '-';
        } elseif (floatval($price) == 0) {
            $price = trans('Marketplace::attributes.product.free');
        } else {
            if ($price != '-') {
                $price = Payments::currency($price);
            }

            if ($type === 'variable' && $price != '-') {
                $price = '<small style="font-size: 9px">' . trans('Marketplace::attributes.product.starts_at') . ' </small>' . $price;
            }
        }

        if ($price != '-' && $this->isTaxIncluded()) {
            $price .= ' <small style="font-size: 9px">' . trans('Marketplace::attributes.product.tax_included') . ' </small>';
        }

        return $price;
    }

    /**
     * @param bool $first
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeSKU($first = false)
    {
        $hasManyRelation = $this->hasMany(SKU::class)->where('marketplace_sku.status', 'active');

        if ($first) {
            $hasManyRelation = $hasManyRelation->first();
        }

        return $hasManyRelation;
    }

    public function getIsSimpleAttribute()
    {
        return $this->type === 'simple';
    }

    public function scopeVisible($query)
    {
        return $query->where('marketplace_products.status', '<>', 'deleted');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('marketplace_products.status', 'active')
            ->has('activeSKU', '>=', 1)
            ->has('activeStore', '=', 1);
    }

    public function activeStore()
    {
        return $this->store()->where('marketplace_stores.status', 'active');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'marketplace_category_product');
    }

    public function activeCategories()
    {
        return $this->categories()->where('marketplace_categories.status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('marketplace_products.is_featured', true);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'marketplace_product_tag');
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }


    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'marketplace_product_attributes',
            'product_id')
            ->withPivot('sku_level');
    }

    public function getVariationOptionsAttribute()
    {
        return $this->attributes()->where('sku_level', true)->get();
    }

    public function activeTags()
    {
        return $this->tags()->where('marketplace_tags.status', 'active');
    }

    public function posts()
    {
        return $this->morphToMany(Content::class, 'postable');
    }

    public function indexed_records()
    {
        return $this->hasMany(IndexedRecord::class, 'indexable', 'fulltext_search');
    }

    public function tax_classes()
    {
        return $this->morphToMany(TaxClass::class, 'taxable');
    }

    public function renderProductOptions($type = null, $sku = null, $attributes = [])
    {
        if ($type) {
            $fields = $this->{$type};
        } else {
            $fields = $this->attributes;
        }

        $input = '';

        foreach ($fields as $field) {
            $input .= Marketplace::renderAttribute($field, $sku, $attributes);
        }

        return $input;
    }

    public function renderProductOptionsForBulk($type = null, $sku = null, $attributes = [])
    {
        if ($type) {
            $fields = $this->{$type};
        } else {
            $fields = $this->attributes;
        }

        $input = '';

        foreach ($fields as $field) {
            switch ($field->type) {
                case 'select':
                case 'radio':
                case 'multi_values':
                    $field->type = 'checkboxes';
                    break;
                case 'number':
                case 'date':
                case 'text':
                case 'textarea':
                    $field->type = 'tag';
                    break;
            }

            $input .= Marketplace::renderAttribute($field, $sku, $attributes);
        }

        return $input;
    }

    public function copyFirstMediatoSKU($sku)
    {
        $first_media = $this->getFirstMedia($this->galleryMediaCollection);

        $media_path = $first_media->getPath();

        $temporaryDirectory = new TemporaryDirectory();
        $temporaryDirectory->create();

        $temporaryFile = $temporaryDirectory->path($first_media->file_name);

        app(Filesystem::class)->copyFromMediaLibrary($first_media, $temporaryFile);

        $newMedia = $sku
            ->addMedia($temporaryFile)
            ->usingName($first_media->name)
            ->toMediaCollection('marketplace-sku-image');
        $newMedia->custom_properties = $first_media->custom_properties;

        $temporaryDirectory->delete();

        return $newMedia;
    }

    public function getSalesCount()
    {
        $skus = $this->sku()->pluck('code')->toArray();

        $order_items = OrderItem::where('type', 'Product')->whereIn('sku_code', $skus)->whereHas('order',
            function ($q) {
                $q->whereIn('status', ['completed', 'processing']);
            });

        $count = $order_items->count();
        return $count;
    }


    public function getShowURL($id = null, $params = [])
    {
        return url('shop/' . $this->slug);
    }

    public function getOriginalShowURL()
    {
        return parent::getShowURL();
    }

    public function shippingRates(): HasMany
    {
        return $this->hasMany(ProductShipping::class);
    }

    public function attributeSets()
    {
        return $this->morphToMany(AttributeSet::class, 'model', 'marketplace_set_has_models', 'model_id', 'set_id');
    }

    public function options()
    {
        return $this->hasMany(SKUOption::class, 'product_id');
    }

    public function getCloneExemptAttributes()
    {
        return ['product_code'];
    }
}
