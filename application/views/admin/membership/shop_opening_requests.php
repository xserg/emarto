<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans("shop_opening_requests"); ?></h3>
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
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr role="row">
                            <th width="20"><?php echo trans("id"); ?></th>
                            <th><?php echo trans("user"); ?></th>
                            <th><?php echo trans("shop_description"); ?></th>
                            <th><?php echo trans("required_files"); ?></th>
                            <th><?= trans("membership_plan"); ?></th>
                            <th><?= trans("payment"); ?></th>
                            <th class="max-width-120"><?php echo trans("options"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user):
                            $membership_plan = $this->membership_model->get_user_plan_by_user_id($user->id, false); ?>
                            <tr>
                                <td><?php echo html_escape($user->id); ?></td>
                                <td>
                                    <a href="<?php echo generate_profile_url($user->slug); ?>" target="_blank" class="table-link">
                                        <img src="<?php echo get_user_avatar($user); ?>" alt="user" class="img-responsive" style="width: 50px;">
                                    </a>
                                    <p class="m-b-5 m-t-10"><?= trans("username") ?>:&nbsp;<strong><?php echo html_escape($user->username); ?></strong></p>
                                    <p class="m-b-5"><?= trans("shop_name") ?>:&nbsp;<strong><?php echo html_escape($user->shop_name); ?></strong></p>
                                    <p class="m-b-5"><?= trans("email") ?>:&nbsp;<strong><?php echo html_escape($user->email); ?></strong></p>
                                    <p class="m-b-5"><?= trans("phone") ?>:&nbsp;<strong><?php echo html_escape($user->phone_number); ?></strong></p>
                                    <p class="m-b-5"><?= trans("location") ?>:&nbsp;<strong><?php echo get_location($user); ?></strong></p>
                                </td>
                                <td style="min-width: 300px !important;"><?php echo html_escape($user->about_me); ?></td>
                                <td>
                                    <?php $files = unserialize_data($user->vendor_documents);
                                    if (!empty($files)):?>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="ticket-attachments">
                                                    <?php foreach ($files as $file):
                                                        echo form_open('support_controller/download_attachment'); ?>
                                                        <input type="hidden" name="name" value="<?= $file['name']; ?>">
                                                        <input type="hidden" name="path" value="<?= $file['path']; ?>">
                                                        <p class="font-600 text-info">
                                                            <button type="submit" class="button-link"><i class="fa fa-file"></i>&nbsp;&nbsp;<span><?= html_escape($file['name']); ?></span></button>
                                                        </p>
                                                        <?php echo form_close();
                                                    endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= !empty($membership_plan) ? $membership_plan->plan_title : ''; ?></td>
                                <td><?php if (!empty($membership_plan)):
                                        echo get_payment_method($membership_plan->payment_method) . "<br>";
                                        if ($membership_plan->payment_status == "awaiting_payment"):?>
                                            <label class="label label-danger"><?= trans("awaiting_payment"); ?></label>
                                        <?php elseif ($membership_plan->payment_status == "payment_received"): ?>
                                            <label class="label label-success"><?= trans("payment_received"); ?></label>
                                        <?php endif;
                                    endif; ?>
                                </td>
                                <td>
                                    <?php echo form_open('membership_controller/approve_shop_opening_request'); ?>
                                    <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                                    <div class="dropdown">
                                        <button class="btn bg-purple dropdown-toggle btn-select-option" type="button" data-toggle="dropdown"><?php echo trans('select_option'); ?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu options-dropdown">
                                            <li>
                                                <button type="submit" name="submit" value="1" class="btn-list-button">
                                                    <i class="fa fa-check option-icon"></i><?php echo trans('approve'); ?>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="submit" name="submit" value="0" class="btn-list-button">
                                                    <i class="fa fa-times option-icon"></i><?php echo trans('decline'); ?>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php echo form_close(); ?>
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

<?php if (!empty($this->session->userdata('mds_send_email_data'))): ?>
    <script>
        $(document).ready(function () {
            var data = JSON.parse(<?php echo json_encode($this->session->userdata("mds_send_email_data"));?>);
            if (data) {
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                    type: "POST",
                    url: base_url + "ajax_controller/send_email",
                    data: data,
                    success: function (response) {
                    }
                });
            }
        });
    </script>
<?php endif;
$this->session->unset_userdata('mds_send_email_data'); ?>
