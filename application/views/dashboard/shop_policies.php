<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans("shop_policies"); ?></h3>
                </div>
            </div>
            <div class="box-body">
                <?php echo form_open("shop-policies-post"); ?>
                
                    
                    <div class="form-group">
                        <label></label>
                        
                            <div class="col-md-6 col-sm-12 col-custom-option">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="status_shop_policies" value="1" id="status_shop_policies_1" class="custom-control-input" <?= ($pages->status_shop_policies == 1) ? 'checked' : ''; ?>>
                                    <label for="status_shop_policies_1" class="custom-control-label"><?php echo trans("enable"); ?></label>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-custom-option">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="status_shop_policies" value="0" id="status_shop_policies_2" class="custom-control-input" <?= ($pages->status_shop_policies != 1) ? 'checked' : ''; ?>>
                                    <label for="status_shop_policies_2" class="custom-control-label"><?php echo trans("disable"); ?></label>
                                </div>
                            </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label"><?= trans("content"); ?></label>
                        <textarea name="content_shop_policies" class="tinyMCE"><?= $pages->content_shop_policies; ?></textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-md btn-success"><?= trans("save_changes") ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs-custom > .nav-tabs > li > a {
        font-size: 14px !important;
        font-weight: 600 !important;
        padding: 12px 30px;
    }

    .nav-tabs-custom > .nav-tabs > li.active {
        border-top-color: #19bb9b;
    }
</style>