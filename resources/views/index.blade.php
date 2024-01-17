<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>Laraship Project</title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&amp;amp;subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="fonts/Linearicons/Linearicons/Font/demo-files/demo.css">
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/owl-carousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="plugins/owl-carousel/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="plugins/slick/slick/slick.css">
    <link rel="stylesheet" href="plugins/nouislider/nouislider.min.css">
    <link rel="stylesheet" href="plugins/lightGallery-master/dist/css/lightgallery.min.css">
    <link rel="stylesheet" href="plugins/jquery-bar-rating/dist/themes/fontawesome-stars.css">
    <link rel="stylesheet" href="plugins/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header class="header header--1" data-sticky="true">
        <div class="header__top" style="background-color: #091E08">
            <div class="ps-container">
                <div class="header__left">
                    <div class="menu--product-categories">
                        <div class="menu__toggle"><i class="icon-menu"></i><span> Shop by Department</span></div>
                        <div class="menu__content">
                            <ul class="menu--dropdown">
                            @foreach(\Shop::getActiveCategories() as $category)
                                <li class="menu-item-has-children has-mega-menu"><a href="#"><i class="icon-laundry"></i>{{$category->name}}</a>
                                    <div class="mega-menu">
                                        <div class="mega-menu__column">
                                            <h4>Electronic<span class="sub-toggle"></span></h4>
                                            <ul class="mega-menu__list">
                                                <li><a href="#">Home Audio &amp; Theathers</a>
                                                </li>
                                               
                                            </ul>
                                        </div>
                                        
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div><a class="ps-logo" href="index.html"><img src="img/logo_light.png" alt=""></a>
                </div>
                <div class="header__center">
                    <form class="ps-form--quick-search" action="index.html" method="get">
                        <div class="form-group--icon"><i class="icon-chevron-down"></i>
                            <select class="form-control" >
                                <option style="border:solid 1px white; border-radius: 5px;"  value="0" selected="selected">All</option>
                                @foreach(\Shop::getActiveCategories() as $category)
                                <option style="border:solid 1px white; border-radius: 5px;" class="level-1" value="wine-cabinets"> {{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input class="form-control" type="text" placeholder="I'm shopping for..." id="input-search">
                        <button style="border: solid 1px white;">Search</button>
                        <div class="ps-panel--search-result">
                            <div class="ps-panel__content">
                                <div class="ps-product ps-product--wide ps-product--search-result">
                                    <div class="ps-product__thumbnail"><a href="product-default.html"><img src="img/products/arrivals/1.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">Apple iPhone Retina 6s Plus 32GB</a>
                                        <div class="ps-product__rating">
                                            <select class="ps-rating" data-read-only="true">
                                                <option value="1">1</option>
                                                <option value="1">2</option>
                                                <option value="1">3</option>
                                                <option value="1">4</option>
                                                <option value="2">5</option>
                                            </select><span></span>
                                        </div>
                                        <p class="ps-product__price">$990.50</p>
                                    </div>
                                </div>
                                <div class="ps-product ps-product--wide ps-product--search-result">
                                    <div class="ps-product__thumbnail"><a href="product-default.html"><img src="img/products/arrivals/1.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">Apple iPhone Retina 6s Plus 64GB</a>
                                        <div class="ps-product__rating">
                                            <select class="ps-rating" data-read-only="true">
                                                <option value="1">1</option>
                                                <option value="1">2</option>
                                                <option value="1">3</option>
                                                <option value="1">4</option>
                                                <option value="2">5</option>
                                            </select><span></span>
                                        </div>
                                        <p class="ps-product__price">$1120.50</p>
                                    </div>
                                </div>
                                <div class="ps-product ps-product--wide ps-product--search-result">
                                    <div class="ps-product__thumbnail"><a href="product-default.html"><img src="img/products/arrivals/1.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">Apple iPhone Retina 6s Plus 128GB</a>
                                        <div class="ps-product__rating">
                                            <select class="ps-rating" data-read-only="true">
                                                <option value="1">1</option>
                                                <option value="1">2</option>
                                                <option value="1">3</option>
                                                <option value="1">4</option>
                                                <option value="2">5</option>
                                            </select><span></span>
                                        </div>
                                        <p class="ps-product__price">$1220.50</p>
                                    </div>
                                </div>
                                <div class="ps-product ps-product--wide ps-product--search-result">
                                    <div class="ps-product__thumbnail"><a href="product-default.html"><img src="img/products/arrivals/2.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">Marshall Kilburn Portable Wireless Speaker</a>
                                        <div class="ps-product__rating">
                                            <select class="ps-rating" data-read-only="true">
                                                <option value="1">1</option>
                                                <option value="1">2</option>
                                                <option value="1">3</option>
                                                <option value="1">4</option>
                                                <option value="2">5</option>
                                            </select><span>01</span>
                                        </div>
                                        <p class="ps-product__price">$36.78 – $56.99</p>
                                    </div>
                                </div>
                                <div class="ps-product ps-product--wide ps-product--search-result">
                                    <div class="ps-product__thumbnail"><a href="product-default.html"><img src="img/products/arrivals/3.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">Herschel Leather Duffle Bag In Brown Color</a>
                                        <div class="ps-product__rating">
                                            <select class="ps-rating" data-read-only="true">
                                                <option value="1">1</option>
                                                <option value="1">2</option>
                                                <option value="1">3</option>
                                                <option value="1">4</option>
                                                <option value="2">5</option>
                                            </select><span>02</span>
                                        </div>
                                        <p class="ps-product__price">$125.30</p>
                                    </div>
                                </div>
                                <div class="ps-product ps-product--wide ps-product--search-result">
                                    <div class="ps-product__thumbnail"><a href="product-default.html"><img src="img/products/arrivals/4.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">Xbox One Wireless Controller Black Color</a>
                                        <div class="ps-product__rating">
                                            <select class="ps-rating" data-read-only="true">
                                                <option value="1">1</option>
                                                <option value="1">2</option>
                                                <option value="1">3</option>
                                                <option value="1">4</option>
                                                <option value="2">5</option>
                                            </select><span>02</span>
                                        </div>
                                        <p class="ps-product__price">$55.30</p>
                                    </div>
                                </div>
                                <div class="ps-product ps-product--wide ps-product--search-result">
                                    <div class="ps-product__thumbnail"><a href="product-default.html"><img src="img/products/arrivals/5.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">Grand Slam Indoor Of Show Jumping Novel</a>
                                        <div class="ps-product__rating">
                                            <select class="ps-rating" data-read-only="true">
                                                <option value="1">1</option>
                                                <option value="1">2</option>
                                                <option value="1">3</option>
                                                <option value="1">4</option>
                                                <option value="2">5</option>
                                            </select><span>02</span>
                                        </div>
                                        <p class="ps-product__price sale">$41.27 <del>$52.99 </del></p>
                                    </div>
                                </div>
                                <div class="ps-product ps-product--wide ps-product--search-result">
                                    <div class="ps-product__thumbnail"><a href="product-default.html"><img src="img/products/arrivals/6.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">Sound Intone I65 Earphone White Version</a>
                                        <div class="ps-product__rating">
                                            <select class="ps-rating" data-read-only="true">
                                                <option value="1">1</option>
                                                <option value="1">2</option>
                                                <option value="1">3</option>
                                                <option value="1">4</option>
                                                <option value="2">5</option>
                                            </select><span>01</span>
                                        </div>
                                        <p class="ps-product__price sale">$41.27 <del>$62.39 </del></p>
                                    </div>
                                </div>
                            </div>
                            <div class="ps-panel__footer text-center"><a href="shop-default.html">See all results</a></div>
                        </div>
                    </form>
                </div>
                <div class="header__right" style="color:white">
                    <div class="header__actions"><a class="header__extra" href="#"><i class="icon-chart-bars"></i><span style="border: solid 1px white;"><i>0</i></span></a><a class="header__extra" href="#"><i class="icon-heart"></i><span style="border: solid 1px white;"><i>0</i></span></a>
                        <div class="ps-cart--mini"><a class="header__extra" href="#"><i class="icon-bag2"></i><span style="border: solid 1px white;"><i>{{ \ShoppingCart::countAllInstances() }}</i></span></a>
                            <div class="ps-cart__content" style="border: solid 1px #091E08; border-radius:5px;">
                                <div class="ps-cart__items">
                                    <div class="ps-product--cart-mobile" style="border: solid 1px white;"> 
                                     <table>
                <tbody>
                @foreach($items = \ShoppingCart::getAllInstanceItems() as $item)
                    <tr  >
                        <td class="product-image">
                            <a href="{{ url('shop', [$item->id->product->slug]) }}">
                                <img src="{{ $item->id->image }}" alt="Product">
                            </a>
                        </td>
                        <td>
                            <div class="product-name">
                                <a style="color:black" href="{{ url('shop', [$item->id->product->slug]) }}">{!! $item->id->product->name !!}</a>
                            </div>
                            <div style="color:black">
                                {!! $item->qty !!} x
                                <span style="color:black" class="product-price"
                                      id="item-total-{{$item->getHash()}}">{{ $item->subtotal() }}</span>
                            </div>
                        </td>
                        <td class="action">
                            <a style="margin-left: 40px;" class="remove" rel="nofollow"
                               href="{{ url('cart/quantity', [$item->getHash()]) }}"
                               data-action="post" data-style="zoom-in"
                               data-request_data='@json(["action"=>"removeItem"])'
                               data-page_action="updateCart"
                               data-toggle="tooltip"
                               >
                                <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                <tr class="total" style="border-top: dotted 1px #091E08">
                    <td style="color:black" colspan="2">@lang('corals-marketplace-pro::labels.template.cart.subtotal')
                        :
                    </td>
                    <td>
                        <span style="color:black" id="total">{{ \ShoppingCart::totalAllInstances() }}</span>
                        @if(Settings::get('marketplace_tax_tax_included_in_price'))
                            <small style="color:black" style="font-size: 9px">@lang('Marketplace::attributes.product.tax_included') </small>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="d-flex justify-content-center">

                    </td>
                </tr>
                </tbody>
            </table>
                                    </div>
                                   
                                </div>
                                <div class="ps-cart__footer">
                                
                                   
                                    <figure><a class="ps-btn" style="background-color: #091E08; color:white" href="{{ url('cart')}}">View Cart</a><a style="background-color: #091E08; color:white" class="ps-btn" href="{{url('checkout')}}">Checkout</a></figure>
                                </div>
                            </div>
                        </div>
                        <div class="ps-block--user-header">

                            <div class="ps-block__right">
                                
                            @if (Route::has('login'))
        
        @auth
        
            <a href="{{ url('/dashboard') }}"><img style="border:solid 2px white; border-radius:50px; height:33px; width: 33px; object-fit: contain" src="{{ user()->picture_thumb }}"> Dashboard</a>
        @else
       
            <a href="{{ route('login') }}"><i style="font-size: 27px"  class="icon-user"></i></a>
        @endauth

@endif
                        
                        
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="navigation" style="background-color: #091E08; border-top: 1px solid white; color: white">
            <div class="ps-container">
                <div class="navigation__left">
                    <div class="menu--product-categories">
                        <div class="menu__toggle"><i class="icon-menu"></i><span style="color:white"> Shop by Categories</span></div>
                        <div class="menu__content">
                            <ul class="menu--dropdown" style="background-color:#091E08; opacity: 0.8; border-radius: 5px;">
                            @foreach(\Shop::getActiveCategories() as $category)   
                            
                                
                            <li class="menu-item-has-children has-mega-menu"><a style="color:white" href="{{ url('shop?category='.$category->slug) }}"><i class="icon-laundry"></i> {{$category->name}}</a>
                                <div class="mega-menu" style="background-color:#091E08; opacity: 0.8; border-radius: 5px;">
                                    <div class="mega-menu__column">
                                        <h4 style="color:white;">Electronic<span class="sub-toggle"></span></h4>
                                        <ul class="mega-menu__list">
                                       
                                            <li><a style="color:white" href="#">Home Audio &amp; Theathers</a>
                                            </li>

                                         
                                           
                                        </ul>
                                    </div>
                                   
                                </div>
                            </li>
                            @endforeach
           
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="navigation__right">
                    <ul class="menu">
                        <li class="menu-item-has-children"><a style="color:white" href="index.html">Home</a><span class="sub-toggle"></span>
                            <ul class="sub-menu" style="background-color:#091E08; opacity: 0.8; border-radius:5px;">
                                <li><a style="color:white" href="index.html">Marketplace Full Width</a>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="menu-item-has-children has-mega-menu"><a style="color:white" href="shop-default.html">Shop</a><span class="sub-toggle"></span>
                            <div class="mega-menu" style="background-color:#091E08; opacity: 0.8; border-radius: 5px;">
                                <div class="mega-menu__column">
                                    <h4 style="color:white">Catalog Pages<span class="sub-toggle"></span></h4>
                                    <ul class="mega-menu__list">
                                        <li><a style="color:white" href="shop-default.html">Shop Default</a>
                                        </li>
                                        
                                    </ul>
                                </div>
                                <div class="mega-menu__column">
                                    <h4 style="color:white">Product Layout<span class="sub-toggle"></span></h4>
                                    <ul class="mega-menu__list">
                                        <li><a style="color:white" href="product-default.html">Default</a>
                                        </li>
                                        
                                    </ul>
                                </div>
                                
                            </div>
                        </li>
                        <li class="menu-item-has-children has-mega-menu"><a style="color:white" href="#">Pages</a><span class="sub-toggle"></span>
                            <div class="mega-menu" style="background-color:#091E08; opacity: 0.8; border-radius:5px;">
                                <div class="mega-menu__column">
                                    <h4 style="color:white">Basic Page<span class="sub-toggle"></span></h4>
                                    <ul class="mega-menu__list">
                                        <li><a style="color:white" href="about-us.html">About Us</a>
                                        </li>
                                        
                                    </ul>
                                </div>
                                <div class="mega-menu__column">
                                    <h4 style="color:white">Vendor Pages<span class="sub-toggle"></span></h4>
                                    <ul class="mega-menu__list">
                                        <li><a style="color:white" href="become-a-vendor.html">Become a Vendor</a>
                                        </li>
                                        
                                    </ul>
                                </div>
                                <div class="mega-menu__column">
                                    <h4 style="color:white">Account Pages<span class="sub-toggle"></span></h4>
                                    <ul class="mega-menu__list">
                                        <li><a style="color:white" href="user-information.html">User Information</a>
                                        </li>
                                       
                                    </ul>
                                </div>
                            </div>
                        </li>
                        
                    </ul>
                    <ul class="navigation__extra">
                        <li><a href="#" style="color:white">Sell on Martfury</a></li>
                        <li><a href="#" style="color:white">Tract your order</a></li>
                        <li>
                            <div class="ps-dropdown"><a href="#" style="color:white">US Dollar</a>
                                <ul class="ps-dropdown-menu">
                                    <li><a href="#">Us Dollar</a></li>
                                    <li><a href="#">Euro</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="ps-dropdown language"><a href="#" style="color:white"><img src="img/flag/en.png" alt="">English</a>
                                <ul class="ps-dropdown-menu">
                                    <li><a href="#"><img src="img/flag/germany.png" alt=""> Germany</a></li>
                                    <li><a href="#"><img src="img/flag/fr.png" alt=""> France</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <header class="header header--mobile" data-sticky="true">
        <div class="header__top">
            <div class="header__left">
                <p>Welcome to Martfury Online Shopping Store !</p>
            </div>
            <div class="header__right">
                <ul class="navigation__extra">
                    <li><a href="#">Sell on Martfury</a></li>
                    <li><a href="#">Tract your order</a></li>
                    <li>
                        <div class="ps-dropdown"><a href="#">US Dollar</a>
                            <ul class="ps-dropdown-menu">
                                <li><a href="#">Us Dollar</a></li>
                                <li><a href="#">Euro</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="ps-dropdown language"><a href="#"><img src="img/flag/en.png" alt="">English</a>
                            <ul class="ps-dropdown-menu">
                                <li><a href="#"><img src="img/flag/germany.png" alt=""> Germany</a></li>
                                <li><a href="#"><img src="img/flag/fr.png" alt=""> France</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="navigation--mobile">
            <div class="navigation__left"><a class="ps-logo" href="index.html"><img src="img/logo_light.png" alt=""></a></div>
            <div class="navigation__right">
                <div class="header__actions">
                    <div class="ps-cart--mini"><a class="header__extra" href="#"><i class="icon-bag2"></i><span><i>0</i></span></a>
                        <div class="ps-cart__content">
                            <div class="ps-cart__items">
                                <div class="ps-product--cart-mobile">
                                    <div class="ps-product__thumbnail"><a href="#"><img src="img/products/clothing/7.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__remove" href="#"><i class="icon-cross"></i></a><a href="product-default.html">MVMTH Classical Leather Watch In Black</a>
                                        <p><strong>Sold by:</strong> YOUNG SHOP</p><small>1 x $59.99</small>
                                    </div>
                                </div>
                                <div class="ps-product--cart-mobile">
                                    <div class="ps-product__thumbnail"><a href="#"><img src="img/products/clothing/5.jpg" alt=""></a></div>
                                    <div class="ps-product__content"><a class="ps-product__remove" href="#"><i class="icon-cross"></i></a><a href="product-default.html">Sleeve Linen Blend Caro Pane Shirt</a>
                                        <p><strong>Sold by:</strong> YOUNG SHOP</p><small>1 x $59.99</small>
                                    </div>
                                </div>
                            </div>
                            <div class="ps-cart__footer">
                                <h3>Sub Total:<strong>$59.99</strong></h3>
                                <figure><a class="ps-btn" href="shopping-cart.html">View Cart</a><a class="ps-btn" href="checkout.html">Checkout</a></figure>
                            </div>
                        </div>
                    </div>
                    <div class="ps-block--user-header">
                        <div class="ps-block__left"><i class="icon-user"></i></div>
                        <div class="ps-block__right"><a href="my-account.html">Login</a><a href="my-account.html">Register</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ps-search--mobile">
            <form class="ps-form--search-mobile" action="index.html" method="get">
                <div class="form-group--nest">
                    <input class="form-control" type="text" placeholder="Search something...">
                    <button ><i class="icon-magnifier"></i></button>
                </div>
            </form>
        </div>
    </header>
    <div class="ps-panel--sidebar" id="cart-mobile">
        <div class="ps-panel__header">
            <h3>Shopping Cart</h3>
        </div>
        <div class="navigation__content">
            <div class="ps-cart--mobile">
                <div class="ps-cart__content">
                    <div class="ps-product--cart-mobile">
                        <div class="ps-product__thumbnail"><a href="#"><img src="img/products/clothing/7.jpg" alt=""></a></div>
                        <div class="ps-product__content"><a class="ps-product__remove" href="#"><i class="icon-cross"></i></a><a href="product-default.html">MVMTH Classical Leather Watch In Black</a>
                            <p><strong>Sold by:</strong> YOUNG SHOP</p><small>1 x $59.99</small>
                        </div>
                    </div>
                </div>
                <div class="ps-cart__footer">
                    <h3>Sub Total:<strong>$59.99</strong></h3>
                    <figure><a class="ps-btn" href="shopping-cart.html">View Cart</a><a class="ps-btn" href="checkout.html">Checkout</a></figure>
                </div>
            </div>
        </div>
    </div>
    <div class="ps-panel--sidebar" id="navigation-mobile">
        <div class="ps-panel__header">
            <h3>Categories</h3>
        </div>
        <div class="ps-panel__content">
            <ul class="menu--mobile">
                <li><a href="#">Hot Promotions</a>
                </li>
                <li class="menu-item-has-children has-mega-menu"><a href="#">Consumer Electronic</a><span class="sub-toggle"></span>
                    <div class="mega-menu">
                        <div class="mega-menu__column">
                            <h4>Electronic<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="#">Home Audio &amp; Theathers</a>
                                </li>
                                <li><a href="#">TV &amp; Videos</a>
                                </li>
                                <li><a href="#">Camera, Photos &amp; Videos</a>
                                </li>
                                <li><a href="#">Cellphones &amp; Accessories</a>
                                </li>
                                <li><a href="#">Headphones</a>
                                </li>
                                <li><a href="#">Videosgames</a>
                                </li>
                                <li><a href="#">Wireless Speakers</a>
                                </li>
                                <li><a href="#">Office Electronic</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Accessories &amp; Parts<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="#">Digital Cables</a>
                                </li>
                                <li><a href="#">Audio &amp; Video Cables</a>
                                </li>
                                <li><a href="#">Batteries</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li><a href="#">Clothing &amp; Apparel</a>
                </li>
                <li><a href="#">Home, Garden &amp; Kitchen</a>
                </li>
                <li><a href="#">Health &amp; Beauty</a>
                </li>
                <li><a href="#">Yewelry &amp; Watches</a>
                </li>
                <li class="menu-item-has-children has-mega-menu"><a href="#">Computer &amp; Technology</a><span class="sub-toggle"></span>
                    <div class="mega-menu">
                        <div class="mega-menu__column">
                            <h4>Computer &amp; Technologies<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="#">Computer &amp; Tablets</a>
                                </li>
                                <li><a href="#">Laptop</a>
                                </li>
                                <li><a href="#">Monitors</a>
                                </li>
                                <li><a href="#">Networking</a>
                                </li>
                                <li><a href="#">Drive &amp; Storages</a>
                                </li>
                                <li><a href="#">Computer Components</a>
                                </li>
                                <li><a href="#">Security &amp; Protection</a>
                                </li>
                                <li><a href="#">Gaming Laptop</a>
                                </li>
                                <li><a href="#">Accessories</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li><a href="#">Babies &amp; Moms</a>
                </li>
                <li><a href="#">Sport &amp; Outdoor</a>
                </li>
                <li><a href="#">Phones &amp; Accessories</a>
                </li>
                <li><a href="#">Books &amp; Office</a>
                </li>
                <li><a href="#">Cars &amp; Motocycles</a>
                </li>
                <li><a href="#">Home Improments</a>
                </li>
                <li><a href="#">Vouchers &amp; Services</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="navigation--list">
        <div class="navigation__content"><a class="navigation__item ps-toggle--sidebar" href="#menu-mobile"><i class="icon-menu"></i><span> Menu</span></a><a class="navigation__item ps-toggle--sidebar" href="#navigation-mobile"><i class="icon-list4"></i><span> Categories</span></a><a class="navigation__item ps-toggle--sidebar" href="#search-sidebar"><i class="icon-magnifier"></i><span> Search</span></a><a class="navigation__item ps-toggle--sidebar" href="#cart-mobile"><i class="icon-bag2"></i><span> Cart</span></a></div>
    </div>
    <div class="ps-panel--sidebar" id="search-sidebar">
        <div class="ps-panel__header">
            <form class="ps-form--search-mobile" action="index.html" method="get">
                <div class="form-group--nest">
                    <input class="form-control" type="text" placeholder="Search something...">
                    <button><i class="icon-magnifier"></i></button>
                </div>
            </form>
        </div>
        <div class="navigation__content"></div>
    </div>
    <div class="ps-panel--sidebar" id="menu-mobile">
        <div class="ps-panel__header">
            <h3>Menu</h3>
        </div>
        <div class="ps-panel__content">
            <ul class="menu--mobile">
                <li class="menu-item-has-children"><a href="index.html">Home</a><span class="sub-toggle"></span>
                    <ul class="sub-menu">
                        <li><a href="index.html">Marketplace Full Width</a>
                        </li>
                        <li><a href="homepage-2.html">Home Auto Parts</a>
                        </li>
                        <li><a href="homepage-10.html">Home Technology</a>
                        </li>
                        <li><a href="homepage-9.html">Home Organic</a>
                        </li>
                        <li><a href="homepage-3.html">Home Marketplace V1</a>
                        </li>
                        <li><a href="homepage-4.html">Home Marketplace V2</a>
                        </li>
                        <li><a href="homepage-5.html">Home Marketplace V3</a>
                        </li>
                        <li><a href="homepage-6.html">Home Marketplace V4</a>
                        </li>
                        <li><a href="homepage-7.html">Home Electronic</a>
                        </li>
                        <li><a href="homepage-8.html">Home Furniture</a>
                        </li>
                        <li><a href="homepage-kids.html">Home Kids</a>
                        </li>
                        <li><a href="homepage-photo-and-video.html">Home photo and picture</a>
                        </li>
                        <li><a href="home-medical.html">Home Medical</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item-has-children has-mega-menu"><a href="shop-default.html">Shop</a><span class="sub-toggle"></span>
                    <div class="mega-menu">
                        <div class="mega-menu__column">
                            <h4>Catalog Pages<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="shop-default.html">Shop Default</a>
                                </li>
                                <li><a href="shop-default.html">Shop Fullwidth</a>
                                </li>
                                <li><a href="shop-categories.html">Shop Categories</a>
                                </li>
                                <li><a href="shop-sidebar.html">Shop Sidebar</a>
                                </li>
                                <li><a href="shop-sidebar-without-banner.html">Shop Without Banner</a>
                                </li>
                                <li><a href="shop-carousel.html">Shop Carousel</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Product Layout<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="product-default.html">Default</a>
                                </li>
                                <li><a href="product-extend.html">Extended</a>
                                </li>
                                <li><a href="product-full-content.html">Full Content</a>
                                </li>
                                <li><a href="product-box.html">Boxed</a>
                                </li>
                                <li><a href="product-sidebar.html">Sidebar</a>
                                </li>
                                <li><a href="product-default.html">Fullwidth</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Product Types<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="product-default.html">Simple</a>
                                </li>
                                <li><a href="product-default.html">Color Swatches</a>
                                </li>
                                <li><a href="product-image-swatches.html">Images Swatches</a>
                                </li>
                                <li><a href="product-countdown.html">Countdown</a>
                                </li>
                                <li><a href="product-multi-vendor.html">Multi-Vendor</a>
                                </li>
                                <li><a href="product-instagram.html">Instagram</a>
                                </li>
                                <li><a href="product-affiliate.html">Affiliate</a>
                                </li>
                                <li><a href="product-on-sale.html">On sale</a>
                                </li>
                                <li><a href="product-video.html">Video Featured</a>
                                </li>
                                <li><a href="product-groupped.html">Grouped</a>
                                </li>
                                <li><a href="product-out-stock.html">Out Of Stock</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Woo Pages<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="shopping-cart.html">Shopping Cart</a>
                                </li>
                                <li><a href="checkout.html">Checkout</a>
                                </li>
                                <li><a href="whishlist.html">Whishlist</a>
                                </li>
                                <li><a href="compare.html">Compare</a>
                                </li>
                                <li><a href="order-tracking.html">Order Tracking</a>
                                </li>
                                <li><a href="my-account.html">My Account</a>
                                </li>
                                <li><a href="checkout-2.html">Checkout 2</a>
                                </li>
                                <li><a href="shipping.html">Shipping</a>
                                </li>
                                <li><a href="payment.html">Payment</a>
                                </li>
                                <li><a href="payment-success.html">Payment Success</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="menu-item-has-children has-mega-menu"><a href="#">Pages</a><span class="sub-toggle"></span>
                    <div class="mega-menu">
                        <div class="mega-menu__column">
                            <h4>Basic Page<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="about-us.html">About Us</a>
                                </li>
                                <li><a href="contact-us.html">Contact</a>
                                </li>
                                <li><a href="faqs.html">Faqs</a>
                                </li>
                                <li><a href="comming-soon.html">Comming Soon</a>
                                </li>
                                <li><a href="404-page.html">404 Page</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Vendor Pages<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="become-a-vendor.html">Become a Vendor</a>
                                </li>
                                <li><a href="vendor-store.html">Vendor Store</a>
                                </li>
                                <li><a href="vendor-dashboard-free.html">Vendor Dashboard Free</a>
                                </li>
                                <li><a href="vendor-dashboard-pro.html">Vendor Dashboard Pro</a>
                                </li>
                                <li><a href="store-list.html">Store List</a>
                                </li>
                                <li><a href="store-list.html">Store List 2</a>
                                </li>
                                <li><a href="store-detail.html">Store Detail</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Account Pages<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="user-information.html">User Information</a>
                                </li>
                                <li><a href="addresses.html">Addresses</a>
                                </li>
                                <li><a href="invoices.html">Invoices</a>
                                </li>
                                <li><a href="invoice-detail.html">Invoice Detail</a>
                                </li>
                                <li><a href="notifications.html">Notifications</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="menu-item-has-children has-mega-menu"><a href="#">Blogs</a><span class="sub-toggle"></span>
                    <div class="mega-menu">
                        <div class="mega-menu__column">
                            <h4>Blog Layout<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="blog-grid.html">Grid</a>
                                </li>
                                <li><a href="blog-list.html">Listing</a>
                                </li>
                                <li><a href="blog-small-thumb.html">Small Thumb</a>
                                </li>
                                <li><a href="blog-left-sidebar.html">Left Sidebar</a>
                                </li>
                                <li><a href="blog-right-sidebar.html">Right Sidebar</a>
                                </li>
                            </ul>
                        </div>
                        <div class="mega-menu__column">
                            <h4>Single Blog<span class="sub-toggle"></span></h4>
                            <ul class="mega-menu__list">
                                <li><a href="blog-detail.html">Single 1</a>
                                </li>
                                <li><a href="blog-detail-2.html">Single 2</a>
                                </li>
                                <li><a href="blog-detail-3.html">Single 3</a>
                                </li>
                                <li><a href="blog-detail-4.html">Single 4</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div id="homepage-1">
        <div class="ps-home-banner ps-home-banner--1">
            <div class="ps-container" style="height: 360px;">
                <div class="ps-section__left">
                <div  class="ps-carousel--nav-inside owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on" data-owl-animate-in="fadeIn" data-owl-animate-out="fadeOut">
                        <div class="ps-banner bg--cover" data-background="img/slider/home-1/sl1.jpg"><a class="ps-banner__overlay" href="shop-default.html"></a></div>
                        <div class="ps-banner bg--cover" data-background="img/slider/home-1/sl2.jpg"><a class="ps-banner__overlay" href="shop-default.html"></a></div>
                        <div class="ps-banner bg--cover" data-background="img/slider/home-1/sl3.jpg"><a class="ps-banner__overlay" href="shop-default.html"></a></div>
                    </div>
                </div>
                <div class="ps-section__right"><a class="ps-collection" href="#"><img style="height: 165px;" src="img/slider/home-1/w1.avif" class="ps-banner" alt=""></a><a class="ps-collection" href="#"><img style="height: 165px;" class="ps-banner" src="img/slider/home-1/w2.avif" alt=""></a></div>
            </div>
        </div>
        <div class="ps-site-features">
            <div class="ps-container">
                <div class="ps-block--site-features">
                    <div class="ps-block__item">
                        <div class="ps-block__left" style="border: 2px solid #091E08; padding: 4px 7px; border-radius: 50px;"><i class="icon-rocket"></i></div>
                        <div class="ps-block__right">
                            <h4>Free Delivery</h4>
                            <p>For all oders over $99</p>
                        </div>
                    </div>
                    <div class="ps-block__item">
                        <div class="ps-block__left" style="border: 2px solid #091E08; padding: 2px 5px; border-radius: 50px;"><i  class="icon-sync"></i></div>
                        <div class="ps-block__right">
                            <h4>90 Days Return</h4>
                            <p>If goods have problems</p>
                        </div>
                    </div>
                    <div class="ps-block__item">
                        <div class="ps-block__left" style="border: 2px solid #091E08; padding: 2px 5px; border-radius: 50px;"><i  class="icon-credit-card"></i></div>
                        <div class="ps-block__right">
                            <h4>Secure Payment</h4>
                            <p>100% secure payment</p>
                        </div>
                    </div>
                    <div class="ps-block__item">
                        <div class="ps-block__left" style="border: 2px solid #091E08; padding: 2px 5px; border-radius: 50px;"><i  class="icon-bubbles"></i></div>
                        <div class="ps-block__right">
                            <h4>24/7 Support</h4>
                            <p>Dedicated support</p>
                        </div>
                    </div>
                    <div class="ps-block__item">
                        <div class="ps-block__left" style="border: 2px solid #091E08; padding: 2px 5px; border-radius: 50px;"><i class="icon-gift"></i></div>
                        <div class="ps-block__right">
                            <h4>Gift Service</h4>
                            <p>Support gift service</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ps-deal-of-day">
            <div class="ps-container">
                <div class="ps-section__header">
                    <div class="ps-block--countdown-deal">
                        <div class="ps-block__left">
                            <h3>Deals of the day</h3>
                        </div>
                        <div class="ps-block__right">
                            <figure style="background-color:#091E08; color:white">
    
                                <figcaption>End in:</figcaption>
                                <ul class="ps-countdown" data-time="December 30, 2021 15:37:25">
                                    <li><span class="days"></span></li>
                                    <li><span class="hours"></span></li>
                                    <li><span class="minutes"></span></li>
                                    <li><span class="seconds"></span></li>
                                </ul>
                            </figure>
                            
                        </div>
                    </div><a href="#" style="border:2px solid #091E08; padding: 4px 10px; border-radius: 5px;">View all</a>
                    

                </div>
                <div class="ps-section__content">
                    <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="7" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                      @foreach($products as $product)
                    <div class="ps-product " style="border: solid 1px #D7DBDD; margin: 5px; border-radius: 5px;">
                            <div class="ps-product__thumbnail"><a href="product-default.html"><img class="ps-img" src="{{ $product->non_featured_image }}" alt=""></a>
                                <div class="ps-product__badge" style="background-color:#091E08; width: auto; border-radius: 50px;">234%</div>
                                <ul class="ps-product__actions">
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="icon-bag2"></i></a></li>
                                    <li><a href="#" data-placement="top" title="Quick View" data-toggle="modal" data-target="#product-quickview"><i class="icon-eye"></i></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Add to Whishlist"><i class="icon-heart"></i></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Compare"><i class="icon-chart-bars"></i></a></li>
                                </ul>
                            </div>
                            <div class="ps-product__container"><a class="ps-product__vendor" href="#">Go Pro</a>
                                <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">{{$product->name}}</a>
                                    <div class="ps-product__rating">
                                        <select class="ps-rating" data-read-only="true">
                                            <option value="1">1</option>
                                            <option value="1">2</option>
                                            <option value="1">3</option>
                                            <option value="1">4</option>
                                            <option value="2">5</option>
                                        </select><span>01</span>
                                    </div>
                                    <p class="ps-product__price sale">{{$product->price}}</p>
                                </div>
                                <div class="ps-product__content hover">
                                    <a href="{{ url('shop', $product->slug)}}" class="btn btn-lg" style="background-color: #091E08; color:white; width:100%;"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                       
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="ps-home-ads">
            <div class="ps-container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 "><a class="ps-collection" href="#"><img style="height: 200px;" src="img\slider\home-1/w3.avif" alt=""></a>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 "><a class="ps-collection" href="#"><img style="height: 200px;" src="img\slider\home-1/w4.avif" alt=""></a>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 "><a class="ps-collection" href="#"><img style="height: 200px;" src="img\slider\home-1/w5.avif" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="ps-top-categories">
            <div class="ps-container">
                <h3 style="border-bottom: 2px solid #091E08; padding-bottom: 5px;">Top categories of the month</h3>
                <div class="row">
                    
                @foreach(\Shop::getActiveCategories() as $category)   
                <div style="border: solid 2px #091E08 ; text-align: center; border-radius:5px; margin: 10px;" class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6  topMonthcat">
                        
                    <div><a class="ps-block__overlay" href="shop-default.html"></a><img src="{{ $category->non_featured_image }}" alt="">
                           
                    <p>{{$category->name}}</p>
                  
                        
                      
                    </div>
                    
                </div>
                @endforeach
                </div>
            </div>
        </div>
        <div class="ps-product-list ps-clothings">
            <div class="ps-container">
                <div class="ps-section__header" style="background-color:#091E08; color:white; border-radius: 5px;">
                    <h3 style="color:white">Consumer Electronics</h3>
                    <ul class="ps-section__links">
                        <li><a style="color:white" href="shop-grid.html">New Arrivals</a></li>
                        <li><a style="color:white" href="shop-grid.html">Best seller</a></li>
                        <li><a style="color:white" href="shop-grid.html">Must Popular</a></li>
                        <li><a style="color:white" href="shop-grid.html">View All</a></li>
                    </ul>
                </div>
                <div class="ps-section__content">
                    <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="7" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                      @foreach($products as $product)
                    <div class="ps-product" style="border: solid 1px #D7DBDD; margin: 5px; border-radius: 5px;">
                            <div class="ps-product__thumbnail"><a href="product-default.html"><img class="ps-img" src="{{ $product->non_featured_image }}" alt=""></a>
                                <div class="ps-product__badge" style="background-color:#091E08; border-radius: 50px;">-16%</div>
                                <ul class="ps-product__actions">
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="icon-bag2"></i></a></li>
                                    <li><a href="#" data-placement="top" title="Quick View" data-toggle="modal" data-target="#product-quickview"><i class="icon-eye"></i></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Add to Whishlist"><i class="icon-heart"></i></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Compare"><i class="icon-chart-bars"></i></a></li>
                                </ul>
                            </div>
                            <div class="ps-product__container"><a class="ps-product__vendor" href="#">Go Pro</a>
                                <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">{{$product->name}}</a>
                                    <div class="ps-product__rating">
                                        <select class="ps-rating" data-read-only="true">
                                            <option value="1">1</option>
                                            <option value="1">2</option>
                                            <option value="1">3</option>
                                            <option value="1">4</option>
                                            <option value="2">5</option>
                                        </select><span>01</span>
                                    </div>
                                    <p class="ps-product__price sale">{{$product->price}}</p>
                                </div>
                                <div class="ps-product__content hover">
                                    <a href="{{ url('shop', $product->slug)}}" class="btn btn-lg" style="background-color: #091E08; color:white; width:100%;"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                       
                    </div>
                    
                </div>
                
            </div>
        </div>
        <div class="ps-product-list ps-clothings">
            <div class="ps-container">
                <div class="ps-section__header" style="background-color:#091E08; border-radius: 5px;">
                    <h3 style="color:white">Apparels & Clothings</h3>
                    <ul class="ps-section__links">
                        <li><a style="color:white" href="shop-grid.html">New Arrivals</a></li>
                        <li><a style="color:white" href="shop-grid.html">Best seller</a></li>
                        <li><a style="color:white" href="shop-grid.html">Must Popular</a></li>
                        <li><a style="color:white" href="shop-grid.html">View All</a></li>
                    </ul>
                </div>
                <div class="ps-section__content">
                    <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="7" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                      @foreach($products as $product)
                    <div class="ps-product" style="border: solid 1px #D7DBDD; margin: 5px; border-radius: 5px;">
                            <div class="ps-product__thumbnail"><a href="product-default.html"><img class="ps-img" src="{{ $product->non_featured_image }}" alt=""></a>
                                <div class="ps-product__badge" style="background-color:#091E08;color:white; border-radius: 50px;">-16%</div>
                                <ul class="ps-product__actions">
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="icon-bag2"></i></a></li>
                                    <li><a href="#" data-placement="top" title="Quick View" data-toggle="modal" data-target="#product-quickview"><i class="icon-eye"></i></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Add to Whishlist"><i class="icon-heart"></i></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Compare"><i class="icon-chart-bars"></i></a></li>
                                </ul>
                            </div>
                            <div class="ps-product__container"><a class="ps-product__vendor" href="#">Go Pro</a>
                                <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">{{$product->name}}</a>
                                    <div class="ps-product__rating">
                                        <select class="ps-rating" data-read-only="true">
                                            <option value="1">1</option>
                                            <option value="1">2</option>
                                            <option value="1">3</option>
                                            <option value="1">4</option>
                                            <option value="2">5</option>
                                        </select><span>01</span>
                                    </div>
                                    <p class="ps-product__price sale">{{$product->price}}</p>
                                </div>
                                <div class="ps-product__content hover">
                                    <a href="{{ url('shop', $product->slug)}}" class="btn btn-lg" style="background-color: #091E08; color:white; width:100%;"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                       
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="ps-product-list ps-garden-kitchen">
            <div class="ps-container">
                <div class="ps-section__header" style="background-color:#091E08; color:white; border-radius: 5px;">
                    <h3>Home, Garden & Kitchen</h3>
                    <ul class="ps-section__links">
                        <li><a style="color:white" href="shop-grid.html">New Arrivals</a></li>
                        <li><a style="color:white" href="shop-grid.html">Best seller</a></li>
                        <li><a style="color:white" href="shop-grid.html">Must Popular</a></li>
                        <li><a style="color:white" href="shop-grid.html">View All</a></li>
                    </ul>
                </div>
                <div class="ps-section__content">
                    <div class="ps-carousel--responsive owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="0" data-owl-nav="false" data-owl-dots="true" data-owl-item="7" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                    @foreach($products as $product)
                    <div class="ps-product" style="border: solid 1px #D7DBDD; margin: 5px; border-radius: 5px;">
                            <div class="ps-product__thumbnail"><a href="product-default.html"><img class="ps-img" src="{{ $product->non_featured_image }}" alt=""></a>
                                <div class="ps-product__badge" style="background-color:#091E08; color: white; border-radius: 50px;">-16%</div>
                                <ul class="ps-product__actions">
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="icon-bag2"></i></a></li>
                                    <li><a href="#" data-placement="top" title="Quick View" data-toggle="modal" data-target="#product-quickview"><i class="icon-eye"></i></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Add to Whishlist"><i class="icon-heart"></i></a></li>
                                    <li><a href="#" data-toggle="tooltip" data-placement="top" title="Compare"><i class="icon-chart-bars"></i></a></li>
                                </ul>
                            </div>
                            <div class="ps-product__container"><a class="ps-product__vendor" href="#">Go Pro</a>
                                <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">{{$product->name}}</a>
                                    <div class="ps-product__rating">
                                        <select class="ps-rating" data-read-only="true">
                                            <option value="1">1</option>
                                            <option value="1">2</option>
                                            <option value="1">3</option>
                                            <option value="1">4</option>
                                            <option value="2">5</option>
                                        </select><span>01</span>
                                    </div>
                                    <p class="ps-product__price sale">{{$product->price}}</p>
                                </div>
                                <div class="ps-product__content hover">
                                    <a href="{{ url('shop', $product->slug)}}" class="btn btn-lg" style="background-color: #091E08; color:white; width:100%;"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
     
                    </div>
                </div>
            </div>
        </div>
        
    
        <div class="ps-product-list ps-new-arrivals">
            <div class="ps-container">
                <div class="ps-section__header" style="background-color:#091E08; color:white; border-radius: 5px;">
                    <h3>Hot New Arrivals</h3>
                    <ul class="ps-section__links">
                        <li><a style="color:white" href="shop-grid.html">Technologies</a></li>
                        <li><a style="color:white" href="shop-grid.html">Electronic</a></li>
                        <li><a style="color:white" href="shop-grid.html">Furnitures</a></li>
                        <li><a style="color:white" href="shop-grid.html">Clothing & Apparel</a></li>
                        <li><a style="color:white" href="shop-grid.html">Health & Beauty</a></li>
                        <li><a style="color:white" href="shop-grid.html">View All</a></li>
                    </ul>
                </div>
                <div class="ps-section__content">
                    <div class="row">
                    @foreach($products as $product)
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 ">
                            <div style="border: 1px solid #D7DBDD; border-radius: 5px; padding: 5px;" class="ps-product--horizontal">
                                <div class="ps-product__thumbnail"><a href="product-default.html"><img class="ps-simg" src="{{ $product->non_featured_image }}" alt=""></a></div>
                                <div class="ps-product__content"><a class="ps-product__title" href="product-default.html">{{$product->name}}</a>
                                    <p class="ps-product__price">{{$product->price}}</p>
                                    <a href="{{ url('shop', $product->slug)}}" class="btn bg-md btn" style="background-color:#091E08; color:white"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                </div>
                                
                            </div>
                           
                                       
                                   
                        </div>
                        @endforeach

                     
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    <footer class="ps-footer">
        <div class="ps-container">
        <form class="ps-form--newsletter" action="do_action" method="post">
                    <div class="row">
                        <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="ps-form__left">
                                
                                <p style="color:white">Subcribe to get information about products and coupons</p>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <div class="ps-form__right">
                                <div class="form-group--nest">
                                    <input class="form-control" type="email" style="height: 50px;" placeholder="Email address">
                                    <button class="ps-btn" style="background-color: #091E08; height:50px; border:solid 1px white; color:white">Subscribe</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <br>
                <hr style="background-color:white">
            <div class="ps-footer__widgets">
                <aside class="widget widget_footer widget_contact-us">
                    <h4 class="widget-title" style="color:white">Contact us</h4>
                    <div class="widget_content">
                        <p style="color:white">Call us 24/7</p>
                        <h3 style="color:white">1800 97 97 69</h3>
                        <p style="color:white">502 New Design Str, Melbourne, Australia <br><a style="color:white" href="mailto:contact@martfury.co">contact@martfury.co</a></p>
                        <ul class="ps-list--social">
                            <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a class="google-plus" href="#"><i class="fa fa-google-plus"></i></a></li>
                            <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </aside>
                <aside class="widget widget_footer">
                    <h4 class="widget-title" style="color:white">Quick links</h4>
                    <ul class="ps-list--link">
                        <li><a style="color:white" href="#">Policy</a></li>
                        <li><a style="color:white" href="#">Term & Condition</a></li>
                        <li><a style="color:white" href="#">Shipping</a></li>
                        <li><a style="color:white"" href="#">Return</a></li>
                        <li><a style="color:white" href="faqs.html">FAQs</a></li>
                    </ul>
                </aside>
                <aside class="widget widget_footer">
                    <h4 class="widget-title" style="color:white">Company</h4>
                    <ul class="ps-list--link">
                        <li><a style="color:white" href="about-us.html">About Us</a></li>
                        <li><a style="color:white" href="#">Affilate</a></li>
                        <li><a style="color:white" href="#">Career</a></li>
                        <li><a style="color:white" href="contact-us.html">Contact</a></li>
                    </ul>
                </aside>
                <aside class="widget widget_footer">
                    <h4 class="widget-title" style="color:white">Bussiness</h4>
                    <ul class="ps-list--link">
                        <li><a style="color:white" href="#">Our Press</a></li>
                        <li><a style="color:white" href="checkout.html">Checkout</a></li>
                        <li><a style="color:white" href="my-account.html">My account</a></li>
                        <li><a style="color:white" href="shop-default.html">Shop</a></li>
                    </ul>
                </aside>
            </div>
           
            <div class="ps-footer__copyright">
                <p style="color:white">© 2021 Internal Project. All Rights Reserved</p>
                
            </div>
        </div>
    </footer>
    
    <div id="back2top"><i class="icon icon-arrow-up"></i></div>
    <div class="ps-site-overlay"></div>
    <div id="loader-wrapper">
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <div class="ps-search" id="site-search"><a class="ps-btn--close" href="#"></a>
        <div class="ps-search__content">
            <form class="ps-form--primary-search" method="get" action="{{ url('shop') }}>
                <input class="form-control" type="text" placeholder="@lang('Marketplace::labels.shop.search')" value="{{request()->get('search')}}" class="ui-autocomplete-input">
                <button type="submit">
                       <i class="fa fa-search"></i>
                    </button>
            </form>
        </div>
    </div>
    <div class="modal fade" id="product-quickview" tabindex="-1" role="dialog" aria-labelledby="product-quickview" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content"><span class="modal-close" data-dismiss="modal"><i class="icon-cross2"></i></span>
                <article class="ps-product--detail ps-product--fullwidth ps-product--quickview">
                    <div class="ps-product__header">
                        <div class="ps-product__thumbnail" data-vertical="false">
                            <div class="ps-product__images" data-arrow="true">
                                <div class="item"><img src="{{ $product->non_featured_image }}" alt=""></div>
                                <div class="item"><img src="{{ $product->non_featured_image }}" alt=""></div>
                                <div class="item"><img src="{{ $product->non_featured_image }}" alt=""></div>
                            </div>
                        </div>
                        
                        <div class="ps-product__info">
                            <h1>{{$product->name}}</h1>
                            <div class="ps-product__meta">
                                <p>Brand:<a href="shop-default.html">Sony</a></p>
                                <div class="ps-product__rating">
                                    <select class="ps-rating" data-read-only="true">
                                        <option value="1">1</option>
                                        <option value="1">2</option>
                                        <option value="1">3</option>
                                        <option value="1">4</option>
                                        <option value="2">5</option>
                                    </select><span>(1 review)</span>
                                </div>
                            </div>
                            <h4 class="ps-product__price">{{$product->price}}</h4>
                            <div class="ps-product__desc">
                                <p>Sold By:<a href="shop-default.html"><strong> Go Pro</strong></a></p>
                                <ul class="ps-list--dot">
                                    <li>{{$product->description}}</li>
                                   
                                </ul>
                            </div>
                            <div class="ps-product__shopping"><a class="ps-btn ps-btn--black" href="{{ url('shop', $product->slug) }}">Add to cart</a><a class="ps-btn" href="#">Buy Now</a>
                                <div class="ps-product__actions"><a href="#"><i class="icon-heart"></i></a><a href="#"><i class="icon-chart-bars"></i></a></div>
                            </div>
                        </div>
                   
                    </div>
                </article>
            </div>
        </div>


    </div>
   
    <script src="plugins/jquery.min.js"></script>
    <script src="plugins/nouislider/nouislider.min.js"></script>
    <script src="plugins/popper.min.js"></script>
    <script src="plugins/owl-carousel/owl.carousel.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/imagesloaded.pkgd.min.js"></script>
    <script src="plugins/masonry.pkgd.min.js"></script>
    <script src="plugins/isotope.pkgd.min.js"></script>
    <script src="plugins/jquery.matchHeight-min.js"></script>
    <script src="plugins/slick/slick/slick.min.js"></script>
    <script src="plugins/jquery-bar-rating/dist/jquery.barrating.min.js"></script>
    <script src="plugins/slick-animation.min.js"></script>
    <script src="plugins/lightGallery-master/dist/js/lightgallery-all.min.js"></script>
    <script src="plugins/sticky-sidebar/dist/sticky-sidebar.min.js"></script>
    <script src="plugins/select2/dist/js/select2.full.min.js"></script>
    <script src="plugins/gmap3.min.js"></script>
    <!-- custom scripts-->
    <script src="js/main.js"></script>
</body>

<style type="text/css">
    .topMonthcat{
        opacity: 1;  
    }
.topMonthcat:hover{
    opacity: 0.8 green;
	animation-name: fadeInOpacity;
	animation-iteration-count: 1;
	animation-timing-function: ease-in;
	animation-duration: 4s;
}
.ps-banner{
    border-radius: 5px;
    border: solid 1px #091E08;
}


.bg-lg{
    height: 30px;
    width: auto !important;
    font-size: 18px !important;
    padding: 0px 20px;
}
.ps-products{
    border:solid 3px whitesmoke; border-radius:5px; padding: 10px;
}
.ps-footer{
    height: auto;
    background-color:#091E08;
    color:white;
    color:black !important;
}
.ps-list--link  li > a{
color:black;
font-family: sans-serif !important;
}
.widget_content p, h3 {
    color:black !important;
    font-family: sans-serif !important;
}
.ps-img{
    object-fit: contain;
    height: 150px;
}
.ps-simg{
    object-fit: contain;
    height: 100px;
    width: 100%;
}

</style>
</html>