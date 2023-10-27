<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo $title; ?> - <a href="<?php echo admin_url(); ?>translations/<?php echo $language->id; ?>"><?php echo $language->name; ?></a></h3>
        </div>
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
                    <?php //$this->load->view('admin/language/_filter_translations'); ?>
                    <?php echo form_open('language_controller/add_translations_post'); ?>
                    <input type="hidden" name="lang_id" value="<?php echo $language->id; ?>">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr role="row">
                            <th><?php echo trans('id'); ?></th>
                            <th><?php echo trans('phrase'); ?></th>
                            <th><?php echo trans('label'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <tr class="tr-phrase">
                                <td style="width: 50px;"><?php echo $i; ?></td>
                                <td style="width: 40%;"><input type="text" class="form-control" name="id[<?php echo $i; ?>]"></td>
                                <td style="width: 60%;"><input type="text" name="value[<?php echo $i; ?>]" data-label="<?php echo $item->id; ?>" data-lang="<?php echo $item->lang_id; ?>" class="form-control"></td>
                            </tr>
                        <?php endfor; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary pull-right">
                        <?php echo trans("save_changes"); ?>
                    </button>
                    <?php echo form_close(); ?>
                </div>
                

                <div class="col-sm-12 table-ft">
                    <div class="row">
                        <div class="pull-right">
                            <?php echo $this->pagination->create_links(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($language->text_direction == "rtl"): ?>
    <link href="<?php echo base_url(); ?>assets/admin/css/rtl.css" rel="stylesheet"/>
<?php endif; ?>
