<link rel="stylesheet" href="<?= base_url('assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.css'); ?>">
<script src="<?= base_url('assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
<div class="row">
    <div class="col-sm-12 col-xs-12 col-md-5">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans('visual_settings'); ?></h3>
                </div>
            </div>
            <form action="<?= base_url('Admin/visualSettingsPost'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="box-body">
                    <div class="form-group">
                        <label><?= trans("color"); ?></label>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="clrpicker">
                                    <input type="text" name="site_color" value="<?= esc($generalSettings->site_color); ?>" class="form-control" style="width: 148px;" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?= trans('logo'); ?></label>
                        <div style="margin-bottom: 10px;">
                            <img src="<?= getLogo(); ?>" alt="logo" style="max-width: 160px; max-height: 160px;">
                        </div>
                        <div class="display-block">
                            <a class='btn btn-success btn-sm btn-file-upload'>
                                <?= trans('select_logo'); ?>
                                <input type="file" name="logo" size="40" accept=".png, .jpg, .jpeg, .gif, .svg" onchange="$('#upload-file-info1').html($(this).val().replace(/.*[\/\\]/, ''));">
                            </a>
                            (.png, .jpg, .jpeg, .gif, .svg)
                        </div>
                        <span class='label label-info' id="upload-file-info1"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?= trans('logo_email'); ?></label>
                        <div style="margin-bottom: 10px;">
                            <img src="<?= getLogoEmail(); ?>" alt="logo" style="max-width: 160px; max-height: 160px;">
                        </div>
                        <div class="display-block">
                            <a class='btn btn-success btn-sm btn-file-upload'>
                                <?= trans('select_logo'); ?>
                                <input type="file" name="logo_email" size="40" accept=".png, .jpg, .jpeg" onchange="$('#upload-file-info3').html($(this).val().replace(/.*[\/\\]/, ''));">
                            </a>
                            (.png, .jpg, .jpeg)
                        </div>
                        <span class='label label-info' id="upload-file-info3"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?= trans('favicon'); ?> (16x16px)</label>
                        <div style="margin-bottom: 10px;">
                            <img src="<?= getFavicon(); ?>" alt="favicon" style="max-width: 100px; max-height: 100px;">
                        </div>
                        <div class="display-block">
                            <a class='btn btn-success btn-sm btn-file-upload'>
                                <?= trans('select_favicon'); ?>
                                <input type="file" name="favicon" size="40" accept=".png" onchange="$('#upload-file-info2').html($(this).val().replace(/.*[\/\\]/, ''));">
                            </a>
                            (.png)
                        </div>
                        <span class='label label-info' id="upload-file-info2"></span>
                    </div>
                    <div class="form-group">
                        <label class="m-b-10"><?= trans("logo_size"); ?></label>
                        <div class="row" style="max-width: 400px; margin-bottom: 15px;">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label"><?= trans("width"); ?>&nbsp;(px)</label>
                                <input type="number" name="logo_width" class="form-control" value="<?= getLogoSize($generalSettings, 'width'); ?>" min="10" max="300">
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label"><?= trans("height"); ?>&nbsp;(px)</label>
                                <input type="number" name="logo_height" class="form-control" value="<?= getLogoSize($generalSettings, 'height'); ?>" min="10" max="300">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><?= trans('save_changes'); ?></button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-12 col-xs-12 col-md-7">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans('watermark'); ?></h3>
                </div>
            </div>
            <form action="<?= base_url('Admin/updateWatermarkSettingsPost'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label"><?= trans('watermark_text'); ?></label>
                        <input type="text" class="form-control" name="watermark_text" value="<?= esc($generalSettings->watermark_text); ?>" placeholder="<?= trans('watermark_text'); ?>">
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label"><?= trans('font_size'); ?></label>
                                <input type="number" class="form-control" name="watermark_font_size" value="<?= esc($generalSettings->watermark_font_size); ?>" min="1" max="500" placeholder="<?= trans('font_size'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label"><?= trans('vertical_alignment'); ?></label>
                                <select class="form-control" name="watermark_vrt_alignment" required>
                                    <option value="top" <?= $generalSettings->watermark_vrt_alignment == 'top' ? 'selected' : ''; ?>><?= trans('top'); ?></option>
                                    <option value="center" <?= $generalSettings->watermark_vrt_alignment == 'center' ? 'selected' : ''; ?>><?= trans('center'); ?></option>
                                    <option value="bottom" <?= $generalSettings->watermark_vrt_alignment == 'bottom' ? 'selected' : ''; ?>><?= trans('bottom'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label"><?= trans('horizontal_alignment'); ?></label>
                                <select class="form-control" name="watermark_hor_alignment" required>
                                    <option value="left" <?= $generalSettings->watermark_hor_alignment == 'left' ? 'selected' : ''; ?>><?= trans('left'); ?></option>
                                    <option value="center" <?= $generalSettings->watermark_hor_alignment == 'center' ? 'selected' : ''; ?>><?= trans('center'); ?></option>
                                    <option value="right" <?= $generalSettings->watermark_hor_alignment == 'right' ? 'selected' : ''; ?>><?= trans('right'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?= trans("add_watermark_product_images"); ?></label>
                        <?= formRadio('watermark_product_images', 1, 0, trans("yes"), trans("no"), $generalSettings->watermark_product_images); ?>
                    </div>
                    <div class="form-group">
                        <label><?= trans("add_watermark_blog_images"); ?></label>
                        <?= formRadio('watermark_blog_images', 1, 0, trans("yes"), trans("no"), $generalSettings->watermark_blog_images); ?>
                    </div>
                    <div class="form-group">
                        <label><?= trans("add_watermark_thumbnail_images"); ?></label>
                        <?= formRadio('watermark_thumbnail_images', 1, 0, trans("yes"), trans("no"), $generalSettings->watermark_thumbnail_images); ?>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><?= trans('save_changes'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .form-group {
        margin-bottom: 30px !important;
    }

    .colorpicker {
        border: 0 !important;
    }

    .colorpicker.colorpicker-inline {
        padding: 0 !important;
    }

    .colorpicker-bar > div {
        color: transparent !important;
    }

    .colorpicker-element input {
        margin-bottom: 10px;
    }
</style>
<script>
    $(function () {
        $('#clrpicker').colorpicker({
            popover: false,
            inline: true,
            container: '#clrpicker',
            format: 'hex'
        });
    });
</script>
