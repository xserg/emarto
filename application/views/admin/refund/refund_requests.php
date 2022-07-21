<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $title; ?></h3>
    </div><!-- /.box-header -->

    <div class="box-body">
        <div class="row">
            <!-- include message block -->
            <div class="col-sm-12">
                <?php $this->load->view('admin/includes/_messages'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <th scope="col"><?php echo trans("product"); ?></th>
                            <th scope="col"><?php echo trans("total"); ?></th>
                            <th scope="col"><?php echo trans("commission_rate"); ?></th>
                            <th scope="col"><?php echo trans("earned_amount"); ?></th>
                            <th scope="col"><?php echo trans("buyer"); ?></th>
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
                                            <a href="<?= admin_url(); ?>order-details/<?= $request->order_id; ?>" target="_blank">
                                                #<?= $request->order_number; ?>&nbsp;-&nbsp;<?= html_escape($product->product_title); ?>
                                            </a>
                                        </td>
                                        <td><?php echo price_formatted($product->product_total_price, $product->product_currency); ?></td>
                                        <td><?php echo $product->commission_rate; ?>%</td>
                                        <td>
                                            <?php $earning = $this->earnings_model->get_earning_by_order_product_id($request->order_product_id, $request->order_number);
                                            $order = get_order_by_order_number($request->order_number);
                                            if (!empty($earning) && !empty($order) && $order->payment_method != "Cash On Delivery"):
                                                echo price_formatted($earning->earned_amount, $earning->currency);
                                            else:
                                                echo trans("not_added_vendor_balance");
                                            endif; ?>
                                        </td>
                                        <td>
                                            <?php $buyer = get_user($product->buyer_id);
                                            if (!empty($buyer)): ?>
                                                <a href="<?php echo generate_profile_url($buyer->slug); ?>" target="_blank" class="font-600"><?php echo html_escape($buyer->username); ?></a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php $seller = get_user($product->seller_id);
                                            if (!empty($seller)): ?>
                                                <a href="<?php echo generate_profile_url($seller->slug); ?>" target="_blank" class="font-600"><?php echo html_escape($seller->username); ?></a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($request->status == 1): ?>
                                                <label class="label label-success"><?php echo trans("approved"); ?></label>
                                            <?php elseif ($request->status == 2): ?>
                                                <label class="label label-danger"><?php echo trans("declined"); ?></label>
                                            <?php else: ?>
                                                <label class="label label-default"><?php echo trans("order_processing"); ?></label>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo time_ago($request->updated_at); ?></td>
                                        <td><?php echo formatted_date($request->created_at); ?></td>
                                        <td>
                                            <?php echo form_open('order_admin_controller/refund_product_post'); ?>
                                            <input type="hidden" name="order_product_id" value="<?= $request->order_product_id; ?>">
                                            <?php if ($request->is_completed == 1): ?>
                                                <a href="<?= admin_url(); ?>refund-requests/<?= $request->id; ?>" class="btn btn-sm btn-default btn-edit"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;<?php echo trans("details"); ?></a>
                                                <label class="label label-success"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;<?php echo trans("refund_approved"); ?></label>
                                            <?php else: ?>
                                                <div class="btn-group btn-group-option">
                                                    <a href="<?= admin_url(); ?>refund-requests/<?= $request->id; ?>" class="btn btn-sm btn-default btn-edit"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;<?php echo trans("details"); ?></a>
                                                    <button type="submit" class="btn btn-sm btn-default btn-edit"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;<?php echo trans("approve_refund"); ?></button>
                                                </div>
                                            <?php endif; ?>
                                            <?php echo form_close(); ?>
                                        </td>
                                    </tr>
                                <?php endif;
                            endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if (empty($refund_requests)): ?>
                        <p class="text-center">
                            <?php echo trans("no_records_found"); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-12">
                <?php if (!empty($refund_requests)): ?>
                    <div class="number-of-entries">
                        <span><?= trans("number_of_entries"); ?>:</span>&nbsp;&nbsp;<strong><?= $num_rows; ?></strong>
                    </div>
                <?php endif; ?>
                <div class="table-pagination">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div>

<div class="row">
    <div class="col-sm-9">
        <div class="alert alert-info alert-large m-t-10">
            <strong><?php echo trans("warning"); ?>!</strong>&nbsp;&nbsp;<?php echo trans("refund_admin_complete_exp"); ?>
        </div>
    </div>
</div>
