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
            'content' => '@slider(furnitica-slider)',
            'published' => 1,
            'published_at' => '2017-11-16 14:26:52',
            'private' => 0,
            'template' => 'home',
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2018-11-01 16:27:04',
            'updated_at' => '2018-11-01 16:27:07',
        ));
    \Corals\Modules\CMS\Models\Page::updateOrCreate(['slug' => 'about-us', 'type' => 'page'],
        array(
            'title' => 'About Us',
            'meta_keywords' => 'about us',
            'meta_description' => 'about us',
            'content' => '
<div id="about-us">
    <div class="about-us-content">	
									<h1 class="title-page">About Us</h1>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 right">
											<a href="#">
												<img class="img-fluid" src="/assets/themes/marketplace-pro/img/1.jpg" alt="#">
											</a>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 left">
											<div class="cms-block f-right">
												<h3 class="page-subheading">WHO WE ARE</h3>
												<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem quis biben
													dum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis
													sed odio sit amet nibh vultate cursus a sit amet mauris. Duis sed odio sit
												amet nibh vultate cursus a sit amet mauris.</p>
												<p>Proin gravida nibh vel velit auctor aliquet. nec sagittis sem nibh id elit. Duis
													sed odio sit amet nibh vultate cursus a sit amet mauris. Duis sed odio sit
												amet nibh vultate cursus a sit amet mauris.</p>
												<a>
													<img class="img-fluid" src="/assets/themes/marketplace-pro/img/4.png" alt="#">
													<span>Mr. kwang shang - CEO</span>
												</a>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 right">
											<div class="cms-block f-left">
												<h3 class="page-subheading">WHAT WE DO</h3>
												<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem quis biben
													dum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis
													sed odio sit amet nibh vultate cursus a sit amet mauris. Duis sed odio sit
												amet nibh vultate cursus a sit amet mauris.</p>
												<p>Proin gravida nibh vel velit auctor aliquet, nec sagittis sem nibh id elit. Duis
													sed odio sit amet nibh vultate cursus a sit amet mauris. Duis sed odio sit
												amet nibh vultate cursus a sit amet mauris.</p>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 left">
											<a href="#">
												<img class="img-fluid" src="/assets/themes/marketplace-pro/img/2.jpg" alt="#">
											</a>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 right">
											<a href="#">
												<img class="img-fluid" src="/assets/themes/marketplace-pro/img/3.jpg" alt="#">
											</a>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 left">
											<div class="cms-block f-right">
												<h3 class="page-subheading no-before">MEET OUR TEAM</h3>
												<div class="testimonials owl-carousel owl-theme owl-loaded owl-drag">
												<div class="owl-stage-outer"><div class="owl-stage" style="transform: translate3d(-1940px, 0px, 0px); transition: all 0.25s ease 0s; width: 3880px;"><div class="owl-item cloned" style="width: 475px; margin-right: 10px;"><div class="item">
														<div class="name">Peter Capidal</div>
														<div class="position">Front-end Developer</div>
														<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem
															quis biben dum auctor, nisi elit consequat ipsum, nec sagittis sem
															nibh id elit. Duis sed odio sit amet nibh vultate cursus a sit amet
															mauris. Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin,
															lorem quis bibendum auctor, nisi elit conse quat ipsum
														</p>
													</div></div><div class="owl-item cloned" style="width: 475px; margin-right: 10px;"><div class="item">
														<div class="name">David James</div>
														<div class="position">Developer</div>
														<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem
															quis biben dum auctor, nisi elit consequat ipsum, nec sagittis sem
															nibh id elit. Duis sed odio sit amet nibh vultate cursus a sit amet
															mauris.Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin,
															lorem quis bibendum auctor, nisi elit conse quat ipsum
														</p>
													</div></div><div class="owl-item" style="width: 475px; margin-right: 10px;"><div class="item">
														<div class="name">William James</div>
														<div class="position">Designer - Stylish</div>
														<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem
															quis biben dum auctor, nisi elit consequat ipsum, nec sagittis sem
															nibh id elit. Duis sed odio sit amet nibh vultate cursus a sit amet
															mauris. Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin,
															lorem quis bibendum auctor, nisi elit conse quat ipsum
														</p>
													</div></div><div class="owl-item" style="width: 475px; margin-right: 10px;"><div class="item">
														<div class="name">Seller Smith</div>
														<div class="position">Web developer</div>
														<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem
															quis biben dum auctor, nisi elit consequat ipsum, nec sagittis sem
															nibh id elit. Duis sed odio sit amet nibh vultate cursus a sit amet
															mauris.
														</p>
													</div></div><div class="owl-item active" style="width: 475px; margin-right: 10px;"><div class="item">
														<div class="name">Peter Capidal</div>
														<div class="position">Front-end Developer</div>
														<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem
															quis biben dum auctor, nisi elit consequat ipsum, nec sagittis sem
															nibh id elit. Duis sed odio sit amet nibh vultate cursus a sit amet
															mauris. Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin,
															lorem quis bibendum auctor, nisi elit conse quat ipsum
														</p>
													</div></div><div class="owl-item" style="width: 475px; margin-right: 10px;"><div class="item">
														<div class="name">David James</div>
														<div class="position">Developer</div>
														<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem
															quis biben dum auctor, nisi elit consequat ipsum, nec sagittis sem
															nibh id elit. Duis sed odio sit amet nibh vultate cursus a sit amet
															mauris.Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin,
															lorem quis bibendum auctor, nisi elit conse quat ipsum
														</p>
													</div></div><div class="owl-item cloned" style="width: 475px; margin-right: 10px;"><div class="item">
														<div class="name">William James</div>
														<div class="position">Designer - Stylish</div>
														<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem
															quis biben dum auctor, nisi elit consequat ipsum, nec sagittis sem
															nibh id elit. Duis sed odio sit amet nibh vultate cursus a sit amet
															mauris. Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin,
															lorem quis bibendum auctor, nisi elit conse quat ipsum
														</p>
													</div></div><div class="owl-item cloned" style="width: 475px; margin-right: 10px;"><div class="item">
														<div class="name">Seller Smith</div>
														<div class="position">Web developer</div>
														<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicudin, lorem
															quis biben dum auctor, nisi elit consequat ipsum, nec sagittis sem
															nibh id elit. Duis sed odio sit amet nibh vultate cursus a sit amet
															mauris.
														</p>
													</div></div></div></div><div class="owl-nav disabled"><div class="owl-prev">prev</div><div class="owl-next">next</div></div><div class="owl-dots"><div class="owl-dot"><span></span></div><div class="owl-dot"><span></span></div><div class="owl-dot active"><span></span></div><div class="owl-dot"><span></span></div></div></div>
												<div class="social-content">
													<div class="social">
														<ul class="list-inline mb-0 justify-content-end">
															<li class="list-inline-item mb-0">
																<a href="#" target="_blank">
																	<i class="fa fa-facebook"></i>
																</a>
															</li>
															<li class="list-inline-item mb-0">
																<a href="#" target="_blank">
																	<i class="fa fa-twitter"></i>
																</a>
															</li>
															<li class="list-inline-item mb-0">
																<a href="#" target="_blank">
																	<i class="fa fa-google"></i>
																</a>
															</li>
															<li class="list-inline-item mb-0">
																<a href="#" target="_blank">
																	<i class="fa fa-instagram"></i>
																</a>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								</div>
',
            'published' => 1,
            'published_at' => '2017-11-16 11:56:34',
            'private' => 0,
            'type' => 'page',
            'template' => NULL,
            'author_id' => 1,
            'deleted_at' => NULL,
            'created_at' => '2017-11-16 11:56:34',
            'updated_at' => '2017-11-16 11:56:34',
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
            'meta_description' => 'Pricing',
            'content' => '<div class="text-center">
<h2>Pricing</h2>

<p class="lead">Easy and Powerful products and plans management.</p>
</div>',
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
            'content' => '<div class="text-center"><h2>Drop Your Message</h2><p class="lead" style="text-align: center;">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>',
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
    Corals\Menu\Models\Menu::updateOrCreate([
        'parent_id' => $topMenuId,
        'key' => null,
        'url' => 'about-us',
        'active_menu_url' => 'about-us',
        'name' => 'About Us',
        'description' => 'About Us Menu Item',
        'icon' => null,
        'target' => null,
        'order' => 970
    ]);
    Corals\Menu\Models\Menu::updateOrCreate([
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


    Corals\Menu\Models\Menu::updateOrCreate([
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

if (\Schema::hasTable('cms_blocks') && class_exists(\Corals\Modules\CMS\Models\Block::class)) {
    $block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Home Title Page', 'key' => 'home-title-page'], [
        'name' => 'Home Title Page',
        'key' => 'home-title-page',
    ]);

    $widgets = array(
        array(
            'title' => 'Free Worldwide Shipping',
            'content' => '<div class="block">
                                            <div class="block-content">
                                                <div class="policy-item">
                                                    <div class="policy-content iconpolicy1">
                                                        <img src="/assets/themes/marketplace-pro/img/home1-policy.png"
                                                             alt="img">
                                                        <div class="policy-name mb-5">FREE DELIVERY FROM $ 250</div>
                                                        <div class="policy-des">Lorem ipsum dolor amet consectetur</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>',
            'block_id' => $block->id,
            'widget_width' => 4,
            'widget_order' => 0,
            'status' => 'active',
        ),
        array(
            'title' => 'FREE INSTALLATION',
            'content' => '<div class="block">
                                            <div class="block-content">
                                                <div class="policy-item">
                                                    <div class="policy-content iconpolicy1">
                                                        <img src="/assets/themes/marketplace-pro/img/home1-policy2.png"
                                                             alt="img">
                                                        <div class="policy-name mb-5">FREE INSTALLATION</div>
                                                        <div class="policy-des">Lorem ipsum dolor amet consectetur</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>',
            'block_id' => $block->id,
            'widget_width' => 4,
            'widget_order' => 1,
            'status' => 'active',
        ),
        array(
            'title' => 'MONEY BACK GUARANTEED',
            'content' => '<div class="block">
                                            <div class="block-content">
                                                <div class="policy-item">
                                                    <div class="policy-content iconpolicy1">
                                                        <img src="/assets/themes/marketplace-pro/img/home1-policy3.png"
                                                             alt="img">
                                                        <div class="policy-name mb-5">MONEY BACK GUARANTEED</div>
                                                        <div class="policy-des">Lorem ipsum dolor amet consectetur</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>',
            'block_id' => $block->id,
            'widget_width' => 4,
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
}

if (\Schema::hasTable('cms_blocks') && class_exists(\Corals\Modules\CMS\Models\Block::class)) {
    $block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Shop The Look', 'key' => 'shop-the-look'], [
        'name' => 'Shop The Look',
        'key' => 'shop-the-look',
    ]);

    $widgets = array(
        array(
            'title' => 'SHOP THE LOOK',
            'content' => '<div class="title-block">
                                <span class="sub-title">shop the lookbook</span>
                                <span>SHOP THE LOOK</span>
                                <span>Our Lookbook 2018
                                    <br> hand-picked arrivals from
                                    <br>the best designer</span>
                            </div>',
            'block_id' => $block->id,
            'widget_width' => false,
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
}

if (\Schema::hasTable('cms_blocks') && class_exists(\Corals\Modules\CMS\Models\Block::class)) {
    $block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Pre Home Block', 'key' => 'pre-home-block'], [
        'name' => 'Pre Home Block',
        'key' => 'pre-home-block',
    ]);

    $widgets = array(
        array(
            'title' => 'pre-block-one',
            'content' => '<div class="tiva-lookbook default">
                                    <div class="row">
                                        <div class="items col-lg-12 col-sm-12 col-xs-12">
                                            <div class="tiva-content-lookbook">
                                                <img class="img-fluid img-responsive" src="/media/demo/marketplace-pro/home1-tolltip1.jpg" alt="lookbook">
                                                
												<div class="item-lookbook item1">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/icon-tolltip2.jpg" alt="lorem-ipsum-dolor-sit-amet">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Lorem ipsum dolor</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £52.00
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												
                                                <div class="item-lookbook item2">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/icon-tolltip1.jpg" alt="contrary-to-popular-belief">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Sed vel malesuada lorem</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £68.00
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>',
            'block_id' => $block->id,
            'widget_order' => 1,
            'widget_width' => 6,
            'status' => 'active',
        ),
        array(
            'title' => 'pre-block-two',
            'content' => '<div class="tiva-lookbook default">
                                    <div class="row">
                                        <div class="items col-lg-12 col-sm-12 col-xs-12">
                                            <div class="tiva-content-lookbook">
                                                <img class="img-fluid img-responsive" src="/media/demo/marketplace-pro/home1-tolltip2.jpg" alt="lookbook">
                                                
												<div class="item-lookbook item3">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/34.jpg" alt="lorem-ipsum-dolor-sit-amet">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Lorem ipsum dolor sit</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £45.00
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												
                                                <div class="item-lookbook item4">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/icon-tolltip6.jpg" alt="lorem-ipsum-dolor-sit-amet">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Lorem ipsum dolor</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £21.00
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												
                                                <div class="item-lookbook item5">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/icon-tolltip4.jpg" alt="mug-the-adventure-begins">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Sed vel malesuada lorem</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £11.90
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-lookbook">
                                            </div>
                                        </div>
                                    </div>
                                </div>',
            'block_id' => $block->id,
            'widget_order' => 2,
            'widget_width' => 6,
            'status' => 'active',
        ),
        array(
            'title' => 'pre-block-three',
            'content' => '<div class="tiva-lookbook default">
                                    <div class="row">
                                        <div class="items col-lg-12 col-sm-12 col-xs-12">
                                            <div class="tiva-content-lookbook">
                                                <img class="img-fluid img-responsive" src="/media/demo/marketplace-pro/home1-tolltip3.jpg" alt="lookbook">
                                                
												<div class="item-lookbook item6">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/icon-tolltip4.jpg" alt="mug-the-adventure-begins">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Sed vel malesuada lorem</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £11.90
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												
                                                <div class="item-lookbook item7">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/13.jpg" alt="brown-bear-vector-graphics">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Orci varius natoque penatibus</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £9.00
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												
                                                <div class="item-lookbook item8">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/icon-tolltip6.jpg" alt="lorem-ipsum-dolor-sit-amet">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Etiam congue nisl nec</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £16.00
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
											
                                            <div class="info-lookbook">
                                            </div>
                                        </div>
                                    </div>
                                </div>',
            'block_id' => $block->id,
            'widget_order' => 3,
            'widget_width' => 6,
            'status' => 'active',
        ),
        array(
            'title' => 'pre-block-four',
            'content' => '<div class="tiva-lookbook default">
                                    <div class="row">
                                        <div class="items col-lg-12 col-sm-12 col-xs-12">
                                            <div class="tiva-content-lookbook">
                                                <img class="img-fluid img-responsive" src="/media/demo/marketplace-pro/home1-tolltip4.jpg" alt="lookbook">
                                                
												<div class="item-lookbook item9">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/icon-tolltip10.jpg" alt="lorem-ipsum-dolor-sit-amet">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Nam semper a ligula nec</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £41.00
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												
                                                <div class="item-lookbook item10">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/icon-tolltip8.jpg" alt="lorem-ipsum-dolor-sit-amet">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Donec accumsan lectus ut</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £11.00
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												
                                                <div class="item-lookbook item11">
                                                    <span class="number-lookbook">+</span>
                                                    <div class="content-lookbook">
                                                        <div class="main-lookbook  d-flex align-items-center">
                                                            <div class="item-thumb">
                                                                <a href="/shop">
                                                                    <img src="/media/demo/marketplace-pro/icon-tolltip9.jpg" alt="lorem-ipsum-dolor-sit-amet">
                                                                </a>
                                                            </div>
                                                            <div class="content-bottom">
                                                                <div class="item-title">
                                                                    <a href="/shop">Fusce quis felis libero</a>
                                                                </div>
                                                                <div class="rating">
                                                                    <div class="star-content">
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                        <div class="star"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="item-price">
                                                                    £11.00
                                                                </div>
                                                                <div class="readmore">
                                                                    <a href="/shop">View More</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
											
                                            <div class="info-lookbook">
                                            </div>
                                        </div>
                                    </div>
                                </div>',
            'block_id' => $block->id,
            'widget_order' => 4,
            'widget_width' => 6,
            'status' => 'active',
        ),
    );
    foreach ($widgets as $widget) {
        \Corals\Modules\CMS\Models\Widget::updateOrCreate(
            ['block_id' => $widget['block_id'], 'title' => $widget['title']],
            $widget
        );
    }
}

if (\Schema::hasTable('cms_blocks') && class_exists(\Corals\Modules\CMS\Models\Block::class)) {
    $block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Pre Home Block Slider', 'key' => 'pre-home-block-slider'], [
        'name' => 'Pre Home Block Slider',
        'key' => 'pre-home-block-slider',
    ]);

    $widgets = array(
        array(
            'title' => 'pre-home-block-slider',
            'content' => '<div class="owl-carousel owl-theme testimonial-type-one">
                                                <div class="item type-one d-flex align-items-center flex-column">
                                                    <div class="textimonial-image">
                                                        <i class="icon-testimonial"></i>
                                                    </div>
                                                    <div class="desc-testimonial">
                                                        <div class="testimonial-content">
                                                            <div class="text">
                                                                <p>" Liquam quis risus viverra, ornare ipsum vitae, congue tellus.
                                                                    Vestibulum nunc lorem, scelerisque a tristique non, accumsan
                                                                    ornare eros. Nullam sapien metus, volutpat dictum, accumsan
                                                                    ornare eros. Nullam sapien metus, volutpat dictum "</p>
                                                            </div>
                                                        </div>
                                                        <div class="testimonial-info">
                                                            <h5 class="mt-0 box-info">David Jame</h5>
                                                            <p class="box-dress">DESIGNER</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item type-one d-flex align-items-center flex-column">
                                                    <div class="textimonial-image">
                                                        <i class="icon-testimonial"></i>
                                                    </div>
                                                    <div class="desc-testimonial">
                                                        <div class="testimonial-content">
                                                            <div class="text">
                                                                <p>" Liquam quis risus viverra, ornare ipsum vitae, congue tellus.
                                                                    Vestibulum nunc lorem, scelerisque a tristique non, accumsan
                                                                    ornare eros. Nullam sapien metus, volutpat dictum, accumsan
                                                                    ornare eros. Nullam sapien metus, volutpat dictum "</p>
                                                            </div>
                                                        </div>
                                                        <div class="testimonial-info">
                                                            <h5 class="mt-0 box-info">Max Control</h5>
                                                            <p class="box-dress">DEVELOPER</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item type-one d-flex align-items-center flex-column">
                                                    <div class="textimonial-image">
                                                        <i class="icon-testimonial"></i>
                                                    </div>
                                                    <div class="desc-testimonial">
                                                        <div class="testimonial-content">
                                                            <div class="text">
                                                                <p>" Liquam quis risus viverra, ornare ipsum vitae, congue tellus.
                                                                    Vestibulum nunc lorem, scelerisque a tristique non, accumsan
                                                                    ornare eros. Nullam sapien metus, volutpat dictum, accumsan
                                                                    ornare eros. Nullam sapien metus, volutpat dictum "</p>
                                                            </div>
                                                        </div>
                                                        <div class="testimonial-info">
                                                            <h5 class="mt-0 box-info">John Do</h5>
                                                            <p class="box-dress">CSS - HTML</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="item type-one d-flex align-items-center flex-column">
                                                    <div class="textimonial-image">
                                                        <i class="icon-testimonial"></i>
                                                    </div>
                                                    <div class="desc-testimonial">
                                                        <div class="testimonial-content">
                                                            <div class="text">
                                                                <p>" Liquam quis risus viverra, ornare ipsum vitae, congue tellus.
                                                                    Vestibulum nunc lorem, scelerisque a tristique non, accumsan
                                                                    ornare eros. Nullam sapien metus, volutpat dictum, accumsan
                                                                    ornare eros. Nullam sapien metus, volutpat dictum "</p>
                                                            </div>
                                                        </div>
                                                        <div class="testimonial-info">
                                                            <h5 class="mt-0 box-info">Elizabeth Pham</h5>
                                                            <p class="box-dress">DEVELOPER</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                            
',
            'block_id' => $block->id,
            'widget_order' => 1,
            'widget_width' => 12,
            'status' => 'active',
        ),
    );
    foreach ($widgets as $widget) {
        \Corals\Modules\CMS\Models\Widget::updateOrCreate(
            ['block_id' => $widget['block_id'], 'title' => $widget['title']],
            $widget
        );
    }
}

if (\Schema::hasTable('cms_blocks') && class_exists(\Corals\Modules\CMS\Models\Block::class)) {
    $block = \Corals\Modules\CMS\Models\Block::updateOrCreate(['name' => 'Best Sellers', 'key' => 'best-sellers'], [
        'name' => 'Best Sellers',
        'key' => 'best-sellers',
    ]);

    $widgets = array(
        array(
            'title' => 'best_sellers',
            'content' => '<h2 class="title-block">
                                <span class="sub-title">Best sellers Products</span>Best Sellers
                            </h2>
                            <div class="content-text">
                                <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                    tempor incididunt ut labore dolore magna aliqua.
                                </p>
                                <div>
                                    <a href="{{ url(\'/shop\') }}">@lang(\'corals-marketplace-pro::labels.partial.shop_now\')</a>
                                </div>
                            </div>',
            'block_id' => $block->id,
            'widget_order' => 1,
            'widget_width' => false,
            'status' => 'active',
        ),
    );
    foreach ($widgets as $widget) {
        \Corals\Modules\CMS\Models\Widget::updateOrCreate(
            ['block_id' => $widget['block_id'], 'title' => $widget['title']],
            $widget
        );
    }
}
