<?php

namespace Corals\Modules\CMS\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CMSSettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->insert([
            [
                'code' => 'home_page_slug',
                'type' => 'TEXT',
                'category' => 'CMS',
                'label' => 'Home page slug',
                'value' => 'home',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'blog_page_slug',
                'type' => 'TEXT',
                'category' => 'CMS',
                'label' => 'Blog page slug',
                'value' => 'blog',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'news_page_slug',
                'type' => 'TEXT',
                'category' => 'CMS',
                'label' => 'News page slug',
                'value' => 'news',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'pricing_page_slug',
                'type' => 'TEXT',
                'category' => 'CMS',
                'label' => 'Pricing page slug',
                'value' => 'pricing',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'faqs_page_slug',
                'type' => 'TEXT',
                'category' => 'CMS',
                'label' => 'Faqs page slug',
                'value' => 'faqs',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'number_of_news_item_show',
                'type' => 'TEXT',
                'category' => 'CMS',
                'label' => 'Number of News Item Show',
                'value' => 5,
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'enable_news_ticker',
                'type' => 'BOOLEAN',
                'category' => 'CMS',
                'label' => 'Enable News Ticker',
                'value' => 'true',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'feed_url_rss',
                'type' => 'TEXT',
                'category' => 'CMS',
                'label' => 'Feed Url',
                'value' => null,
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'rss_to_json_Api_Key',
                'type' => 'TEXT',
                'category' => 'CMS',
                'label' => 'Rss to Json Api Key',
                'value' => 'aoqoefowy4hyn3qgsmodmozxqagxvxz7wtnduko6',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'cms_comments_enabled',
                'type' => 'BOOLEAN',
                'category' => 'CMS',
                'label' => 'Comments Enabled',
                'value' => 'true',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'cms_comments_require_approval',
                'type' => 'BOOLEAN',
                'category' => 'CMS',
                'label' => 'Comments Require Approval',
                'value' => 'false',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'cms_comments_allow_guest',
                'type' => 'BOOLEAN',
                'category' => 'CMS',
                'label' => 'Comments Allow Guest ',
                'value' => 'false',
                'editable' => 1,
                'hidden' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
