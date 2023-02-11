<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-7 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("update_category"); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open_multipart('category_controller/update_category_post'); ?>

            <input type="hidden" name="id" value="<?php echo $category->id; ?>">

            <div class="box-body">

                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>
                <?php foreach ($this->languages as $language):
                    $lang_category = $this->category_model->get_category_by_lang($category->id, $language->id); ?>
                    <div class="form-group">
                        <label><?php echo trans("category_name"); ?> (<?php echo $language->name; ?>)</label>
                        <input type="text" class="form-control" name="name_lang_<?php echo $language->id; ?>" placeholder="<?php echo trans("category_name"); ?>" value="<?php echo (!empty($lang_category)) ? $lang_category->name : ""; ?>" maxlength="255" required>
                    </div>
                <?php endforeach; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans("slug"); ?>
                        <small>(<?php echo trans("slug_exp"); ?>)</small>
                    </label>
                    <input type="text" class="form-control" name="slug" value="<?php echo html_escape($category->slug); ?>" placeholder="<?php echo trans("slug"); ?>">
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('title'); ?> (<?php echo trans('meta_tag'); ?>)</label>
                    <input type="text" class="form-control" name="title_meta_tag"
                           placeholder="<?php echo trans('title'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo html_escape($category->title_meta_tag); ?>">
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('description'); ?> (<?php echo trans('meta_tag'); ?>)</label>
                    <input type="text" class="form-control" name="description"
                           placeholder="<?php echo trans('description'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo html_escape($category->description); ?>">
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('keywords'); ?> (<?php echo trans('meta_tag'); ?>)</label>
                    <input type="text" class="form-control" name="keywords"
                           placeholder="<?php echo trans('keywords'); ?> (<?php echo trans('meta_tag'); ?>)" value="<?php echo html_escape($category->keywords); ?>">
                </div>


                <div class="form-group">
                    <label><?php echo trans('order'); ?></label>
                    <input type="number" class="form-control" name="category_order" placeholder="<?php echo trans('order'); ?>" value="<?php echo html_escape($category->category_order); ?>" min="1" max="99999" required>
                </div>

                <div class="form-group">
                    <div id="category_select_container">
                        <?php $parent_array = array();
                        if (!empty($category->parent_tree)) {
                            $parent_array = explode(',', $category->parent_tree);
                        }
                        array_push($parent_array, $category->id);
                        $level = 1;
                        foreach ($parent_array as $parent_id):
                            $parent_item = $this->category_model->get_category($parent_id);
                            if (!empty($parent_item)):
                                $subcategories = $this->category_model->get_subcategories_by_parent_id($parent_item->parent_id);
                                if (!empty($subcategories)): ?>
                                    <select name="parent_id[]" class="form-control subcategory-select" data-level="<?= $level; ?>" onchange="get_subcategories(this.value,'<?= $level; ?>');">
                                        <option value=""><?php echo trans('none'); ?></option>
                                        <?php foreach ($subcategories as $subcategory):
                                            if ($subcategory->id != $category->id):?>
                                                <option value="<?= $subcategory->id; ?>" <?= $subcategory->id == $parent_item->id ? 'selected' : ''; ?>><?= category_name($subcategory); ?></option>
                                            <?php endif;
                                        endforeach; ?>
                                    </select>
                                <?php endif;
                            endif;
                            $level++;
                        endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans('visibility'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="visibility" value="1" id="visibility_1"
                                   class="square-purple" <?php echo ($category->visibility == '1') ? 'checked' : ''; ?>>
                            <label for="visibility_1" class="option-label cursor-pointer"><?php echo trans('show'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="visibility" value="0" id="visibility_2"
                                   class="square-purple" <?php echo ($category->visibility == '0') ? 'checked' : ''; ?>>
                            <label for="visibility_2" class="option-label cursor-pointer"><?php echo trans('hide'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans('show_on_main_menu'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="show_on_main_menu" value="1" id="show_on_main_menu_1" class="square-purple" <?= $category->show_on_main_menu == 1 ? 'checked' : ''; ?>>
                            <label for="show_on_main_menu_1" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="show_on_main_menu" value="0" id="show_on_main_menu_2" class="square-purple" <?= $category->show_on_main_menu != 1 ? 'checked' : ''; ?>>
                            <label for="show_on_main_menu_2" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans('show_image_on_main_menu'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="show_image_on_main_menu" value="1" id="show_image_on_main_menu_1" class="square-purple" <?= $category->show_image_on_main_menu == 1 ? 'checked' : ''; ?>>
                            <label for="show_image_on_main_menu_1" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="show_image_on_main_menu" value="0" id="show_image_on_main_menu_2" class="square-purple" <?= $category->show_image_on_main_menu != 1 ? 'checked' : ''; ?>>
                            <label for="show_image_on_main_menu_2" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('image'); ?></label>
                    <?php if (!empty($category->image)): ?>
                        <div class="img-category display-block m-b-15">
                            <img src="<?php echo get_category_image_url($category); ?>" alt="" style="height: 200px;">
                        </div>
                    <?php endif; ?>
                    <div class="display-block">
                        <a class='btn btn-success btn-sm btn-file-upload'>
                            <?php echo trans('select_image'); ?>
                            <input type="file" id="Multifileupload" name="file" size="40" accept=".png, .jpg, .jpeg, .gif">
                        </a>
                        <?php if (!empty($category->image)): ?>
                            <a href="#" class="btn btn-sm btn-danger btn-delete-category-img" onclick="delete_category_image('<?php echo $category->id; ?>');"><?php echo trans("delete"); ?></a>
                        <?php endif; ?>
                    </div>

                    <div id="MultidvPreview" class="image-preview"></div>
                </div>

            </div>


            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?> </button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>

<?php $this->load->view('admin/category/_select_category', ['input_name' => 'parent_id[]']); ?>
