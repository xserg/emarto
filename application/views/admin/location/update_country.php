<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("update_country"); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('admin_controller/update_country_post'); ?>
            <input type="hidden" name="id" value="<?php echo $country->id; ?>">
            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>

                <div class="form-group">
                    <label><?php echo trans("name"); ?></label>
                    <input type="text" class="form-control" name="name" placeholder="<?php echo trans("name"); ?>"
                           value="<?php echo $country->name; ?>" maxlength="200" required>
                </div>
                <div class="form-group">
                    <label><?php echo trans("name"); ?></label>
                    <input type="text" class="form-control" name="name_rus" placeholder="<?php echo trans("name"); ?>"
                           value="<?php echo $country->name_rus; ?>" maxlength="200" required>
                </div>
                <div class="form-group">
                    <label><?php echo trans("continent"); ?></label>
                    <select name="continent_code" class="form-control">
                        <?php $continents = get_continents();
                        if (!empty($continents)):
                            foreach ($continents as $key => $value):?>
                                <option value="<?= $key; ?>" <?= $key == $country->continent_code ? 'selected' : ''; ?>><?= $value; ?></option>
                            <?php endforeach;
                        endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans('status'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="status" value="1" id="status_1" class="square-purple" <?php echo ($country->status == 1) ? 'checked' : ''; ?>>
                            <label for="status_1" class="option-label"><?php echo trans('active'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="status" value="0" id="status_2" class="square-purple" <?php echo ($country->status != 1) ? 'checked' : ''; ?>>
                            <label for="status_2" class="option-label"><?php echo trans('inactive'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans('seller'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="seller" value="1" id="status_1" class="square-purple" <?php echo ($country->seller == 1) ? 'checked' : ''; ?>>
                            <label for="status_1" class="option-label"><?php echo trans('active'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="seller" value="0" id="status_2" class="square-purple" <?php echo ($country->seller != 1) ? 'checked' : ''; ?>>
                            <label for="status_2" class="option-label"><?php echo trans('inactive'); ?></label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('update_country'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>
