<?php defined('BASEPATH') or exit('No direct script access allowed');
function print_sub_categories($parent_id, $lang_id)
{
    $ci =& get_instance();
    $subcategories = $ci->category_model->get_subcategories_by_parent_id($parent_id, $lang_id);
    if (!empty($subcategories)) {
        foreach ($subcategories as $category) {
            $i = 0;
            if ($i == 0) {
                if (!empty($category->has_subcategory)) {
                    echo '<div class="panel-group">';
                } else {
                    echo '<div class="panel-group cursor-default">';
                }
                echo '<div class="panel panel-default">';
                if (!empty($category->has_subcategory)) {
                    $div_content = '<div class="panel-heading" data-item-id="' . $category->id . '" href="#collapse_' . $category->id . '">';
                } else {
                    $div_content = '<div class="panel-heading">';
                }
                $div_content .= '<div class="left">';
                if (!empty($category->has_subcategory)) {
                    $div_content .= '<i class="fa fa-caret-right"></i>';
                } else {
                    $div_content .= '<i class="fa fa-circle" style="font-size: 8px;"></i>';
                }
                $div_content .= category_name($category) . '<span class="id">(' . trans("id") . ': ' . $category->id . ')</span>';
                $div_content .= '</div>';
                $div_content .= '<div class="right">';
                $div_content .= ($category->is_featured == 1) ? '<label class="label bg-teal">' . trans("featured") . '</label>' : '';
                $div_content .= ($category->visibility == 1) ? '<label class="label bg-olive">' . trans("visible") . '</label>' : '<label class="label bg-danger">' . trans("hidden") . '</label>';
                $div_content .= '<div class="btn-group btn-group-option">';
                $div_content .= '<a href="' . admin_url() . 'update-category/' . $category->id . '" target="_blank" class="btn btn-sm btn-default btn-edit">' . trans("edit") . '</a>';
                $div_content .= '<a href="javascript:void(0)" class="btn btn-sm btn-default btn-delete" data-item-id="' . $category->id . '"><i class="fa fa-trash-o"></i></a>';
                $div_content .= '</div>';
                $div_content .= '</div>';
                $div_content .= '</div>';
                echo $div_content;
                echo '<div id="collapse_' . $category->id . '" class="panel-collapse collapse"><div class="panel-body nested-sortable" data-parent-id="' . $category->id . '">';
            } else {
                echo '<div id="collapse_' . $category->id . '" class="list-group-item" data-item-id="' . $category->id . '">' . category_name($category) . '<span class="id">(' . trans("id") . ': ' . $category->id . ')</span>' . '</div>';
            }
            print_sub_categories($category->id, $lang_id);
            $i++;
            if ($i > 0) {
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
    }
} ?>

<div class="panel-body">
    <?= print_sub_categories($parent_category_id, $lang_id); ?>
</div>
