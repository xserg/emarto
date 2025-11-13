<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="dm-uploader-container">
    <div id="drag-and-drop-zone" class="dm-uploader text-center">
        <p class="dm-upload-icon">
            <i class="icon-upload"></i>
        </p>
        <p class="dm-upload-text"><?php echo trans("drag_drop_images_here"); ?>&nbsp;<span style="text-decoration: underline"><?php echo trans('browse_files'); ?></span></p>
        <a class='btn btn-md dm-btn-select-files'>
            <input type="file" name="file" id="file" size="40" multiple="multiple" 
            <?php if (empty($modesy_images))
                    echo "required"; ?>
            />
        </a>
        <ul class="dm-uploaded-files" id="files-image">
            
        </ul>
        <div class="error-message-img-upload"></div>
    </div>
</div>


<!-- File item template -->
<script type="text/html" id="files-template-image">
    <li class="media">
        <img class="preview-img" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="bg">
        <div class="media-body">
            <div class="progress">
                <div class="dm-progress-waiting"><?php echo trans("waiting"); ?></div>
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </li>
</script>

<script>
    var img_count = <?php echo sizeof($modesy_images); ?>;
    var max_count = 5;
    var newOrder = [];
    if (img_count == 0) {
        $("#delete_all").hide();
    }
    $(function () {
        $('#file').dmUploader({
            url: '<?php echo base_url(); ?>upload-image-session-post',
            maxFileSize: <?php echo $this->general_settings->max_file_size_image; ?>,
            queue: true,
            allowedTypes: 'image/*',
            extFilter: ["jpg", "jpeg", "png", "gif"],
            fieldName: 'file',
            extraData: function (id) {
                return {
                    "file_id": id,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": $.cookie(csfr_cookie_name),
                    "order": newOrder
                };
            },
            onDragEnter: function () {
                this.addClass('active');
            },
            onDragLeave: function () {
                this.removeClass('active');
            },
            onNewFile: function (id, file) {
                img_count ++;
                $("#delete_all").show();

                if (img_count > max_count) {
                    img_count --;
                    $(".error-message-img-upload").html("<?php echo trans('too_many_files'); ?>");
                    setTimeout(function () {
                        $(".error-message-img-upload").empty();
                    },  4000);
                    return false;
                }
                ui_multi_add_file(id, file, "image");
                if (typeof FileReader !== "undefined") {
                    var reader = new FileReader();
                    var img = $('#uploaderFile' + id).find('img');

                    reader.onload = function (e) {
                        img.attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            },
            onBeforeUpload: function (id) {
                $('#uploaderFile' + id + ' .dm-progress-waiting').hide();
                ui_multi_update_file_progress(id, 0, '', true);
                ui_multi_update_file_status(id, 'uploading', 'Uploading...');
            },
            onUploadProgress: function (id, percent) {
                ui_multi_update_file_progress(id, percent);
            },
            onUploadSuccess: function (id, data) {
                var data = {
                    "file_id": id,
                    "sys_lang_id": sys_lang_id
                };
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                    type: "POST",
                    url: base_url + "get-sess-uploaded-image-post",
                    data: data,
                    success: function (response) {
                        document.getElementById("uploaderFile" + id).innerHTML = response;
                    }
                });
                ui_multi_update_file_status(id, 'success', 'Upload Complete');
                ui_multi_update_file_progress(id, 100, 'success', false);
              
                file = $("#file");
                file.value = id;
                file.removeAttr("required");
            },
            onFileSizeError: function (file) {
                $(".error-message-img-upload").html("<?php echo trans('file_too_large') . ' ' . formatSizeUnits($this->general_settings->max_file_size_image); ?>");
                setTimeout(function () {
                    $(".error-message-img-upload").empty();
                }, 4000);
            },
            onFileExtError: function (file) {
              console.log('error');
                $(".error-message-img-upload").html("<?php echo trans('invalid_file_type'); ?>");
                setTimeout(function () {
                    $(".error-message-img-upload").empty();
                }, 4000);
            },
        });
    });


    //delete product image session
    $(document).on('click', '.btn-delete-product-img-session2', function () {
        var file_id = $(this).attr('data-file-id');
        var data = {
            "file_id": file_id,
            "sys_lang_id": sys_lang_id
        };
        if (img_count) {
            img_count --;
        }
        if (img_count == 0) {
           $("#delete_all").hide();
        }
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "file_controller/delete_image_session",
            data: data,
            success: function () {
                $('#' + file_id).remove();
            }
        });
    });

    //delete product image session
    $(document).on('click', '.btn-delete-product-img-session', function () {
        var file_id = $(this).attr('data-file-id');
        var data = {
            "file_id": file_id,
            "sys_lang_id": sys_lang_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "file_controller/delete_image_session",
            data: data,
            success: function () {
                $('#uploaderFile' + file_id).remove();
            }
        });
    });


    function delete_session_images(message) {
        swal({
            text: message,
            icon: "warning",
            buttons: [sweetalert_cancel, sweetalert_ok],
            dangerMode: true,
        }).then(function (willDelete) {
            if (willDelete) {
                var data = {
                    //"product_id": product_id
                };
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                    method: "POST",
                    url: base_url + "file_controller/delete_session_images",
                    data: data
                })
                    .done(function (response) {
                        location.reload();
                    })

            }
        });
    }
</script>

