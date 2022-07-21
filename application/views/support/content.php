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
                            <?php if (!empty($category)): ?>
                                <li class="breadcrumb-item active" aria-current="page"><?= $category->name; ?></li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                    <h1 class="title-category"><?= html_escape($category->name); ?></h1>
                    <div class="row">
                        <div class="col-md-3 hide-mobile">
                            <div class="all-help-topics">
                                <a href="<?php echo generate_url('help_center'); ?>">
                                    <i class="icon-angle-left"></i>
                                    <?= trans("all_help_topics"); ?>
                                </a>
                            </div>
                            <ul class="ul-support-articles">
                                <?php if (!empty($articles)):
                                    foreach ($articles as $item):; ?>
                                        <li <?= $article->id == $item->id ? 'class="active"' : ''; ?>><a href="<?= generate_url('help_center') . "/" . html_escape($category->slug) . "/" . html_escape($item->slug); ?>"><?= html_escape($item->title); ?></a></li>
                                    <?php endforeach;
                                endif; ?>
                            </ul>
                        </div>
                        <div class="col-sm-12 col-lg-9">
                            <div class="help-center-collapse">
                                <a href="#related_help_topics" data-toggle="collapse" class="collapse-title">
                                    <?= trans("related_help_topics"); ?>
                                    <div class="float-right">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="mds-svg-icon" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </div>
                                </a>
                                <div id="related_help_topics" class="collapse">
                                    <ul class="ul-support-articles">
                                        <?php if (!empty($articles)):
                                            foreach ($articles as $item):?>
                                                <li <?= $article->id == $item->id ? 'class="active"' : ''; ?>><a href="<?= generate_url('help_center') . "/" . html_escape($category->slug) . "/" . html_escape($item->slug); ?>"><?= html_escape($item->title); ?></a></li>
                                            <?php endforeach;
                                        endif; ?>
                                    </ul>
                                </div>
                            </div>
                            <h2 class="article-title"><?= html_escape($article->title); ?></h2>
                            <div class="article-content"><?= $article->content; ?></div>
                            <div class="need-more-help need-more-help-article">
                                <h3 class="title"><?= trans("still_have_questions"); ?></h3>
                                <span class="text-muted"><?= trans("still_have_questions_exp"); ?></span>
                                <a href="<?= generate_url('help_center', 'submit_request'); ?>" class="btn btn-lg btn-custom">
                                    <svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#fff" class="mds-svg-icon">
                                        <path d="M896 0q182 0 348 71t286 191 191 286 71 348-71 348-191 286-286 191-348 71-348-71-286-191-191-286-71-348 71-348 191-286 286-191 348-71zm0 128q-190 0-361 90l194 194q82-28 167-28t167 28l194-194q-171-90-361-90zm-678 1129l194-194q-28-82-28-167t28-167l-194-194q-90 171-90 361t90 361zm678 407q190 0 361-90l-194-194q-82 28-167 28t-167-28l-194 194q171 90 361 90zm0-384q159 0 271.5-112.5t112.5-271.5-112.5-271.5-271.5-112.5-271.5 112.5-112.5 271.5 112.5 271.5 271.5 112.5zm484-217l194 194q90-171 90-361t-90-361l-194 194q28 82 28 167t-28 167z"/>
                                    </svg>
                                    <?= trans("contact_support"); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>