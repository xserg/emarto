<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <div class="row">
        <div class="col-sm-10">
            <div class="box">
                <div class="box-header with-border">
                    <div class="left">
                        <h3 class="box-title"><?= trans('add_shipping_zone'); ?></h3>
                    </div>
                    <div class="right">
                        <a href="<?php echo generate_dash_url("shipping_settings"); ?>" class="btn btn-success btn-add-new">
                            <i class="fa fa-list-ul"></i>&nbsp;&nbsp;<?= trans('shipping_zones'); ?>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <?php $this->load->view('dashboard/includes/_messages'); ?>

                    <?php echo form_open("add-shipping-zone-post", "id=add-shipping-zone"); ?>
                    <input type="hidden" name="sys_lang_id" value="<?= $this->selected_lang->id; ?>">
                    <div class="form-group">
                      <label class="control-label"><?php echo trans("zone_name"); ?></label>
                      <select id="zone_name" class="form-control" name="zone_name" data-placeholder="<?= trans("zone_name"); ?>">
                          <option value="">&nbsp;</option>
                          <option value="domestic"><?php echo trans("domestic"); ?></option>
                          <option value="international"><?php echo trans("international"); ?></option>
                      </select>
                      <br><br><a href="#custom_name"  id="#btn_custom_name" class="btn btn-sm btn-info" data-toggle="collapse" ><?= trans("select_zone_name", true); ?></a>

                    </div>
                    <div class="form-group collapse" id="custom_name" >
                            <input type="text" name="zone_name_lang_<?= $this->selected_lang->id; ?>" class="form-control form-input m-b-5" placeholder="<?= trans("zone_name"); ?>" maxlength="255">
                    </div>

                    <?php
                    $vars = array('selected_option' => 'flat_rate', 'option_unique_id' => uniqid(), 'shipping_classes' => $shipping_classes);
                    $html_content = $this->load->view("dashboard/shipping/_response_shipping_method2", $vars, true);

                    echo $html_content;
                    ?>

                    <div class="form-group">
                        <label class="control-label"><?php echo trans("regions"); ?></label>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="selected_regions_container" class="selected-regions"></div>
                            </div>
                            <div class="col-sm-12">
                                <div id="form_group_continents" class="form-group m-b-5" style="display: block;">

                                    <select id="select_continents" class="select2 form-control" data-placeholder="<?= trans("continent"); ?>">

                                        <?php if (!empty($continents)):
                                            foreach ($continents as $key => $continent):?>
                                                <option value="<?= $key; ?>"><?= $continent; ?></option>
                                            <?php endforeach;
                                        endif; ?>
                                    </select>
                                </div>
                                <div id="form_group_countries" class="form-group m-b-5" style="display: none;">
                                    <select id="select_countries" class="select2 form-control" data-placeholder="<?= trans("country"); ?>">
                                        <option></option>
                                    </select>
                                </div>
                                <div id="form_group_states" class="form-group m-b-5" style="display: none;">
                                    <select id="select_states" class="select2 form-control" data-placeholder="<?= trans("state"); ?>">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div id="btn_select_region_container" class="col-sm-12" style="display: block;">
                                <a href="javascript:void(0)" id="btn_select_region" class="btn btn-sm btn-info"><i class="fa fa-check"></i>&nbsp;<?php echo trans("select_region") ?></a>
                            </div>
                        </div>
                    </div>

                    <!--div class="form-group">
                        <label class="control-label m-b-10"><?php echo trans("shipping_methods"); ?></label>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="selected_shipping_methods"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="javascript:void(0)" id="btn_add_shipping_method" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalShippingMethod"><i class="fa fa-plus"></i>&nbsp;<?php echo trans("add_shipping_method") ?></a>
                            </div>
                        </div>
                    </div-->

                    <div class="form-group text-right">
                      <a href="<?php echo generate_dash_url("shipping_settings"); ?>" class="btn btn-success">
                        <?= trans('cancel'); ?>
                      </a>
                        <button id="zone_submit" type="submit" name="submit" value="update" class="btn btn-md btn-success"><?php echo trans("submit") ?></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <div id="modalShippingMethod" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                    <h4 class="modal-title"><?= trans("shipping_methods"); ?></h4>
                </div>
                <div class="modal-body">
                    <select id="select_shipping_methods" class="form-control custom-select">
                        <?php $options = get_shipping_methods();
                        if (!empty($options)):
                            foreach ($options as $option):?>
                                <option value="<?= $option; ?>"><?= trans($option); ?></option>
                            <?php endforeach;
                        endif; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_select_shipping_method" class="btn btn-success" data-dismiss="modal"><?= trans("add_shipping_method"); ?></button>
                </div>
            </div>
        </div>
    </div>
<script>
  var default_country = <?= $default_country ?>;
  var shipping_method_requiired = "<?= trans("shipping_method_requiired"); ?>";
  var shipping_time_requiired = "<?= trans("shipping_time_requiired"); ?>";
  var shipping_name_requiired = "<?= trans("shipping_name_requiired"); ?>";
  var select_shipping_destinations = "<?= trans("select_shipping_destinations"); ?>";
  var shipping_not_null = "<?= trans("select_shipping_destinations"); ?>";
  
</script>
<?php $this->load->view('dashboard/shipping/_js_shipping'); ?>
