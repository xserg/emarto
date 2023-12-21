<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($option_unique_id) && !empty($selected_option)): ?>
    <div id="row_shipping_method_<?= $option_unique_id; ?>" class="row">
        <div class="col-sm-12">
            <input type="hidden" name="option_unique_id[]" value="<?= $option_unique_id; ?>">
            <input type="hidden" name="method_type_<?= $option_unique_id; ?>" value="<?= $selected_option; ?>">
            <input type="hidden" name="method_operation_<?= $option_unique_id; ?>" value="add">
                <div class="response-shipping-method">

                    <div id="modalMethod<?= $option_unique_id; ?>">
                        <div >
                            <div class="modal-content">
                              <div class="modal-header">
                                    <h4 class="modal-title"><?= trans('free_shipping'); ?></h4>
                              </div>
                              <div class="modal-body">
                                  <div class="form-group">
                                      <div class="row">
                                          <div class="col-sm-12 col-xs-12">
                                              <label><?= trans("status"); ?></label>
                                          </div>
                                          <div class="col-md-6 col-sm-12 col-custom-option">
                                              <div class="custom-control custom-radio">
                                                  <input type="radio" name="status" value="1" id="status_1" class="custom-control-input" <?= $method->status == 1 ? "checked" : ""; ?>>
                                                  <label for="status_1" class="custom-control-label"><?= trans("enable"); ?></label>
                                              </div>
                                          </div>
                                          <div class="col-md-6 col-sm-12 col-custom-option">
                                              <div class="custom-control custom-radio">
                                                  <input type="radio" name="status" value="0" id="status_2" class="custom-control-input" <?= $method->status != 1 ? "checked" : ""; ?>>
                                                  <label for="status_2" class="custom-control-label"><?= trans("disable"); ?></label>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                                    <h4 class="modal-title"><?= trans($selected_option); ?></h4>
                                </div>
                                <div class="modal-body">

                                    <?php if (empty($shipping_classes)): ?>
                                        <div class="form-group">
                                            <label><?= trans("shipping_class_costs"); ?></label>
                                            <?php foreach ($shipping_classes as $shipping_class): ?>
                                                <div class="input-group m-b-5">
                                                    <span class="input-group-addon"><?= $this->default_currency->symbol; ?></span>
                                                    <input type="text" name="flat_rate_cost_<?= $option_unique_id; ?>_class_<?= $shipping_class->id; ?>" class="form-control form-input price-input" placeholder="<?= @parse_serialized_name_array($shipping_class->name_array, $this->selected_lang->id); ?>" maxlength="19">
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
                                                    <input type="text" name="flat_rate_cost_<?= $option_unique_id; ?>_class_<?= $shipping_class->id; ?>" class="form-control form-input price-input" value="<?= $class_cost; ?>"
                                                           placeholder="<?= @parse_serialized_name_array($shipping_class->name_array, $this->selected_lang->id); ?>" maxlength="19" id="flat_rate_cost_class_<?= $shipping_class->id; ?>">
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
