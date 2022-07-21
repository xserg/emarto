<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $selected_categories = array();
if (!empty(old("category_ids"))) {
    $selected_categories = explode(',', old("category_ids"));
}
$selected_products = array();
if ($this->session->flashdata('selected_products_ids')) {
    $selected_products = $this->session->flashdata('selected_products_ids');
}

function print_sub_categories($categories, $category_ids, $selected_categories, $selected_products)
{
    $ci =& get_instance();
    $html = '<ul>';
    foreach ($categories as $category):
        if (in_array($category->id, $category_ids)):
            $html .= "<li><div class='category-title'>";
            if (in_array($category->id, $selected_categories)):
                $html .= "<input type='checkbox' name='category_id[]' id='cb_category_" . $category->id . "' value='" . $category->id . "' data-id='" . $category->id . "' data-parent='" . $category->parent_tree . "' class='category-checkbox' checked>";
            else:
                $html .= "<input type='checkbox' name='category_id[]' id='cb_category_" . $category->id . "' value='" . $category->id . "' data-id='" . $category->id . "' data-parent='" . $category->parent_tree . "' class='category-checkbox'>";
            endif;
            $html .= "&nbsp;&nbsp;<label for='cb_category_" . $category->id . "' class='lbl-cat'>" . html_escape($category->name) . "</label></div></li>";
            $products = get_coupon_products_by_category($ci->auth_user->id, $category->id);
            if (!empty($products)):
                $html .= "<div class='items'>";
                foreach ($products as $product):
                    $html .= "<div class='item'>";
                    if (in_array($product->id, $selected_products)):
                        $html .= "<input type='checkbox' name='product_id[]' id='cb_product_" . $product->id . "' value='" . $product->id . "' data-parent='" . $category->parent_tree . "," . $category->id . "' checked>";
                    else:
                        $html .= "<input type='checkbox' name='product_id[]' id='cb_product_" . $product->id . "' value='" . $product->id . "' data-parent='" . $category->parent_tree . "," . $category->id . "'>";
                    endif;
                    $html .= "&nbsp;&nbsp;<label for='cb_product_" . $product->id . "'>" . html_escape($product->title) . "</label>";
                    $html .= "</div>";
                endforeach;
                $html .= "</div>";
            endif;
            $subcategories = get_subcategories($category->id);
            if (!empty($subcategories)):
                $html .= print_sub_categories($subcategories, $category_ids, $selected_categories, $selected_products);
            endif;
        endif;
    endforeach;
    $html .= '</ul>';
    return $html;
} ?>

    <div class="row">
        <div class="col-sm-12">
            <?php $this->load->view('dashboard/includes/_messages'); ?>
        </div>
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="left">
                        <h3 class="box-title"><?= html_escape($title); ?></h3>
                    </div>
                    <div class="right">
                        <a href="<?php echo generate_dash_url("coupons"); ?>" class="btn btn-success btn-add-new">
                            <i class="fa fa-list-ul"></i>&nbsp;&nbsp;<?php echo trans("coupons"); ?>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <?php echo form_open("add-coupon-post"); ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans("coupon_code"); ?>&nbsp;&nbsp;<small>(<?= trans("exp_special_characters"); ?>)</small></label>
                        <div class="position-relative">
                            <input type="text" name="coupon_code" id="input_coupon_code" value="<?= old("coupon_code"); ?>" class="form-control form-input" placeholder="<?php echo trans("coupon_code"); ?>" maxlength="49" required>
                            <button type="button" class="btn btn-default btn-generate-sku" onclick="$('#input_coupon_code').val(Math.random().toString(36).substr(2,8).toUpperCase());"><?= trans("generate"); ?></button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans("discount_rate"); ?></label>
                        <div class="input-group">
                            <span class="input-group-addon">%</span>
                            <input type="number" name="discount_rate" id="input_discount_rate" value="<?= old("discount_rate"); ?>" aria-describedby="basic-addon-discount" class="form-control form-input" placeholder="<?= trans("eg"); ?>: 5" min="0" max="99" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans("number_of_coupons"); ?>&nbsp;<small>(<?= trans("number_of_coupons_exp"); ?>)</small></label>
                        <input type="number" name="coupon_count" value="<?= old("coupon_count"); ?>" class="form-control form-input" placeholder="<?= trans("eg"); ?>: 100" min="1" max="99999999" required>
                    </div>
                    <div class="form-group">
                        <label class="font-600"><?php echo trans("minimum_order_amount"); ?>&nbsp;<small>(<?= trans("coupon_minimum_cart_total_exp"); ?>)</small></label>
                        <div class="input-group">
                            <span class="input-group-addon"><?= $this->default_currency->symbol; ?></span>
                            <input type="hidden" name="currency" value="<?= $this->default_currency->code; ?>">
                            <input type="text" name="minimum_order_amount" id="product_price_input" value="<?= old("minimum_order_amount"); ?>" aria-describedby="basic-addon1" class="form-control form-input price-input validate-price-input" placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label><?php echo trans("coupon_usage_type"); ?></label>
                            </div>
                            <div class="col-md-6 col-sm-12 col-custom-option">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="usage_type" value="single" id="usage_type_1" class="custom-control-input" <?= old("usage_type") != 'multiple' ? 'checked' : ''; ?>>
                                    <label for="usage_type_1" class="custom-control-label"><?php echo trans("coupon_usage_type_1"); ?></label>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-custom-option">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="usage_type" value="multiple" id="usage_type_2" class="custom-control-input" <?= old('usage_type') == 'multiple' ? 'checked' : ''; ?>>
                                    <label for="usage_type_2" class="custom-control-label"><?php echo trans("coupon_usage_type_2"); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 max-600">
                                <label><?php echo trans("expiry_date"); ?></label>
                                <div class='input-group date' id='datetimepicker'>
                                    <input type='text' class="form-control" name="expiry_date" value="<?= old("expiry_date"); ?>" placeholder="<?php echo trans("expiry_date"); ?>" required>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo trans("products"); ?></label>
                        <div class="category-structure-list-container">
                            <?php if (!empty($categories) && !empty($categories[0])):
                                foreach ($categories as $category):
                                    if ($category->parent_id == 0):?>
                                        <ul class="category-structure-list">
                                            <li>
                                                <div class="category-title">
                                                    <input type='checkbox' name='category_id[]' value='<?= $category->id; ?>' id='cb_category_<?= $category->id; ?>' data-id='<?= $category->id; ?>' data-parent="<?= $category->parent_tree; ?>" class='category-checkbox' <?= in_array($category->id, $selected_categories) ? 'checked' : ''; ?>>&nbsp;&nbsp;<label for="cb_category_<?= $category->id; ?>" class="lbl-cat"><?= html_escape($category->name); ?></label>
                                                </div>
                                                <?php $products = get_coupon_products_by_category($this->auth_user->id, $category->id);
                                                if (!empty($products)):?>
                                                    <div class="items">
                                                        <?php foreach ($products as $product): ?>
                                                            <div class="item">
                                                                <input type='checkbox' name='product_id[]' id="cb_product_<?= $product->id; ?>" value='<?= $product->id; ?>' <?= in_array($product->id, $selected_products) ? 'checked' : ''; ?>>&nbsp;&nbsp;<label for="cb_product_<?= $product->id; ?>"><?= html_escape($product->title); ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </li>
                                            <?php
                                            $subcategories = get_subcategories($category->id);
                                            echo print_sub_categories($subcategories, $category_ids, $selected_categories, $selected_products); ?>
                                        </ul>
                                    <?php endif;
                                endforeach;
                            endif; ?>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" name="submit" value="update" class="btn btn-md btn-success"><?php echo trans("add_coupon") ?></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <script src="<?php echo base_url(); ?>assets/js/moment.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/ru.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    
    
    <script>
        //datetimepicker
        $(function () {
            $('#datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                locale: sys_lang_id == 1 ? 'en' : 'ru'
            });
        });
        $(document).on('change', '.category-checkbox', function () {
            var id = $(this).attr('data-id');
            var is_all_checked = true;
            var is_parent_checked = true;
            if (!$(this).is(":checked")) {
                is_parent_checked = false;
            }
            $("input:checkbox").each(function () {
                var data = $(this).attr('data-parent');
                var array = data.split(',');
                if (jQuery.inArray(id, array) != -1) {
                    if (!$(this).is(":checked")) {
                        is_all_checked = false;
                    }
                }
            });
            $("input:checkbox").each(function () {
                var data = $(this).attr('data-parent');
                var array = data.split(',');
                if (jQuery.inArray(id, array) != -1) {
                    if (is_all_checked == true) {
                        $(this).prop('checked', false);
                    } else {
                        $(this).prop('checked', true);
                    }
                    //uncheck if parent unchecked
                    if (is_parent_checked == false) {
                        $(this).prop('checked', false);
                    } else {
                        $(this).prop('checked', true);
                    }
                }
            });
        });
    </script>
<?php if ($this->session->flashdata('reset_checkbox')): ?>
    <script>
        $("input[type=checkbox]").prop('checked', false);
    </script>
<?php endif; ?>