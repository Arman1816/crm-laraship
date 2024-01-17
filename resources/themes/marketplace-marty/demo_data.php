<?php
$categories = [];
$posts = [];

if (\Schema::hasTable('posts')
    && class_exists(\Corals\Modules\CMS\Models\Page::class)
    && class_exists(\Corals\Modules\CMS\Models\Post::class)
) {
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'home', 'type' => 'page',],
        array(
            'title' => 'Home',
            'meta_keywords' => 'home',
            'meta_description' => 'home',
            'content' => '<h1>
<span class="light">Welcome to</span>
            <span class="bold">Laraship Marketplace</span></h1>
            <p class="tagline">A Premuim Multi Vendor eCommerce Loaded with Awesome features!</p>',
            'published' => 1,
            'published_at' => '2017-11-16 14:26:52',
            'private' => 0,
            'template' => 'home',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2018-11-01 16:27:04',
            'updated_at' => '2018-11-01 16:27:07',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'blog', 'type' => 'page'],
        array(
            'title' => 'Blog',
            'meta_keywords' => 'Blog',
            'meta_description' => 'Blog',
            'content' => '<div class="text-center">
<h2>Blog</h2>
<p class="lead">Pellentesque habitant morbi tristique senectus et netus et malesuada</p>
</div>',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'right',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'pricing', 'type' => 'page'],
        array(
            'title' => 'Pricing',
            'meta_keywords' => 'Pricing',
            'meta_description' => '',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'full',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'how-to-shop', 'type' => 'page'],
        array(
            'title' => 'Become A Laraship Marketplace Author',
            'meta_keywords' => 'How to shop',
            'meta_description' => 'How to shop',
            'content' => '<section class="how_it_works">
<div class="how_it_works_module">
<div class="container">
<div class="row">
<div class="col-lg-5 col-md-6 v_middle">
<div class="area_image"><img alt="area images" src="/assets/themes/marketplace-marty/images/1.png" /></div>
</div>
<!-- end /.col-md-12 -->

<div class="col-lg-5 col-md-6 offset-lg-2 v_middle">
<div class="area_content">
<h2>Create a Free Account</h2>

<p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra justo ut sceler isque the mattis, leo quam aliquet congue this there placerat mi id nisi interdum mollis. Praesent pharetra justo ut scelerisque the mattis, leo quam aliquet. Nunc placer atmi id nisi interdum mollis quam.</p>
<a class="btn btn--default btn--white btn--round" href="#">Register Now</a></div>
</div>
<!-- end /.col-md-12 --></div>
</div>
</div>
<!-- end /.how_it_works_module -->

<div class="how_it_works_module">
<div class="container">
<div class="row">
<div class="col-lg-5 col-md-6 v_middle">
<div class="area_content">
<h2>Upload Your Products</h2>

<p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra justo ut sceler isque the mattis, leo quam aliquet congue this there placerat mi id nisi interdum mollis. Praesent pharetra justo ut scelerisque the mattis, leo quam aliquet. Nunc placer atmi id nisi interdum mollis quam.</p>
<a class="btn btn--white btn--default btn--round" href="#">Register Now</a></div>
</div>
<!-- end /.col-md-12 -->

<div class="col-lg-5 col-md-6 v_middle offset-lg-2">
<div class="area_image"><img alt="area images" src="/assets/themes/marketplace-marty/images/2.png" /></div>
</div>
<!-- end /.col-md-12 --></div>
</div>
</div>
<!-- end /.how_it_works_module -->

<div class="how_it_works_module">
<div class="container">
<div class="row">
<div class="col-lg-5 col-md-6 v_middle">
<div class="area_image"><img alt="area images" src="/assets/themes/marketplace-marty/images/3.png" /></div>
</div>
<!-- end /.col-md-12 -->

<div class="col-lg-5 col-md-6 offset-lg-2 v_middle">
<div class="area_content">
<h2>Start Making Money</h2>

<p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra justo ut sceler isque the mattis, leo quam aliquet congue this there placerat mi id nisi interdum mollis. Praesent pharetra justo ut scelerisque the mattis, leo quam aliquet. Nunc placer atmi id nisi interdum mollis quam.</p>
<a class="btn btn--default btn--white btn--round" href="#">Register Now</a></div>
</div>
<!-- end /.col-md-12 --></div>
</div>
</div>
<!-- end /.how_it_works_module --><!--================================
        END HOW IT WORKS AREA
    =================================--><!--================================
        START CALL TO ACTION AREA
    =================================-->

<section class="call-to-action bgimage">
<div class="bg_image_holder"><img alt="" src="/assets/themes/marketplace-marty/images/calltobg.jpg" /></div>

<div class="container content_above">
<div class="row">
<div class="col-md-12">
<div class="call-to-wrap">
<h1 class="text--white">Ready to Join Our Marketplace!</h1>

<h4 class="text--white">Over 25,000 designers and developers trust the Laraship Marketplace.</h4>
<a class="btn btn--lg btn--round btn--white callto-action-btn" href="#">Join Us Today</a></div>
</div>
</div>
</div>
</section>
<!--================================
        END CALL TO ACTION AREA
    =================================--></section>
',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'full',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'contact-us', 'type' => 'page'],
        array(
            'title' => 'Contact Us',
            'meta_keywords' => 'Contact Us',
            'meta_description' => 'Contact Us',
            'content' => '<div class="text-center"><h2 class="color:#2b373a">Drop Your Message</h2><p class="lead" style="text-align: center;">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => 'contact',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
        ));

    $posts[] = \Corals\Modules\CMS\Models\Post::updateOrCreate(['slug' => 'lorem-ipsum-dolor-sit-amet1', 'type' => 'post'],
        array(
            'title' => 'LOREM IPSUM DOLOR SIT AMET',
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ut iaculis arcu. Proin tincidunt, ipsum nec vehicula euismod, neque nibh pretium lorem, at ornare risus sem et risus. Curabitur pulvinar dui viverra libero lobortis in dictum velit luctus. Donec imperdiet tincidunt interdum

Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam libero lobortis in dictum velit luctus. Donec imperdiet tincidunt interdum.</p>',
            'published' => 1,
            'published_at' => '2018-10-31 11:18:23',
            'private' => 0,
            'type' => 'post',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2018-10-31 23:45:51',
            'updated_at' => '2018-10-31 13:18:23',
        ));
    $posts[] = \Corals\Modules\CMS\Models\Post::updateOrCreate(['slug' => 'rutrum-nonvopxe-sapiente-delectus-aut-bonbde', 'type' => 'post'],
        array(
            'title' => 'Rutrum Nonvopxe Sapiente Delectus Aut Bonbde',
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ut iaculis arcu. Proin tincidunt, ipsum nec vehicula euismod, neque nibh pretium lorem, at ornare risus sem et risus. Curabitur pulvinar dui viverra libero lobortis in dictum velit luctus. Donec imperdiet tincidunt interdum

Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam libero lobortis in dictum velit luctus. Donec imperdiet tincidunt interdum.</p>',
            'published' => 1,
            'published_at' => '2018-10-31 10:21:25',
            'private' => 0,
            'type' => 'post',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2018-10-31 13:21:25',
            'updated_at' => '2018-10-31 13:21:25',
        ));
    $posts[] = \Corals\Modules\CMS\Models\Post::updateOrCreate(['slug' => 'lorem-ipsum-dolor-sit-amet', 'type' => 'post'],
        array(
            'title' => 'LOREM IPSUM DOLOR SIT AMET',
            'meta_keywords' => NULL,
            'meta_description' => NULL,
            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ut iaculis arcu. Proin tincidunt, ipsum nec vehicula euismod, neque nibh pretium lorem, at ornare risus sem et risus. Curabitur pulvinar dui viverra libero lobortis in dictum velit luctus. Donec imperdiet tincidunt interdum

Lorem ipsum dolor sit amet, consectetur adipisicing elit, do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim adminim veniam libero lobortis in dictum velit luctus. Donec imperdiet tincidunt interdum.</p>',
            'published' => 1,
            'published_at' => '2018-10-31 10:33:19',
            'private' => 0,
            'type' => 'post',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2018-10-31 10:31:46',
            'updated_at' => '2018-10-31 10:33:19',
        ));
}

