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

                                                <td><?php echo $request->price ? price_formatted($request->price, $request->currency) : ''; ?></td>
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