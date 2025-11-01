<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view("profile/_cover_image"); ?>
<div id="wrapper">
    <div class="container">
        <?php if (empty($user->cover_image)): ?>
            <div class="row">
                <div class="col-12">
                    <nav class="nav-breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?= trans("home"); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= trans("followers"); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-12">
                <div class="profile-page-top">
                    <?php $this->load->view("profile/_profile_user_info"); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php $this->load->view("profile/_profile_tabs"); ?>
            </div>
            <div class="col-12">
                <div class="sidebar-tabs-content">
                    <?= $pages->content_shop_policies; ?>
                </div>
            </div>
        </div>
    </div>
</div>