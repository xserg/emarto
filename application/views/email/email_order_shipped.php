<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('email/_header', ['title' => trans("your_order_shipped")]); ?>
<table role="presentation" class="main">
    <?php if (!empty($order)): ?>
        <tr>
            <td class="wrapper">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <h1 style="text-decoration: none; font-size: 24px;line-height: 28px;font-weight: bold"><?php echo trans("your_order_shipped"); ?></h1>
                            <div class="mailcontent" style="line-height: 26px;font-size: 14px;">
                                <h2 style="margin-bottom: 10px; font-size: 16px;font-weight: 600;"><?php echo trans("order_information"); ?></h2>
                                <p style="color: #555;">
                                    <?php echo trans("order"); ?>:&nbsp;#<?php echo $order->order_number; ?><br>
                                    <?php echo trans("payment_status"); ?>:&nbsp;<?php echo trans($order->payment_status); ?><br>
                                    <?php echo trans("payment_method"); ?>:&nbsp;<?= get_payment_method($order->payment_method); ?><br>
                                    <?php echo trans("date"); ?>:&nbsp;<?php echo formatted_date($order->created_at); ?><br>
                                </p>
                            </div>
                            <?php if (!empty($order_product)): ?>
                                <br>
                                <p>
                                <h2 style="margin-bottom: 10px; font-size: 16px;font-weight: 600;"><?php echo trans("shipping"); ?></h2>
                                <?= trans("tracking_code"); ?>:&nbsp;<?= $order_product->shipping_tracking_number; ?><br>
                                <?= trans("shipping_slug"); ?>:&nbsp;<?= $order_product->shipping_slug; ?><br>
                                </p>
                            <?php endif; ?>
                            <h3 style="margin-bottom: 10px;font-size: 16px;font-weight: 600;border-bottom: 1px solid #d1d1d1;padding-bottom: 5px; margin-top: 45px;"><?php echo trans("shipped_product"); ?></h3>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="text-align: left" class="table-products">
                                <tr>
                                    <th style="padding: 10px 0; border-bottom: 2px solid #ddd;"> </th>
                                    <th style="padding: 10px 0; border-bottom: 2px solid #ddd;"><?php echo trans("product"); ?></th>
                                    <th style="padding: 10px 0; border-bottom: 2px solid #ddd;"><?php echo trans("unit_price"); ?></th>
                                    <th style="padding: 10px 0; border-bottom: 2px solid #ddd;"><?php echo trans("quantity"); ?></th>
                                    <th style="padding: 10px 0; border-bottom: 2px solid #ddd;"><?php echo trans("vat"); ?></th>
                                    <th style="padding: 10px 0; border-bottom: 2px solid #ddd;"><?php echo trans("total"); ?></th>
                                </tr>
                                <?php if (!empty($order_product)): ?>
                                    <tr>
                                        <td>
                                            <div class="left">
                                                <div class="img-table">
                                                    <a href="<?php echo generate_product_url_by_slug($item->product_slug); ?>" target="_blank">
                                                        <img src="<?php echo get_product_image($item->product_id, 'image_small'); ?>" data-src="" alt="" class="img-thumbnail"/>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 40%; padding: 15px 0; border-bottom: 1px solid #ddd;"><?php echo $order_product->product_title; ?></td>
                                        <td style="padding: 10px 2px; border-bottom: 1px solid #ddd;"><?php echo price_formatted($order_product->product_unit_price, $order_product->product_currency); ?></td>
                                        <td style="padding: 10px 2px; border-bottom: 1px solid #ddd;"><?php echo $order_product->product_quantity; ?></td>
                                        <?php if (!empty($order->price_vat)): ?>
                                            <td style="padding: 10px 2px; border-bottom: 1px solid #ddd;">
                                                <?php if (!empty($order_product->product_vat)): ?>
                                                    <?php echo price_formatted($order_product->product_vat, $order_product->product_currency); ?>&nbsp;(<?php echo $order_product->product_vat_rate; ?>%)
                                                <?php endif; ?>
                                            </td>
                                        <?php else: ?>
                                            <td style="padding: 10px 2px; border-bottom: 1px solid #ddd;">-</td>
                                        <?php endif; ?>
                                        <td style="padding: 10px 2px; border-bottom: 1px solid #ddd;"><?php echo price_formatted($order_product->product_total_price, $order_product->product_currency); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%; text-align: right;margin-top: 40px;">
                                <tr>
                                    <td style="width: 70%"><?php echo trans("subtotal"); ?></td>
                                    <td style="width: 30%;padding-right: 15px;font-weight: 600;"><?php echo price_formatted($order->price_subtotal, $order->price_currency); ?></td>
                                </tr>
                                <?php if (!empty($order->price_vat)): ?>
                                    <tr>
                                        <td style="width: 70%"><?php echo trans("vat"); ?></td>
                                        <td style="width: 30%;padding-right: 15px;font-weight: 600;"><?php echo price_formatted($order->price_vat, $order->price_currency); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td style="width: 70%"><?php echo trans("shipping"); ?></td>
                                    <td style="width: 30%;padding-right: 15px;font-weight: 600;"><?php echo price_formatted($order->price_shipping, $order->price_currency); ?></td>
                                </tr>
                                <?php if ($order->coupon_discount > 0): ?>
                                    <tr>
                                        <td style="width: 70%"><?php echo trans("coupon"); ?>&nbsp;&nbsp;[<?= html_escape($order->coupon_code); ?>]</td>
                                        <td style="width: 30%;padding-right: 15px;font-weight: 600;">-<?php echo price_formatted($order->coupon_discount, $order->price_currency); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <?php $price_second_currency = "";
                                    $transaction = $this->transaction_model->get_transaction_by_order_id($order->id);
                                    if (!empty($transaction) && $transaction->currency != $order->price_currency):
                                        $price_second_currency = price_currency_format($transaction->payment_amount, $transaction->currency);
                                    endif; ?>
                                    <td style="width: 70%;font-weight: bold"><?php echo trans("total"); ?></td>
                                    <td style="width: 30%;padding-right: 15px;font-weight: 600;">
                                        <?php echo price_formatted($order->price_total, $order->price_currency);
                                        if (!empty($price_second_currency)):?>
                                            <br><span style="font-weight: 400;white-space: nowrap;">(<?= trans("paid"); ?>:&nbsp;<?= $price_second_currency; ?>&nbsp;<?= $transaction->currency; ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <br>
                            <p style="color: #555;">

                            </p>
                            <p style='text-align: center;margin-top: 40px;'>
                                <a href="<?php echo generate_url("order_details") . '/' . $order->order_number; ?>" style='font-size: 14px;text-decoration: none;padding: 14px 40px;background-color: #09b1ba;color: #ffffff !important; border-radius: 3px;'>
                                    <?php echo trans("see_order_details"); ?>
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php endif; ?>
</table>
<?php $this->load->view('email/_footer'); ?>
