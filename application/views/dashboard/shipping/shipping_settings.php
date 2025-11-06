<?php defined('BASEPATH') or exit('No direct script access allowed'); 
                if ($this->auth_user->currency) {
                    $this->default_currency = get_currency_by_code($this->auth_user->currency);
                } 
?>                
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans("shipping_zones"); ?></h3>
                </div>
                <div class="right">
                    <?php if ($_GET['product_id']) { ?>
                    <a href="<?= generate_dash_url("product/product-details/").$_GET['product_id'] ?>" class="btn btn-gray" style="background-color: #e6e6e6; color: black;">
                        <?= trans("back_to_listing"); ?>
                    </a>
                    <?php } ?>
                    <a href="<?= generate_dash_url("add_shipping_zone"); ?>?product_id=<?= $_GET['product_id'] ?>" class="btn btn-success btn-add-new">
                        <i class="fa fa-plus"></i>&nbsp;&nbsp;<?= trans("add_shipping_zone"); ?>
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <!-- include message block -->
                    <div class="col-sm-12">
                        <?php if (!empty($this->session->flashdata('msg_shipping_zone'))):
                            $this->load->view('admin/includes/_messages');
                        endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped dataTable" role="grid">
                                <thead>
                                <tr role="row">
                                    <th scope="col"><?= trans("zone_name"); ?></th>
                                    <th scope="col"><?= trans("regions"); ?></th>
                                    <th scope="col"><?= trans("shipping_methods"); ?></th>
                                    <th scope="col"><?= trans("options"); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($shipping_zones)): ?>
                                    <?php foreach ($shipping_zones as $shipping_zone): ?>
                                        <tr>
                                            <td><?= @parse_serialized_name_array($shipping_zone->name_array, $this->selected_lang->id); ?></td>
                                            <td>
                                                <?php $locations = get_shipping_locations_by_zone($shipping_zone->id);
                                                if (!empty($locations)):
                                                    $i = 0;
                                                    foreach ($locations as $location):
                                                        if (!empty($location->country_name) && !empty($location->state_name)):?>
                                                            <span class="pull-left"><?= $i != 0 ? ", " : ''; ?><?= $location->country_name . "/" . $location->state_name; ?></span>
                                                        <?php
                                                        elseif (!empty($location->country_name) && empty($location->state_name)):?>
                                                            <span class="pull-left"><?= $i != 0 ? ", " : ''; ?><?= $location->country_name; ?></span>
                                                        <?php else: ?>
                                                            <span class="pull-left"><?= $i != 0 ? ", " : ''; ?><?= get_continent_name_by_key($location->continent_code, $this->selected_lang->id); ?></span>
                                                        <?php endif;
                                                        $i++;
                                                    endforeach;
                                                endif; ?>
                                            </td>
                                            <td>
                                                <?php $methods = get_shipping_payment_methods_by_zone($shipping_zone->id, 1);
                                                $i = 0;
                                                if (!empty($methods)):
                                                    foreach ($methods as $method): ?>
                                                        <span class="pull-left">
                                                          <?= @parse_serialized_name_array($method->name_array, $this->selected_lang->id)
                                                          . ' (' . $method->time . ' ' . trans('business2') . ' ' . trans('days3') . ')&nbsp;'
                                                          . $this->default_currency->symbol . ' '
                                                          . number_format(get_price($method->cost, "input"), 2, ".", "")
                                                          . ($method->status == 2 ? ' ' . trans('free_shipping') : '');
                                                          ?>

                                                        </span><br>
                                                        <?php $i++;
                                                    endforeach;
                                                endif; ?>
                                            </td>
                                            <td style="width: 120px;">
                                                <div class="btn-group btn-group-option">
                                                    <a href="<?= generate_dash_url("edit_shipping_zone"); ?>/<?= $shipping_zone->id; ?>" class="btn btn-sm btn-default btn-edit" data-toggle="tooltip" title="<?= trans('edit'); ?>"><i class="fa fa-edit"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-default btn-delete" data-toggle="tooltip" title="<?= trans('delete'); ?>" onclick="delete_item('dashboard_controller/delete_shipping_zone_post','<?= $shipping_zone->id; ?>','<?= trans("confirm_delete"); ?>');"><i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>


<div id="modalAddShippingClass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                <h4 class="modal-title"><?= trans("add_shipping_class"); ?></h4>
            </div>
            <?php echo form_open("add-shipping-class-post"); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("name"); ?></label>
                    <?php foreach ($this->languages as $language): ?>
                        <input type="text" name="name_lang_<?= $language->id; ?>" class="form-control form-input m-b-5" placeholder="<?= $language->name; ?>" maxlength="255" required>
                    <?php endforeach; ?>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?= trans("status"); ?></label>
                        </div>
                        <div class="col-md-6 col-sm-12 col-custom-option">
                            <div class="custom-control custom-radio">
                                <input type="radio" name="status" value="1" id="status_1" class="custom-control-input" checked>
                                <label for="status_1" class="custom-control-label"><?= trans("enable"); ?></label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-custom-option">
                            <div class="custom-control custom-radio">
                                <input type="radio" name="status" value="0" id="status_2" class="custom-control-input">
                                <label for="status_2" class="custom-control-label"><?= trans("disable"); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><?= trans("submit"); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div id="modalAddDeliveryTime" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                <h4 class="modal-title"><?= trans("add_delivery_time"); ?></h4>
            </div>
            <?php echo form_open("add-shipping-delivery-time-post"); ?>
            <div class="modal-body">
              <div class="form-group">
                <label class="control-label"><?php echo trans("name"); ?></label>
                <select name="default_option" class="form-control form-input m-b-5" required>
                  <option></option>
                  <?php foreach ($shipping_default_delivery_times as $k => $delivery_time): ?>
                    <option value=<?=$k?>><?= @parse_serialized_option_array($delivery_time, $this->selected_lang->id); ?></option>
                  <?php endforeach; ?>
                </select>
                <!--br><br><a href="#custom_name"  id="#btn_custom_name" class="btn btn-sm btn-info" data-toggle="collapse" data-target="#custom_name"><?= trans("select_zone_name", true); ?></a-->
              </div>
                <div class="form-group collapse" id="custom_name" >
                    <label class="control-label"><?php echo trans("option"); ?></label>
                    <?php foreach ($this->languages as $language): ?>
                        <input type="text" name="option_lang_<?= $language->id; ?>" class="form-control form-input m-b-5" placeholder="<?= $language->name; ?>" maxlength="255" required-->
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><?= trans("submit"); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<style>
    .table-delivery-times .dataTables_length, .table-delivery-times .dataTables_filter {
        display: none;
    }
</style>
