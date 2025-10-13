<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-send-message" role="document">
        <div class="modal-content">
            <!-- form start -->
            <?php echo form_open('/order_controller/cancel_order_post', ['id' => 'form_cancel_order', 'class' => '']); ?> 
                <?php if (!empty($order->id)): ?>
                    <input type="hidden" name="order_id" value="<?= $order->id; ?>">
                <?php endif; ?>
                <div class="modal-header">
                    <h4 class="title"><?php echo trans("cancel_order_confirm"); ?></h4>
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="send-message-result"></div>
                            
                            <div class="form-group m-b-sm-0">
                                <label class="control-label"><?php echo trans("cancel_order_message"); ?></label>
                                <textarea name="message" id="message_text" class="form-control form-textarea" placeholder="<?php echo trans("write_a_message"); ?>" maxlength="80" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                    
                    <button type="submit" class="btn btn-md btn-custom btn-green"><?php echo trans("yes"); ?></button>
                    <button type="button" class="btn btn-md btn-gray" data-dismiss="modal"><?php echo trans("no"); ?></button>
                </div>
            </form>
            <!-- form end -->
        </div>
    </div>
</div>
