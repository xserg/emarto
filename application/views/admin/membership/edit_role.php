<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-sm-10">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?php echo trans("edit_role"); ?></h3>
                </div>
                <div class="right">
                    <a href="<?php echo admin_url(); ?>roles-permissions" class="btn btn-success btn-add-new">
                        <i class="fa fa-list-ul"></i>&nbsp;&nbsp;<?php echo trans("roles"); ?>
                    </a>
                </div>
            </div>
            <?php echo form_open('membership_controller/edit_role_post'); ?>
            <div class="box-body">
                <?php $this->load->view('admin/includes/_messages'); ?>
                <input type="hidden" name="id" value="<?= $role->id ?>">
                <?php foreach ($this->languages as $language):
                    $role_name = parse_serialized_name_array($role->role_name, $language->id, false); ?>
                    <div class="form-group">
                        <label><?php echo trans("role_name"); ?> (<?php echo $language->name; ?>)</label>
                        <input type="text" class="form-control" name="role_name_<?php echo $language->id; ?>" value="<?= html_escape($role_name); ?>" placeholder="<?php echo trans("role_name"); ?>" maxlength="255" required>
                    </div>
                <?php endforeach; ?>

                <?php if ($role->is_default != 1): ?>
                    <div class="form-group">
                        <label class="m-b-15"><?php echo trans("permissions"); ?></label>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <?php $permissions = get_permissions_array();
                                if (!empty($permissions)):
                                    $i = 0;
                                    $role_permissions = explode(',', $role->permissions);
                                    foreach ($permissions as $key => $value):
                                        if ($i <= 17):?>
                                            <div class="m-b-15">
                                                <?php if (is_array($role_permissions) && in_array($key, $role_permissions)): ?>
                                                    <input type="checkbox" name="permissions[]" value="<?= $key; ?>" id="per_<?= $key; ?>" class="square-purple" checked>&nbsp;&nbsp;&nbsp;
                                                <?php else: ?>
                                                    <input type="checkbox" name="permissions[]" value="<?= $key; ?>" id="per_<?= $key; ?>" class="square-purple">&nbsp;&nbsp;&nbsp;
                                                <?php endif; ?>
                                                <label for="per_<?= $key; ?>" class="control-label cursor-pointer"><?= trans($value); ?></label>
                                            </div>
                                        <?php endif;
                                        $i++;
                                    endforeach;
                                endif; ?>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <?php if (!empty($permissions)):
                                    $i = 0;
                                    $role_permissions = explode(',', $role->permissions);
                                    foreach ($permissions as $key => $value):
                                        if ($i > 17):?>
                                            <div class="m-b-15">
                                                <?php if (is_array($role_permissions) && in_array($key, $role_permissions)): ?>
                                                    <input type="checkbox" name="permissions[]" value="<?= $key; ?>" id="per_<?= $key; ?>" class="square-purple" checked>&nbsp;&nbsp;&nbsp;
                                                <?php else: ?>
                                                    <input type="checkbox" name="permissions[]" value="<?= $key; ?>" id="per_<?= $key; ?>" class="square-purple">&nbsp;&nbsp;&nbsp;
                                                <?php endif; ?>
                                                <label for="per_<?= $key; ?>" class="control-label cursor-pointer"><?= trans($value); ?></label>
                                            </div>
                                        <?php endif;
                                        $i++;
                                    endforeach;
                                endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans("save_changes"); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>