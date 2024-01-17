<?php

namespace Corals\Modules\CMS\database\seeds;

use Corals\Modules\CMS\Models\Page;
use Corals\Modules\CMS\Models\Block;
use Corals\Modules\CMS\Models\Widget;
use Illuminate\Database\Seeder;

class CMSDemoDatabaseSeeder extends Seeder
{
    /**
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function run()
    {
        Page::updateOrCreate(['slug' => 'faqs', 'type' => 'page'],
            array(
                'title' => 'FAQs',
                'slug' => 'faqs',
                'meta_keywords' => 'faqs',
                'meta_description' => 'FAQs',
                'content' => '<section class="gray-bg" id="sec4">
                        <div class="container">
                            <div class="section-title" style="padding-bottom: 0;">
                                <h2> FAQ</h2>
                                <div class="section-subtitle">popular questions</div>
                                <span class="section-separator"></span>
                                <p>Explore some of the best tips from around the city from our partners and friends.</p>
                            </div>
                        </div>
                    </section>
    <!-- About Section End -->',
                'content' => '<section class="gray-bg text-center">
<div class="container">
<div class="row">
<div class="col-md-12">
<h4 class="section-subtitle">Popular Questions</h4>

<p>Explore some of the best tips from around the city from our partners and friends.</p>
</div>
</div>
</div>
</section>',
                'published' => 1,
                'published_at' => '2018-10-3 11:56:34',
                'private' => 0,
                'type' => 'page',
                'template' => 'full',
                'author_id' => 1,
                'deleted_at' => NULL,
                'created_at' => '2017-11-16 11:56:34',
                'updated_at' => '2017-11-16 11:56:34',
            ));

        Page::updateOrCreate(['slug' => 'news', 'type' => 'page'],
            array(
                'title' => 'News',
                'slug' => 'news',
                'meta_keywords' => 'news',
                'meta_description' => 'News',
                'content' => '',
                'published' => 1,
                'published_at' => '2019-05-29 11:56:34',
                'private' => 0,
                'type' => 'page',
                'template' => 'default',
                'author_id' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-05-29 11:56:34',
                'updated_at' => '2019-05-29 11:56:34',
            ));

        $block = Block::updateOrCreate(['name' => 'Pre Footer Block', 'key' => 'pre-footer-block'], [
            'name' => 'Pre Footer Block',
            'key' => 'pre-footer-block',
        ]);

        $widgets = array(
            array(
                'title' => 'Free Worldwide Shipping',
                'content' => '<div class="text-center mb-30"><img
                        class="d-block w-90 img-thumbnail rounded-circle mx-auto mb-3"
                        src="/assets/themes/ecommerce-basic/img/services/01.png"
                        alt="Shipping">
                <h6>Free Worldwide Shipping</h6>
                <p class="text-muted margin-bottom-none">Free shipping for all orders over $100</p>
            </div>',
                'block_id' => $block->id,
                'widget_width' => 3,
                'widget_order' => 0,
                'status' => 'active',
            ),
            array(
                'title' => 'Money Back Guarantee',
                'content' => '<div class="text-center mb-30"><img
                        class="d-block w-90 img-thumbnail rounded-circle mx-auto mb-3"
                        src="/assets/themes/ecommerce-basic/img/services/02.png"
                        alt="Money Back">
                <h6>Money Back Guarantee</h6>
                <p class="text-muted margin-bottom-none">We return money within 30 days</p>
            </div>',
                'block_id' => $block->id,
                'widget_width' => 3,
                'widget_order' => 1,
                'status' => 'active',
            ),

            array(
                'title' => '24/7 Customer Support',
                'content' => '<div class="text-center mb-30"><img
                        class="d-block w-90 img-thumbnail rounded-circle mx-auto mb-3"
                        src="/assets/themes/ecommerce-basic/img/services/03.png"
                        alt="Support">
                <h6>24/7 Customer Support</h6>
                <p class="text-muted margin-bottom-none">Friendly 24/7 customer support</p>
            </div>',
                'block_id' => $block->id,
                'widget_width' => 3,
                'widget_order' => 2,
                'status' => 'active',

            ),
            array(
                'title' => 'Secure Online Payment',
                'content' => '<div class="text-center mb-30"><img
                        class="d-block w-90 img-thumbnail rounded-circle mx-auto mb-3"
                        src="/assets/themes/ecommerce-basic/img/services/04.png"
                        alt="Payment">
                <h6>Secure Online Payment</h6>
                <p class="text-muted margin-bottom-none">We posess SSL / Secure Certificate</p>
            </div>',
                'block_id' => $block->id,
                'widget_width' => 3,
                'widget_order' => 3,
                'status' => 'active',

            ),
        );
        foreach ($widgets as $widget) {
            \Corals\Modules\CMS\Models\Widget::updateOrCreate(
                ['block_id' => $widget['block_id'], 'title' => $widget['title']],
                $widget
            );
        }

        $news[] = \Corals\Modules\CMS\Models\News::updateOrCreate(
            array(
                'title' => 'Get Started with Laraship Laravel Marketplace',
                'slug' => 'get-started-with-laraship ',
                'meta_keywords' => NULL,
                'meta_description' => NULL,
                'content' => '<p>In this article, we are going to talk about laraship Marketplace, the amazing Laravel marketplace platform, and explore some of its nice features and how to utilize them.</p>',
                'published' => 1,
                'published_at' => '2019-3-04 11:18:23',
                'private' => 0,
                'type' => 'news',
                'template' => NULL,
                'author_id' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-3-04 16:45:51',
                'updated_at' => '2019-3-04 16:18:23',)
        );
        $news[] = \Corals\Modules\CMS\Models\News::updateOrCreate(
            array(
                'title' => 'How to Speed your Laraship Website or Web Application',
                'slug' => 'how-to-speed-your-laraship',
                'meta_keywords' => NULL,
                'meta_description' => NULL,
                'content' => '<p>As we know, the site speed is becoming the main factor to help to index your website under search engines like Google, and in the article, we will introduce some of the techniques to speed your Laraship website.
As Laraship built on Laravel you will find some of the techniques are part of Laravel engine, what’s nice in Laraship you can utilize Laravel because it hasn’t overridden any of Laravel components or modules.</p>',
                'published' => 1,
                'published_at' => '2019-3-04 11:18:23',
                'private' => 0,
                'type' => 'news',
                'template' => NULL,
                'author_id' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-3-04 16:45:51',
                'updated_at' => '2019-3-04 16:18:23',)
        );
        $news[] = \Corals\Modules\CMS\Models\News::updateOrCreate(['slug' => 'laraship-affiliate-program'],
            array(
                'title' => 'Update Laraship to Laravel 5.7',
                'slug' => 'update-laraship ',
                'meta_keywords' => NULL,
                'meta_description' => NULL,
                'content' => '<p>Laraship team is excited to the announcement of the new Laraship update to support Laravel 5.7.To update your copy please follow the link below to update :https://www.laraship.com/docs/laraship/update-laraship/update-laraship-to-laravel-5-7/ Note:since there are some...</p>',
                'published' => 1,
                'published_at' => '2019-3-04 11:18:23',
                'private' => 0,
                'type' => 'news',
                'template' => NULL,
                'author_id' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-3-04 16:45:51',
                'updated_at' => '2019-3-04 16:18:23')
        );
        $news[] = \Corals\Modules\CMS\Models\News::updateOrCreate(['slug' => 'laraship-affiliate-program'],
            array(
                'title' => 'Laraship Affiliate Program is available!',
                'slug' => 'laraship-affiliate-program',
                'meta_keywords' => NULL,
                'meta_description' => NULL,
                'content' => '<p>Laraship team is excited to announce our referral program, because we believe that success is a sharing story, we have are introducing the affiliate program where members can receive 20% of their referral purchases, signup to our program and start promoting our products, not only you’re making money by that but also helps other people in building their applications using the most advanced eCommerce , Marketplace , Classified, and SaaS product. they will save cost, time, and make sure they build their apps on a robust, reliable and high quality product.
To enroll click on the link below</p>',
                'published' => 1,
                'published_at' => '2019-3-04 11:18:23',
                'private' => 0,
                'type' => 'news',
                'template' => NULL,
                'author_id' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-3-04 16:45:51',
                'updated_at' => '2019-3-04 16:18:23',)
        );
        $news[] = \Corals\Modules\CMS\Models\News::updateOrCreate(['slug' => 'ways-to-communicate'],
            array(
                'title' => '10 Ways to communicate with Laraship Users',
                'slug' => 'ways-to-communicate',
                'meta_keywords' => NULL,
                'meta_description' => NULL,
                'content' => '<p>Building a Laravel communication platform is not easy and require a lot of efforts, in the same time in most cases you don’t have the time to build such tools because of lack of time, resources, and budget.

Communication channels are important in any web application, especially for transactional web applications where users need to be updated with all aspects related to their accounts and items, like orders, products,

A lot of researches are made to improve user retention and so many approaches have been introduced like A/B testing, tracking codes, screenshot tracking to analyze user behavior in the system, which indicates how important not only to bring traffic to your site but also how to decrease bounce rate and increase return visits.

Laraship is the only Laravel platform that has implemented a complete set of communication tools, modular and integrated.</p>',
                'published' => 1,
                'published_at' => '2019-3-04 11:18:23',
                'private' => 0,
                'type' => 'news',
                'template' => NULL,
                'author_id' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-3-04 16:45:51',
                'updated_at' => '2019-3-04 16:18:23',)
        );
    }
}
