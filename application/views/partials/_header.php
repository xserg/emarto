<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= $this->selected_lang->short_form ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?></title>
    <meta name="description" content="<?= xss_clean($description); ?>"/>
    <meta name="keywords" content="<?= xss_clean($keywords); ?>"/>
    <meta name="author" content="<?= xss_clean($this->general_settings->application_name); ?>"/>
    <link rel="shortcut icon" type="image/png" href="<?= get_favicon($this->general_settings); ?>"/>
    <meta property="og:locale" content="en-US"/>
    <meta property="og:site_name" content="<?= xss_clean($this->general_settings->application_name); ?>"/>
<?php if (isset($show_og_tags)): ?>
    <meta property="og:type" content="<?= !empty($og_type) ? $og_type : 'website'; ?>"/>
    <meta property="og:title" content="<?= !empty($og_title) ? $og_title : 'index'; ?>"/>
    <meta property="og:description" content="<?= $og_description; ?>"/>
    <meta property="og:url" content="<?= $og_url; ?>"/>
    <meta property="og:image" content="<?= $og_image; ?>"/>
    <meta property="og:image:width" content="<?= !empty($og_width) ? $og_width : 250; ?>"/>
    <meta property="og:image:height" content="<?= !empty($og_height) ? $og_height : 250; ?>"/>
    <meta property="article:author" content="<?= !empty($og_author) ? $og_author : ''; ?>"/>
    <meta property="fb:app_id" content="<?= $this->general_settings->facebook_app_id; ?>"/>
<?php if (!empty($og_tags)):foreach ($og_tags as $tag): ?>
    <meta property="article:tag" content="<?= $tag->tag; ?>"/>
<?php endforeach; endif; ?>
    <meta property="article:published_time" content="<?= !empty($og_published_time) ? $og_published_time : ''; ?>"/>
    <meta property="article:modified_time" content="<?= !empty($og_modified_time) ? $og_modified_time : ''; ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@<?= xss_clean($this->general_settings->application_name); ?>"/>
    <meta name="twitter:creator" content="@<?= xss_clean($og_creator); ?>"/>
    <meta name="twitter:title" content="<?= xss_clean($og_title); ?>"/>
    <meta name="twitter:description" content="<?= xss_clean($og_description); ?>"/>
    <meta name="twitter:image" content="<?= $og_image; ?>"/>
<?php else: ?>
    <meta property="og:image" content="<?= get_logo($this->general_settings); ?>"/>
    <meta property="og:image:width" content="160"/>
    <meta property="og:image:height" content="60"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="<?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?>"/>
    <meta property="og:description" content="<?= xss_clean($description); ?>"/>
    <meta property="og:url" content="<?= base_url(); ?>"/>
    <meta property="fb:app_id" content="<?= $this->general_settings->facebook_app_id; ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@<?= xss_clean($this->general_settings->application_name); ?>"/>
    <meta name="twitter:title" content="<?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?>"/>
    <meta name="twitter:description" content="<?= xss_clean($description); ?>"/>
<?php endif; ?>
<?php if ($this->general_settings->pwa_status == 1): ?>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="<?= xss_clean($this->general_settings->application_name); ?>">
    <meta name="msapplication-TileImage" content="<?= base_url(); ?>assets/img/pwa/144x144.png">
    <meta name="msapplication-TileColor" content="#2F3BA2">
    <link rel="manifest" href="<?= base_url(); ?>manifest.json">
    <link rel="apple-touch-icon" href="<?= base_url(); ?>assets/img/pwa/144x144.png">
<?php endif; ?>
    <link rel="canonical" href="<?= current_full_url(); ?>"/>
<?php if ($this->general_settings->multilingual_system == 1):
foreach ($this->languages as $language): ?>
    <link rel="alternate" href="<?= convert_url_by_language($language); ?>" hreflang="<?= $language->language_code ?>"/>
<?php endforeach; endif; ?>
<link rel="stylesheet" href="<?= base_url(); ?>assets/admin/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/font-icons/css/mds-icons.min.css"/>
    <?= !empty($this->fonts->site_font_url) ? $this->fonts->site_font_url : ''; ?>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/lotsy.css"/>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/plugins-2.1.css"/>
    <?php $this->load->view("partials/_css_js_header"); ?>
    <?php if ($this->rtl == true): ?>
        <link rel="stylesheet" href="<?= base_url(); ?>assets/css/rtl-2.1.min.css">
    <?php endif; ?>
    <?= $this->general_settings->custom_css_codes; ?>
    <?= $this->general_settings->google_adsense_code; ?>
