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

                                    <div class="form-group">
                                      <div>
                                        <div class="col-md-5 col-sm-4"><label><?= trans("shipping_class_costs"); ?></label></div>
                                        <div class="col-md-7 col-sm-8"><label><?= trans("shipping_time"); ?></label></div>
                                      </div>
                                        <?php
                                        $class_data = get_shipping_class_data($method->flat_rate_class_costs_array);
                                        foreach ($shipping_classes as $shipping_class):
                                            $class_cost = get_shipping_class_cost_by_method($method->flat_rate_class_costs_array, $shipping_class->id);
                                            if (!empty($class_cost)):
                                                $class_cost = get_price($class_cost, "input");
                                            endif; ?>

                                            <div class="input-group m-b-5">

                                              <div class="col-md-2 col-sm-2" style="padding-right: 0;">
                                                <span class="shipping-label"><?= @parse_serialized_name_array($shipping_class->name_array, $this->selected_lang->id); ?>,  <?= $this->default_currency->symbol; ?></span>
                                              </div>

                                              <div class="col-md-3 col-sm-3" style="padding-left: 0;">
                                                <input type="text" name="flat_rate_cost_class_<?= $shipping_class->id; ?>" class="form-control form-input price-input" value="<?= $class_cost; ?>"
                                                       placeholder="0.00" maxlength="19" id="flat_rate_cost_class_<?= $shipping_class->id; ?>">
                                              </div>

                                              <div class="col-md-2 col-sm-2">
                                                 <select name="time_<?= $shipping_class->id; ?>" class="form-control form-input">
                                                   <option></option>
                                                   <?php foreach ($shipping_delivery_time_ranges[$shipping_class->id] as $delivery_time): ?>
                                                     <option value=<?=$delivery_time?> <?php if($class_data[$shipping_class->id]['time'] == $delivery_time) echo ' selected'; ?>><?= $delivery_time . ' ' . trans('business2') . ' ' . trans('days2') ?></option>
                                                   <?php endforeach; ?>
                                                 </select>
                                              </div>

                                              <div class="col-md-4 col-sm-3">
                                                  <div class="row m-t-10">
                                                       <div class="col-md-4 custom-control custom-radio">
                                                           <input type="radio" name="status_<?= $shipping_class->id; ?>" value="1" id="status_<?= $shipping_class->id; ?>_1" class="custom-control-input"  checked>
                                                           <label for="status_<?= $shipping_class->id; ?>_1" class="custom-control-label"><?= trans("enable"); ?></label>
                                                       </div>

                                                       <div class="col-md-4 custom-control custom-radio">
                                                           <input type="radio" name="status_<?= $shipping_class->id; ?>" value="0" id="status_<?= $shipping_class->id; ?>_2" class="custom-control-input">
                                                           <label for="status_<?= $shipping_class->id; ?>_2" class="custom-control-label"><?= trans("disable"); ?></label>
                                                       </div>
                                                      <div class="col-md-4 custom-control custom-radio">
                                                          <input type="radio" name="status_<?= $shipping_class->id; ?>" value="2" id="status_<?= $shipping_class->id; ?>_3" class="custom-control-input">
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
                    </div>
                </div>
        </div>
    </div>
