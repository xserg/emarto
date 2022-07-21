<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo trans("refund"); ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 m-t-15 m-b-30">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-6">
                        <h1 class="page-title m-b-5"><?= trans("refund_requests"); ?></h1>
                    </div>
                    <div class="col-12 col-sm-6">
                        <button type="button" class="btn btn-info color-white float-right m-b-5" data-toggle="modal" data-target="#modalRefundRequest">
                            <svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#fff" class="mds-svg-icon">
                                <path d="M1600 736v192q0 40-28 68t-68 28h-416v416q0 40-28 68t-68 28h-192q-40 0-68-28t-28-68v-416h-416q-40 0-68-28t-28-68v-192q0-40 28-68t68-28h416v-416q0-40 28-68t68-28h192q40 0 68 28t28 68v416h416q40 0 68 28t28 68z"/>
                            </svg>
                            <?= trans("submit_refund_request"); ?>
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
                                    <th scope="col"><?php echo trans("product"); ?></th>
                                    <th scope="col"><?php echo trans("total"); ?></th>
                                    <th scope="col"><?php echo trans("seller"); ?></th>
                                    <th scope="col"><?php echo trans("status"); ?></th>
                                    <th scope="col"><?php echo trans("updated"); ?></th>
                                    <th scope="col"><?php echo trans("date"); ?></th>
                                    <th scope="col"><?php echo trans("options"); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($refund_requests)): ?>
                                    <?php foreach ($refund_requests as $request):
                                        $product = get_order_product($request->order_product_id);
                                        if (!empty($product)):?>
                                            <tr>
                                                <td>
                                                    <a href="<?= generate_url("order_details") . "/" . $request->order_number; ?>" target="_blank" class="a-hover-underline">
                                                        #<?= $request->order_number; ?>&nbsp;-&nbsp;<?= html_escape($product->product_title); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo price_formatted($product->product_total_price, $product->product_currency); ?></td>
                                                <td>
                                                    <?php $seller = get_user($product->seller_id);
                                                    if (!empty($seller)): ?>
                                                        <a href="<?php echo generate_profile_url($seller->slug); ?>" target="_blank" class="font-600"><?php echo html_escape($seller->username); ?></a>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($request->status == 1): ?>
                                                        <label class="badge badge-lg badge-success"><?php echo trans("approved"); ?></label>
                                                    <?php elseif ($request->status == 2): ?>
                                                        <label class="badge badge-lg badge-danger"><?php echo trans("declined"); ?></label>
                                                    <?php else: ?>
                                                        <label class="badge badge-lg badge-secondary"><?php echo trans("order_processing"); ?></label>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo time_ago($request->updated_at); ?></td>
                                                <td><?php echo formatted_date($request->created_at); ?></td>
                                                <td>
                                                    <a href="<?php echo generate_url("refund_requests") . "/" . $request->id; ?>" class="btn btn-sm btn-table-info"><?php echo trans("details"); ?></a>
                                                </td>
                                            </tr>
                                        <?php endif;
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
            <?php echo form_open('order_controller/submit_refund_request'); ?>
            <div class="modal-header">
                <h5 class="modal-title"><?php echo trans("submit_refund_request"); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"><i class="icon-close"></i> </span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label"><?= trans("product"); ?></label>
                    <select class="form-control custom-select" name="order_product_id" required>
                        <option value=""><?= trans("select"); ?></option>
                        <?php if (!empty($user_orders)):
                            foreach ($user_orders as $order):
                                $hide_products = false;
                                if ($order->payment_method == "Bank Transfer" && $order->payment_status == "awaiting_payment") {
                                    $hide_products = true;
                                }
                                if ($order->status != 2 && $hide_products == false):
                                    $products = get_order_products($order->id);
                                    if (!empty($products)):?>
                                        <option disabled><?= formatted_date($order->created_at); ?></option>
                                        <?php foreach ($products as $product):
                                            if (!in_array($product->id, $active_refund_request_ids)):?>
                                                <option value="<?= $product->id; ?>">#<?= $order->order_number; ?>&nbsp;-&nbsp;<?= html_escape($product->product_title); ?></option>
                                            <?php endif;
                                        endforeach;
                                    endif;
                                endif;
                            endforeach;
                        endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?= trans("refund_reason_explain"); ?></label>
                    <textarea name="message" class="form-control" aria-hidden="true" required><?= old('message'); ?></textarea>
                </div>
                <div class="form-group text-right m-0">
                    <button type="submit" class="btn btn-md btn-custom"><?= trans("submit"); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

