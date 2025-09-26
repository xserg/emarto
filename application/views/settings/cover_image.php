<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
                    </ol>
                </nav>

                <h1 class="page-title"><?php echo trans("settings"); ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="row-custom">
                    <!-- load profile nav -->
                    <?php $this->load->view("settings/_setting_tabs"); ?>
                </div>
            </div>

            <div class="col-sm-12 col-md-9">
                <div class="row-custom">
                    <div class="profile-tab-content">
                        <!-- include message block -->
                        <?php $this->load->view('partials/_messages'); ?>

                        <?php echo form_open_multipart("cover-image-post", ['id' => 'cover-dropzone', 'class' => 'dropzone']); ?>
                        <div class="form-group">
                            <label class="control-label"><?php echo trans("cover_image"); ?>&nbsp;(1920x400)</label>
                            <?php if (!empty($this->auth_user->cover_image)): ?>
                            <img src="<?= base_url() . $this->auth_user->cover_image; ?>" class="img-fluid m-b-15">
                            <?php else: ?>
                                <div class="edit-profile-cover-image dropzone-previews" id="preview">
                                    <i class="icon-image"></i>
                                </div>
                            <?php endif; ?>
                            <p class="m-0">

                                
                                <?php if (!empty($this->auth_user->cover_image)): ?>
                                    <button type="submit" class="btn btn-md btn-secondary btn-file-upload btn-file-upload-cover" name="submit" value="delete_cover"><?= trans("delete"); ?></button>
                                <?php else: ; ?>
                                    <div class="dz-message btn btn-md btn-custom"><?php echo trans('select_image'); ?></div>
                                <?php endif; ?>
                            </p>
                            <span class='badge badge-light' id="upload-file-info"></span>
                        </div>

                        <div class="form-group m-t-10">
                            <div class="row">
                                <div class="col-12">
                                    <label class="control-label"><?php echo trans('type'); ?></label>
                                </div>
                                <div class="col-md-3 col-sm-4 col-12 col-option">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="cover_image_type" value="full_width" id="cover_image_type_1" class="custom-control-input" <?php echo ($this->auth_user->cover_image_type == 'full_width') ? 'checked' : ''; ?>>
                                        <label for="cover_image_type_1" class="custom-control-label"><?php echo trans("full_width"); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4 col-12 col-option">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="cover_image_type" value="boxed" id="cover_image_type_2" class="custom-control-input" <?php echo ($this->auth_user->cover_image_type == 'boxed') ? 'checked' : ''; ?>>
                                        <label for="cover_image_type_2" class="custom-control-label"><?php echo trans("boxed"); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="submit" value="update" class="btn btn-md btn-custom"><?php echo trans("save_changes") ?></button>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->
<?php if (empty($this->auth_user->cover_image)): ?>
<script src="/assets/js/dropzone.min.js"></script>
<link rel="stylesheet" href="/assets/css/dropzone_cover.css" type="text/css" />

<script>
    Dropzone.options.coverDropzone = { // camelized version of the `id`
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 9, // MB
    autoProcessQueue: false,
    uploadMultiple: false,
    parallelUploads: 1,
    maxFiles: 1,
    previewsContainer: "#preview",
    addRemoveLinks: true,
    
    init: function() {
    var myDropzone = this;

    // First change the button to actually tell Dropzone to process the queue.
    this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
      // Make sure that the form isn't actually being sent.
      e.preventDefault();
      e.stopPropagation();
      myDropzone.processQueue();
    });

    this.on("queuecomplete", function () {
        window.location.reload();
        });

    }
  };
</script>
<?php endif; ?>
