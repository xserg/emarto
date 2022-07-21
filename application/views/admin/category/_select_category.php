<script>
    function get_subcategories(parent_id, level, div_container = 'category_select_container') {
        level = parseInt(level);
        var new_level = level + 1;
        var data = {
            'parent_id': parent_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "ajax_controller/get_subcategories",
            data: data,
            success: function (response) {
                $('.subcategory-select').each(function () {
                    if (parseInt($(this).attr('data-level')) > level) {
                        $(this).remove();
                    }
                });
                var obj = JSON.parse(response);
                if (obj.result == 1 && obj.html_content != '') {
                    var select_tag = '<select class="form-control subcategory-select" data-level="' + new_level + '" name="<?= $input_name; ?>" onchange="get_subcategories(this.value,' + new_level + ',\'' + div_container + '\');">' +
                        '<option value=""><?= trans('none'); ?></option>' + obj.html_content + '</select>';
                    $('#' + div_container).append(select_tag);
                }
            }
        });
    }
</script>