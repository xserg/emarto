<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo html_escape($title); ?></li>
                    </ol>
                </nav>
                <div class="row m-b-15">
                    <div class="col-6">
                        <?php if ($page->title_active == 1): ?>
                            <h1 class="page-title"><?php echo html_escape($page->title); ?></h1>
                        <?php endif; ?>
                    </div>
                    <div class="col-6">
                        <div class="shops-search-container">
                            <div class="search">
                                <form action="<?= generate_url('shops'); ?>" method="get">
                                    <input type="text" name="q" class="form-control form-input" value="<?= html_escape(input_get('q')); ?>" placeholder="<?= trans("search"); ?>">
                                    <button type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#222222" viewBox="0 0 16 16" class="mds-svg-icon">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if (!empty($shops)): ?>
                        <?php foreach ($shops as $shop):
                            $show_shop = true;
                            if ($shop->role_id == 1 && $shop->num_products <= 0):
                                $show_shop = false;
                            endif;
                            if ($show_shop):?>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                    <div class="member-list-item">
                                        <div class="left">
                                            <a href="<?php echo generate_profile_url($shop->slug); ?>">
                                                <img src="<?php echo get_user_avatar($shop); ?>" alt="<?php echo get_shop_name($shop); ?>" class="img-fluid img-profile lazyload">
                                            </a>
                                        </div>
                                        <div class="right">
                                            <a href="<?php echo generate_profile_url($shop->slug); ?>">
                                                <p class="username"><?php echo get_shop_name($shop); ?></p>
                                            </a>
                                            <p class="text-muted m-b-10"><?php echo trans("products") . ": " . $shop->num_products; ?></p>

                                            <?php if ($this->auth_check): ?>
                                                <?php if ($shop->id != $this->auth_user->id): ?>
                                                    <?php echo form_open('follow-unfollow-user-post', ['class' => 'form-inline']); ?>
                                                    <input type="hidden" name="following_id" value="<?php echo $shop->id; ?>">
                                                    <input type="hidden" name="follower_id" value="<?php echo $this->auth_user->id; ?>">
                                                    <?php if (is_user_follows($shop->id, $this->auth_user->id)): ?>
                                                        <p>
                                                            <button class="btn btn-md btn-outline-gray"><i class="icon-user-minus"></i><?php echo trans("unfollow"); ?></button>
                                                        </p>
                                                    <?php else: ?>
                                                        <p>
                                                            <button class="btn btn-md btn-outline-gray"><i class="icon-user-plus"></i><?php echo trans("follow"); ?></button>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php echo form_close(); ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <p>
                                                    <button class="btn btn-md btn-outline" data-toggle="modal" data-target="#loginModal"><i class="icon-user-plus"></i><?php echo trans("follow"); ?></button>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;
                        endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="no-records-found">
                                <?php echo trans("no_records_found"); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="float-right">
                            <?php echo $this->pagination->create_links(); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
