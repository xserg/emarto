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
                                                    <input type="text" name="flat_rate_cost_class_<?= $shipping_class->id; ?>" class="form-control form-input price-input" value="<?= $class_cost; ?>"
                                                           placeholder="0.00" maxlength="19" id="flat_rate_cost_class_<?= $shipping_class->id; ?>">
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
