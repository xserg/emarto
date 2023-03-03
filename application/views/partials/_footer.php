<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer-top">
                    <div class="row">
                        <div class="col-12 col-md-3 footer-widget">                            
                            <div class="row-custom">
                                <div class="footer-about">
                                    <?= $this->settings->about_footer; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 footer-widget">
                            <div class="nav-footer">
                                <div class="row-custom">
                                    <h4 class="footer-title"><?php echo trans("footer_quick_links"); ?></h4>
                                </div>
                                <div class="row-custom">
                                    <ul>
                                        
                                        <?php if (!empty($this->menu_links)):
                                            foreach ($this->menu_links as $menu_link):
                                                if ($menu_link->location == 'quick_links'):
                                                    $item_link = generate_menu_item_url($menu_link);
                                                    if (!empty($menu_link->page_default_name)):
                                                        $item_link = generate_url($menu_link->page_default_name);
                                                    endif; ?>
                                                    <li><a href="<?= $item_link; ?>"><?php echo html_escape($menu_link->title); ?></a></li>
                                                <?php endif;
                                            endforeach;
                                        endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 footer-widget">
                            <div class="nav-footer">
                                <div class="row-custom">
                                    <h4 class="footer-title"><?php echo trans("footer_information"); ?></h4>
                                </div>
                                <div class="row-custom">
                                    <ul>
                                        <?php if (!empty($this->menu_links)):
                                            foreach ($this->menu_links as $menu_link):
                                                if ($menu_link->location == 'information'):
                                                    $item_link = generate_menu_item_url($menu_link);
                                                    if (!empty($menu_link->page_default_name)):
                                                        $item_link = generate_url($menu_link->page_default_name);
                                                    endif; ?>
                                                    <li><a href="<?= $item_link; ?>"><?php echo html_escape($menu_link->title); ?></a></li>
                                                <?php endif;
                                            endforeach;
                                        endif; ?>

                                        <?php if (!empty($this->menu_links)):
                                            foreach ($this->menu_links as $menu_link):
                                                if ($menu_link->location == 'information'):?>
                                                <?php endif;
                                            endforeach;
                                        endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 footer-widget">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="footer-title"><?php echo trans("follow_us"); ?></h4>
                                    
                                </div>
                            </div>
                            <?php if ($this->general_settings->newsletter_status == 1): ?>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="newsletter">
                                            <div class="widget-newsletter">
                                                <h4 class="footer-title"><?= trans("newsletter"); ?></h4>
                                                <form id="form_newsletter_footer" class="form-newsletter">
                                                    <div class="newsletter">
                                                        <input type="email" name="email" class="newsletter-input" maxlength="199" placeholder="<?php echo trans("enter_email"); ?>" required>
                                                        <button type="submit" name="submit" value="form" class="newsletter-button"><?php echo trans("subscribe"); ?></button>
                                                    </div>
                                                    <input type="text" name="url">
                                                    <div id="form_newsletter_response"></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="footer-bottom">
                <div class="container">
                    <div class="copyright">
                        <?php echo html_escape($this->settings->copyright); ?>
                    </div>
                    
                    <div class="footer-info">
                      <ul>
                    <?php if (!empty($this->menu_links)):
                        foreach ($this->menu_links as $menu_link):
                            if ($menu_link->location == 'information'):
                                $item_link = generate_menu_item_url($menu_link);
                                if (!empty($menu_link->page_default_name)):
                                    $item_link = generate_url($menu_link->page_default_name);
                                endif; ?>
                                <li><a href="<?= $item_link; ?>"><?php echo html_escape($menu_link->title); ?></a></li>
                            <?php endif;
                        endforeach;
                    endif; ?>
                  </ul>
                  </div>
                  
                    <div class="footer-social-links">
                      <?php $this->load->view('partials/_social_links', ['show_rss' => true]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php if (!isset($_COOKIE["modesy_cookies_warning"]) && $this->settings->cookies_warning): ?>
    <div class="cookies-warning">
        <div class="text"><?php echo $this->settings->cookies_warning_text; ?></div>
        <a href="javascript:void(0)" onclick="hide_cookies_warning();" class="icon-cl"> <i class="icon-close"></i></a>
    </div>
<?php endif; ?>
<a href="javascript:void(0)" class="scrollup"><i class="icon-arrow-up"></i></a>

<script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url(); ?>assets/js/plugins-2.1.js"></script>
<script src="<?= base_url(); ?>assets/js/script-2.0.js"></script>
<?php if (!empty($this->session->userdata('mds_send_email_data'))): ?>
    <script>$(document).ready(function () {
            var data = JSON.parse(<?= json_encode($this->session->userdata("mds_send_email_data"));?>);
            if (data) {
                data[mds_config.csfr_token_name] = $.cookie(mds_config.csfr_cookie_name);
                data["sys_lang_id"] = mds_config.sys_lang_id;
                $.ajax({
                    type: "POST", url: "<?= base_url(); ?>mds-send-email-post", data: data, success: function (response) {
                    }
                });
            }
        });</script>
<?php endif;
$this->session->unset_userdata('mds_send_email_data'); ?>
<?php if (check_cron_time() == true): ?>
    <script>$.ajax({type: "POST", url: "<?= base_url(); ?>mds-run-internal-cron"});</script>
<?php endif; ?>
<script>$('<input>').attr({type: 'hidden', name: 'sys_lang_id', value: '<?= $this->selected_lang->id; ?>'}).appendTo('form[method="post"]');</script>
<script>
    <?php if (!empty($index_categories)):foreach ($index_categories as $category):?>
    if ($('#category_products_slider_<?= $category->id; ?>').length != 0) {
        $('#category_products_slider_<?= $category->id; ?>').slick({autoplay: false, autoplaySpeed: 4900, infinite: true, speed: 200, swipeToSlide: true, rtl: mds_config.rtl, cssEase: 'linear', prevArrow: $('#category-products-slider-nav-<?= $category->id; ?> .prev'), nextArrow: $('#category-products-slider-nav-<?= $category->id; ?> .next'), slidesToShow: 5, slidesToScroll: 1, responsive: [{breakpoint: 992, settings: {slidesToShow: 4, slidesToScroll: 1}}, {breakpoint: 768, settings: {slidesToShow: 3, slidesToScroll: 1}}, {breakpoint: 576, settings: {slidesToShow: 2, slidesToScroll: 1}}]});
    }
    <?php endforeach;
    endif; ?>
    <?php if ($this->general_settings->pwa_status == 1): ?>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('<?= base_url();?>pwa-sw.js').then(function (registration) {
            }, function (err) {
                console.log('ServiceWorker registration failed: ', err);
            }).catch(function (err) {
                console.log(err);
            });
        });
    } else {
        console.log('service worker is not supported');
    }
    <?php endif; ?>
</script>
<?php if (!empty($video) || !empty($audio)): ?>
    <script src="<?= base_url(); ?>assets/vendor/plyr/plyr.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/plyr/plyr.polyfilled.min.js"></script>
    <script>const player = new Plyr('#player');
        $(document).ajaxStop(function () {
            const player = new Plyr('#player');
        });
        const audio_player = new Plyr('#audio_player');
        $(document).ajaxStop(function () {
            const player = new Plyr('#audio_player');
        });
        $(document).ready(function () {
            setTimeout(function () {
                $(".product-video-preview").css("opacity", "1");
            }, 300);
            setTimeout(function () {
                $(".product-audio-preview").css("opacity", "1");
            }, 300);
        });</script>
<?php endif; ?>
<?php if (!empty($load_support_editor)):
    $this->load->view('support/_editor');
endif; ?>
<?php if (check_newsletter_modal($this)): ?>
    <script>$(window).on('load', function () {
            $('#modal_newsletter').modal('show');
        });</script>
<?php endif; ?>
<?= $this->general_settings->google_analytics; ?>
<?= $this->general_settings->custom_javascript_codes; ?>
</body>
</html>
