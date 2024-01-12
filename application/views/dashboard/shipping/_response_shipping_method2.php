<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

    <div id="row_shipping_method" class="row">
        <div class="col-sm-12">
            <input type="hidden" name="method_type" value="flat_rate">
            <input type="hidden" name="method_operation" value="add">
                <div class="response-shipping-method">

                    <div id="modalMethod">
                        <div >
                            <div class="modal-content">


                                <div class="modal-body">
                                  <div class="form-group">
                                      <label><?= trans("cost_calculation_type"); ?></label>
                                      <select name="flat_rate_cost_calculation_type" class="form-control custom-select">
                                          <option value="each_product" <?= $method->flat_rate_cost_calculation_type == "each_product" ? "selected" : ""; ?>><?= trans("charge_shipping_for_each_product"); ?></option>
                                          <option value="each_different_product" <?= $method->flat_rate_cost_calculation_type == "each_different_product" ? "selected" : ""; ?>><?= trans("charge_shipping_for_each_different_product"); ?></option>
                                          <option value="cart_total" <?= $method->flat_rate_cost_calculation_type == "cart_total" ? "selected" : ""; ?>><?= trans("fixed_shipping_cost_for_cart_total"); ?></option>
                                      </select>
                                  </div>

                                    <?php if (empty($shipping_classes)): ?>
                                        <div class="form-group">
                                            <label><?= trans("shipping_class_costs"); ?></label>
                                            <?php foreach ($shipping_classes as $shipping_class): ?>
                                                <div class="input-group m-b-5">
                                                    <span class="input-group-addon"><?= $this->default_currency->symbol; ?></span>
                                                    <input type="text" name="flat_rate_cost_class_<?= $shipping_class->id; ?>" class="form-control form-input price-input" placeholder="<?= @parse_serialized_name_array($shipping_class->name_array, $this->selected_lang->id); ?>" maxlength="19">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($shipping_classes)): ?>
                                        <div class="form-group">
                                            <label><?= trans("shipping_class_costs"); ?></label>
                                            <?php foreach ($shipping_classes as $shipping_class):
                                                $class_cost = get_shipping_class_cost_by_method($method->flat_rate_class_costs_array, $shipping_class->id);
                                                if (!empty($class_cost)):
                                                    $class_cost = get_price($class_cost, "input");
                                                endif; ?>
                                                <div class="input-group m-b-5">
                                                    <span class="shipping-label"><?= @parse_serialized_name_array($shipping_class->name_array, $this->selected_lang->id); ?>,  <?= $this->default_currency->symbol; ?></span>
                                                    <input type="text" name="flat_rate_cost_class_<?= $shipping_class->id; ?>" class="form-control form-input price-input" value="<?= $class_cost; ?>" style="width: 30%;"
                                                           placeholder="0.00" maxlength="19" id="flat_rate_cost_class_<?= $shipping_class->id; ?>">


                                                  <select name="time_<?= $shipping_class->id; ?>" class="form-control form-input" style="width: 30%; margin-left: 10px;">
                                                    <option></option>
                                                    <?php foreach ($shipping_default_delivery_times as $k => $delivery_time): ?>
                                                      <option value=<?=$k?>><?= @parse_serialized_option_array($delivery_time, $this->selected_lang->id); ?></option>
                                                    <?php endforeach; ?>
                                                  </select>


                                                <div class="col-md-1 col-sm-3 m-t-10">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="status_<?= $shipping_class->id; ?>" value="1" id="status_<?= $shipping_class->id; ?>_1" class="custom-control-input" checked>
                                                        <label for="status_<?= $shipping_class->id; ?>_1" class="custom-control-label"><?= trans("enable"); ?></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-sm-3 m-t-10">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="status_<?= $shipping_class->id; ?>" value="0" id="status_<?= $shipping_class->id; ?>_2" class="custom-control-input">
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
