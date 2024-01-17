<?php

namespace Corals\Modules\CMS\Traits;

use Corals\Modules\CMS\Facades\CMS;
use Corals\Modules\CMS\Http\Requests\PageRequest;
use Corals\Modules\CMS\Models\Category;
use Corals\Modules\CMS\Models\Content;
use Corals\Modules\CMS\Models\Faq;
use Corals\Modules\CMS\Models\Post;
use Corals\Modules\CMS\Models\News;
use Corals\Modules\Subscriptions\Models\Product;
use Corals\Modules\Utility\Models\Tag\Tag;
use Illuminate\Http\Request;

trait CMSControllerFunctions
{
    use SEOTools;

    public $contentQuery = null;

    /**
     * @param bool $ignoreInternal
     */
    public function resetContentQuery($ignoreInternal = false)
    {
        $this->contentQuery = Content::query()->published();

        if (!$ignoreInternal) {
            $this->contentQuery->internal($this->internalState);
        }
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        $slug = \Settings::get('home_page_slug', 'home');

        $item = $this->contentQuery->where('slug', \Str::slug($slug))->first();

        if (!$item) {
            abort(404);
        }

        $item = $item->toContentType();


        $this->cmsSEO($item, null, url('/'), 'website');

        $home = true;
        $template = $item->template ?: 'full';

        $view = $this->view_prefix . '/templates.' . $template;


        return view($view)->with(compact('item', 'home'));
    }

