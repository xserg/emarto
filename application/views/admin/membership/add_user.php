<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("add_user"); ?></h3>
            </div>
            <?php echo form_open('membership_controller/add_user_post'); ?>
            <div class="box-body">
                <?php $this->load->view('admin/includes/_messages'); ?>
                <div class="form-group">
                    <label class="control-label"><?php echo trans("username"); ?></label>
                    <input type="text" name="username" class="form-control auth-form-input" placeholder="<?php echo trans("username"); ?>" value="<?php echo old("username"); ?>" required>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans("first_name"); ?></label>
                    <input type="text" name="first_name" class="form-control auth-form-input" placeholder="<?php echo trans("first_name"); ?>" value="<?php echo old("first_name"); ?>" required>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans("last_name"); ?></label>
                    <input type="text" name="last_name" class="form-control auth-form-input" placeholder="<?php echo trans("last_name"); ?>" value="<?php echo old("last_name"); ?>" required>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans("email_address"); ?></label>
                    <input type="email" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("email_address"); ?>" value="<?php echo old("email"); ?>" required>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans("password"); ?></label>
                    <input type="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" value="<?php echo old("password"); ?>" required>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans("role"); ?></label>
                    <select name="role_id" class="form-control" required>
                        <option value=""><?= trans("select"); ?></option>
                        <?php if (!empty($roles)):
                            foreach ($roles as $item):
                                $role_name = @parse_serialized_name_array($item->role_name, $this->selected_lang->id, true); ?>
                                <option value="<?= $item->id; ?>"><?= html_escape($role_name); ?></option>
                            <?php endforeach;
                        endif; ?>
                    </select>
                </div>

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_user'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
