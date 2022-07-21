<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?= html_escape($title); ?></h3>
        </div>
    </div>
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
                    <table class="table table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <th scope="col"><?php echo trans("product"); ?></th>
                            <th scope="col"><?php echo trans("total"); ?></th>
                            <th scope="col"><?php echo trans("buyer"); ?></th>
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
                                            <a href="<?= generate_dash_url("sale") . "/" . $request->order_number; ?>" target="_blank">
                                                #<?= $request->order_number; ?>&nbsp;-&nbsp;<?= html_escape($product->product_title); ?>
                                            </a>
                                        </td>
                                        <td><?php echo price_formatted($product->product_total_price, $product->product_currency); ?></td>
                                        <td>
                                            <?php $buyer = get_user($product->buyer_id);
                                            if (!empty($buyer)): ?>
                                                <a href="<?php echo generate_profile_url($buyer->slug); ?>" target="_blank" class="font-600"><?php echo html_escape($buyer->username); ?></a>
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
                                            <a href="<?= generate_dash_url("refund_requests"); ?>/<?= $request->id; ?>" class="btn btn-sm btn-default btn-details">
                                                <i class="fa fa-info-circle" aria-hidden="true"></i><?php echo trans("details"); ?></a>
                                        </td>
                                    </tr>
                                <?php endif;
                            endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (empty($refund_requests)): ?>
                    <p class="text-center">
                        <?php echo trans("no_records_found"); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
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

