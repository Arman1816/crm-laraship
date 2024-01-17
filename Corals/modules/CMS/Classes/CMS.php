<?php

namespace Corals\Modules\CMS\Classes;

use Corals\Modules\CMS\Models\Category;
use Corals\Modules\CMS\Models\Content;
use Corals\Modules\CMS\Models\Download;
use Corals\Modules\CMS\Models\News;
use Corals\Modules\CMS\Models\Post;
use Corals\Modules\CMS\Models\Testimonial;
use Corals\Modules\Utility\Models\Tag\Tag;
use \Spatie\MediaLibrary\MediaCollections\Models\Media;

class CMS
{
    /**
     * CMS constructor.
     */
    function __construct()
    {
    }

    public function mergeURLPath(...$paths): string
    {
        return collect($paths)->map(function (string $path) {
            return trim($path, '/');
        })->implode('/');
    }

    /**
     * @param bool $objects
     * @param null $status
     * @param bool $internalState
     * @return mixed
     */
    public function getCategoriesList($objects = false, $status = null, $internalState = null, $belongsTo = 'post')
    {
        $categories = Category::query()->where('belongs_to', '=', $belongsTo);

        if (!is_null($internalState)) {
            $categories = $categories->whereHas('posts', function ($query) use ($internalState) {
                $query->internal($internalState);
            });
        }

        $not_available_categories = $this->getNotAvailableCategories();
        if ($not_available_categories) {
            $categories->whereNotIn('id', $not_available_categories);
        }
        if ($status) {
            $categories = $categories->where('status', $status);
        }
        if ($objects) {
            $categories = $categories->get();
        } else {
            $categories = $categories->pluck('name', 'id');
        }

        if ($categories->isEmpty()) {
            return [];
        } else {
            return $categories;
        }
    }

    /**
     * @param $category
     * @param bool $internalState
     * @return mixed
     */
    public function getCategoryPostsCount($category, $internalState = false)
    {
        $posts = $category->posts()->internal($internalState)->published();

        if (!user()) {
            $posts = $posts->public();
        }

        return $posts->count();
    }

    /**
     * @param bool $objects
     * @param null $status
     * @param bool $internalState
     * @return mixed
     */
    public function getTagsList($objects = false, $status = null)
    {
        $tags = Tag::query()->withModule('CMS');

        if ($status) {
            $tags = $tags->where('status', $status);
        }

        if ($objects) {
            $tags = $tags->get();
        } else {
            $tags = $tags->pluck('name', 'id');
        }

        if ($tags->isEmpty()) {
            return collect([]);
        } else {
            return $tags;
        }
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getTestimonialsList($limit = 3)
    {
        $testimonials = Testimonial::query()->published();

        return $testimonials->take($limit)->get();
    }

    public function getDownloadsList($limit = 3)
    {
        $downloads = Download::query()->published();

        return $downloads->take($limit)->get();
    }


    /**
     * @param Content $content
     * @return \Illuminate\Contracts\Routing\UrlGenerator|null|string
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function getContentFeaturedImage(Content $content)
    {
        if (!$content) {
            return null;
        }
        $class = $content->contentTypeMapping[$content->type];

        $media = Media::where('collection_name', 'featured-image')
            ->where('model_id', $content->id)
            ->where('model_type', $class)
            ->first();

        if ($media) {
            return $media->getFullUrl();
        } elseif ($content->featured_image_link) {
            return url($content->featured_image_link);
        } else {
            return null;
        }
    }

    public function getLatestPosts($limit = 2, $internalState = false)
    {
        $posts = Post::whereHas('categories', function ($categories) {
            $categories->where('status', 'active');
        })->internal($internalState);

        $posts = $posts->published();

        if (!user()) {
            $posts = $posts->public();
        }

        $posts = $posts->orderBy('published_at', 'desc')->take($limit)->get();

        return $posts;
    }

    public function getFrontendThemeTemplates()
    {
        $frontend_theme = \Settings::get('active_frontend_theme');
        $theme_views_path = \Theme::find($frontend_theme)->viewsPath;
        $templates = [];
        foreach (glob(themes_path($theme_views_path . '/templates/*.php')) as $template) {
            $template_key = basename(str_replace('.blade.php', '', $template));
            $templates[$template_key] = ucfirst($template_key);
        }
        return $templates;

    }

    public function getNotAvailableCategories()
    {
        if (user() && user()->hasPermissionTo('Administrations::admin.cms')) {
            return [];
        }
        $not_available_categories = [];
        if (\Modules::isModuleActive('corals-subscriptions')) {

            $categories = Category::all();
            $not_available_categories = [];
            foreach ($categories as $category) {
                $subscription_plans = $category->subscribable_plans;
                if ($subscription_plans) {
                    foreach ($subscription_plans as $subscription_plan) {
                        if (!user() || !user()->activeSubscriptions->where('plan_id', $subscription_plan->id)->count()) {
                            $not_available_categories [] = $category->id;

                        }
                    }
                }
            }
        }
        return $not_available_categories;
    }

    public function getLatestNews($limit = 3)
    {

        $news = News::published();
        $news = $news->orderBy('published_at', 'desc')->take($limit)->get();

        return $news;
    }

    public function getCategoriesBelongsTo()
    {
        $belongs_to = [
            'page' => trans('CMS::attributes.category.page'),
            'post' => trans('CMS::attributes.category.post'),
            'faq' => trans('CMS::attributes.category.faq'),
        ];

        return $belongs_to;
    }

    public function getRelatedPosts($post)
    {
        $posts = Post::query()
            ->where('id', '<>', $post->id)
            ->published()
            ->whereHas('categories', function ($categories) use ($post) {
                $categories->where('status', 'active')
                    ->whereIn('categories.id', $post->activeCategories()->pluck('category_id')->toArray());
            });


        if (!user()) {
            $posts = $posts->public();
        }

        return $posts->inRandomOrder()->take(3)->get();
    }
}
