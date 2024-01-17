<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\GatewayStatusTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\Marketplace\Traits\DownloadableModel;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SKU extends BaseModel implements HasMedia
{
    use PresentableTrait, LogsActivity, InteractsWithMedia, GatewayStatusTrait, DownloadableModel;

    protected $table = 'marketplace_sku';
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.sku';

    protected static $logAttributes = ['regular_price', 'sale_price'];

    protected $guarded = ['id'];

    protected $appends = ['price'];

    public $mediaCollectionName = 'marketplace-sku-image';

    protected $casts = [
        'shipping' => 'array',
        'properties' => 'json'
    ];

    /**
     * @return string[]
     */
    public function getCloneableRelations()
    {
        return [
            'options'
        ];
    }

    public function options()
    {
        return $this->hasMany(SKUOption::class, 'sku_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return mixed|string
     */
    public function getImageAttribute()
    {
        $media = $this->getFirstMedia($this->mediaCollectionName);
        if ($media) {
            return $media->getUrl();
        } else {
            return optional($this->product)->image ?? asset(config($this->config . '.default_image'));
        }
    }

    public function getTaxAmountAttribute()
    {
        if (!$this->product->isTaxIncluded()) {
            return 0;
        }

        return $this->getPriceAttribute(true) * $this->product->getTax();
    }

    public function getPriceAttribute($noTax = false)
    {
        $regularPrice = $this->attributes['regular_price'];

        if (user() && !empty(user()->classification)) {
            if (isset($this->product->classification_price[user()->classification])) {
                $regularPrice = $this->product->classification_price[user()->classification];
            }
        }

        $salePrice = $this->attributes['sale_price'];

        if ($salePrice && $salePrice < $regularPrice) {
            $price = min($regularPrice, $salePrice);
        } else {
            $price = $regularPrice;
        }

        if ($price && !$noTax && $this->product->isTaxIncluded()) {
            $price *= 1 + $this->product->getTax();
        }

        return $price;
    }

    public function getDiscountAttribute()
    {
        $regularPrice = $this->attributes['regular_price'];

        $salePrice = $this->attributes['sale_price'];

        if ($salePrice && $salePrice < $regularPrice) {
            $discount = (1 - ($salePrice / $regularPrice)) * 100;
            return number_format($discount, 0, '.', '');
        } else {
            return 0;
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getStockStatusAttribute()
    {
        $inventory = $this->checkInventory(1, false);

        $inventory = \Filters::do_filter('sku_pre_stock_status', $inventory, $this);

        if ($inventory) {
            return 'in_stock';
        } else {
            return 'out_of_stock';
        }
    }

    /**
     * @param int $quantity
     * @return bool
     * @throws \Exception
     */
    public function isAvailable($quantity = 1): bool
    {
        $this->refresh();
        return $this->status === 'active' && $this->checkInventory($quantity, false);
    }

    public function getCurrencyAttribute()
    {
        $currency = \Payments::admin_currency_code();

        return $currency;
    }

    public function getCurrencyIconAttribute()
    {
        $currency = $this->currency;

        return 'fa fa-fw fa-' . strtolower($currency);
    }

    public function scopeVisible($query)
    {
        return $query->whereHas('product', function ($query) {
            $query->where('status', '<>', 'deleted');
        });
    }

    public function scopeActive($query)
    {
        $query->where('status', 'active');
    }

    /**
     * @param int $quantity
     * @param bool $throw_error
     * @return bool
     * @throws \Exception
     */
    public function checkInventory($quantity = 1, $throw_error = false)
    {
        $available = null;
        $available_quantity = 0;
        switch ($this->inventory) {
            case 'infinite':
                $available = true;
                break;
            case 'bucket':
                if ($this->inventory_value == "out_of_stock") {
                    $available = false;
                } else {
                    $available = true;
                }
                break;

            case 'finite':
                if ($quantity <= $this->inventory_value) {
                    $available = true;
                } else {
                    $available_quantity = $this->inventory_value;
                    $available = false;
                }
                break;
            default:
                $available = false;
        }

        if (!$available) {
            if ($throw_error) {
                if ($available_quantity) {
                    throw new \Exception(trans('Marketplace::exception.sku.item_has_only_quantity',
                        ['quantity' => $available_quantity]));
                } else {
                    throw new \Exception(trans('Marketplace::exception.sku.item_current_out'));
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * @param $qty
     * @return array
     */
    public function getPriceWithOffers($qty, $calculateExcludingAdditionalQty = false): array
    {
        $price = $this->price;

        $subtotal = null;

        if ($this->product->offers) {
            foreach ($this->product->offers ?? [] as $offer) {
                switch ($offer['type']) {
                    case 'bundle_price':
                        $offerQty = data_get($offer, 'quantity');
                        $offerPrice = data_get($offer, 'value');

                        $offerIncludingQty = intval(($qty / $offerQty));

                        $remainingQty = $qty % $offerQty;

                        $offerTotal = $offerIncludingQty * $offerPrice;

                        $remainingTotal = $remainingQty * $price;

                        $total = $offerTotal + $remainingTotal;

                        $price = $total / $qty;
                        $subtotal = $total;
                        break;
                    case 'get_free':
                        $offerQty = data_get($offer, 'quantity');

                        $getFreePerBundle = data_get($offer, 'value');

                        $offerIncludingQty = intval(($qty / $offerQty));

                        $additionalQty = $offerIncludingQty * $getFreePerBundle;

                        if ($calculateExcludingAdditionalQty) {
                            $offerWithBundleQty = $offerQty + $getFreePerBundle;

                            $remainingQty = $qty % $offerWithBundleQty;
                            $offerIncludingQty = intval(($qty / $offerWithBundleQty));
                            $subtotal = ($remainingQty * $price) + ($offerIncludingQty * $offerQty * $price);
                        } else {
                            $subtotal = $qty * $price;
                            $qty += $additionalQty;
                        }

                        break;
                }
            }
        } else {
            $pricePerQuantity = $this->product->price_per_quantity;

            foreach ($pricePerQuantity ?? [] as $pricePerQ) {
                if ($qty >= data_get($pricePerQ, 'min_quantity') && $qty <= data_get($pricePerQ, 'max_quantity')) {
                    $offerNumber = data_get($pricePerQ, 'price');

                    switch (data_get($pricePerQ, 'type')) {
                        case 'percentage':
                            $price = $offerNumber * $price / 100;
                            break;
                        case 'fixed':
                            $price = $offerNumber;
                            break;
                    }
                }
            }
        }

        return ['price' => $price, 'subtotal' => $subtotal, 'quantity' => $qty];
    }
}
