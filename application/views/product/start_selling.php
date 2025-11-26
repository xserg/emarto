<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/file-uploader/css/jquery.dm-uploader.min.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/file-uploader/css/styles.css"/>
<script src="<?php echo base_url(); ?>assets/vendor/file-uploader/js/jquery.dm-uploader.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/file-uploader/js/ui.js"></script>
<script>
    var base_url = "<?= base_url(); ?>";
    var sys_lang_id = "<?= $this->selected_lang->id; ?>";
    var csfr_token_name = "<?= $this->security->get_csrf_token_name(); ?>";
    var csfr_cookie_name = "<?= $this->config->item('csrf_cookie_name'); ?>";
</script>

<div id="wrapper">
    <div class="container">
        <div class="row">
            <div id="content" class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb"></ol>
                </nav>
                <h1 class="page-title page-title-product m-b-15"><?php echo trans("start_selling"); ?></h1>
                <div class="form-add-product">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-12 col-lg-10">
                            <div class="row">
                                <div class="col-12">
                                    <p class="start-selling-description text-muted"><?php echo trans("start_selling_exp"); ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <!-- include message block -->
                                    <?php $this->load->view('product/_messages'); ?>
                                </div>
                            </div>

                            <?php if ($this->auth_check):
                                if ($this->auth_user->is_active_shop_request == 1):?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-info" role="alert">
                                                <?php echo trans("msg_shop_opening_requests"); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($this->auth_user->is_active_shop_request == 2): ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-secondary" role="alert">
                                                <?php echo trans("msg_shop_request_declined"); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <?php echo form_open_multipart($this->lang_base_url.'start-selling', ['id' => 'form_validate', 'class' => 'validate_terms validate_phone', 'onkeypress' => "return event.keyCode != 13;"]); ?>
                                            <?php if (!empty($plan)): ?>
                                                <input type="hidden" name="plan_id" value="<?php echo $plan->id; ?>">
                                            <?php endif; ?>
                                            <div class="form-box m-b-15">
                                                <div class="form-box-head text-center">
                                                    <h4 class="title title-start-selling-box"><?php echo trans('tell_us_about_shop'); ?></h4>
                                                </div>
                                                <div class="form-box-body">

                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo trans("shop_name"); ?></label>
                                                        <input type="text" name="shop_name" class="form-control form-input" value="<?php echo $this->auth_user->username; ?>" placeholder="<?php echo trans("shop_name"); ?>" maxlength="<?php echo $this->username_maxlength; ?>">
                                                    </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo trans("legal_name"); ?></label>
                                <input type="text" name="legal_name" class="form-control form-input" value="<?php echo html_escape($this->auth_user->legal_name); ?>" placeholder="<?php //echo trans("legal_name"); ?>" maxlength="250">
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo trans("role"); ?></label>
                                <input type="text" name="role" class="form-control form-input" value="<?php echo html_escape($this->auth_user->role); ?>" placeholder="<?php //echo trans("role"); ?>" maxlength="250">
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo trans("business_number"); ?></label>
                                <input type="text" name="business_number" class="form-control form-input" value="<?php echo html_escape($this->auth_user->business_number); ?>" placeholder="<?php //echo trans("business_number"); ?>" maxlength="250">
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo trans("business_address"); ?></label>
                                <input type="text" name="business_address" class="form-control form-input" value="<?php echo html_escape($this->auth_user->business_address); ?>" placeholder="<?php //echo trans("business_address"); ?>" maxlength="250">
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo trans("taxpayer_number"); ?></label>
                                <input type="text" name="taxpayer_number" class="form-control form-input" value="<?php echo html_escape($this->auth_user->taxpayer_number); ?>" placeholder="<?php //echo trans("taxpayer_number"); ?>" maxlength="250">
                            </div>
                         
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-12 col-sm-4 m-b-15">
                                                                <label class="control-label"><?php echo trans("first_name"); ?></label>
                                                                <input type="text" name="first_name" class="form-control form-input" value="<?php echo html_escape($first_name); ?>" placeholder="<?php echo trans("first_name"); ?>">
                                                            </div>
                                                            <div class="col-12 col-sm-4 m-b-15">
                                                                <label class="control-label"><?php echo trans("last_name"); ?></label>
                                                                <input type="text" name="last_name" class="form-control form-input" value="<?php echo html_escape($last_name); ?>" placeholder="<?php echo trans("last_name"); ?>">
                                                            </div>
                                                            <div class="col-12 col-sm-4 m-b-15">
                                                                <label class="control-label"><?php echo trans("phone_number"); ?></label><br>
                                                                <input type="text" type="tel" id="phone"  name="phone_number" class="custom-control-validate-input" value="<?php echo html_escape($this->auth_user->phone_number); ?>">
                                                                <br><span id="error-msg" class="text-danger hide"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo trans('location'); ?></label>
                                                        <?php
                                                        $countries_f = [];
                                                        $onlyCountries = '';
                                                        foreach ($this->countries as $country) {
                                                           if ($country->seller == 1) {
                                                              $countries_f[] = $country;
                                                              $onlyCountries .= ($onlyCountries ? '","' : '"').$country->iso;
                                                           }
                                                        }
                                                        $onlyCountries .= '"';
                                                        $this->countries = $countries_f;
                                                        $this->load->view(
                                                          "partials/_location",
                                                          ['countries' => $this->countries,
                                                          'country_id' => $country_id,
                                                          'state_id' => $state_id,
                                                          'city_id' => $city_id,
                                                          'map' => false]
                                                        );
                                                        ?>
                                                    </div>
                                                    <?php if ($this->general_settings->request_documents_vendors == 1): ?>
                                                        
                                                        <div class="form-group">
                                                            
                                                            <label class="control-label">
                                                                <?php echo trans("required_files"); ?>
                                                                <?php if (!empty($this->general_settings->explanation_documents_vendors)): ?>
                                                                    <span class="text-muted font-weight-normal">(<?= $this->general_settings->explanation_documents_vendors; ?>)</span>
                                                                <?php endif; ?>
                                                            </label>

                                                            <?php $this->load->view("product/_image_upload_box", ['modesy_images' => []]); ?>
                                                        </div>

                                                    <?php endif; ?>
                                                    <div class="form-group">
                                                        <label class="control-label"><?php echo trans("about_shop"); ?></label>
                                                        <textarea name="about_shop" class="form-control form-textarea" placeholder="<?php //echo trans("about_shop"); ?>" maxlength="1000" required><?= $this->auth_user->about_shop; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group m-t-15">
                                                <div class="custom-control custom-checkbox custom-control-validate-input">
                                                    <input type="checkbox" class="custom-control-input" name="terms_conditions" id="terms_conditions" value="1">
                                                    <label for="terms_conditions" class="custom-control-label"><?php echo trans("terms_conditions_exp"); ?>
                                                      <?php $page_terms = get_page_by_default_name("terms_conditions", $this->selected_lang->id);
                                                      $ci =& get_instance();
                                                      $page_policy = $ci->page_model->get_page("privacy-policy", $this->selected_lang->id);
                                                      if (!empty($page_terms)): ?>
                                                          <a href="<?= generate_url($page_terms->page_default_name); ?>" class="link-terms" target="_blank"><strong><?= html_escape($page_terms->title); ?></strong></a>
                                                          <?php echo trans("and"); ?> <a href="<?= generate_url($page_policy->slug); ?>" class="link-terms" target="_blank"><strong><?= html_escape($page_policy->title); ?></strong></a>.
                                                      <?php endif; ?>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-lg btn-custom float-right"><?php echo trans("submit"); ?></button>
                                            </div>

                                            <?php echo form_close(); ?>

                                        </div>
                                    </div>
                                <?php endif;
                            endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php  
$country = $this->location_model->get_country($this->default_location->country_id, $lang); 
if (stristr($onlyCountries, $country->iso)) {
    $initialCountry = 'initialCountry: "' . $country->iso . '",';
} else {
    $initialCountry = '';
}
?>
<link rel="stylesheet" href="/assets/css/intlTelInput.css">
<script src="/assets/js/intlTelInput.js"></script>
<script>
  var input = document.querySelector("#phone");
  if (input) {
  var iti = window.intlTelInput(input, {
    //autoHideDialCode: true,
    nationalMode: true,
    onlyCountries: [<?php echo $onlyCountries; ?>],
    placeholderNumberType: "MOBILE",
    preferredCountries: [],
    separateDialCode: true,
    utilsScript: "/assets/js/utils.js",
    <?php echo $initialCountry; ?>
  });
  }

</script>

