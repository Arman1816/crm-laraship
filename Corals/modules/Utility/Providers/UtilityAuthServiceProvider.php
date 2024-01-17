<?php

namespace Corals\Modules\Utility\Providers;

use Corals\Modules\Utility\Models\Address\Location;
use Corals\Modules\Utility\Models\Category\Attribute;
use Corals\Modules\Utility\Models\Category\Category;
use Corals\Modules\Utility\Models\Comment\Comment;
use Corals\Modules\Utility\Models\Guide\Guide;
use Corals\Modules\Utility\Models\ListOfValue\ListOfValue;
use Corals\Modules\Utility\Models\Rating\Rating;
use Corals\Modules\Utility\Models\SEO\SEOItem;
use Corals\Modules\Utility\Models\Tag\Tag;
use Corals\Modules\Utility\Models\Webhook\Webhook;
use Corals\Modules\Utility\Models\Wishlist\Wishlist;
use Corals\Modules\Utility\Policies\Address\LocationPolicy;
use Corals\Modules\Utility\Policies\Category\AttributePolicy;
use Corals\Modules\Utility\Policies\Category\CategoryPolicy;
use Corals\Modules\Utility\Policies\Comment\CommentPolicy;
use Corals\Modules\Utility\Policies\Guide\GuidePolicy;
use Corals\Modules\Utility\Policies\ListOfValue\ListOfValuePolicy;
use Corals\Modules\Utility\Policies\Rating\RatingPolicy;
use Corals\Modules\Utility\Policies\SEO\SEOItemPolicy;
use Corals\Modules\Utility\Policies\Tag\TagPolicy;
use Corals\Modules\Utility\Policies\Webhook\WebhookPolicy;
use Corals\Modules\Utility\Policies\Wishlist\WishlistPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class UtilityAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Rating::class => RatingPolicy::class,
        Comment::class => CommentPolicy::class,
        Wishlist::class => WishlistPolicy::class,
        Tag::class => TagPolicy::class,
        Attribute::class => AttributePolicy::class,
        Category::class => CategoryPolicy::class,
        Location::class => LocationPolicy::class,
        SEOItem::class => SEOItemPolicy::class,
        Webhook::class => WebhookPolicy::class,
        ListOfValue::class => ListOfValuePolicy::class,
        Guide::class => GuidePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
