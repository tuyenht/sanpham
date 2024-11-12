<div class="row">
    <div class="col-sm-12 title-section">
        <h3><?= trans('newsletter'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= trans('users'); ?>&nbsp;(<?= countItems($users); ?>)</h3>
            </div>
            <div class="box-body">
                <div class="tableFixHead">
                    <table class="table table-users" style="max-width: 100%;">
                        <thead>
                        <tr>
                            <th style="width: 50px;"><input type="checkbox" id="check_all_users"></th>
                            <th style="width: 50px;"><?= trans("id"); ?></th>
                            <th><?= trans("username"); ?></th>
                            <th><?= trans("email"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($users)):
                            foreach ($users as $item): ?>
                                <tr>
                                    <td><input type="checkbox" name="user_email" class="checkbox-user-email" value="<?= $item->id; ?>"></td>
                                    <td><?= $item->id; ?></td>
                                    <td style="max-width: 200px;"><?= esc(getUsername($item)); ?></td>
                                    <td style="max-width: 200px;"><?= $item->email; ?></td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer">
                <button type="button" data-receiver-type="user" class="btn btn-lg btn-block btn-info btn-submit-emails"><?= trans("send_email"); ?>&nbsp;&nbsp;<i class="fa fa-send"></i></button>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= trans('subscribers'); ?>&nbsp;(<?= countItems($subscribers); ?>)</h3>
            </div>
            <div class="box-body">
                <?php if (empty($subscribers)): ?>
                    <p class="text-muted"><?= trans("no_records_found"); ?></p>
                <?php else: ?>
                    <div class="tableFixHead">
                        <table class="table table-subscribers">
                            <thead>
                            <tr>
                                <th width="20"><input type="checkbox" id="check_all_subscribers"></th>
                                <th style="width: 50px;"><?= trans("id"); ?></th>
                                <th><?= trans("email"); ?></th>
                                <th><?= trans("options"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($subscribers)):
                                foreach ($subscribers as $item): ?>
                                    <tr>
                                        <td><input type="checkbox" name="subscriber_email" class="checkbox-subscriber-email" value="<?= $item->id; ?>"></td>
                                        <td><?= $item->id; ?></td>
                                        <td><?= $item->email; ?></td>
                                        <td><a href="javascript:void(0)" onclick="deleteItem('Admin/deleteNewsletterPost','<?= $item->id; ?>','<?= trans("confirm_delete", true); ?>');" class="text-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;<?= trans('delete'); ?></a></td>
                                    </tr>
                                <?php endforeach;
                            endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <button type="button" data-receiver-type="subscriber" class="btn btn-lg btn-block btn-info btn-submit-emails"><?= trans("send_email"); ?>&nbsp;&nbsp;<i class="fa fa-send"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= trans('settings'); ?></h3>
            </div>
            <form action="<?= base_url('Admin/newsletterSettingsPost'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="box-body">
                    <div class="form-group">
                        <label><?= trans("status"); ?></label>
                        <?= formRadio('newsletter_status', 1, 0, trans("enable"), trans("disable"), $generalSettings->newsletter_status); ?>
                    </div>
                    <div class="form-group">
                        <label><?= trans("newsletter_popup"); ?></label>
                        <?= formRadio('newsletter_popup', 1, 0, trans("enable"), trans("disable"), $generalSettings->newsletter_popup); ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?= trans("image"); ?></label>
                        <div style="margin-bottom: 10px;">
                            <img src="<?= base_url($generalSettings->newsletter_image); ?>" alt="" style="max-width: 300px; max-height: 300px;">
                        </div>
                        <div class="display-block">
                            <a class='btn btn-success btn-sm btn-file-upload'>
                                <?= trans('select_image'); ?>
                                <input type="file" name="file" size="40" accept=".jpg, .jpeg, .webp, .png" onchange="$('#upload-file-info').html($(this).val().replace(/.*[\/\\]/, ''));">
                            </a>
                            (.jpg, .jpeg, .webp, .png)
                        </div>
                        <span class='label label-info' id="upload-file-info"></span>
                    </div>
                </div>
                <div class="box-footer text-right">
                    <button type="submit" name="submit" value="general" class="btn btn-primary"><?= trans('save_changes'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<form action="<?= base_url('Admin/newsletterSendEmail'); ?>" method="post" id="formSubmitEmails">
    <?= csrf_field(); ?>
    <input type="hidden" name="email_receiver_type" id="input_email_receiver_type" value="">
    <input type="hidden" name="selected_ids" id="input_selected_ids" value="">
</form>

<script>
    $("#check_all_users").click(function () {
        $('.table-users input:checkbox').not(this).prop('checked', this.checked);
    });
    $("#check_all_subscribers").click(function () {
        $('.table-subscribers input:checkbox').not(this).prop('checked', this.checked);
    });

    $(document).on('click', '.btn-submit-emails', function () {
        var emailReceiverType = $(this).attr("data-receiver-type");
        var form = document.getElementById("formSubmitEmails");
        var checkboxClassName = '';

        if (emailReceiverType == 'user') {
            checkboxClassName = 'checkbox-user-email';
        } else if (emailReceiverType == 'subscriber') {
            checkboxClassName = 'checkbox-subscriber-email';
        }

        var arrayIds = [];
        $('.' + checkboxClassName + ':checkbox:checked').each(function () {
            arrayIds.push(parseInt($(this).val()));
        });

        $('#input_email_receiver_type').val(emailReceiverType);
        $('#input_selected_ids').val(arrayIds.toString());
        form.submit();
    });
</script>

<style>
    .tableFixHead {
        overflow: auto;
        max-height: 600px !important;
    }

    .tableFixHead thead th {
        position: sticky;
        top: 0;
        z-index: 1;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        padding: 8px 16px;
    }

    th {
        background: #fff !important;
    }
</style>