    /**
     * @param $item
     * @param $image
     * @param null $url
     * @param string $type
     */
    private function cmsSEO($item, $image, $url = null, $type = 'article')
    {
        $seoItem = [
            'title' => $item->title,
            'meta_description' => $item->meta_description,
            'url' => $url ?: url($item->slug),
            'type' => $type,
            'image' => $image ?? \Settings::get('site_logo'),
            'meta_keywords' => $item->meta_keywords
        ];

        $this->setSEO((object)$seoItem);
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function show(Request $request, $slug = '')
    {
        \Actions::do_action('pre_show_front_end_page_by_slug', $slug);

        if ($page = $this->isSpecialPageSlug($slug)) {

            return $this->{$page}($request);
        }

        $item = $this->contentQuery->where('slug', \Str::slug($slug))->first();

        if (!$item) {
            abort(404);
        }
        $item = $item->toContentType();

        if (!user() || !user()->hasPermissionTo('Administrations::admin.cms')) {
            if ($item->private) {
                if (!user()) {
                    session()->put('url.intended', url()->current());
                    return redirectTo('login');
                } else {
                    $hasRequiredSubscriptions = $this->hasRequiredSubscriptions($item);


                    if (!$hasRequiredSubscriptions && !$item->users->contains(user())) {
                        if (\Modules::isModuleActive('corals-subscriptions')) {
                            return redirectTo('subscriptions/select');
                        } else {
                            abort(404);
                        }
                    }
                }
            } elseif (!$this->hasRequiredSubscriptions($item)) {
                abort(404);
            }
        }

        if (!is_null($item->template)) {

            $view = 'templates.' . $item->template;
        } else {
            $view = $item->type == 'post' ? 'post' : 'templates.default';
        }
        $blog = null;
        $home = null;

        if ($item->type == 'post') {
            $blog = $this->getBlog();
        }

        $featured_image = CMS::getContentFeaturedImage($item);

        $this->cmsSEO($item, $featured_image);

        $view = $this->view_prefix . "/$view";

        return view($view)->with(compact('item', 'featured_image', 'blog', 'home'));
    }

    private function hasRequiredSubscriptions($item)
    {
        $can_access = true;
        if ($item->categories->count() > 0) {
            foreach ($item->categories as $category) {
                $subscription_plans = $category->subscribable_plans;
                if ($subscription_plans && sizeof($subscription_plans) > 0) {
                    $can_access = false;
                    foreach ($subscription_plans as $subscription_plan) {
                        if (user() && user()->activeSubscriptions->where('plan_id', $subscription_plan->id)->count()) {
                            return true;
                        }
                    }
                }
            }
        }


        return $can_access;


    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function blog(Request $request)
    {
        $blog = $this->getBlog();


        $posts = Post::whereHas('categories', function ($categories) {
            $categories->where('status', 'active');
            $categories->where('belongs_to', 'post');
        });


        $not_available_categories = \CMS::getNotAvailableCategories();

        if ($not_available_categories) {
            $posts->whereRaw(' `posts`.`id` NOT IN(SELECT post_id from category_post where category_id in(' . implode(',', $not_available_categories) . ') )');
        }

        $posts = $this->getPosts($posts, $request);

        $featured_image = CMS::getContentFeaturedImage($blog);

        $this->cmsSEO($blog, $featured_image);

        $title = null;

        if ($request->has('query')) {
            $title = $this->formatTitle(strip_tags($request->get('query')), trans('CMS::labels.cms.search_results_for'));
        }

        $view = $this->view_prefix . '/blog';

        return view($view)->with(compact('blog', 'posts', 'title', 'featured_image'));
    }

    public function news(Request $request)
    {
        $page_new = $this->getNews();

        $news = News::query();

        $news = $this->getPosts($news, $request);

        $title = null;

        $featured_image = CMS::getContentFeaturedImage($page_new);

        $this->cmsSEO($page_new, $featured_image);

        $view = $this->view_prefix . '/news';

        return view($view)->with(compact('page_new', 'news', 'title', 'featured_image'));


    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function faqs(Request $request)
    {
        $faq = $this->getfaq();

        $faqs = Faq::whereHas('categories', function ($categories) {
            $categories->where('status', 'active');
            $categories->where('belongs_to', 'faq');
        });

        $faqs = $this->getPosts($faqs, $request);

        $title = null;

        $featured_image = CMS::getContentFeaturedImage($faq);

        $this->cmsSEO($faq, $featured_image);

        $view = $this->view_prefix . '/faqs';

        return view($view)->with(compact('faq', 'faqs', 'title'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function pricing(Request $request)
    {
        $slug = \Settings::get('pricing_page_slug', 'pricing');

        $pricing = $this->contentQuery->where('slug', \Str::slug($slug))->first();

        if (!$pricing) {
            abort(404);
        }

        $products = [];

        if (\Modules::isModuleActive('corals-subscriptions')) {
            $products = Product::active()->get();
        }

        $featured_image = CMS::getContentFeaturedImage($pricing);

        $this->cmsSEO($pricing, $featured_image);

        $view = $this->view_prefix . '/pricing_public';

        return view($view)->with(compact('pricing', 'products', 'featured_image'));
    }

    /**
     * @param $slug
     * @return bool|string
     */
    protected function isSpecialPageSlug($slug)
    {
        $home = \Settings::get('home_page_slug', 'home');
        $blog = \Settings::get('blog_page_slug', 'blog');
        $faqs = \Settings::get('faqs_page_slug', 'faqs');
        $news = \Settings::get('news_page_slug', 'news');
        $pricing = \Settings::get('pricing_page_slug', 'pricing');

        switch ($slug) {
            case $home:
                return 'index';
            case $blog:
                return 'blog';
            case $faqs:
                return 'faqs';
            case $pricing:
                return 'pricing';
            case $news:
                return 'news';
            default:
                return false;
        }
    }

    /**
     * @return mixed
     */
    protected function getBlog()
    {
        $slug = \Settings::get('blog_page_slug', 'blog');

        $this->resetContentQuery(true);

        $blog = $this->contentQuery->where('slug', \Str::slug($slug))->first();

        if (!$blog) {
            abort(404);
        }

        return $blog;
    }


    protected function getNews()
    {
        $slug = \Settings::get('news_page_slug', 'news');

        $this->resetContentQuery(true);

        $new = $this->contentQuery->where('slug', \Str::slug($slug))->first();

        if (!$new) {
            abort(404);
        }

        return $new;
    }

    /**
     * @return mixed
     */
    protected function getFaq()
    {
        $slug = \Settings::get('faqs_page_slug', 'faqs');

        $this->resetContentQuery(true);

        $faq = $this->contentQuery->where('slug', \Str::slug($slug))->first();

        if (!$faq) {
            abort(404);
        }

        return $faq;
    }


    /**
     * @param $posts
     * @param Request $request
     * @return mixed
     */
    protected function getPosts($posts, Request $request)
    {
        $posts = $posts->published()->internal($this->internalState);

        if (!user()) {
            $posts = $posts->public();
        }

        $query = strip_tags($request->get('query'));

        if (!empty($query)) {
            //TODO::use fulltext search
            $posts = $posts->where(function ($subQuery) use ($query) {
                $subQuery->where('title', 'like', "%$query%")
                    ->orWhere('content', 'like', "%$query%");
            });
        }

        $posts = $posts->paginate(config('cms.frontend.page_limit', 10));

        return $posts;
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return $this
     */
    public function category(Request $request, $slug = '')
    {
        $blog = $this->getBlog();

        $category = Category::active()->where('slug', $slug)->first();

        if (!$category) {
            abort(404);
        }

        $posts = $category->posts();

        $posts = $this->getPosts($posts, $request);

        $item = new \stdClass();

        $item->title = $category->name;

        $item->meta_description = $blog->meta_description;
        $item->meta_keywords = $blog->meta_keywords;

        $this->cmsSEO($item, null, url('category/' . $slug));

        $title = $this->formatTitle($category->name);

        $view = $this->view_prefix . '/blog';

        return view($view)->with(compact('blog', 'posts', 'title'));
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return $this
     */
    public function tag(Request $request, $slug = '')
    {
        $blog = $this->getBlog();

        $tag = Tag::active()->where('slug', $slug)->first();

        if (!$tag) {
            abort(404);
        }

        $posts = Post::query()->withAnyTags([$tag->name], 'CMS');

        $posts = $this->getPosts($posts, $request);

        $item = new \stdClass();

        $item->title = $tag->name;

        $item->meta_description = $blog->meta_description;
        $item->meta_keywords = $blog->meta_keywords;

        $this->cmsSEO($item, null, url('tag/' . $slug));

        $title = $this->formatTitle($tag->name);

        $view = $this->view_prefix . '/blog';

        return view($view)->with(compact('blog', 'posts', 'title'));
    }

    /**
     * @param $title
     * @param string $prefix
     * @return string
     */
    private function formatTitle($title, $prefix = '')
    {
        return $formattedTitle = trans('CMS::labels.cms.blog_formatted_title', ['prefix' => $prefix, 'title' => $title]);
    }

    /**
     * @param PageRequest $request
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function adminShow(PageRequest $request, $slug = '')
    {
        $item = Content::query()->where('slug', \Str::slug($slug))->firstOrFail();

        if (!is_null($item->template)) {
            $view = 'templates.' . $item->template;
        } else {
            $view = $item->type == 'post' ? 'post' : 'templates.default';
        }

        $featured_image = CMS::getContentFeaturedImage($item);

        $this->cmsSEO($item, $featured_image);

        $blog = null;
        $home = null;

        if ($item->type == 'post') {
            $blog = $this->getBlog();
        }

        $view = $this->view_prefix . "/$view";

        return view($view)->with(compact('item', 'featured_image', 'blog', 'home'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function contactEmail(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        \Mail::send('emails.contact',
            array(
                'name' => $request->post('name'),
                'email' => $request->post('email'),
                'phone' => @$request->post('phone'),
                'company' => @$request->post('company'),
                'subject' => $request->post('subject'),
                'user_message' => $request->post('message')
            ), function ($message) use ($request) {
                $message->to(\Settings::get('contact_form_email'), \Settings::get('site_name', 'Corals'))
                    ->replyTo($request->post('email'))
                    ->subject('Contact Submission');
            });

        return \Response::json(['message' => trans('CMS::labels.message.email_sent_success'), 'class' => 'alert-success', 'level' => 'success']);
    }
}
