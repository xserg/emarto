<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo trans("buy_requests"); ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 m-t-15 m-b-30">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-6">
                        <h1 class="page-title m-b-5"><?= trans("buy_requests"); ?></h1>
                    </div>
                    <div class="col-12 col-sm-6">
                        <button type="button" class="btn btn-info color-white float-right m-b-5" data-toggle="modal" data-target="#modalRefundRequest">
                            <svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#fff" class="mds-svg-icon">
                                <path d="M1600 736v192q0 40-28 68t-68 28h-416v416q0 40-28 68t-68 28h-192q-40 0-68-28t-28-68v-416h-416q-40 0-68-28t-28-68v-192q0-40 28-68t68-28h416v-416q0-40 28-68t68-28h192q40 0 68 28t28 68v416h416q40 0 68 28t28 68z"/>
                            </svg>
                            <?= trans("submit_buy_request"); ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row-custom">
                    <div class="profile-tab-content">
                        <!-- include message block -->
                        <?php $this->load->view('partials/_messages'); ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
          
                                  <th><?php echo trans('product'); ?></th>
                                    <th><?php echo trans('category'); ?></th>
                                    <th scope="col"><?php echo trans("title"); ?></th>
                                    <th scope="col"><?php echo trans("description"); ?></th>
                                    <th scope="col"><?php echo trans("price"); ?></th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($refund_requests)): ?>
                                    <?php foreach ($refund_requests as $request):
                                        //$product = get_order_product($request->order_product_id);
                                        //if (!empty($product)):?>
                                            <tr>
                                                <td class="td-product">
                                                  <div class="img-table">
                                                    <a href="<?php echo generate_url("buy_requests") . "/" . $request->id; ?>" target="_blank">
                                                        <img src="<?php echo base_url() . "uploads/buy/" .  $request->image_path_thumb; ?>" data-src="" alt="" class="lazyload img-responsive post-image"/>
                                                    </a>
                                                </div>
                                                </td>
                                                
                                                <td>
                                                    <?php $category = $this->category_model->get_category($request->category_id);
                                                    if (!empty($category)) {
                                                        echo html_escape($category->name);
                                                    } ?>
                                                </td>
                                                <td><?php echo $request->title; ?></td>
                                                <td><?php echo $request->description; ?></td>

                                                <td><?php echo $request->price ? $request->price . ' ' . $request->currency : ''; ?></td>
                                                <td><?php echo formatted_date($request->created_at); ?></td>
                                                <td>
                                                    <a href="<?php echo generate_url("buy_requests") . "/" . $request->id; ?>" class="btn btn-sm btn-table-info"><?php echo trans("details"); ?></a>
                                                </td>
                                                <td style="width: 120px;">
                                                    <div class="btn-group btn-group-option">
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-default btn-delete" data-toggle="tooltip" title="<?= trans('delete'); ?>" onclick="delete_product(<?php echo $request->id; ?>,'<?php echo trans("confirm_product"); ?>');"><i class="fa fa-trash-o"></i></a>
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                        <?php //endif;
                                    endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($refund_requests)): ?>
                            <p class="text-center text-muted">
                                <?php echo trans("no_records_found"); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row-custom m-t-15">
                    <div class="float-right">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRefundRequest" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-custom modal-refund">
            <?php echo form_open_multipart('buy_controller/submit_buy_request'); ?>
            <div class="modal-header">
                <h5 class="modal-title"><?php echo trans("submit_buy_request"); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"><i class="icon-close"></i> </span>
                </button>
            </div>
            <div class="modal-body">
              <?= trans('buy_message'); ?>
              <br><br>
              <div class="form-group">
                  <label class="control-label"><?php echo trans('category'); ?></label>
                  <select class="form-control" name="category_id[]" onchange="get_subcategories(this.value, 0);" required>
                      <option value="0"><?php echo trans('none'); ?></option>
                      <?php foreach ($parent_categories as $parent_category): ?>
                          <option value="<?php echo $parent_category->id; ?>"><?php echo category_name($parent_category); ?></option>
                      <?php endforeach; ?>
                  </select>
                  <div id="category_select_container"></div>
              </div>
              
                <?php $this->load->view("buy/_image_upload_box"); ?>
                <div class="form-group">
                    <label class="control-label"><?= trans("buy_title"); ?></label>
                    <input type=text name="title" class="form-control"  >
                </div>  
                <div class="form-group">
                    <label class="control-label"><?= trans("buy_description"); ?></label>
                    <textarea name="description" class="form-control" aria-hidden="true" placeholder="<?= trans('i_am_looking_for'); ?>"></textarea>
                </div> 
                <div class="form-group">
                  <div class="row">                  
                    <div class="col-sm-4">
                        <label class="control-label"><?php echo trans('buy_location'); ?></label>
                        <select id="select_countries" name="country_id" class="select form-control" onchange="get_states(this.value, '<?php echo $map; ?>');">
                            <option value=""><?php echo trans('country'); ?></option>
                            <?php foreach ($this->countries as $item):
                                if (!empty($country_id)): ?>
                                    <option value="<?php echo $item->id; ?>" <?php echo ($item->id == $country_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $item->id; ?>"><?php echo html_escape($item->name); ?></option>
                                <?php endif;
                            endforeach; ?>
                        </select>
                    </div>           
                  <div class="col-sm-4">
                      <label class="control-label"><?= trans("buy_price"); ?></label>
                      <input type=text name="price" class="form-control"  >
                  </div>
                  <div class="col-sm-4 col-custom-field">
                      <label class="control-label"><?= trans("currency"); ?></label>
                      <select name="currency" class="form-control">
                        <?php foreach ($this->currencies as $currency):
                          if ($currency->status == 1):?>
                              <option  value="<?= $currency->code; ?>"><?= $currency->code; ?>&nbsp;(<?= $currency->symbol; ?>)</option>
                          <?php endif;
                        endforeach; ?>
                      </select>                      
                  </div>        
                 </div>
                </div>                
                <div class="col-sm-12 text-left m-t-15 m-b-15">
                            <label class="control-label">
                              <b><?php echo trans("terms_new"); ?></b>                
                            </label>
                </div>
                <div class="form-group text-right m-0">
                    <button type="submit" class="btn btn-md btn-custom"><?= trans("submit"); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php $this->load->view('admin/category/_select_category', ['input_name' => 'category_id[]']); ?>

<script>
    var base_url = "<?= base_url(); ?>";
    var csfr_token_name = "<?= $this->security->get_csrf_token_name(); ?>";
    var csfr_cookie_name = "<?= $this->config->item('csrf_cookie_name'); ?>";
    var sweetalert_ok = "<?= trans("ok"); ?>";
    var sweetalert_cancel = "<?= trans("cancel"); ?>";

//delete product
function delete_product(product_id, message) {
  console.log(product_id, message);
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                "id": product_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                method: "POST",
                url: base_url + "buy_controller/delete_request",
                data: data
            })
                .done(function (response) {
                    location.reload();
                })

        }
    });
}
</script>