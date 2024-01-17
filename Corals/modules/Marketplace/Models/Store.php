<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Search\Indexable;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\Advert\Models\Advertiser;
use Corals\Modules\Utility\Models\Rating\Rating;
use Corals\Modules\Utility\Traits\Rating\ReviewRateable as ReviewRateableTrait;
use Corals\Modules\Utility\Traits\Wishlist\Wishlistable;
use Corals\User\Models\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Store extends BaseModel implements HasMedia
{
    use Indexable, PresentableTrait, ReviewRateableTrait, Wishlistable, Sluggable, LogsActivity, InteractsWithMedia;

    public $htmlentitiesExcluded = ['return_policy'];

    public $allowPublicSearch = true;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.store';

    protected static $logAttributes = [];

    protected $guarded = ['id'];

    protected $table = 'marketplace_stores';

    protected $casts = [
        'properties' => 'array',
    ];

    protected $indexContentColumns = ['short_description', 'address'];
    protected $indexTitleColumns = ['name', 'slug'];


    public $mediaCollectionName = 'marketplace-store-thumbnail';
    public $coverPhotoMediaCollectionName = 'marketplace-store-cover';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDisplayReference()
    {
        return $this->name;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function skus()
    {
        return $this->hasManyThrough(SKU::class, Product::class);
    }


    public function reviews()
    {
        return $this->hasManyDeep(
            Rating::class,
            [Product::class],
            ['store_id', ['reviewrateable_type', 'reviewrateable_id']]
        );
    }

    public function getStoreReviews($count = false, $type = null, $approved_only = true)
    {
        $reviews = $this->reviews();


        if ($approved_only) {
            $reviews->approved();
        }

        if ($type == "bad") {
            $reviews->whereIn('rating', [1, 2, 3]);
        } elseif ($type == "good") {
            $reviews->whereIn('rating', [4, 5]);
        }
        if ($count) {
            return $reviews->count();
        } else {
            return $reviews->get();
        }
    }

    public function getRecommendationPercentage()
    {
        $all_reviews = $this->getStoreReviews(true);
        $good_reviews = $this->getStoreReviews(true, 'good');

        if ($all_reviews) {
            return floor($good_reviews * 100 / $all_reviews);
        } else {
            return 100;
        }
    }

    public function productCategories()
    {
        return $this->hasManyDeep(Category::class, [Product::class, 'marketplace_category_product']);
    }

    public function productBrands()
    {
        return $this->hasManyThrough(
            Brand::class,
            Product::class,
            'store_id',
            'id',
            'id',
            'brand_id'
        );
    }


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ]
        ];
    }

    public function getUrl($with_path = false)
    {
        $enable_subdomain = \Settings::get('marketplace_general_enable_subdomain', false);
        $enable_domain_parking = \Settings::get('marketplace_general_enable_domain_parking', false);


        if ($enable_domain_parking && $this->parking_domain) {
            $valid_domain = filter_var($this->parking_domain, FILTER_VALIDATE_DOMAIN);
            if ($valid_domain) {
                return $valid_domain;
            }
        }
        if ($this->id == 1) {
            return config('app.url') . '/' . \Request::path();;
        }

        if ($enable_subdomain) {
            $domain_params = parse_url(config('app.url'));

            $domain = $domain_params['scheme'] . '://' . $this->slug . '.' . $domain_params['host'];
            if ($with_path) {
                $domain .= '/' . \Request::path();
            }
        } else {
            $domain = url('store/' . $this->slug);
        }

        return $domain;
    }

    /**
     * @return null|string
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function getCoverPhotoAttribute()
    {
        $media = $this->getFirstMedia($this->coverPhotoMediaCollectionName);

        if ($media) {
            return $media->getFullUrl();
        } else {
            return asset(config($this->config . '.default_cover_image'));
        }
    }

    /**
     * Get all of the ads  for the store.
     */
    public function advertiser()
    {
        return $this->morphOne(Advertiser::class, 'owner');
    }

    public function scopeFeatured($query)
    {
        return $query->where('marketplace_stores.is_featured', true);
    }

    public function scopeWithoutMain($query)
    {
        return $query->whereNotNull('slug')->where('slug', '<>', '');
    }

    public function scopeActive($query)
    {
        return $query->where('marketplace_stores.status', 'active');
    }
}