</head>
<body>
<header id="header">
    <?php $this->load->view("partials/_top_bar"); ?>
    <div class="main-menu">
        <div class="container-fluid">
            <div class="row">
                <div class="nav-top">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-8 nav-top-left">
                                <div class="row-align-items-center">
                                    <div class="logo">
                                        <a href="<?php echo lang_base_url(); ?>"><img src="<?php echo get_logo($this->general_settings); ?>" alt="logo"></a>
                                    </div>
                                    <div class="top-search-bar<?= $this->general_settings->multi_vendor_system != 1 ? ' top-search-bar-single-vendor' : ''; ?>">
                                        <?php echo form_open(generate_url('search'), ['id' => 'form_validate_search', 'class' => 'form_search_main', 'method' => 'get']); ?>
                                        <div class="left">
                                            <div class="dropdown search-select">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><?= !empty($input_search_category) ? category_name($input_search_category) : trans("all_categories"); ?></button>
                                                <i class="icon-arrow-down search-select-caret"></i>
                                                <input type="hidden" name="search_category_input" id="input_search_category" value="<?= !empty($input_search_category) ? $input_search_category->id : 'all'; ?>">
                                                <div class="dropdown-menu search-categories">
                                                    <a class="dropdown-item" data-value="all" href="javascript:void(0)"><?= trans("all_categories"); ?></a>
                                                    <?php if (!empty($this->parent_categories)):
                                                        foreach ($this->parent_categories as $search_cat):?>
                                                            <a class="dropdown-item" data-value="<?= $search_cat->id; ?>" href="javascript:void(0)"><?= html_escape($search_cat->name); ?></a>
                                                        <?php endforeach;
                                                    endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <input type="text" name="search" maxlength="300" pattern=".*\S+.*" id="input_search" class="form-control input-search" value="<?php echo (!empty($filter_search)) ? $filter_search : ''; ?>" placeholder="<?php echo trans("search_exp"); ?>" required autocomplete="off">
                                            <button class="btn btn-default btn-search"><i class="icon-search"></i></button>
                                            <div id="response_search_results" class="search-results-ajax"></div>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 nav-top-right">
                                <ul class="nav align-items-center">
                                    <?php if ($this->is_sale_active): ?>
                                        <li class="nav-item nav-item-cart li-main-nav-right">
                                            <a href="<?php echo generate_url("cart"); ?>">
                                                
                                                <img src="/assets/img/cart1.svg" width=30px>
                                                <?php $cart_product_count = get_cart_product_count(); ?>
                                                <span class="notification span_cart_product_count <?= $cart_product_count <= 0 ? 'visibility-hidden' : ''; ?>"><?php echo $cart_product_count; ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($this->auth_check): ?>
                                        <li class="nav-item li-main-nav-right">
                                            <a href="<?php echo generate_url("wishlist") . "/" . $this->auth_user->slug; ?>">
                                                <!--i class="icon-heart-o"></i-->
                                                <img src="/assets/img/heart.svg" width=30px>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="nav-item li-main-nav-right">
                                            <a href="<?php echo generate_url("wishlist"); ?>">
                                                
                                                  <img src="/assets/img/heart.svg" width=30px>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($this->auth_check): ?>
                                        <?php if (is_multi_vendor_active()): ?>
                                            <li class="nav-item m-r-0"><a href="<?php echo generate_dash_url("add_product"); ?>" class="btn btn-md btn-custom btn-sell-now m-r-0"><?= trans("sell_now"); ?></a></li>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if (is_multi_vendor_active()): ?>
                                            <li class="nav-item m-r-0"><a href="javascript:void(0)" class="btn btn-md btn-custom btn-sell-now m-r-0" data-toggle="modal" data-target="#loginModal"><?= trans("sell_now"); ?></a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nav-main">
                    <?php $this->load->view("partials/_nav_main"); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-nav-container">
        <div class="nav-mobile-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="nav-mobile-header-container">
                        <div class="menu-icon">
                            <a href="javascript:void(0)" class="btn-open-mobile-nav"><i class="icon-menu"></i></a>
                        </div>
                        <div class="mobile-logo">
                            <a href="<?php echo lang_base_url(); ?>"><img src="<?php echo get_logo($this->general_settings); ?>" alt="logo" class="logo"></a>
                        </div>
                        <div class="mobile-search">
                            <a class="search-icon"><i class="icon-search"></i></a>
                        </div>
                        <div class="mobile-cart<?= !$this->is_sale_active ? ' hidden' : ''; ?>">
                            <a href="<?php echo generate_url("cart"); ?>"><i class="icon-cart"></i>
                                <?php $cart_product_count = get_cart_product_count(); ?>
                                <span class="notification span_cart_product_count"><?php echo $cart_product_count; ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="top-search-bar mobile-search-form <?= $this->general_settings->multi_vendor_system != 1 ? ' top-search-bar-single-vendor' : ''; ?>">
                        <?php echo form_open(generate_url('search'), ['id' => 'form_validate_search_mobile', 'method' => 'get']); ?>
                        <div class="left">
                            <div class="dropdown search-select">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><?= !empty($input_search_category) ? category_name($input_search_category) : trans("all_categories"); ?></button>
                                <i class="icon-arrow-down search-select-caret"></i>
                                <input type="hidden" name="search_category_input" id="input_search_category_mobile" value="<?= !empty($input_search_category) ? $input_search_category->id : 'all'; ?>">
                                <div class="dropdown-menu search-categories">
                                    <a class="dropdown-item" data-value="all" href="javascript:void(0)"><?= trans("all_categories"); ?></a>
                                    <?php if (!empty($this->parent_categories)):
                                        foreach ($this->parent_categories as $search_cat):?>
                                            <a class="dropdown-item" data-value="<?= $search_cat->id; ?>" href="javascript:void(0)"><?= html_escape($search_cat->name); ?></a>
                                        <?php endforeach;
                                    endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="right">
                            <input type="text" id="input_search_mobile" name="search" maxlength="300" pattern=".*\S+.*" class="form-control input-search" value="<?php echo (!empty($filter_search)) ? $filter_search : ''; ?>" placeholder="<?php echo trans("search"); ?>" required autocomplete="off">
                            <button class="btn btn-default btn-search"><i class="icon-search"></i></button>
                            <div id="response_search_results_mobile" class="search-results-ajax"></div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div id="overlay_bg" class="overlay-bg"></div>
