<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin-top: 10px;">
    <tr>
        <td class="content-block" style="text-align: center;width: 100%;">
            <?php if (!empty($this->settings->facebook_url)) : ?>
                <a href="<?php echo html_escape($this->settings->facebook_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
                    <img src="<?php echo base_url(); ?>assets/img/social-icons/facebook.png" alt="" style="width: 28px; height: 28px;"/>
                </a>
            <?php endif; ?>
            <?php if (!empty($this->settings->twitter_url)) : ?>
                <a href="<?php echo html_escape($this->settings->twitter_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
                    <img src="<?php echo base_url(); ?>assets/img/social-icons/twitter.png" alt="" style="width: 28px; height: 28px;"/>
                </a>
            <?php endif; ?>
            <?php if (!empty($this->settings->pinterest_url)) : ?>
                <a href="<?php echo html_escape($this->settings->pinterest_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
                    <img src="<?php echo base_url(); ?>assets/img/social-icons/pinterest.png" alt="" style="width: 28px; height: 28px;"/>
                </a>
            <?php endif; ?>
            <?php if (!empty($this->settings->instagram_url)) : ?>
                <a href="<?php echo html_escape($this->settings->instagram_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
                    <img src="<?php echo base_url(); ?>assets/img/social-icons/instagram.png" alt="" style="width: 28px; height: 28px;"/>
                </a>
            <?php endif; ?>
            <?php if (!empty($this->settings->linkedin_url)) : ?>
                <a href="<?php echo html_escape($this->settings->linkedin_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
                    <img src="<?php echo base_url(); ?>assets/img/social-icons/linkedin.png" alt="" style="width: 28px; height: 28px;"/>
                </a>
            <?php endif; ?>
            <?php if (!empty($this->settings->vk_url)) : ?>
                <a href="<?php echo html_escape($this->settings->vk_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
                    <img src="<?php echo base_url(); ?>assets/img/social-icons/vk.png" alt="" style="width: 28px; height: 28px;"/>
                </a>
            <?php endif; ?>
            <?php if (!empty($this->settings->youtube_url)) : ?>
                <a href="<?php echo html_escape($this->settings->youtube_url); ?>" target="_blank" style="color: transparent;margin-right: 5px;">
                    <img src="<?php echo base_url(); ?>assets/img/social-icons/youtube.png" alt="" style="width: 28px; height: 28px;"/>
                </a>
            <?php endif; ?>
        </td>
    </tr>
</table>

<!-- START FOOTER -->
<div class="footer">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td class="content-block powered-by">
                <span class="apple-link"><?php echo html_escape($this->settings->contact_address); ?></span><br>
                <?php echo trans('You_are_receiving_this_email'); ?><br>
                <?php echo trans('automated_message'); ?><br><br>
                <?php echo html_escape($this->settings->copyright); ?><br><br>
                    
                <?php 
                $page_terms = get_page_by_default_name("terms_conditions", $this->selected_lang->id);
                $ci =& get_instance();
                $page_policy = $ci->page_model->get_page("privacy-policy", $this->selected_lang->id);            
                ?>
                    <a href="<?= generate_url($page_terms->page_default_name); ?>" class="link-terms" target="_blank"><strong><?= html_escape($page_terms->title); ?></strong></a>
                    | <a href="<?= generate_url($page_policy->slug); ?>" class="link-terms" target="_blank"><strong><?= html_escape($page_policy->title); ?></strong></a>
                    | <a href="<?= generate_url('help_center'); ?>" class="nav-link" target="_blank"><strong><?= trans("help_center"); ?></strong></a>
                
                
            </td>
        </tr>
    </table>
</div>
<!-- END FOOTER -->

<!-- END CENTERED WHITE CONTAINER -->
</div>
</td>
<td>&nbsp;</td>
</tr>
</table>

<style>
    .wrapper table tr td img {
        height: auto !important;
    }

    .table-products {
        border-bottom: 1px solid #d1d1d1;
        padding-bottom: 30px;
        margin-top: 20px;
    }

    .table-products th, td {
        padding: 8px 5px;
    }

    .wrapper table tr td img {
        height: auto !important;
    }
</style>
</body>
</html>
