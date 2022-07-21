<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="support">
                    <nav class="nav-breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= trans("help_center"); ?></li>
                        </ol>
                    </nav>
                    <h1 class="page-title main-title"><strong><?= trans("how_can_we_help"); ?></strong></h1>

                    <div class="row">
                        <div class="col-12">
                            <div class="search-container">
                                <div class="search">
                                    <form action="<?= generate_url('help_center', 'search'); ?>" method="get">
                                        <input type="text" name="q" class="form-control form-input" placeholder="<?= trans("search"); ?>" required>
                                        <button type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#222222" viewBox="0 0 16 16" class="mds-svg-icon">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php if (!empty($support_categories)):
                            foreach ($support_categories as $item):?>
                                <div class="col-sm-4">
                                    <?php if ($item->num_content > 0): ?>
                                        <a href="<?= generate_url('help_center') . '/' . html_escape($item->slug); ?>" class="a-box-support">
                                            <div class="box-support">
                                                <h3 class="title"><?= html_escape($item->name); ?></h3>
                                                <span><?= trans_with_field('num_articles', $item->num_content); ?></span>
                                            </div>
                                        </a>
                                    <?php else: ?>
                                        <div class="box-support">
                                            <h3 class="title"><?= html_escape($item->name); ?></h3>
                                            <span><?= trans_with_field('num_articles', $item->num_content); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach;
                        endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="need-more-help">
                                <h3 class="title"><?= trans("need_more_help"); ?></h3>
                                <span class="text-muted"><?= trans("contact_support_exp"); ?></span>
                                <a href="<?= generate_url('help_center', 'submit_request'); ?>" class="btn btn-lg btn-custom">
                                    <svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#fff" class="mds-svg-icon">
                                        <path d="M896 0q182 0 348 71t286 191 191 286 71 348-71 348-191 286-286 191-348 71-348-71-286-191-191-286-71-348 71-348 191-286 286-191 348-71zm0 128q-190 0-361 90l194 194q82-28 167-28t167 28l194-194q-171-90-361-90zm-678 1129l194-194q-28-82-28-167t28-167l-194-194q-90 171-90 361t90 361zm678 407q190 0 361-90l-194-194q-82 28-167 28t-167-28l-194 194q171 90 361 90zm0-384q159 0 271.5-112.5t112.5-271.5-112.5-271.5-271.5-112.5-271.5 112.5-112.5 271.5 112.5 271.5 271.5 112.5zm484-217l194 194q90-171 90-361t-90-361l-194 194q28 82 28 167t-28 167z"/>
                                    </svg>
                                    <?= trans("contact_support"); ?>
                                </a>
                                <?php if ($this->auth_check): ?>
                                    <a href="<?= generate_url('help_center', 'tickets'); ?>" class="btn btn-lg btn-custom">
                                        <svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#fff" class="mds-svg-icon">
                                            <path d="M384 1408q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm0-512q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm1408 416v192q0 13-9.5 22.5t-22.5 9.5h-1216q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1216q13 0 22.5 9.5t9.5 22.5zm-1408-928q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm1408 416v192q0 13-9.5 22.5t-22.5 9.5h-1216q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1216q13 0 22.5 9.5t9.5 22.5zm0-512v192q0 13-9.5 22.5t-22.5 9.5h-1216q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1216q13 0 22.5 9.5t9.5 22.5z"/>
                                        </svg>
                                        <?= trans("support_tickets"); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>