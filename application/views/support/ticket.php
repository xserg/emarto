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
                            <li class="breadcrumb-item active" aria-current="page"><?= trans("submit_a_request"); ?></li>
                        </ol>
                    </nav>

                    <div class="row justify-content-center">
                        <div class="col-12 m-t-15 m-b-30">
                            <h1 class="page-title page-title-ticket"><?= trans("ticket"); ?>: #<?= $ticket->id; ?></h1>
                            <a href="<?= generate_url('help_center', 'tickets'); ?>" class="btn btn-info color-white float-right">
                                <svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#fff" class="mds-svg-icon">
                                    <path d="M384 1408q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm0-512q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm1408 416v192q0 13-9.5 22.5t-22.5 9.5h-1216q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1216q13 0 22.5 9.5t9.5 22.5zm-1408-928q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm1408 416v192q0 13-9.5 22.5t-22.5 9.5h-1216q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1216q13 0 22.5 9.5t9.5 22.5zm0-512v192q0 13-9.5 22.5t-22.5 9.5h-1216q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h1216q13 0 22.5 9.5t9.5 22.5z"/>
                                </svg>
                                <?= trans("support_tickets") ?>
                            </a>
                        </div>

                        <div class="col-12">
                            <div class="ticket-container shadow-sm">
                                <div class="new-ticket-content new-ticket-content-reply">
                                    <div class="ticket-header">
                                        <p><strong><?= trans("subject"); ?>:&nbsp;<?= html_escape($ticket->subject); ?></strong></p>
                                        <div class="row row-ticket-details">
                                            <div class="col-12 col-md-2">
                                                <strong><?= trans("status"); ?></strong>
                                                <?php if ($ticket->status == 1): ?>
                                                    <label class="badge badge-lg badge-success color-white"><?= trans("open"); ?></label>
                                                <?php elseif ($ticket->status == 2): ?>
                                                    <label class="badge badge-lg badge-warning color-white"><?= trans("responded"); ?></label>
                                                <?php elseif ($ticket->status == 3): ?>
                                                    <label class="badge badge-lg badge-secondary color-white"><?= trans("closed"); ?></label>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <strong><?= trans("date"); ?></strong>
                                                <span><?= formatted_date($ticket->created_at); ?></span>
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <strong><?= trans("last_update"); ?></strong>
                                                <span><?= time_ago($ticket->updated_at); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="ticket-buttons">
                                                <button class="btn btn-info color-white float-left" type="button" data-toggle="collapse" data-target="#collapseTicketAnswer" aria-expanded="false" aria-controls="collapseTicketAnswer">
                                                    <i class="icon-reply"></i><?= trans("reply"); ?>
                                                </button>
                                                <?php if ($ticket->status != 3): ?>
                                                    <button class="btn btn-secondary color-white float-right" type="button" onclick="close_support_ticket(<?= $ticket->id; ?>);">
                                                        <i class="icon-times"></i><?= trans("close_ticket"); ?>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="collapse " id="collapseTicketAnswer">
                                                <div class="reply-editor">
                                                    <?php echo form_open('support_controller/send_message_post'); ?>
                                                    <input type="hidden" name="ticket_id" value="<?= $ticket->id; ?>">
                                                    <div class="form-group m-0">
                                                        <label class="control-label"><?= trans("message"); ?></label>
                                                    </div>
                                                    <div class="form-group" style="min-height: 400px">
                                                        <textarea name="message" class="tinyMCEticket" aria-hidden="true"><?= old('message'); ?></textarea>
                                                    </div>

                                                    <div class="form-group m-0">
                                                        <label class="control-label"><?= trans("attachments"); ?></label>
                                                        <div class="dm-uploader-container">
                                                            <div id="drag-and-drop-zone" class="dm-uploader text-center mb-2">
                                                                <p class="dm-upload-text">
                                                                    <?= trans("drag_drop_file_here"); ?>&nbsp;<span style="text-decoration: underline; font-weight: 600;"><?= trans('browse_files'); ?>
                                                                </p>
                                                                <a class='btn btn-md dm-btn-select-files'>
                                                                    <input type="file" name="file" size="40" multiple="multiple">
                                                                </a>
                                                            </div>
                                                            <ul class="dm-uploaded-files" id="files-file"></ul>
                                                        </div>

                                                        <script type="text/html" id="files-template-file">
                                                            <li class="media">
                                                                <div class="media-body">
                                                                    <div class="progress">
                                                                        <div class="dm-progress-waiting"></div>
                                                                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </script>

                                                        <div id="response_uploaded_files" class="uploaded-files">
                                                            <?php if (!empty($this->session->userdata('ticket_attachments'))):
                                                                $filesSession = $this->session->userdata('ticket_attachments');
                                                                foreach ($filesSession as $file):
                                                                    if (!empty($file->uniqid) && !empty($file->name) && !empty($file->ticket_type) && $file->ticket_type == 'client'): ?>
                                                                        <div class="item">
                                                                            <div class="item-inner">
                                                                                <?php echo html_escape($file->name); ?>
                                                                                <a href="javascript:void(0)" onclick="delete_support_attachment('<?php echo html_escape($file->uniqid); ?>')">
                                                                                    <i class="icon-times"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif;
                                                                endforeach;
                                                            endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="text-right m-t-20">
                                                        <button type="submit" class="btn btn-md btn-custom"><?= trans("send_message"); ?></button>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ticket-content ticket-content-reset">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <ul class="list-unstyled">

                                                <?php if (!empty($subtickets)):
                                                    foreach ($subtickets as $subticket):
                                                        $user = get_user($subticket->user_id); ?>
                                                        <li class="media<?= $subticket->is_support_reply != 1 ? ' media-client' : ''; ?>">
                                                            <img class="img-profile" src="<?= get_user_avatar($user) ?>" alt="">
                                                            <div class="media-body">
                                                                <h5 class="title mt-0 mb-3">
                                                                    <a href="<?= generate_profile_url($user->slug) ?>" class="font-color" target="_blank"><?= get_shop_name($user); ?></a>
                                                                </h5>
                                                                <span class="date text-right"><?= time_ago($subticket->created_at); ?></span>
                                                                <div class="message">
                                                                    <?= $subticket->message; ?>
                                                                </div>
                                                                <?php $files = unserialize_data($subticket->attachments);
                                                                if (!empty($files)):?>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="ticket-attachments">
                                                                                <?php foreach ($files as $file):
                                                                                    echo form_open('support_controller/download_attachment'); ?>
                                                                                    <input type="hidden" name="name" value="<?= $file->orj_name; ?>">
                                                                                    <input type="hidden" name="path" value="<?= get_support_attachment_url($subticket, $file); ?>">
                                                                                    <p>
                                                                                        <button type="submit">
                                                                                            <svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#3f7cbd" class="mds-svg-icon" style="top: -1px;">
                                                                                                <path d="M1152 512v-472q22 14 36 28l408 408q14 14 28 36h-472zm-128 32q0 40 28 68t68 28h544v1056q0 40-28 68t-68 28h-1344q-40 0-68-28t-28-68v-1600q0-40 28-68t68-28h800v544z"/>
                                                                                            </svg>
                                                                                            <span><?= html_escape($file->orj_name); ?></span>
                                                                                        </button>
                                                                                    </p>
                                                                                    <?php echo form_close();
                                                                                endforeach; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </li>
                                                    <?php endforeach;
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
    </div>
</div>