if (\Schema::hasTable('categories') && class_exists(\Corals\Modules\CMS\Models\Category::class)) {
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Computers',
        'slug' => 'computers',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Smartphone',
        'slug' => 'smartphone',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Gadgets',
        'slug' => 'gadgets',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Technology',
        'slug' => 'technology',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Engineer',
        'slug' => 'engineer',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Subscriptions',
        'slug' => 'subscriptions',
    ]);
    $categories[] = \Corals\Modules\CMS\Models\Category::updateOrCreate([
        'name' => 'Billing',
        'slug' => 'billing',
    ]);
}
$posts_media = [
    0 => array(
        'id' => 10,
        'model_type' => 'Corals\\Modules\\CMS\\Models\\Post',
        'collection_name' => 'featured-image',
        'name' => 'marketplace_furnitica_one',
        'file_name' => 'marketplace_furnitica_one.jpg',
        'mime_type' => 'image/jpg',
        'disk' => 'media',
        'size' => 50.6,
        'manipulations' => '[]',
        'custom_properties' => '{"root":"demo"}',
        'order_column' => 10,
        'created_at' => '2018-10-31 23:45:51',
        'updated_at' => '2018-10-31 23:45:51',
    ),
    1 => array(
        'id' => 11,
        'model_type' => 'Corals\\Modules\\CMS\\Models\\Post',
        'collection_name' => 'featured-image',
        'name' => 'marketplace_furnitica_two',
        'file_name' => 'marketplace_furnitica_two.jpg',
        'mime_type' => 'image/jpg',
        'disk' => 'media',
        'size' => 38.5,
        'manipulations' => '[]',
        'custom_properties' => '{"root":"demo"}',
        'order_column' => 11,
        'created_at' => '2018-10-31 13:21:25',
        'updated_at' => '2018-10-31 13:21:25',
    ),
    2 => array(
        'id' => 12,
        'model_type' => 'Corals\\Modules\\CMS\\Models\\Post',
        'collection_name' => 'featured-image',
        'name' => 'marketplace_furnitica_three',
        'file_name' => 'marketplace_furnitica_three.jpg',
        'mime_type' => 'image/jpg',
        'disk' => 'media',
        'size' => 75.7,
        'manipulations' => '[]',
        'custom_properties' => '{"root":"demo"}',
        'order_column' => 12,
        'created_at' => '2018-10-31 13:33:19',
        'updated_at' => '2018-10-31 13:33:19',
    ),
];
foreach ($posts as $index => $post) {
    $randIndex = rand(0, 6);
    if (isset($categories[$randIndex])) {
        $category = $categories[$randIndex];
        try {
            \DB::table('category_post')->insert(array(
                array(
                    'post_id' => $post->id,
                    'category_id' => $category->id,
                )
            ));
        } catch (\Exception $exception) {
        }
    }

    if (isset($posts_media[$index])) {
        try {
            $posts_media[$index]['model_id'] = $post->id;
            \DB::table('media')->insert($posts_media[$index]);
        } catch (\Exception $exception) {
        }
    }
}

