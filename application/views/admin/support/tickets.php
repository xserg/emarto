<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?= trans('support_tickets'); ?></h3>
        </div>
    </div>

    <div class="box-body">
        <div class="row">
            <!-- include message block -->
            <div class="col-sm-12">
                <?php $this->load->view('admin/includes/_messages'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 m-b-30">
                <a href="<?= admin_url(); ?>support-tickets?status=1" class="btn bnt-support-status<?= $status == 1 ? ' btn-success' : ' btn-outline-success'; ?> m-r-5 font-600">(<?= $num_rows_open; ?>)&nbsp;<?= trans("open"); ?></a>
                <a href="<?= admin_url(); ?>support-tickets?status=2" class="btn bnt-support-status<?= $status == 2 ? ' btn-warning' : ' btn-outline-warning'; ?> m-r-5 font-600">(<?= $num_rows_responded; ?>)&nbsp;<?= trans("responded"); ?></a>
                <a href="<?= admin_url(); ?>support-tickets?status=3" class="btn bnt-support-status<?= $status == 3 ? ' btn-secondary' : ' btn-outline-secondary'; ?> font-600">(<?= $num_rows_closed; ?>)&nbsp;<?= trans("closed"); ?></a>
            </div>

            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped cs_datatable_lang" role="grid">
                        <thead>
                        <tr role="row">
                            <th width="20"><?= trans("id") ?></th>
                            <th><?= trans("subject") ?></th>
                            <th><?= trans("user") ?></th>
                            <th><?= trans("status") ?></th>
                            <th><?= trans("date") ?></th>
                            <th><?= trans("updated") ?></th>
                            <th class="th-options"><?= trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php if (!empty($tickets)):
                            foreach ($tickets as $ticket): ?>
                                <tr>
                                    <td>#<?= $ticket->id; ?></td>
                                    <td style="max-width: 400px;"><?= html_escape($ticket->subject); ?></td>
                                    <td>
                                        <?php $user = get_user($ticket->user_id);
                                        if (!empty($user)): ?>
                                            <a href="<?php echo generate_profile_url($user->slug); ?>" target="_blank" class="table-username">
                                                <?php echo html_escape($user->username); ?>
                                            </a>
                                        <?php else:
                                            echo trans("guest");
                                        endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($ticket->status == 1): ?>
                                            <label class="label label-success"><?= trans("open"); ?></label>
                                        <?php elseif ($ticket->status == 2): ?>
                                            <label class="label label-warning"><?= trans("responded"); ?></label>
                                        <?php elseif ($ticket->status == 3): ?>
                                            <label class="label label-default"><?= trans("closed"); ?></label>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= formatted_date($ticket->created_at); ?></td>
                                    <td><?= time_ago($ticket->updated_at); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-option">
                                            <a href="<?= admin_url(); ?>support-ticket/<?= $ticket->id; ?>" class="btn btn-sm btn-default btn-edit"><?php echo trans("show"); ?></a>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-default btn-delete" onclick="delete_item('membership_controller/delete_transaction_post','<?php echo $ticket->id; ?>','<?php echo trans("confirm_delete"); ?>');"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-12 text-right">
                <?php echo $this->pagination->create_links(); ?>
            </div>
        </div>
    </div><!-- /.box-body -->
</div>

<style>
    .bnt-support-status {
        padding: 8px 50px;
        -webkit-transition: all 0.2s ease-in-out;
        -moz-transition: all 0.2s ease-in-out;
        -ms-transition: all 0.2s ease-in-out;
        -o-transition: all 0.2s ease-in-out;
        transition: all 0.2s ease-in-out;
        margin-bottom: 5px;
    }
</style>