<!--include mobile menu-->
<?php $this->load->view("partials/_nav_mobile"); ?>
<input type="hidden" class="search_type_input" name="search_type" value="product">
<?php if (!$this->auth_check): ?>
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered login-modal" role="document">
            <div class="modal-content">
                <div class="auth-box">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                    <h4 class="title"><?php echo trans("login"); ?></h4>
                    <!-- form start -->
                    <form id="form_login" novalidate="novalidate">
                        <div class="social-login">
                            <?php $this->load->view("partials/_social_login", ["or_text" => trans("login_with_email")]); ?>
                        </div>
                        <!-- include message block -->
                        <div id="result-login" class="font-size-13"></div>
                        <div class="form-group">
                            <input type="text" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("username"); ?> / <?php echo trans("email"); ?>" maxlength="255" required>
                        </div>
                        <div class="form-group password">
                            <input type="password" id="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" minlength="4" maxlength="255" required>
                            <i class="fa fa-eye" id="togglePassword" onclick="togglePassword(this)"></i>            
                        </div>
                        <div class="form-group text-right">   
                            <a href="<?php echo generate_url("forgot_password"); ?>" class="link-forgot-password"><?php echo trans("forgot_password"); ?></a>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-md btn-custom btn-block"><?php echo trans("login"); ?></button>
                        </div>

                        <p class="p-social-media m-0 m-t-5"><?php echo trans("dont_have_account"); ?> <a href="<?php echo generate_url("register"); ?>" class="link"><?php echo trans("register"); ?></a></p>
                    </form>
                    <!-- form end -->
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->general_settings->location_search_header == 1): ?>
    <div class="modal fade" id="locationModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered login-modal location-modal" role="document">
            <div class="modal-content">
                <div class="auth-box">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                    <h4 class="title"><?php echo trans("select_location"); ?></h4>
                    <p class="location-modal-description"><?= trans_with_field("location_explanation", $this->general_settings->application_name); ?></p>
                    <div class="form-group m-b-20">
                        <div class="input-group input-group-location">
                            <i class="icon-map-marker"></i>
                            <input type="text" id="input_location" class="form-control form-input" value="<?= $this->default_location_input; ?>" placeholder="<?php echo trans("enter_location") ?>" autocomplete="off">
                            <a href="javascript:void(0)" class="btn-reset-location-input<?= (empty($this->default_location->country_id)) ? ' hidden' : ''; ?>"><i class="icon-close"></i></a>
                        </div>
                        <div class="search-results-ajax">
                            <div class="search-results-location">
                                <div id="response_search_location"></div>
                            </div>
                        </div>
                        <div id="location_id_inputs">
                            <input type="hidden" name="country" value="<?= $this->default_location->country_id; ?>" class="input-location-filter">
                            <input type="hidden" name="state" value="<?= $this->default_location->state_id; ?>" class="input-location-filter">
                            <input type="hidden" name="city" value="<?= $this->default_location->city_id; ?>" class="input-location-filter">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" id="btn_submit_location" class="btn btn-md btn-custom btn-block"><?php echo trans("update_location"); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->general_settings->newsletter_status == 1 && $this->general_settings->newsletter_popup == 1): ?>
    <div id="modal_newsletter" class="modal fade modal-center modal-newsletter" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-close" aria-hidden="true"></i></button>
                    <h4 class="modal-title"><?= trans("join_newsletter"); ?></h4>
                    <p class="modal-desc"><?= trans("newsletter_desc"); ?></p>
                    <form id="form_newsletter_modal" class="form-newsletter" data-form-type="modal">
                        <div class="form-group">
                            <div class="modal-newsletter-inputs">
                                <input type="email" name="email" class="form-control form-input newsletter-input" placeholder="<?= trans('enter_email') ?>">
                                <button type="submit" id="btn_modal_newsletter" class="btn"><?= trans("subscribe"); ?></button>
                            </div>
                        </div>
                        <input type="text" name="url">
                        <div id="modal_newsletter_response" class="text-center modal-newsletter-response">
                            <div class="form-group text-center m-b-0 text-close">
                                <button type="button" class="text-close" data-dismiss="modal"><?= trans("no_thanks"); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div id="menu-overlay"></div>