if (class_exists(\Corals\Menu\Models\Menu::class) && \Schema::hasTable('posts')) {
    // seed root menus
    $topMenu = Corals\Menu\Models\Menu::updateOrCreate(['key' => 'frontend_top'], [
        'parent_id' => 0,
        'url' => null,
        'name' => 'Frontend Top',
        'description' => 'Frontend Top Menu',
        'icon' => null,
        'target' => null,
        'order' => 0
    ]);

    $topMenuId = $topMenu->id;

    // seed children menu
    Corals\Menu\Models\Menu::updateOrCreate(['key' => 'home'], [
        'parent_id' => $topMenuId,
        'url' => '/',
        'active_menu_url' => '/',
        'name' => 'Home',
        'description' => 'Home Menu Item',
        'icon' => 'fa fa-home',
        'target' => null,
        'order' => 0
    ]);
    Corals\Menu\Models\Menu::updateOrCreate(['key' => 'how-to-shop'], [
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'how-to-shop',
        'active_menu_url' => 'how-to-shop',
        'name' => 'How to shop',
        'description' => 'how to shop menu item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
    Corals\Menu\Models\Menu::updateOrCreate(['key' => 'pricing'], [
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'pricing',
        'active_menu_url' => 'pricing',
        'name' => 'Pricing',
        'description' => 'Pricing Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);


    Corals\Menu\Models\Menu::updateOrCreate(['key' => 'contact-us'], [
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'contact-us',
        'active_menu_url' => 'contact-us',
        'name' => 'Contact Us',
        'description' => 'Contact Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);

    $footerMenu = Corals\Menu\Models\Menu::updateOrCreate(['key' => 'frontend_footer'], [
        'parent_id' => 0,
        'url' => null,
        'name' => 'Frontend Footer',
        'description' => 'Frontend Footer Menu',
        'icon' => null,
        'target' => null,
        'order' => 0
    ]);

    $footerMenuId = $footerMenu->id;

// seed children menu
    Corals\Menu\Models\Menu::updateOrCreate(['key' => 'footer_home'], [
        'parent_id' => $footerMenuId,
        'url' => '/',
        'active_menu_url' => '/',
        'name' => 'Home',
        'description' => 'Home Menu Item',
        'icon' => 'fa fa-home',
        'target' => null,
        'order' => 0
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $footerMenuId,
        'key' => null,
        'url' => 'about-us',
        'active_menu_url' => 'about-us',
        'name' => 'About Us',
        'description' => 'About Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $footerMenuId,
        'key' => null,
        'url' => 'contact-us',
        'active_menu_url' => 'contact-us',
        'name' => 'Contact Us',
        'description' => 'Contact Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 980
    ]);
}


$block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Blocks Home', 'key' => 'blocks-home-marketplace'], [
    'name' => 'Blocks Home',
    'key' => 'blocks-home-marketplace',
]);

$widgets = array(
    array(
        'title' => 'Blocks Home',
        'content' => '
                    <div class="feature">
                        <div class="feature__img">
                            <img src="{!! \Theme::url(\'images/feature1.png\') !!}" alt="feature">
                        </div>
                        <div class="feature__title">
                            <h3>Best UX Research</h3>
                        </div>
                        <div class="feature__desc">
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 4,
        'widget_order' => 0,
        'status' => 'active',
    ),
    array(
        'title' => 'Blocks Home',
        'content' => '
                    <div class="feature">
                        <div class="feature__img">
                            <img src="{!! \Theme::url(\'images/feature2.png\') !!}" alt="feature">
                        </div>
                        <div class="feature__title">
                            <h3>Fully Responsive</h3>
                        </div>
                        <div class="feature__desc">
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                    </div>
                ',
        'block_id' => $block->id,
        'widget_width' => 4,
        'widget_order' => 0,
        'status' => 'active',
    ),
    array(
        'title' => 'Blocks Home',
        'content' => '
                  <div class="feature">
                        <div class="feature__img">
                            <img src="{!! \Theme::url(\'images/feature3.png\') !!}" alt="feature">
                        </div>
                        <div class="feature__title">
                            <h3>Buy & Sell Easily</h3>
                        </div>
                        <div class="feature__desc">
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                    </div>
                ',
        'block_id' => $block->id,
        'widget_width' => 4,
        'widget_order' => 0,
        'status' => 'active',
    ),
);
foreach ($widgets as $widget) {
    \Corals\Modules\CMS\Models\Widget::updateOrCreate(
        ['block_id' => $widget['block_id'], 'title' => $widget['title']],
        $widget
    );
}


$block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Why Choose Marty', 'key' => 'blocks-why-choose'], [
    'name' => 'Why Choose Marty',
    'key' => 'blocks-why-choose',
]);


$widgets = array(
    array(
        'title' => 'Why Choose Laraship Marty',
        'content' => '<div class="section-title">
                        <h1>Why Choose
                            <span class="highlighted">Laraship Marty</span>
                        </h1>
                        <p>Laborum dolo rumes fugats untras. Etharums ser quidem rerum facilis dolores nemis omnis
                            fugats.
                            Lid
                            est laborum dolo rumes fugats untras.</p>
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 12,
        'widget_order' => 0,
        'status' => 'active',
    ),
);
foreach ($widgets as $widget) {
    \Corals\Modules\CMS\Models\Widget::updateOrCreate(
        ['block_id' => $widget['block_id'], 'title' => $widget['title']],
        $widget
    );
}


$block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Blocks Home Marty', 'key' => 'blocks-home-marty'], [
    'name' => 'Blocks Home Marty',
    'key' => 'blocks-home-marty',
]);

