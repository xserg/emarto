<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row support-admin">
    <div class="col-sm-12">
        <?php $this->load->view('admin/includes/_messages'); ?>
    </div>
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans("refund"); ?></h3>
                </div>
            </div>
            <div class="box-body">
                <div class="col-12">
                    <div class="ticket-container">
                        <div class="new-ticket-content new-ticket-content-reply">
                            <div class="ticket-header">
                                <p>
                                    <strong><?= trans("product"); ?>:&nbsp;
                                        <a href="<?= admin_url(); ?>order-details/<?= $refund_request->order_id; ?>" target="_blank">
                                            #<?= $refund_request->order_number; ?>&nbsp;-&nbsp;<?= html_escape($product->product_title); ?>
                                        </a>
                                    </strong>
                                </p>
                                <div class="row row-ticket-details">
                                    <div class="col-xs-4 col-md-2 m-b-5">
                                        <strong><?= trans("status"); ?></strong>
                                        <?php if ($refund_request->status == 1): ?>
                                            <label class="label label-success"><?php echo trans("approved"); ?></label>
                                        <?php elseif ($refund_request->status == 2): ?>
                                            <label class="label label-danger"><?php echo trans("declined"); ?></label>
                                        <?php else: ?>
                                            <label class="label label-default"><?php echo trans("order_processing"); ?></label>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-xs-4 col-md-2 m-b-5">
                                        <strong><?= trans("total"); ?></strong>
                                        <span><?php echo price_formatted($product->product_total_price, $product->product_currency); ?></span>
                                    </div>
                                    <div class="col-xs-4 col-md-2 m-b-5">
                                        <strong><?= trans("buyer"); ?></strong>
                                        <?php $buyer = get_user($product->buyer_id);
                                        if (!empty($buyer)): ?>
                                            <a href="<?php echo generate_profile_url($buyer->slug); ?>" target="_blank" class="font-600"><?php echo html_escape($buyer->username); ?></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-xs-4 col-md-2 m-b-5">
                                        <strong><?= trans("seller"); ?></strong>
                                        <?php $seller = get_user($product->seller_id);
                                        if (!empty($seller)): ?>
                                            <a href="<?php echo generate_profile_url($seller->slug); ?>" target="_blank" class="font-600"><?php echo html_escape($seller->username); ?></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-xs-4 col-md-2 m-b-5">
                                        <strong><?= trans("last_update"); ?></strong>
                                        <span><?= time_ago($refund_request->updated_at); ?></span>
                                    </div>
                                    <div class="col-xs-4 col-md-2 m-b-5">
                                        <strong><?= trans("date"); ?></strong>
                                        <span><?= formatted_date($refund_request->created_at); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ticket-content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="list-unstyled">
                                        <?php if (!empty($messages)):
                                            foreach ($messages as $message):
                                                $user = get_user($message->user_id);
                                                if (!empty($user)):?>
                                                    <li class="media">
                                                        <div class="left">
                                                            <img class="img-profile" src="<?= get_user_avatar($user) ?>" alt="">
                                                        </div>
                                                        <div class="right">
                                                            <div class="media-body">
                                                                <h5 class="title m-t-0 mb-3">
                                                                    <a href="<?= generate_profile_url($user->slug) ?>" class="font-color" target="_blank"><?= get_shop_name($user); ?></a>
                                                                </h5>
                                                                <span class="date text-right"><?= time_ago($message->created_at); ?></span>
                                                                <div class="message">
                                                                    <?= $message->message; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php endif;
                                            endforeach;
                                        endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .support-admin .ticket-content .media .media-body .message {
        font-size: 14px !important;
    }
</style>