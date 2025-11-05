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

        <div class="form-group">
          <div>
            <div class="col-md-5 col-sm-4"><label><?= trans("shipping_class_costs"); ?></label></div>
            <div class="col-md-7 col-sm-8"><label><?= trans("shipping_time"); ?></label></div>
          </div>
            <?php
            $class_data = get_shipping_class_data($method->flat_rate_class_costs_array);
                if ($this->auth_user->currency) {
                    $this->default_currency = get_currency_by_code($this->auth_user->currency);
                } 
            foreach ($shipping_classes as $shipping_class):
                $class_cost = get_shipping_class_cost_by_method($method->flat_rate_class_costs_array, $shipping_class->id);
                if (!empty($class_cost)):
                    $class_cost = get_price($class_cost, "input");
                endif; ?>

                <div class="input-group m-b-5">

                  <div class="col-md-2 col-sm-2" style="padding-right: 0;">
                    <span class="shipping-label"><?= @parse_serialized_name_array($shipping_class->name_array, $this->selected_lang->id); ?>,  <?= $this->default_currency->symbol; ?></span>
                  </div>

                  <div class="col-md-2 col-sm-2" style="padding-left: 0;">
                    <input type="text" name="flat_rate_cost_class_<?= $shipping_class->id; ?>" class="form-control form-input price-input" value="<?= $class_cost; ?>"
                           placeholder="0.00" maxlength="19" id="flat_rate_cost_class_<?= $shipping_class->id; ?>">
                  </div>


                  <div class="col-md-3 col-sm-2" style="padding-left: 0;">
                     <select name="time_<?= $shipping_class->id; ?>" class="form-control form-input">
                       <option></option>
                       <?php foreach ($shipping_delivery_time_ranges[$shipping_class->id] as $delivery_time): ?>
                         <option value=<?=$delivery_time?> <?php if($class_data[$shipping_class->id]['time'] == $delivery_time) echo ' selected'; ?>><?= $delivery_time . ' ' . trans('business2') . ' ' . trans('days3') ?></option>
                       <?php endforeach; ?>
                     </select>
                  </div>

                  <div class="col-md-5 col-sm-3">
                      <div class="row m-t-10">
                           <div class="col-md-4 custom-control custom-radio">
                               <input type="radio" name="status_<?= $shipping_class->id; ?>" value="1" id="status_<?= $shipping_class->id; ?>_1" class="custom-control-input"  <?php if($class_data[$shipping_class->id]['status'] == 1) echo ' checked'; ?>>
                               <label for="status_<?= $shipping_class->id; ?>_1" class="custom-control-label"><?= trans("enable"); ?></label>
                           </div>

                           <div class="col-md-4 custom-control custom-radio">
                               <input type="radio" name="status_<?= $shipping_class->id; ?>" value="0" id="status_<?= $shipping_class->id; ?>_2" class="custom-control-input" <?php if($class_data[$shipping_class->id]['status'] == 0) echo ' checked'; ?>>
                               <label for="status_<?= $shipping_class->id; ?>_2" class="custom-control-label"><?= trans("disable"); ?></label>
                           </div>
                          <div class="col-md-4 custom-control custom-radio">
                              <input type="radio" name="status_<?= $shipping_class->id; ?>" value="2" id="status_<?= $shipping_class->id; ?>_3" class="custom-control-input" <?php if($class_data[$shipping_class->id]['status'] == 2) echo ' checked'; ?>>
                              <label for="status_<?= $shipping_class->id; ?>_3" class="custom-control-label"><?= trans("free_shipping"); ?></label>
                          </div>
                      </div>
                  </div>

                </div>
            <?php endforeach; ?>
        </div>

      </div>
  </div>
</div>
<?php endif; ?>
<style>
.shipping-label2 {
    display: table-cell;
    width: 200px;
    border: 1px solid #ccc;
    border-right: 0;
    font-size: 14px;
    font-weight: 400;
    height: 40px;
    text-align: center;
    border-color: #d2d6de;
    background-color: #F1F3F5;
    vertical-align: middle;
    padding-right: : 0;
}
</style>