$widgets = array(
    array(
        'title' => 'Blocks Home Marty 1',
        'content' => '<div class="feature2">
                        <span class="feature2__count">01</span>
                        <div class="feature2__content">
                            <span class="lnr lnr-license pcolor"></span>
                            <h3 class="feature2-title">One Time Payment</h3>
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 4,
        'widget_order' => 1,
        'status' => 'active',
    ),
    array(
        'title' => 'Blocks Home Marty 2',
        'content' => '<div class="feature2">
                        <span class="feature2__count">02</span>
                        <div class="feature2__content">
                            <span class="lnr lnr-magic-wand scolor"></span>
                            <h3 class="feature2-title">Quality Products</h3>
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 4,
        'widget_order' => 2,
        'status' => 'active',
    ),
    array(
        'title' => 'Blocks Home Marty 3',
        'content' => '<div class="feature2">
                        <span class="feature2__count">03</span>
                        <div class="feature2__content">
                            <span class="lnr lnr-lock mcolor1"></span>
                            <h3 class="feature2-title">100% Secure Paymentt</h3>
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 4,
        'widget_order' => 3,
        'status' => 'active',
    ),
    array(
        'title' => 'Blocks Home Marty 4',
        'content' => ' <div class="feature2">
                        <span class="feature2__count">04</span>
                        <div class="feature2__content">
                            <span class="lnr lnr-chart-bars mcolor2"></span>
                            <h3 class="feature2-title">Well Organized Code</h3>
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                        <!-- end /.feature2__content -->
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 4,
        'widget_order' => 4,
        'status' => 'active',
    ),
    array(
        'title' => 'Blocks Home Marty 5',
        'content' => '<div class="feature2">
                        <span class="feature2__count">05</span>
                        <div class="feature2__content">
                            <span class="lnr lnr-leaf mcolor3"></span>
                            <h3 class="feature2-title">Life Time Free Update</h3>
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                        <!-- end /.feature2__content -->
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 4,
        'widget_order' => 5,
        'status' => 'active',
    ),
    array(
        'title' => 'Blocks Home Marty 6',
        'content' => '<div class="feature2">
                        <span class="feature2__count">06</span>
                        <div class="feature2__content">
                            <span class="lnr lnr-phone mcolor4"></span>
                            <h3 class="feature2-title">Fast and Friendly Support</h3>
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                        <!-- end /.feature2__content -->
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 4,
        'widget_order' => 6,
        'status' => 'active',
    ),
);
foreach ($widgets as $widget) {
    \Corals\Modules\CMS\Models\Widget::updateOrCreate(
        ['block_id' => $widget['block_id'], 'title' => $widget['title']],
        $widget
    );
}


