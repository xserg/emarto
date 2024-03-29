<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans("members"); ?></h3>
        </div>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php $this->load->view('admin/includes/_messages'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <?php $this->load->view('admin/membership/_filters'); ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr role="row">
                            <th width="20"><?php echo trans("id"); ?></th>
                            <th><?php echo trans("user"); ?></th>
                            <th><?php echo trans("email"); ?></th>
                            <th><?php echo trans("phone"); ?></th>
                            <th><?php echo trans("location"); ?></th>
                            <th><?php echo trans("status"); ?></th>
                            <th><?php echo trans("message");//echo str_replace(":", "", trans("last_seen")); ?></th>
                            <th><?php echo trans("date"); ?></th>
                            <th class="max-width-120"><?php echo trans("options"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo html_escape($user->id); ?></td>
                                <td>
                                    <div class="img-table-user">
                                        <a href="<?php echo generate_profile_url($user->slug); ?>" target="_blank" class="table-link">
                                            <img src="<?php echo get_user_avatar($user); ?>" alt="user" class="img-responsive" style="height: 50px;width: 50px;">
                                        </a>
                                    </div>
                                    <a href="<?php echo generate_profile_url($user->slug); ?>" target="_blank" class="table-link"><?php echo html_escape($user->username); ?></a>
                                </td>
                                <td>
                                    <?php echo html_escape($user->email);
                                    if ($user->email_status == 1): ?>
                                        <small class="text-success">(<?php echo trans("confirmed"); ?>)</small>
                                    <?php else: ?>
                                        <small class="text-danger">(<?php echo trans("unconfirmed"); ?>)</small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo html_escape($user->phone_number);?></td>
                                <td>
                                  <?php 
                                  if ( $user->country_id && $key = array_search($user->country_id, array_column($this->countries, 'id'))) {
                                      echo $this->countries[$key]->name;
                                  }
                                  ?>
                                </td>
                                <td>
                                    <?php if ($user->cancel_status == 0): ?>
                                        <label class="label label-success"><?php echo trans('active'); ?></label>
                                    <?php elseif ($user->cancel_status == 1): ?>
                                        <label class="label label-danger"><?php echo trans('pending'); ?></label>
                                    <?php elseif ($user->cancel_status == 2): ?>
                                        <label class="label label-danger"><?php echo trans('approved'); ?></label>    
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $user->message; //echo time_ago($user->last_seen); ?></td>
                                <td><?php echo formatted_date($user->created_at); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn bg-purple dropdown-toggle btn-select-option" type="button" data-toggle="dropdown"><?php echo trans('select_option'); ?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu options-dropdown">
                                            <li>
                                                <a href="javascript:void(0)" onclick="delete_item('membership_controller/delete_user_post','<?php echo $user->id; ?>','<?php echo trans("confirm_user"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('approve'); ?></a>
                                            </li>
                                            <?php if ($user->status == 0): ?>
                                            <li>      
                                               <a href="javascript:void(0)" onclick="cancel_approve_user(<?php echo $user->cancel_id; ?>, 1);"><i class="fa fa-stop-circle option-icon"></i><?php echo trans('pending'); ?></a>
                                            </li>

                                            <?php endif; ?>
                                            <li>      
                                               <a href="javascript:void(0)" onclick="cancel_approve_user(<?php echo $user->cancel_id; ?>, 3);"><i class="fa fa-stop-circle option-icon"></i><?php echo trans('delete'); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo admin_url(); ?>edit-user/<?php echo $user->id; ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit_user'); ?></a>
                                            </li>
                                          
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (empty($users)): ?>
                        <p class="text-center text-muted"><?= trans("no_records_found"); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-12 text-right">
                <?php echo $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($users)):
    foreach ($users as $user): ?>
        <div id="modalRole<?= $user->id; ?>" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo trans('change_user_role'); ?></h4>
                    </div>
                    <?php echo form_open('membership_controller/change_user_role_post'); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <input type="hidden" name="user_id" value="<?= $user->id; ?>">
                                <?php if (!empty($roles)):
                                    foreach ($roles as $item):
                                        $role_name = @parse_serialized_name_array($item->role_name, $this->selected_lang->id, true); ?>
                                        <div class="col-sm-6 m-b-15">
                                            <input type="radio" name="role_id" value="<?= $item->id; ?>" id="role_<?= $item->id; ?>" class="square-purple" <?= $user->role_id == $item->id ? 'checked' : ''; ?> required>&nbsp;&nbsp;
                                            <label for="role_<?= $item->id; ?>" class="option-label cursor-pointer"><?= html_escape($role_name); ?></label>
                                        </div>
                                    <?php endforeach;
                                endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><?php echo trans('save_changes'); ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('close'); ?></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    <?php endforeach;
endif; ?>

<script>
//cancel account remove user ban
function cancel_approve_user(id, status) {
    var data = {
        'id': id,
        'status': status
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "membership_controller/cancel_approve_user",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
};
</script>