<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="support">
                    <nav class="nav-breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo generate_url('help_center'); ?>"><?php echo trans("help_center"); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= trans("support_tickets"); ?></li>
                        </ol>
                    </nav>

                    <div class="row">
                        <div class="col-12 m-t-15 m-b-30">
                            <h1 class="page-title page-title-ticket"><?= trans("support_tickets"); ?></h1>
                            <a href="<?= generate_url('help_center', 'submit_request'); ?>" class="btn btn-info color-white float-right btn-submit-request">
                                <svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#fff" class="mds-svg-icon">
                                    <path d="M1600 736v192q0 40-28 68t-68 28h-416v416q0 40-28 68t-68 28h-192q-40 0-68-28t-28-68v-416h-416q-40 0-68-28t-28-68v-192q0-40 28-68t68-28h416v-416q0-40 28-68t68-28h192q40 0 68 28t28 68v416h416q40 0 68 28t28 68z"/>
                                </svg>
                                <?= trans("submit_a_request"); ?>
                            </a>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col"><?= trans("id") ?></th>
                                        <th scope="col"><?= trans("subject") ?></th>
                                        <th scope="col"><?= trans("date") ?></th>
                                        <th scope="col"><?= trans("updated") ?></th>
                                        <th scope="col"><?= trans("status") ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($tickets)):
                                        foreach ($tickets as $ticket):?>
                                            <tr class="cursor-pointer" onclick="location.href = '<?= generate_url('help_center', 'ticket'); ?>/<?= $ticket->id; ?>'">
                                                <td>#<?= $ticket->id; ?></td>
                                                <td><a href="<?= generate_url('help_center', 'ticket'); ?>/<?= $ticket->id; ?>"><?= html_escape($ticket->subject); ?></a></td>
                                                <td><?= formatted_date($ticket->created_at); ?></td>
                                                <td><?= time_ago($ticket->updated_at); ?></td>
                                                <td>
                                                    <?php if ($ticket->status == 1): ?>
                                                        <label class="badge badge-lg badge-success color-white"><?= trans("open"); ?></label>
                                                    <?php elseif ($ticket->status == 2): ?>
                                                        <label class="badge badge-lg badge-warning color-white"><?= trans("responded"); ?></label>
                                                    <?php elseif ($ticket->status == 3): ?>
                                                        <label class="badge badge-lg badge-secondary color-white"><?= trans("closed"); ?></label>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (empty($tickets)): ?>
                                <p class="text-center">
                                    <?php echo trans("no_records_found"); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>