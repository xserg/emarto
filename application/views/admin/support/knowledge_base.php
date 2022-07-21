<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row" style="margin-bottom: 15px;">
    <div class="col-sm-12">
        <h3 style="font-size: 18px; font-weight: 600;margin-top: 10px;"><?php echo trans('knowledge_base'); ?></h3>
    </div>
</div>

<div class="form-group">
    <label><?php echo trans("language"); ?></label>
    <select name="lang_id" class="form-control" onchange="window.location.href = '<?php echo admin_url(); ?>knowledge-base?lang='+this.value;" style="max-width: 600px;">
        <?php foreach ($this->languages as $language): ?>
            <option value="<?php echo $language->id; ?>" <?php echo ($language->id == $lang_id) ? 'selected' : ''; ?>><?php echo $language->name; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div class="row">
    <div class="col-sm-12">
        <?php $this->load->view('admin/includes/_messages'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans("contents"); ?></h3>
                </div>
                <div class="right">
                    <a href="<?php echo admin_url(); ?>knowledge-base/add-content?lang=<?= $lang_id; ?>" class="btn btn-success btn-add-new">
                        <i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo trans('add_content'); ?>
                    </a>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped data_table" role="grid">
                                <thead>
                                <tr role="row">
                                    <th width="20"><?php echo trans('id'); ?></th>
                                    <th><?php echo trans('title'); ?></th>
                                    <th><?php echo trans('language'); ?></th>
                                    <th><?php echo trans('category'); ?></th>
                                    <th><?php echo trans('date'); ?></th>
                                    <th class="th-options"><?php echo trans('options'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($contents)):
                                    foreach ($contents as $item): ?>
                                        <tr>
                                            <td><?php echo html_escape($item->id); ?></td>
                                            <td><?php echo html_escape($item->title); ?></td>
                                            <td>
                                                <?php
                                                $language = get_language($item->lang_id);
                                                if (!empty($language)) {
                                                    echo $language->name;
                                                } ?>
                                            </td>
                                            <td><?php echo html_escape($item->category_name); ?></td>
                                            <td><?php echo formatted_date($item->created_at); ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn bg-purple dropdown-toggle btn-select-option" type="button" data-toggle="dropdown"><?php echo trans('select_option'); ?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu options-dropdown">
                                                        <li>
                                                            <a href="<?php echo admin_url(); ?>knowledge-base/edit-content/<?= $item->id; ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" onclick="delete_item('support_admin_controller/delete_content_post','<?php echo $item->id; ?>','<?php echo trans("confirm_delete"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans("categories"); ?></h3>
                </div>
                <div class="right">
                    <a href="<?php echo admin_url(); ?>knowledge-base/add-category?lang=<?= $lang_id; ?>" class="btn btn-success btn-add-new">
                        <i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo trans('add_category'); ?>
                    </a>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped data_table" role="grid">
                                <thead>
                                <tr role="row">
                                    <th width="20"><?php echo trans('id'); ?></th>
                                    <th><?php echo trans('title'); ?></th>
                                    <th><?php echo trans('language'); ?></th>
                                    <th class="th-options"><?php echo trans('options'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($categories)):
                                    foreach ($categories as $item): ?>
                                        <tr>
                                            <td><?php echo html_escape($item->id); ?></td>
                                            <td><?php echo html_escape($item->name); ?></td>
                                            <td>
                                                <?php
                                                $language = get_language($item->lang_id);
                                                if (!empty($language)) {
                                                    echo $language->name;
                                                } ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn bg-purple dropdown-toggle btn-select-option" type="button" data-toggle="dropdown"><?php echo trans('select_option'); ?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu options-dropdown">
                                                        <li>
                                                            <a href="<?php echo admin_url(); ?>knowledge-base/edit-category/<?= $item->id; ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" onclick="delete_item('support_admin_controller/delete_category_post','<?php echo $item->id; ?>','<?php echo trans("confirm_delete"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>