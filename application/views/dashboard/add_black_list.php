<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <div class="row">
        <div class="col-sm-10">
            <div class="box">
                <div class="box-header with-border">
                    <div class="left">
                        <h3 class="box-title"><?= trans('add_ban'); ?></h3>
                    </div>
                </div>
                <div class="box-body">
                    <?php $this->load->view('dashboard/includes/_messages'); ?>
                    <?php echo form_open("add-black-list-post"); ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans("username"); ?></label>    
                            <input type="text" name="username" class="form-control form-input m-b-5" placeholder="<?php echo trans("username"); ?>" maxlength="60" autocomplete="off" required>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" name="submit" value="update" class="btn btn-md btn-success"><?php echo trans("submit") ?></button>
                    </div>
                    <input type="hidden" name="sys_lang_id" value="<?= $this->selected_lang->id; ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
