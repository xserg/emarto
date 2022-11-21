<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?= html_escape($title); ?></h3>
        </div>
        <div class="right">
            <a href="<?= generate_dash_url("add_black_list"); ?>" class="btn btn-success btn-add-new">
                <i class="fa fa-plus"></i>&nbsp;&nbsp;<?= trans("add_ban"); ?>
            </a>
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
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr role="row">
                            <th scope="col"><?php echo trans("id"); ?></th>
                            <th scope="col"><?php echo trans("username"); ?></th>
                            <th scope="col"><?php echo trans("comment"); ?></th>
                            <th scope="col"><?php echo trans("date"); ?></th>
                            <th class="max-width-120"><?= trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($comments)): ?>
                            <?php foreach ($comments as $comment):
                                $product = get_active_product($comment->product_id); ?>
                                <tr>
                                    <td style="width: 5%;"><?php echo $comment->id; ?></td>
                                    <td style="width: 10%;">
                                        <a href="<?php echo generate_profile_url($comment->user_slug); ?>" class="link-black" target="_blank">
                                            <?php echo html_escape($comment->username); ?>
                                        </a>
                                    </td>
                                    <td style="width: 40%;"><?php echo html_escape($comment->comment); ?></td>
                                    
                                    <td class="white-space-nowrap" style="width: 15%"><?php echo formatted_date($comment->ban_date); ?></td>
                                    <td style="width: 120px;">
                                        <div class="btn-group btn-group-option">
      
                                            <a href="javascript:void(0)" class="btn btn-sm btn-default btn-delete" data-toggle="tooltip" title="<?= trans('delete'); ?>" onclick='delete_item("dashboard_controller/delete_ban_post","<?= $comment->id; ?>","<?= trans("confirm_delete"); ?>");'><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (empty($comments)): ?>
                    <p class="text-center">
                        <?php echo trans("no_records_found"); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?php if (!empty($comments)): ?>
                    <div class="number-of-entries">
                        <span><?= trans("number_of_entries"); ?>:</span>&nbsp;&nbsp;<strong><?= $num_rows; ?></strong>
                    </div>
                <?php endif; ?>
                <div class="table-pagination">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div>

