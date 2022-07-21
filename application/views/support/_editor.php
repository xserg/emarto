<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="<?= base_url(); ?>assets/vendor/file-uploader/js/jquery.dm-uploader.min.js"></script>
<script src="<?= base_url(); ?>assets/vendor/file-uploader/js/ui.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/tinymce/tinymce.min.js"></script>
<link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/file-uploader/css/jquery.dm-uploader.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/file-uploader/css/styles.css">
<script>
    tinymce.init({
        selector: '.tinyMCEticket',
        min_height: 400,
        valid_elements: '*[*]',
        relative_urls: false,
        remove_script_host: false,
        directionality: mds_config.rtl == true ? 'rtl' : 'ltr',
        entity_encoding: "raw",
        language: "<?php echo $this->selected_lang->text_editor_lang; ?>",
        menubar:  false,
        toolbar: 'fullscreen code preview | bold italic | alignleft aligncenter alignright alignjustify | numlist bullist | forecolor backcolor removeformat | table image media link | outdent indent',
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code codesample fullscreen",
            "insertdatetime media table paste imagetools"
        ],
        content_css: ['<?php echo base_url(); ?>assets/vendor/tinymce/editor_content.css'],
    });
    tinymce.DOM.loadCSS('<?php echo base_url(); ?>assets/vendor/tinymce/editor_ui.css');

    $(function () {
        $('#drag-and-drop-zone').dmUploader({
            url: '<?= base_url(); ?>support_controller/upload_support_attachment',
            queue: false,

            extraData: function (id) {
                return {
                    "file_id": id,
                    "ticket_type": 'client',
                    "<?= $this->security->get_csrf_token_name(); ?>": $.cookie(mds_config.csfr_cookie_name)
                };
            },
            onDragEnter: function () {
                this.addClass('active');
            },
            onDragLeave: function () {
                this.removeClass('active');
            },
            onInit: function () {
            },
            onComplete: function (id) {
            },
            onNewFile: function (id, file) {
                ui_multi_add_file(id, file, "file");
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
                var obj = JSON.parse(data);
                if (obj.result == 1) {
                    document.getElementById("response_uploaded_files").innerHTML = obj.response;
                }
                document.getElementById("uploaderFile" + id).remove();
                ui_multi_update_file_status(id, 'success', 'Upload Complete');
                ui_multi_update_file_progress(id, 100, 'success', false);
            },
            onFileSizeError: function (file) {
                alert("<?= trans("file_too_large") ?>");
            },
        });
    });
</script>