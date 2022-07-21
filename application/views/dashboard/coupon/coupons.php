<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-sm-12">
        <?php $this->load->view('dashboard/includes/_messages'); ?>
    </div>
</div>
<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?= html_escape($title); ?></h3>
        </div>
        <div class="right">
            <a href="<?= generate_dash_url("add_coupon"); ?>" class="btn btn-success btn-add-new">
                <i class="fa fa-plus"></i>&nbsp;&nbsp;<?= trans("add_coupon"); ?>
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" role="grid">
                        <thead>
                        <tr role="row">
                            <th><?= trans("coupon_code"); ?></th>
                            <th><?= trans("discount_rate"); ?></th>
                            <th><?= trans("number_of_coupons"); ?></th>
                            <th><?= trans("expiry_date"); ?></th>
                            <th><?= trans("status"); ?></th>
                            <th><?= trans("date"); ?></th>
                            <th class="max-width-120"><?= trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($coupons)):
                            foreach ($coupons as $item): ?>
                                <tr>
                                    <td><?= html_escape($item->coupon_code); ?></td>
                                    <td><?= $item->discount_rate; ?>%</td>
                                    <td><?= $item->coupon_count; ?>&nbsp;<small class="text-danger">(<?= trans("used"); ?>:&nbsp;<b><?= get_used_coupons_count($item->coupon_code); ?></b>)</small></td>
                                    <td><?= formatted_date($item->expiry_date); ?>&nbsp;<span class="text-danger"></td>
                                    <td>
                                        <?php if (date('Y-m-d H:i:s') > $item->expiry_date): ?>
                                            <label class="label label-danger"><?php echo trans("expired"); ?></label>
                                        <?php else: ?>
                                            <label class="label label-success"><?php echo trans("active"); ?></label>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= formatted_date($item->created_at); ?></td>
                                    <td style="width: 120px;">
                                        <div class="btn-group btn-group-option">
                                            <a href="<?php echo generate_dash_url("edit_coupon") . "/" . $item->id; ?>" class="btn btn-sm btn-default btn-edit" data-toggle="tooltip" title="<?= trans('edit'); ?>"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-default btn-delete" data-toggle="tooltip" title="<?= trans('delete'); ?>" onclick='delete_item("dashboard_controller/delete_coupon_post","<?= $item->id; ?>","<?= trans("confirm_delete"); ?>");'><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (empty($coupons)): ?>
                    <p class="text-center">
                        <?= trans("no_records_found"); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?php if (!empty($coupons)): ?>
                    <div class="number-of-entries">
                        <span><?= trans("number_of_entries"); ?>:</span>&nbsp;&nbsp;<strong><?= $num_rows; ?></strong>
                    </div>
                <?php endif; ?>
                <div class="table-pagination">
                    <?= $this->pagination->create_links(); ?>
                </div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div>

<!-- Modal -->
<?php if (!empty($quote_requests)):
    foreach ($quote_requests as $quote_request):
        $quote_product = get_product($quote_request->product_id); ?>
        <div class="modal fade" id="modalSubmitQuote<?= $quote_request->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-custom">
                    <!-- form start -->
                    <?= form_open('submit-quote-post'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title"><?= trans("submit_a_quote"); ?></h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true"><i class="icon-close"></i> </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" class="form-control" value="<?= $quote_request->id; ?>">

                        <div class="form-group">
                            <label class="control-label"><?= trans('price'); ?></label>
                            <div class="input-group">
                                <span class="input-group-addon"><?= $this->default_currency->symbol; ?></span>
                                <input type="hidden" name="currency" value="<?= $this->payment_settings->default_currency; ?>">
                                <input type="text" name="price" aria-describedby="basic-addon1" class="form-control form-input price-input validate-price-input" data-item-id="<?= $quote_request->id; ?>" data-product-quantity="<?= $quote_request->product_quantity; ?>"
                                       placeholder="<?= $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <p class="calculated-price">
                                <strong><?= trans("unit_price"); ?> (<?= $this->default_currency->symbol; ?>):&nbsp;&nbsp;
                                    <span id="unit_price_<?= $quote_request->id; ?>" class="earned-price">
                                        <?= number_format(0, 2, '.', ''); ?>
                                    </span>
                                </strong><br>
                                <strong><?= trans("you_will_earn"); ?> (<?= $this->default_currency->symbol; ?>):&nbsp;&nbsp;
                                    <span id="earned_price_<?= $quote_request->id; ?>" class="earned-price">
                                        <?php $earned_price = $quote_product->price - (($quote_product->price * $this->general_settings->commission_rate) / 100);
                                        $earned_price = number_format($earned_price, 2, '.', '');
                                        echo get_price($earned_price, 'input'); ?>
                                    </span>
                                </strong>&nbsp;&nbsp;&nbsp;
                                <small> (<?= trans("commission_rate"); ?>:&nbsp;&nbsp;<?= $this->general_settings->commission_rate; ?>%)</small>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-default" data-dismiss="modal"><?= trans("close"); ?></button>
                        <button type="submit" class="btn btn-md btn-success"><?= trans("submit"); ?></button>
                    </div>
                    <?= form_close(); ?><!-- form end -->
                </div>
            </div>
        </div>
    <?php endforeach;
endif; ?>


<script>
    //calculate product earned value
    var thousands_separator = '<?= $this->thousands_separator; ?>';
    var commission_rate = '<?= $this->general_settings->commission_rate; ?>';
    $(document).on("input keyup paste change", ".price-input", function () {
        var input_val = $(this).val();
        var data_item_id = $(this).attr('data-item-id');
        var data_product_quantity = $(this).attr('data-product-quantity');
        input_val = input_val.replace(',', '.');
        var price = parseFloat(input_val);
        commission_rate = parseInt(commission_rate);
        //calculate earned price
        if (!Number.isNaN(price)) {
            var earned_price = price - ((price * commission_rate) / 100);
            earned_price = earned_price.toFixed(2);
            if (thousands_separator == ',') {
                earned_price = earned_price.replace('.', ',');
            }
        } else {
            earned_price = '0' + thousands_separator + '00';
        }

        //calculate unit price
        if (!Number.isNaN(price)) {
            var unit_price = price / data_product_quantity;
            unit_price = unit_price.toFixed(2);
            if (thousands_separator == ',') {
                unit_price = unit_price.replace('.', ',');
            }
        } else {
            unit_price = '0' + thousands_separator + '00';
        }

        $("#earned_price_" + data_item_id).html(earned_price);
        $("#unit_price_" + data_item_id).html(unit_price);
    });

    $(document).on("click", ".btn_submit_quote", function () {
        $('.modal-title').text("<?= trans("submit_a_quote"); ?>");
    });
    $(document).on("click", ".btn_update_quote", function () {
        $('.modal-title').text("<?= trans("update_quote"); ?>");
    });
</script>

<?php if (!empty($this->session->userdata('mds_send_email_data'))): ?>
    <script>
        $(document).ready(function () {
            var data = JSON.parse(<?= json_encode($this->session->userdata("mds_send_email_data"));?>);
            if (data) {
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                data["sys_lang_id"] = sys_lang_id;
                $.ajax({
                    type: "POST",
                    url: base_url + "mds-send-email-post",
                    data: data,
                    success: function (response) {
                    }
                });
            }
        });
    </script>
<?php endif;
$this->session->unset_userdata('mds_send_email_data'); ?>