$block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Blocks Home Shopping', 'key' => 'blocks-home-shopping'], [
    'name' => 'Blocks Home Shopping',
    'key' => 'blocks-home-shopping',
]);

$widgets = array(
    array(
        'title' => 'Blocks Home Sell',
        'content' => '<div class="no-padding">
                    <div class="proposal proposal--left bgimage">
                        <div class="bg_image_holder">
                            <img src="{!! \Theme::url(\'images/bbg.png\') !!}" alt="prop image">
                        </div>
                        <div class="content_above">
                            <div class="proposal__icon ">
                                <img src="{!! \Theme::url(\'images/buy.png\') !!}" alt="Buy icon">
                            </div>
                            <div class="proposal__content ">
                                <h1 class="text--white">Sell Your Products</h1>
                                <p class="text--white">Nunc placerat mi id nisi interdum mollis. Praesent pharetra,
                                    justo ut
                                    scelerisque the mattis,
                                    leo quam aliquet diamcongue is laoreet elit metus.</p>
                            </div>
                            <a href="#" class="btn--round btn btn--lg btn--white">Become an Author</a>
                        </div>
                    </div>
                    <!-- end /.proposal -->
                </div>',
        'block_id' => $block->id,
        'widget_width' => 6,
        'widget_order' => 1,
        'status' => 'active',
    ),
    array(
        'title' => 'Blocks Home Shopping',
        'content' => '<div class="no-padding">
                    <div class="proposal proposal--right">
                        <div class="bg_image_holder">
                            <img src="{!! \Theme::url(\'images/sbg.png\') !!}" alt="this is magic">
                        </div>
                        <div class="content_above">
                            <div class="proposal__icon">
                                <img src="{!! \Theme::url(\'images/sell.png\') !!}" alt="Sell icon">
                            </div>
                            <div class="proposal__content ">
                                <h1 class="text--white">Start Shopping Today</h1>
                                <p class="text--white">Nunc placerat mi id nisi interdum mollis. Praesent pharetra,
                                    justo ut
                                    scelerisque the mattis,
                                    leo quam aliquet diamcongue is laoreet elit metus.</p>
                            </div>
                            <a href="#" class="btn--round btn btn--lg btn--white">Start Shopping</a>
                        </div>
                    </div>
                </div>',
        'block_id' => $block->id,
        'widget_width' => 6,
        'widget_order' => 2,
        'status' => 'active',
    ),
);

