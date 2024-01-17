<?php

namespace Corals\Modules\CMS\Classes;

use Corals\Modules\CMS\Contracts\MetaTags;
use Corals\Modules\CMS\Contracts\SEOTools as SEOContract;
use Corals\Modules\Utility\Models\SEO\SEOItem;

class SEOTools implements SEOContract
{
    /**
     * @return MetaTags|\Illuminate\Foundation\Application|mixed
     */
    public function metatags()
    {
        return app('seotools.metatags');
    }

    /**
     * @return \Corals\Modules\CMS\Contracts\OpenGraph
     */
    public function opengraph()
    {
        return app('seotools.opengraph');
    }

    /**
     * @return \Corals\Modules\CMS\Contracts\TwitterCards
     */
    public function twitter()
    {
        return app('seotools.twitter');
    }

    /**
     * Setup title for all seo providers.
     *
     * @param string $title
     *
     * @return \Corals\Modules\CMS\Contracts\SEOTools
     */
    public function setTitle($title)
    {
        if (user() && $unreadNotifications = user()->unreadNotifications()->count()) {
            $title = "($unreadNotifications) $title";
        }

        $this->metatags()->setTitle($title);
        $this->opengraph()->setTitle($title);
        $this->twitter()->setTitle($title);

        return $this;
    }

    /**
     * Setup description for all seo providers.
     *
     * @param $description
     *
     * @return \Corals\Modules\CMS\Contracts\SEOTools
     */
    public function setDescription($description)
    {
        $this->metatags()->setDescription($description);
        $this->opengraph()->setDescription($description);
        $this->twitter()->setDescription($description);

        return $this;
    }

    /**
     * Setup keywords for all seo providers.
     *
     * @param $keywords
     *
     * @return \Corals\Modules\CMS\Contracts\SEOTools
     */
    public function setKeywords($keywords)
    {
        $this->metatags()->setKeywords($keywords);

        return $this;
    }

    /**
     * Sets the canonical URL.
     *
     * @param string $url
     *
     * @return \Corals\Modules\CMS\Contracts\SEOTools
     */
    public function setCanonical($url)
    {
        $this->metatags()->setCanonical($url);

        return $this;
    }

    /**
     * @param array|string $urls
     *
     * @return \Corals\Modules\CMS\Contracts\SEOTools
     */
    public function addImages($urls)
    {
        if (is_array($urls)) {
            $this->opengraph()->addImages($urls);
        } else {
            $this->opengraph()->addImage($urls);
        }

        $this->twitter()->addImage($urls);

        return $this;
    }

    /**
     * Get current title from metatags.
     *
     * @param bool $session
     *
     * @return string
     */
    public function getTitle($session = false)
    {
        if ($session) {
            return $this->metatags()->getTitleSession();
        }

        return $this->metatags()->getTitle();
    }

    /**
     * Generate from all seo providers.
     *
     * @param bool $minify
     *
     * @return string
     */
    public function generate($minify = false)
    {
        $current = request()->path();

        $route = request()->route();

        $seoItem = SEOItem::query()->where('slug', $current)->first();

        if (!$seoItem) {
            $seoItem = SEOItem::query()->where('route', $route->uri)->first();
        }

        if ($seoItem) {
            $this->setSEO($seoItem);
        }

        $html = $this->metatags()->generate();
        $html .= PHP_EOL;
        $html .= $this->opengraph()->generate();
        $html .= PHP_EOL;
        $html .= $this->twitter()->generate();

        return ($minify) ? str_replace(PHP_EOL, '', $html) : $html;
    }

    public function setSEO(SEOItem $SEOItem)
    {
        $this->setTitle($this->solveForPlaceholders($SEOItem->title) . ' | ' . \Settings::get('site_name', 'Corals'));

        $this->setDescription($this->solveForPlaceholders($SEOItem->meta_description));

        $this->setKeywords($SEOItem->meta_keywords);

        $this->opengraph()->setUrl(url()->current());

        if ($SEOItem->type) {
            $this->opengraph()->addProperty('type', $SEOItem->type);
        }

        if ($SEOItem->image) {
            $this->opengraph()->addProperty('image', $SEOItem->image);
        }
    }

    protected function solveForPlaceholders($pattern)
    {
        $arguments = view()->getShared();

        $pattern = \preg_replace_callback(
        /* @lang RegExp */
            '#\{(.*?)\}#',
            static function ($matches) use ($arguments) {
                $placeholder = $matches[0];

                $placeholder = str_replace(['{', '}'], ['', ''], $placeholder);

                return \Arr::get($arguments, $placeholder, $placeholder);
            },
            $pattern
        );

        return \str_replace(['{{', '}}', '[[', ']]'], ['{', '}', '[', ']'], $pattern);
    }
}
