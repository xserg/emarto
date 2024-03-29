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
                                    <?php $this->load->view("product/details/_preview"); ?>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6">
                                <div id="response_product_details" class="product-content-details">
                                    <?php $this->load->view("product/details/_product_details"); ?>
                                    <div class="row-custom product-links">
                                      <div class="purchase-protection-icon">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="32" fill="blue" class="bi bi-shield-check" viewBox="0 0 16 16">
  <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
  <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
</svg>
                                      </div>

                                      <div class="purchase-protection">
                                      <a href="<?php echo generate_url("purchase-protection");?>"><b><?= trans("purchase-protection"); ?></b></a><br>
                                      <?= trans("shop_with_confidence"); ?>
                                      </div>
                                      <div class="shipping_return_policy"><a class="link-underlined" href="<?php echo generate_url("shipping_return_policy");?>"><?= trans("shipping_return_policy"); ?></a></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="product-description post-text-responsive">
                                <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab_description" data-toggle="tab" href="#tab_description_content" role="tab" aria-controls="tab_description" aria-selected="true"><?php echo trans("description"); ?></a>
                                    </li>
                                    <?php if (!empty($custom_fields)): ?>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab_additional_information" data-toggle="tab" href="#tab_additional_information_content" role="tab" aria-controls="tab_additional_information" aria-selected="false"><?php echo trans("additional_information"); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($shipping_status == 1 || $product_location_status == 1): ?>
                                        <li class="nav-item">
                                            <?php if ($shipping_status == 1 && $product_location_status != 1): ?>
                                                <a class="nav-link" id="tab_shipping" data-toggle="tab" href="#tab_shipping_content" role="tab" aria-controls="tab_shipping" aria-selected="false"><?= trans("shipping"); ?></a>
                                            <?php elseif ($shipping_status != 1 && $product_location_status == 1): ?>
                                                <a class="nav-link" id="tab_shipping" data-toggle="tab" href="#tab_shipping_content" role="tab" aria-controls="tab_shipping" aria-selected="false" onclick="load_product_shop_location_map();"><?= trans("location"); ?></a>
                                            <?php else: ?>
                                                <a class="nav-link" id="tab_shipping" data-toggle="tab" href="#tab_shipping_content" role="tab" aria-controls="tab_shipping" aria-selected="false" onclick="load_product_shop_location_map();"><?= trans("shipping_location"); ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($this->general_settings->reviews == 1): ?>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab_reviews" data-toggle="tab" href="#tab_reviews_content" role="tab" aria-controls="tab_reviews" aria-selected="false"><?php echo trans("reviews"); ?>&nbsp;(<?php echo $review_count; ?>)</a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($this->general_settings->product_comments == 1): ?>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab_comments" data-toggle="tab" href="#tab_comments_content" role="tab" aria-controls="tab_comments" aria-selected="false"><?php echo trans("comments"); ?>&nbsp;(<?php echo $comment_count; ?>)</a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($this->general_settings->facebook_comment_status == 1): ?>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab_facebook_comments" data-toggle="tab" href="#tab_facebook_comments_content" role="tab" aria-controls="facebook_comments" aria-selected="false"><?php echo trans("facebook_comments"); ?></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>

                                <div id="accordion" class="tab-content">
                                    <div class="tab-pane fade show active" id="tab_description_content" role="tabpanel">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="card-link" data-toggle="collapse" href="#collapse_description_content">
                                                    <?php echo trans("description"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                </a>
                                            </div>
                                            <div id="collapse_description_content" class="collapse-description-content collapse show" data-parent="#accordion">
                                                <div class="description">
                                                    <?= !empty($product_details->description) ? $product_details->description : ''; ?>
                                                </div>
                                                <div class="row-custom text-right m-b-10">
                                                    <?php if ($this->auth_check):
                                                        if ($product->user_id != $this->auth_user->id):?>
                                                            <a href="javascript:void(0)" class="text-muted link-abuse-report" data-toggle="modal" data-target="#reportProductModal">
                                                                <?= trans("report_this_product"); ?>
                                                            </a>
                                                        <?php endif;
                                                    else: ?>
                                                        <a href="javascript:void(0)" class="text-muted link-abuse-report" data-toggle="modal" data-target="#loginModal">
                                                            <?= trans("report_this_product"); ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($custom_fields)): ?>
                                        <div class="tab-pane fade" id="tab_additional_information_content" role="tabpanel">
                                            <div class="card">
                                                <div class="card-header">
                                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapse_additional_information_content">
                                                        <?php echo trans("additional_information"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                    </a>
                                                </div>
                                                <div id="collapse_additional_information_content" class="collapse-description-content collapse" data-parent="#accordion">
                                                    <table class="table table-striped table-product-additional-information">
                                                        <tbody>
                                                        <?php foreach ($custom_fields as $custom_field):
                                                            $field_value = get_custom_field_product_values($custom_field, $product->id, $this->selected_lang->id);
                                                            if (!empty($field_value)):?>
                                                                <tr>
                                                                    <td class="td-left"><?= parse_serialized_name_array($custom_field->name_array, $this->selected_lang->id); ?></td>
                                                                    <td class="td-right"><?= html_escape($field_value); ?></td>
                                                                </tr>
                                                            <?php endif;
                                                        endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($shipping_status == 1 || $product_location_status == 1): ?>
                                        <div class="tab-pane fade" id="tab_shipping_content" role="tabpanel">
                                            <div class="card">
                                                <div class="card-header">
                                                    <?php if ($shipping_status == 1 && $product_location_status != 1): ?>
                                                        <a class="card-link collapsed" data-toggle="collapse" href="#collapse_shipping_content"><?= trans("shipping"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i></a>
                                                    <?php elseif ($shipping_status != 1 && $product_location_status == 1): ?>
                                                        <a class="card-link collapsed" data-toggle="collapse" href="#collapse_shipping_content" onclick="load_product_shop_location_map();"><?= trans("location"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i></a>
                                                    <?php else: ?>
                                                        <a class="card-link collapsed" data-toggle="collapse" href="#collapse_shipping_content" onclick="load_product_shop_location_map();"><?= trans("shipping_location"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i></a>
                                                    <?php endif; ?>
                                                </div>
                                                <div id="collapse_shipping_content" class="collapse-description-content collapse" data-parent="#accordion">
                                                    <table class="table table-product-shipping">
                                                        <tbody>
                                                        <?php if ($shipping_status == 1): ?>
                                                            <tr>
                                                                <td class="td-left"><?php echo trans("shipping_cost"); ?></td>
                                                                <td class="td-right">
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <label class="control-label"><?php echo trans("select_your_location"); ?></label>
                                                                            </div>
                                                                            <div class="col-12 col-md-4 m-b-sm-15">
                                                                                <select id="select_countries_product" name="country_id" class="select2 form-control" data-placeholder="<?= trans("country"); ?>" onchange="get_states(this.value,false,'product'); $('#product_shipping_cost_container').empty();">
                                                                                    <option></option>
                                                                                    <?php foreach ($this->countries as $item): ?>
                                                                                        <option value="<?= $item->id; ?>" <?= ($this->default_location->country_id == $item->id) ? ' selected' : ''; ?>><?= html_escape($item->name); ?></option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            </div>
                                                                            <div id="get_states_container_product" class="col-12 col-md-4">
                                                                                <select id="select_states_product" name="state_id" class="select2 form-control" data-placeholder="<?= trans("state"); ?>" onchange="get_product_shipping_cost(this.value, '<?= $product->id; ?>');">
                                                                                    <option></option>
                                                                                    <?php $states = get_states_by_country($this->default_location->country_id);
                                                                                    if (!empty($states)):
                                                                                        foreach ($states as $item): ?>
                                                                                            <option value="<?= $item->id; ?>" <?= $item->id == $this->default_location->state_id ? 'selected' : ''; ?>><?= html_escape($item->name); ?></option>
                                                                                        <?php endforeach;
                                                                                    endif; ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="product_shipping_cost_container" class="product-shipping-methods"></div>
                                                                    <div class="row-custom">
                                                                        <div class="product-shipping-loader">
                                                                            <div class="spinner">
                                                                                <div class="bounce1"></div>
                                                                                <div class="bounce2"></div>
                                                                                <div class="bounce3"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php if (!empty($delivery_time)): ?>
                                                                <tr>
                                                                    <td class="td-left"><?php echo trans("delivery_time"); ?></td>
                                                                    <td class="td-right"><span><?= @parse_serialized_option_array($delivery_time->option_array, $this->selected_lang->id); ?></span></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php if ($product_location_status == 1): ?>
                                                            <tr>
                                                                <td class="td-left"><?php echo trans("shop_location"); ?></td>
                                                                <td class="td-right"><span id="span_shop_location_address"><?php echo get_location($user); ?></span></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                    <?php if ($product_location_status == 11): ?>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="product-location-map">
                                                                    <iframe id="iframe_shop_location_address" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->general_settings->reviews == 1): ?>
                                        <div class="tab-pane fade" id="tab_reviews_content" role="tabpanel">
                                            <div class="card">
                                                <div class="card-header">
                                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapse_reviews_content">
                                                        <?php echo trans("reviews"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                    </a>
                                                </div>
                                                <div id="collapse_reviews_content" class="collapse-description-content collapse" data-parent="#accordion">
                                                    <div id="review-result">
                                                        <?php $this->load->view('product/details/_reviews'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->general_settings->product_comments == 1): ?>
                                        <div class="tab-pane fade" id="tab_comments_content" role="tabpanel">
                                            <div class="card">
                                                <div class="card-header">
                                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapse_comments_content">
                                                        <?php echo trans("comments"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                    </a>
                                                </div>
                                                <div id="collapse_comments_content" class="collapse-description-content collapse" data-parent="#accordion">
                                                    <input type="hidden" value="<?php echo $comment_limit; ?>" id="product_comment_limit">
                                                    <div class="comments-container">
                                                        <div class="row">
                                                            <div class="col-12 col-md-6">
                                                                <?php $this->load->view('product/details/_comments'); ?>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <div class="col-comments-inner">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="row-custom row-comment-label">
                                                                                <label class="label-comment"><?php echo trans("add_a_comment"); ?></label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <form id="form_add_comment">
                                                                                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                                                                <?php if (!$this->auth_check): ?>
                                                                                    <div class="form-row">
                                                                                        <div class="form-group col-md-6">
                                                                                            <input type="text" name="name" id="comment_name" class="form-control form-input" placeholder="<?php echo trans("name"); ?>">
                                                                                        </div>
                                                                                        <div class="form-group col-md-6">
                                                                                            <input type="email" name="email" id="comment_email" class="form-control form-input" placeholder="<?php echo trans("email_address"); ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                                <div class="form-group">
                                                                                    <textarea name="comment" id="comment_text" class="form-control form-input form-textarea" placeholder="<?php echo trans("comment"); ?>"></textarea>
                                                                                </div>
                                                                                <?php if (!$this->auth_check):
                                                                                    generate_recaptcha();
                                                                                endif; ?>
                                                                                <div class="form-group">
                                                                                    <button type="submit" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
                                                                                </div>
                                                                            </form>
                                                                            <div id="message-comment-result" class="message-comment-result"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->general_settings->facebook_comment_status == 1): ?>
                                        <div class="tab-pane fade" id="tab_facebook_comments_content" role="tabpanel">
                                            <div class="card">
                                                <div class="card-header">
                                                    <a class="card-link collapsed" data-toggle="collapse" href="#collapse_facebook_comments_content">
                                                        <?php echo trans("facebook_comments"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                    </a>
                                                </div>
                                                <div id="collapse_facebook_comments_content" class="collapse-description-content collapse" data-parent="#accordion">
                                                    <div class="fb-comments" data-href="<?php echo current_url(); ?>" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
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