foreach ($widgets as $widget) {
    \Corals\Modules\CMS\Models\Widget::updateOrCreate(
        ['block_id' => $widget['block_id'], 'title' => $widget['title']],
        $widget
    );
}


$block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Blocks Home Support', 'key' => 'blocks-home-support'], [
    'name' => 'Blocks Home Support',
    'key' => 'blocks-home-support',
]);

$widgets = array(
    array(
        'title' => 'Blocks Home Shopping',
        'content' => '<div class="special-feature feature--2">
                        <img src="{!! \Theme::url(\'images/spf2.png\') !!}" alt="Special Feature image">
                        <h3 class="special__feature-title">Fast and Friendly
                            <span class="highlight">Support</span>
                        </h3>
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 6,
        'widget_order' => 0,
        'status' => 'active',
    ),
    array(
        'title' => 'Blocks Home Sell',
        'content' => '<div class="special-feature feature--1">
                        <img src="{!! \Theme::url(\'images/spf1.png\') !!}" alt="Special Feature image">
                        <h3 class="special__feature-title">30 Days Money Back
                            <span class="highlight">Guarantee</span>
                        </h3>
                    </div>',
        'block_id' => $block->id,
        'widget_width' => 6,
        'widget_order' => 1,
        'status' => 'active',
    )
);

foreach ($widgets as $widget) {
    \Corals\Modules\CMS\Models\Widget::updateOrCreate(
        ['block_id' => $widget['block_id'], 'title' => $widget['title']],
        $widget
    );
}


$block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Call to Action', 'key' => 'call-to-action'], [
    'name' => 'Call to Action',
    'key' => 'call-to-action',
]);

$widgets = array(
    array(
        'title' => 'Call to Action Widget',
        'content' => '        <div class="bg_image_holder">
            <img src="' . \Theme::url('images/calltobg.jpg') . '" alt="">
        </div>
        <div class="container content_above">
            <div class="row">
                <div class="col-md-12">
                    <div class="call-to-wrap">
                        <h1 class="text--white">Ready to Join Our Marketplace!</h1>
                        <h4 class="text--white">Over 25,000 designers and developers trust the Marty.</h4>
                        <a href="' . url('register') . '" class="btn btn--lg btn--round btn--white callto-action-btn">Join Us Today</a>
                    </div>
                </div>
            </div>
        </div>',
        'block_id' => $block->id,
        'widget_width' => 12,
        'widget_order' => 1,
        'status' => 'active',
    )
);

foreach ($widgets as $widget) {
    \Corals\Modules\CMS\Models\Widget::updateOrCreate(
        ['block_id' => $widget['block_id'], 'title' => $widget['title']],
        $widget
    );
}
