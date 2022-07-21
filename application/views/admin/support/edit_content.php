<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="left">
                        <h3 class="box-title"><?= $title; ?></h3>
                    </div>
                    <div class="right">
                        <a href="<?php echo admin_url(); ?>knowledge-base?lang=<?= $content->lang_id; ?>" class="btn btn-success btn-add-new">
                            <i class="fa fa-list-ul"></i>&nbsp;&nbsp;<?php echo trans('knowledge_base'); ?>
                        </a>
                    </div>
                </div><!-- /.box-header -->

                <!-- form start -->
                <?php echo form_open('support_admin_controller/edit_content_post'); ?>
                <div class="box-body">
                    <!-- include message block -->
                    <?php $this->load->view('admin/includes/_messages'); ?>
                    <input type="hidden" name="id" value="<?= $content->id; ?>">
                    <div class="form-group">
                        <label class="control-label"><?php echo trans('title'); ?></label>
                        <input type="text" class="form-control" name="title" placeholder="<?php echo trans('title'); ?>" value="<?= $content->title; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo trans("slug"); ?>
                            <small>(<?php echo trans("slug_exp"); ?>)</small>
                        </label>
                        <input type="text" class="form-control" name="slug" placeholder="<?php echo trans("slug"); ?>" value="<?= $content->slug; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>

                    <div class="form-group">
                        <label><?php echo trans("language"); ?></label>
                        <select name="lang_id" class="form-control" onchange="get_knowledge_base_categories_by_lang(this.value);" style="max-width: 600px;">
                            <?php foreach ($this->languages as $language): ?>
                                <option value="<?php echo $language->id; ?>" <?php echo $content->lang_id == $language->id ? 'selected' : ''; ?>><?php echo $language->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo trans("categories"); ?></label>
                        <select name="category_id" id="categories" class="form-control" style="max-width: 600px;" required>
                            <?php if (!empty($categories)):
                                foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>" <?= $content->category_id == $category->id ? 'selected' : ''; ?>><?php echo $category->name; ?></option>
                                <?php endforeach;
                            endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo trans('sort'); ?></label>
                        <input type="number" class="form-control" name="content_order" placeholder="<?php echo trans('sort'); ?>" value="<?= $content->content_order; ?>" min="1" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> style="max-width: 600px;">
                    </div>

                    <div class="form-group" style="margin-top: 30px;">
                        <label><?php echo trans('content'); ?></label>
                        <div class="row">
                            <div class="col-sm-12 m-b-5">
                                <button type="button" class="btn btn-success btn-file-manager" data-image-type="editor" data-toggle="modal" data-target="#imageFileManagerModal"><i class="fa fa-image"></i>&nbsp;&nbsp;<?php echo trans("add_image"); ?></button>
                            </div>
                        </div>
                        <textarea class="form-control tinyMCE" name="content"><?= $content->content; ?></textarea>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
                </div>
                <!-- /.box-footer -->

                <?php echo form_close(); ?><!-- form end -->
            </div>
            <!-- /.box -->
        </div>
    </div>
<?php $this->load->view('admin/includes/_image_file_manager'); ?>