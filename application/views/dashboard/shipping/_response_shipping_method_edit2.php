<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($method)):
    $option_unique_id = $method->id;
    $selected_option = $method->method_type;

    ?>
<div id="row_shipping_method" class="row">
    <div class="col-sm-12">
        <input type="hidden" name="option_unique_id" value="<?= $option_unique_id; ?>">
        <input type="hidden" name="method_type" value="<?= $selected_option; ?>">
        <input type="hidden" name="method_operation" value="edit">
            <div class="response-shipping-method">
                <span class="title"><?= @parse_serialized_name_array($method->name_array, $this->selected_lang->id); ?></span>
                <div id="modalMethod" class="" role="dialog">
                    <div class="">
                        <div class="modal-content">

                            <div class="modal-body">
                                <div class="form-group m-b-10">

                                    <?php foreach ($this->languages as $language): ?>
                                        <input type="hidden" name="method_name_lang_<?= $language->id; ?>" class="form-control form-input m-b-5" value="flat_rate" placeholder="<?= $language->name; ?>" maxlength="255">
                                    <?php endforeach; ?>
                                </div>
                                <div class="form-group">
                                    <label><?= trans("cost_calculation_type"); ?></label>
                                    <select name="flat_rate_cost_calculation_type" class="form-control custom-select">
                                        <option value="each_product" <?= $method->flat_rate_cost_calculation_type == "each_product" ? "selected" : ""; ?>><?= trans("charge_shipping_for_each_product"); ?></option>
                                        <option value="each_different_product" <?= $method->flat_rate_cost_calculation_type == "each_different_product" ? "selected" : ""; ?>><?= trans("charge_shipping_for_each_different_product"); ?></option>
                                        <option value="cart_total" <?= $method->flat_rate_cost_calculation_type == "cart_total" ? "selected" : ""; ?>><?= trans("fixed_shipping_cost_for_cart_total"); ?></option>
                                    </select>
                                </div>

                                <?php if (!empty($shipping_classes)): ?>
                                    <div class="form-group">
                                        <label><?= trans("shipping_class_costs"); ?></label>
                                        <?php
                                        $class_data = get_shipping_class_data($method->flat_rate_class_costs_array);
                                        foreach ($shipping_classes as $shipping_class):
                                            $class_cost = get_shipping_class_cost_by_method($method->flat_rate_class_costs_array, $shipping_class->id);
                                            if (!empty($class_cost)):
                                                $class_cost = get_price($class_cost, "input");
                                            endif; ?>
                                            <div class="input-group m-b-5">
                                                <span class="shipping-label"><?= @parse_serialized_name_array($shipping_class->name_array, $this->selected_lang->id); ?>,  <?= $this->default_currency->symbol; ?></span>
                                                <input type="text" name="flat_rate_cost_class_<?= $shipping_class->id; ?>" class="form-control form-input price-input" value="<?= $class_cost; ?>"
                                                       placeholder="0.00" maxlength="19" id="flat_rate_cost_class_<?= $shipping_class->id; ?>" style="width: 30%;">

                                                         <select name="time_<?= $shipping_class->id; ?>" class="form-control form-input" style="width: 30%; margin-left: 10px;">
                                                           <option></option>
                                                           <?php foreach ($shipping_default_delivery_times as $k => $delivery_time): ?>
                                                             <option value=<?=$k?> <?php if($class_data[$shipping_class->id]['time'] == $k) echo ' selected'; ?>><?= @parse_serialized_option_array($delivery_time, $this->selected_lang->id); ?></option>
                                                           <?php endforeach; ?>
                                                         </select>

                                                       <div class="col-md-1 col-sm-3 m-t-10">
                                                           <div class="custom-control custom-radio">
                                                               <input type="radio" name="status_<?= $shipping_class->id; ?>" value="1" id="status_<?= $shipping_class->id; ?>_1" class="custom-control-input" <?php if($class_data[$shipping_class->id]['status'] == 1) echo ' checked'; ?>>
                                                               <label for="status_<?= $shipping_class->id; ?>_1" class="custom-control-label"><?= trans("enable"); ?></label>
                                                           </div>
                                                       </div>
                                                       <div class="col-md-1 col-sm-3 m-t-10">
                                                           <div class="custom-control custom-radio">
                                                               <input type="radio" name="status_<?= $shipping_class->id; ?>" value="0" id="status_<?= $shipping_class->id; ?>_2" class="custom-control-input" <?php if($class_data[$shipping_class->id]['status'] == 0) echo ' checked'; ?>>
                                                               <label for="status_<?= $shipping_class->id; ?>_2" class="custom-control-label"><?= trans("disable"); ?></label>
                                                           </div>
                                                       </div>


                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
<?php endif; ?>
