<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="support">
                    <nav class="nav-breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo generate_url('help_center'); ?>"><?php echo trans("help_center"); ?></a></li>
                        </ol>
                    </nav>
                    <h1 class="title-category"><?= trans("search_results"); ?>:&nbsp;<span><?= html_escape($q); ?></span>
                        <?php if (!empty($contents)): ?>
                            <br><span class="number-of-results"><?= trans("number_of_results") . ": " . $num_rows; ?></span>
                        <?php endif; ?>
                    </h1>
                    <div class="row">
                        <div class="col-12">
                            <ul class="support-search-results">
                                <?php if (!empty($contents)):
                                    foreach ($contents as $item): ?>
                                        <li>
                                            <div class="title">
                                                <a href="<?= generate_url('help_center') . "/" . html_escape($item->category_slug) . "/" . html_escape($item->slug); ?>"><?= html_escape($item->title); ?></a>
                                            </div>
                                            <div class="category">
                                                <a href="<?= generate_url('help_center') . "/" . html_escape($item->category_slug); ?>"><?= html_escape($item->category_name); ?></a>
                                            </div>
                                        </li>
                                    <?php endforeach;
                                endif; ?>
                            </ul>

                            <?php if (empty($contents)): ?>
                                <p class="text-center text-muted m-t-15">
                                    <?php echo trans("no_results_found"); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 m-t-30">
                            <div class="float-left">
                                <div class="all-help-topics">
                                    <a href="<?php echo generate_url('help_center'); ?>">
                                        <i class="icon-angle-left"></i>
                                        <?= trans("all_help_topics"); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="float-right">
                                <?php echo $this->pagination->create_links(); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>