<?php

namespace Corals\Modules\Utility;

use Corals\Modules\Utility\Commands\RatingCalculator;
use Corals\Modules\Utility\Facades\Address\Address;
use Corals\Modules\Utility\Facades\Category\Category;
use Corals\Modules\Utility\Facades\ListOfValue\ListOfValues;
use Corals\Modules\Utility\Facades\Rating\RatingManager;
use Corals\Modules\Utility\Facades\SEO\SEOItems;
use Corals\Modules\Utility\Facades\Tag\Tag;
use Corals\Modules\Utility\Facades\Utility;
use Corals\Modules\Utility\Http\Middleware\ContentConsent\ContentConsentMiddleware;
use Corals\Modules\Utility\Models\Address\Location;
use Corals\Modules\Utility\Models\Category\Attribute;
use Corals\Modules\Utility\Models\Category\AttributeOption;
use Corals\Modules\Utility\Models\Category\Category as CargoryModel;
use Corals\Modules\Utility\Models\Category\Category as CategoryModel;
use Corals\Modules\Utility\Models\Category\ModelOption;
use Corals\Modules\Utility\Models\Comment\Comment;
use Corals\Modules\Utility\Models\Rating\Rating;
use Corals\Modules\Utility\Models\Schedule\Schedule;
use Corals\Modules\Utility\Models\SEO\SEOItem;
use Corals\Modules\Utility\Models\Tag\Tag as TagModel;
use Corals\Modules\Utility\Models\Wishlist\Wishlist;
use Corals\Modules\Utility\Notifications\Comment\CommentCreated;
use Corals\Modules\Utility\Notifications\Comment\CommentToggleStatus;
use Corals\Modules\Utility\Notifications\Rating\RateCreated;
use Corals\Modules\Utility\Notifications\Rating\RatingToggleStatus;
use Corals\Modules\Utility\Providers\UtilityAuthServiceProvider;
use Corals\Modules\Utility\Providers\UtilityObserverServiceProvider;
use Corals\Modules\Utility\Providers\UtilityRouteServiceProvider;
use Corals\Settings\Facades\Settings;
use Corals\User\Communication\Facades\CoralsNotification;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class UtilityServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */

    public function boot()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Utility');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Utility');

        // Load migrations
//        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerMorphMaps();
        $this->registerCustomFieldsModels();
        $this->registerCommand();


        $this->addEvents();

        \Filters::add_filter('corals_middleware', [\Corals\Modules\Utility\Classes\Utility::class, 'guideMiddleware'], 8);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/utility.php', 'utility');

        $this->app->register(UtilityRouteServiceProvider::class);
        $this->app->register(UtilityAuthServiceProvider::class);
        $this->app->register(UtilityObserverServiceProvider::class);

        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Utility', Utility::class);
            $loader->alias('Address', Address::class);
            $loader->alias('Category', Category::class);
            $loader->alias('Tag', Tag::class);
            $loader->alias('RatingManager', RatingManager::class);
            $loader->alias('SEOItems', SEOItems::class);
            $loader->alias('ListOfValues', ListOfValues::class);
        });

        $this->app['router']->pushMiddlewareToGroup('web', ContentConsentMiddleware::class);
    }

    protected function registerMorphMaps()
    {
        Relation::morphMap([
            'UtilityLocation' => Location::class,
            'UtilityAttribute' => Attribute::class,
            'UtilityAttributeOption' => AttributeOption::class,
            'UtilityCategory' => CargoryModel::class,
            'UtilityModelOption' => ModelOption::class,
            'UtilityComment' => Comment::class,
            'UtilityRating' => Rating::class,
            'UtilitySEOItem' => SEOItem::class,
            'UtilitySchedule' => Schedule::class,
            'UtilityTag' => TagModel::class,
            'UtilityWishlist' => Wishlist::class,
        ]);
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(CategoryModel::class, 'Category (Utility)');
        Settings::addCustomFieldModel(Location::class, 'Location (Utility)');
    }


    protected function registerCommand()
    {
        $this->commands(RatingCalculator::class);

    }

    protected function addEvents()
    {
        CoralsNotification::addEvent(
            'notifications.rate.rate_created',
            'Rate Created',
            RateCreated::class);

        CoralsNotification::addEvent(
            'notifications.rate.rate_toggle_status',
            'Rate Toggle Status',
            RatingToggleStatus::class);

        CoralsNotification::addEvent(
            'notifications.comment.comment_created',
            'Comment Created',
            CommentCreated::class);

        CoralsNotification::addEvent(
            'notifications.comment.comment_toggle_status',
            'Comment Toggle Status',
            CommentToggleStatus::class);
    }
}
