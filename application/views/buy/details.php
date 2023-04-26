<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <!-- Wrapper -->
    <div id="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="nav-breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-products">
                            <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                            <?php if (!empty($parent_categories_tree)):
                                foreach ($parent_categories_tree as $item):?>
                                    <li class="breadcrumb-item"><a href="<?php echo generate_category_url($item); ?>"><?php echo category_name($item); ?></a></li>
                                <?php endforeach;
                            endif; ?>
                            <li class="breadcrumb-item active"><?= html_escape($title); ?></li>
                        </ol>
                    </nav>
                </div>

                <div class="col-12">
                  <?php if ($user->vacation_status) : ?>
                  <div class="vacation"><img src="/assets/img/flag.png" width=30px> <?= $user->vacation_text ?></div>
                  <br>
                  <?php endif; ?>
                    <div class="product-details-container <?php echo ((!empty($video) || !empty($audio)) && item_count($product_images) < 2) ? "product-details-container-digital" : ""; ?>">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6">
                                <div id="product_slider_container">
                                    <?php $this->load->view("buy/_preview"); ?>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6">
                                <div id="response_product_details" class="product-content-details">
                                    <?php $this->load->view("buy/_product_details"); ?>
                                    
                                    <div class="row-custom product-links">  
                                      <div class="shipping_return_policy"><a class="link-underlined" href="<?php echo generate_url("shipping_return_policy");?>"><?= trans("shipping_return_policy"); ?></a></div>                                    
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="product-description post-text-responsive">
                                


                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row-custom row-bn">
                        <!--Include banner-->
                        <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "product", "class" => "m-b-30"]); ?>
                    </div>
                </div>
                <?php if (!empty($user_products) && $this->general_settings->multi_vendor_system == 1): ?>
                    <div class="col-12 section section-related-products m-t-30">
                        <h3 class="title"><?php echo trans("more_from"); ?>&nbsp;<a href="<?php echo generate_profile_url($user->slug); ?>"><?php echo get_shop_name($user); ?></a></h3>
                        <div class="row row-product">
                            <!--print related posts-->
                            <?php $count = 0;
                            foreach ($user_products as $item):
                                if ($count < 5):?>
                                    <div class="col-6 col-sm-4 col-md-3 col-mds-5 col-product">
                                        <?php $this->load->view('product/_product_item', ['product' => $item]); ?>
                                    </div>
                                <?php endif;
                                $count++;
                            endforeach; ?>
                        </div>
                        <?php if (item_count($user_products) > 5): ?>
                            <div class="row-custom text-center">
                                <a href="<?php echo generate_profile_url($product->user_slug); ?>" class="link-see-more"><span><?php echo trans("view_all"); ?>&nbsp;</span><i class="icon-arrow-right"></i></a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($related_products)): ?>
                    <div class="col-12 section section-related-products">
                        <h3 class="title"><?php echo trans("you_may_also_like"); ?></h3>
                        <div class="row row-product">
                            <!--print related posts-->
                            <?php foreach ($related_products as $item): ?>
                                <div class="col-6 col-sm-4 col-md-3 col-mds-5 col-product">
                                    <?php $this->load->view('product/_product_item', ['product' => $item]); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-12">
                    <div class="row-custom row-bn">
                        <!--Include banner-->
                        <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "product_bottom", "class" => "m-b-30"]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view("partials/_modal_send_message", ['subject' => html_escape($title), 'product_id' => $product->id]); ?>

<?php if ($this->auth_check && $product->user_id != $this->auth_user->id): ?>
    <div class="modal fade" id="reportProductModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-custom modal-report-abuse">
                <form id="form_report_product" method="post">
                    <input type="hidden" name="id" value="<?= $product->id; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo trans("report_this_product"); ?></h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true"><i class="icon-close"></i> </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div id="response_form_report_product" class="col-12"></div>
                            <div class="col-12">
                                <div class="form-group m-0">
                                    <label><?= trans("description"); ?></label>
                                    <textarea name="description" class="form-control form-textarea" placeholder="<?= trans("abuse_report_exp"); ?>" minlength="5" maxlength="10000" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->general_settings->facebook_comment_status == 1):
    echo $this->general_settings->facebook_comment;
endif; ?>