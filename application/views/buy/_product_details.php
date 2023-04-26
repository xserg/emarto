<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <div class="row">
        <div class="col-12">
            <?php if ($product->product_type == 'digital'):
                if ($product->is_free_product == 1):
                    if ($this->auth_check):?>
                        <div class="row-custom m-t-10">
                            <?php echo form_open('download-free-digital-file-post'); ?>
                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                            <button class="btn btn-instant-download"><i class="icon-download-solid"></i><?php echo trans("download") ?></button>
                            <?php echo form_close(); ?>
                        </div>
                    <?php else: ?>
                        <div class="row-custom m-t-10">
                            <button class="btn btn-instant-download" data-toggle="modal" data-target="#loginModal"><i class="icon-download-solid"></i><?php echo trans("download") ?></button>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (!empty($digital_sale)): ?>
                        <div class="row-custom m-t-10">
                            <?php echo form_open('download-purchased-digital-file-post'); ?>
                            <input type="hidden" name="sale_id" value="<?php echo $digital_sale->id; ?>">
                            <button class="btn btn-instant-download"><i class="icon-download-solid"></i><?php echo trans("download") ?></button>
                            <?php echo form_close(); ?>
                        </div>
                    <?php else: ?>
                        <label class="label-instant-download"><i class="icon-download-solid"></i><?php echo trans("instant_download"); ?></label>
                    <?php endif;
                endif;
            endif; ?>

            <h1 class="product-title"><?= html_escape($title); ?></h1>
            
            <div class="row-custom meta">
                <div class="product-details-user">
                    <?php echo trans("by"); ?>&nbsp;<a href="<?php echo generate_profile_url($product->user_slug); ?>"><?php echo character_limiter(get_shop_name_product($product), 30, '..'); ?></a>
                </div>
                <?php if ($this->general_settings->product_comments == 1): ?>
                    <span><i class="icon-comment"></i><?php echo html_escape($comment_count); ?></span>
                <?php endif; ?>
              
                <span><i class="icon-heart"></i><?php echo get_product_wishlist_count($product->id); ?></span>
                <span><i class="icon-eye"></i><?php echo html_escape($product->pageviews); ?></span>
            </div>
            <div class="row-custom price">
                <div id="product_details_price_container" class="d-inline-block">
                    <?php 
                    //echo $product->price.'<br>';
                    
                      echo $product->description;
                    //$this->load->view("product/details/_price", ['product' => $product, 'price' => $product->price, 'discount_rate' => $product->discount_rate]); 
                    ?>
                    
                </div>
                <?php $show_ask = true;
                if ($product->listing_type == 'ordinary_listing' && empty($product->external_link)):
                    $show_ask = false;
                endif;
                if ($show_ask == true):?>
                    <?php if ($this->auth_check): ?>
                        <button class="btn btn-contact-seller" data-toggle="modal" data-target="#messageModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
                    <?php else: ?>
                        <button class="btn btn-contact-seller" data-toggle="modal" data-target="#loginModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
                    <?php endif;
                endif; ?>
            </div>

            <div class="row-custom details">
                
                <?php if ($this->product_settings->marketplace_sku == 1 && !empty($product->sku)): ?>
                    <div class="item-details">
                        <div class="left">
                            <label><?php echo trans("sku"); ?></label>
                        </div>
                        <div class="right">
                            <span><?php echo html_escape($product->sku); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($product->product_type == 'digital' && !empty($product->files_included)): ?>
                    <div class="item-details">
                        <div class="left">
                            <label><?php echo trans("files_included"); ?></label>
                        </div>
                        <div class="right">
                            <span><?php echo html_escape($product->files_included); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($product->listing_type == 'ordinary_listing'): ?>
                    <div class="item-details">
                        <div class="left">
                            <label><?php echo trans("uploaded"); ?></label>
                        </div>
                        <div class="right">
                            <span><?php echo time_ago($product->created_at); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php if ($product->listing_type == 'sell_on_site' || $product->listing_type == 'license_key'):
    echo form_open(get_product_form_data($product)->add_to_cart_url, ['id' => 'form_add_cart']);
endif;
if ($product->listing_type == 'bidding'):
    echo form_open(get_product_form_data($product)->add_to_cart_url, ['id' => 'form_request_quote']);
endif; ?>
    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
    <div class="row">
        <div class="col-12">
            <div class="row-custom product-variations">
                <div class="row row-product-variation item-variation">
                    <?php if (!empty($full_width_product_variations)):
                        foreach ($full_width_product_variations as $variation):
                            $this->load->view('product/details/_product_variations', ['variation' => $variation]);
                        endforeach;
                    endif;
                    if (!empty($half_width_product_variations)):
                        foreach ($half_width_product_variations as $variation):
                            $this->load->view('product/details/_product_variations', ['variation' => $variation]);
                        endforeach;
                    endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12"><?php $this->load->view('product/details/_messages'); ?></div>
    </div>
    <div class="row">
        <div class="col-12 product-add-to-cart-container">
            <?php if ($product->is_sold != 1 && $product->listing_type != 'ordinary_listing' && $product->product_type != 'digital'): ?>
                <div >
                    
                </div>
            <?php endif; 
          
            $buttton = get_product_form_data($product)->button;
            if ($product->is_sold != 1 && !empty($buttton) && !$ban):
              if (!$user->vacation_status) :
              ?>
                <div class="button-container">
                    <?php //echo $buttton; ?>
                </div>
                <?php endif; ?>
            <div class=" button-container-wishlist">
                <?php if ($this->product_model->is_product_in_wishlist($product->id) == 1): ?>                  
                    <a href="javascript:void(0)" class="btn-wishlist btn-add-remove-wishlist" data-product-id="<?php echo $product->id; ?>" data-type="details"><i class="icon-heart"></i><span><?php echo trans("remove_from_wishlist"); ?></span></a>
                <?php else: ?>
                    <a href="javascript:void(0)" class="btn-wishlist btn-add-remove-wishlist" data-product-id="<?php echo $product->id; ?>" data-type="details"><i class="icon-heart-o"></i><span><?php echo trans("add_to_wishlist"); ?></span></a>
                <?php endif; ?>
            </div>
            
            <?php endif; ?>
        </div>

      

    </div>
<?php echo form_close(); ?>

    <!--Include social share-->
<?php //$this->load->view("product/details/_product_share"); ?>