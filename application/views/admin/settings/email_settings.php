<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $this->load->view('admin/includes/_messages'); ?>
<div class="row">
    <div class="col-sm-12 col-lg-6">
        <?php echo form_open('admin_controller/email_settings_post'); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= trans('email_settings'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label"><?= trans('mail_protocol'); ?></label>
                    <select name="mail_protocol" class="form-control" onchange="window.location.href = '<?= admin_url(); ?>email-settings?protocol='+this.value;">
                        <option value="smtp" <?= $protocol == "smtp" ? "selected" : ""; ?>>SMTP</option>
                        <option value="mail" <?= $protocol == "mail" ? "selected" : ""; ?>>Mail</option>
                    </select>
                </div>
                <?php if ($protocol == "smtp"): ?>
                    <div class="form-group">
                        <label class="control-label"><?= trans('mail_library'); ?></label>
                        <select name="mail_library" class="form-control">
                            <option value="swift" <?= $this->general_settings->mail_library == "swift" ? "selected" : ""; ?>>Swift Mailer</option>
                            <option value="php" <?= $this->general_settings->mail_library == "php" ? "selected" : ""; ?>>PHP Mailer</option>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="mail_library" value="php">
                <?php endif; ?>

                <?php if ($protocol == "smtp"): ?>
                    <div class="form-group">
                        <label class="control-label"><?= trans('encryption'); ?></label>
                        <select name="mail_encryption" class="form-control">
                            <option value="tls" <?= $this->general_settings->mail_encryption == "tls" ? "selected" : ""; ?>>TLS</option>
                            <option value="ssl" <?= $this->general_settings->mail_encryption == "ssl" ? "selected" : ""; ?>>SSL</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?= trans('mail_host'); ?></label>
                        <input type="text" class="form-control" name="mail_host" placeholder="<?= trans('mail_host'); ?>" value="<?= html_escape($this->general_settings->mail_host); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?= trans('mail_port'); ?></label>
                        <input type="text" class="form-control" name="mail_port" placeholder="<?= trans('mail_port'); ?>" value="<?= html_escape($this->general_settings->mail_port); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?= trans('mail_username'); ?></label>
                        <input type="text" class="form-control" name="mail_username" placeholder="<?= trans('username'); ?>" value="<?= html_escape($this->general_settings->mail_username); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?= trans('password'); ?></label>
                        <input type="password" class="form-control" name="mail_password" placeholder="<?= trans('password'); ?>" value="<?= html_escape($this->general_settings->mail_password); ?>">
                    </div>
                <?php else: ?>
                    <input type="hidden" name="mail_encryption" value="<?= $this->general_settings->mail_encryption; ?>">
                    <input type="hidden" name="mail_host" value="<?= $this->general_settings->mail_host; ?>">
                    <input type="hidden" name="mail_port" value="<?= $this->general_settings->mail_port; ?>">
                    <input type="hidden" name="mail_username" value="<?= $this->general_settings->mail_username; ?>">
                    <input type="hidden" name="mail_password" value="<?= $this->general_settings->mail_password; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label class="control-label"><?= trans('title'); ?></label>
                    <input type="text" class="form-control" name="mail_title" placeholder="<?= trans('title'); ?>" value="<?= html_escape($this->general_settings->mail_title); ?>">
                </div>

                <div class="form-group">
                    <label class="control-label"><?= trans('reply_to'); ?></label>
                    <input type="email" class="form-control" name="mail_reply_to" placeholder="<?= trans('reply_to'); ?>" value="<?= html_escape($this->general_settings->mail_reply_to); ?>">
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" name="submit" value="email" class="btn btn-primary pull-right"><?= trans('save_changes'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>

    <?php echo form_open('admin_controller/email_verification_post'); ?>
    <div class="col-sm-12 col-lg-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('email_verification'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('email_verification'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="email_verification" value="1" id="email_verification_1" class="square-purple" <?php echo ($this->general_settings->email_verification == '1') ? 'checked' : ''; ?>>
                            <label for="email_verification_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="email_verification" value="0" id="email_verification_2" class="square-purple" <?php echo ($this->general_settings->email_verification == '0') ? 'checked' : ''; ?>>
                            <label for="email_verification_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="verification" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?><!-- form end -->

    <?php echo form_open('admin_controller/email_options_post'); ?>
    <div class="col-sm-12 col-lg-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('email_options'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('email_option_product_added'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_new_product" value="1" id="email_option_product_added_1" class="square-purple" <?php echo ($this->general_settings->send_email_new_product == '1') ? 'checked' : ''; ?>>
                            <label for="email_option_product_added_1" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_new_product" value="0" id="email_option_product_added_2" class="square-purple" <?php echo ($this->general_settings->send_email_new_product == '0') ? 'checked' : ''; ?>>
                            <label for="email_option_product_added_2" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('email_option_send_order_to_buyer'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_buyer_purchase" value="1" id="email_option_send_order_to_buyer_1" class="square-purple" <?php echo ($this->general_settings->send_email_buyer_purchase == '1') ? 'checked' : ''; ?>>
                            <label for="email_option_send_order_to_buyer_1" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_buyer_purchase" value="0" id="email_option_send_order_to_buyer_2" class="square-purple" <?php echo ($this->general_settings->send_email_buyer_purchase == '0') ? 'checked' : ''; ?>>
                            <label for="email_option_send_order_to_buyer_2" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('email_option_send_email_order_shipped'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_order_shipped" value="1" id="send_email_order_shipped_1" class="square-purple" <?php echo ($this->general_settings->send_email_order_shipped == '1') ? 'checked' : ''; ?>>
                            <label for="send_email_order_shipped_1" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_order_shipped" value="0" id="send_email_order_shipped_2" class="square-purple" <?php echo ($this->general_settings->send_email_order_shipped == '0') ? 'checked' : ''; ?>>
                            <label for="send_email_order_shipped_2" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('email_option_contact_messages'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_contact_messages" value="1" id="send_email_contact_messages_1" class="square-purple" <?php echo ($this->general_settings->send_email_contact_messages == '1') ? 'checked' : ''; ?>>
                            <label for="send_email_contact_messages_1" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_contact_messages" value="0" id="send_email_contact_messages_2" class="square-purple" <?php echo ($this->general_settings->send_email_contact_messages == '0') ? 'checked' : ''; ?>>
                            <label for="send_email_contact_messages_2" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('send_email_shop_opening_request'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_shop_opening_request" value="1" id="send_email_shop_opening_request_1" class="square-purple" <?php echo ($this->general_settings->send_email_shop_opening_request == '1') ? 'checked' : ''; ?>>
                            <label for="send_email_shop_opening_request_1" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_shop_opening_request" value="0" id="send_email_shop_opening_request_2" class="square-purple" <?php echo ($this->general_settings->send_email_shop_opening_request == '0') ? 'checked' : ''; ?>>
                            <label for="send_email_shop_opening_request_2" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('bidding_system_emails'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_bidding_system" value="1" id="send_email_bidding_system_1" class="square-purple" <?php echo ($this->general_settings->send_email_bidding_system == '1') ? 'checked' : ''; ?>>
                            <label for="send_email_bidding_system_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-3 col-xs-12 col-option">
                            <input type="radio" name="send_email_bidding_system" value="0" id="send_email_bidding_system_2" class="square-purple" <?php echo ($this->general_settings->send_email_bidding_system == '0') ? 'checked' : ''; ?>>
                            <label for="send_email_bidding_system_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('email_address'); ?> (<?php echo trans("admin_emails_will_send"); ?>)</label>
                    <input type="text" class="form-control" name="mail_options_account"
                           placeholder="<?php echo trans('email_address'); ?>" value="<?php echo html_escape($this->general_settings->mail_options_account); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="verification" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?><!-- form end -->

    <?php echo form_open('admin_controller/send_test_email_post'); ?>
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('send_test_email'); ?></h3><br>
                <small class="small-title"><?php echo trans('send_test_email_exp'); ?></small>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label"><?php echo trans('email_address'); ?></label>
                    <input type="text" class="form-control" name="email" placeholder="<?php echo trans('email_address'); ?>" required>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="contact" class="btn btn-primary pull-right"><?php echo trans('send_email'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?><!-- form end -->

</div>