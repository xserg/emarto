<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-sm-10">
        <div class="box">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans('edit_shipping_zone'); ?></h3>
                </div>
                <div class="right">
                    <a href="<?php echo generate_dash_url("shipping_settings"); ?>" class="btn btn-success btn-add-new">
                        <i class="fa fa-list-ul"></i>&nbsp;&nbsp;<?= trans('shipping_zones'); ?>
                    </a>
                </div>
            </div>

            <div class="box-body">
                <?php $this->load->view('dashboard/includes/_messages'); ?>

                <?php echo form_open("edit-shipping-zone-post"); ?>
                <input type="hidden" name="zone_id" value="<?= $shipping_zone->id; ?>">
                <input type="hidden" name="sys_lang_id" value="<?= $this->selected_lang->id; ?>">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("zone_name"); ?>: </label>

                    <?php foreach ($this->languages as $language):
                      if ($language->id == $this->selected_lang->id) { echo @parse_serialized_name_array($shipping_zone->name_array, $language->id); }?>
                        <!--input type="text" name="zone_name_lang_<?= $language->id; ?>" class="form-control form-input m-b-5" value="<?= @parse_serialized_name_array($shipping_zone->name_array, $language->id); ?>" placeholder="<?= $language->name; ?>" maxlength="255" required-->
                    <?php endforeach; ?>
                </div>

                <?php $methods = get_shipping_payment_methods_by_zone($shipping_zone->id);
                if (!empty($methods)):
                    foreach ($methods as $method):
                        $this->load->view('dashboard/shipping/_response_shipping_method_edit2', ['method' => $method]);
                    endforeach;
                endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans("regions"); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="selected_regions_container" class="selected-regions">
                                <?php $locations = get_shipping_locations_by_zone($shipping_zone->id);
                                if (!empty($locations)):
                                    $i = 0;
                                    $array_regions = array();
                                    foreach ($locations as $location):
                                        if (!empty($location->country_name) && !empty($location->state_name)):
                                            array_push($array_regions, "state-" . $location->state_id); ?>
                                            <div class="region"><?= $location->country_name . "/" . $location->state_name; ?><a href="javascript:void(0)" onclick="delete_shipping_location('<?= $location->id; ?>');"><i class="fa fa-times"></i></a><input type="hidden" value="<?= $location->state_id; ?>" name="state[]"></div>
                                        <?php elseif (!empty($location->country_name) && empty($location->state_name)):
                                            array_push($array_regions, "country-" . $location->country_id); ?>
                                            <div class="region"><?= $location->country_name; ?><a href="javascript:void(0)"><i class="fa fa-times" onclick="delete_shipping_location('<?= $location->id; ?>');"></i></a><input type="hidden" value="<?= $location->country_id; ?>" name="country[]"></div>
                                        <?php else:
                                            array_push($array_regions, "continent-" . $location->continent_code); ?>
                                            <div class="region"><?= get_continent_name_by_key($location->continent_code,  $this->selected_lang->id); ?><a href="javascript:void(0)" onclick="delete_shipping_location('<?= $location->id; ?>');"><i class="fa fa-times"></i></a><input type="hidden" value="<?= $location->continent_code; ?>" name="continent[]"></div>
                                        <?php endif;
                                        $i++;
                                    endforeach;
                                endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group m-b-5">
                                <select id="select_continents" class="select2 form-control" data-placeholder="<?= trans("continent"); ?>">
                                    <option></option>
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
                        <div id="btn_select_region_container" class="col-sm-12" style="display: none;">
                            <a href="javascript:void(0)" id="btn_select_region" class="btn btn-sm btn-info"><i class="fa fa-check"></i>&nbsp;<?php echo trans("select_region") ?></a>
                        </div>
                    </div>
                </div>


                <div class="form-group text-right">
                    <button type="submit" name="submit" value="update" class="btn btn-md btn-success"><?php echo trans("save_changes") ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('dashboard/shipping/_js_shipping'); ?>

<script>
  var shipping_method_requiired = "<?= trans("shipping_method_requiired"); ?>";
  var shipping_time_requiired = "<?= trans("shipping_time_requiired"); ?>";
  var shipping_name_requiired = "<?= trans("shipping_name_requiired"); ?>";
  var select_shipping_destinations = "<?= trans("select_shipping_destinations"); ?>";
  
    <?php if (!empty($array_regions)):
    foreach ($array_regions as $array_region):?>
    array_regions.push("<?= $array_region; ?>");
    <?php endforeach;
    endif; ?>
</script>
