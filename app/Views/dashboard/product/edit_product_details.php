<link rel="stylesheet" href="<?= base_url('assets/vendor/datepicker/css/bootstrap-datepicker.standalone.css'); ?>">
<script src="<?= base_url('assets/vendor/datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/vendor/plyr/plyr.css'); ?>">
<script src="<?= base_url('assets/vendor/plyr/plyr.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/plyr/plyr.polyfilled.min.js'); ?>"></script>

<?php $backUrl = generateDashUrl('edit_product') . '/' . $product->id; ?>
<script type="text/javascript">
    history.pushState(null, null, '<?= $_SERVER["REQUEST_URI"]; ?>');
    window.addEventListener('popstate', function (event) {
        window.location.assign('<?= $backUrl; ?>');
    });
</script>

<?php if ($product->is_draft == 1): ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="wizard-product">
                <h1 class="product-form-title"><?= trans("add_product"); ?></h1>
                <div class="row">
                    <div class="col-md-12 wizard-add-product">
                        <ul class="wizard-progress">
                            <li class="active" id="step_general"><strong><?= trans("general_information"); ?></strong></li>
                            <li class="active" id="step_dedails"><strong><?= trans("details"); ?></strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif;
if ($showShippingOptionsWarning): ?>
    <div class="alert alert-danger alert-large">
        <i class="fa fa-warning"></i>&nbsp;&nbsp;<?= trans("vendor_no_shipping_option_warning"); ?>&nbsp;<a href="<?= generateDashUrl('shipping_settings'); ?>" target="_blank" class="link-blue"><?= trans("shipping_settings"); ?></a>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-add-product">
            <div class="box-body">
                <?php if ($product->is_draft != 1): ?>
                    <h1 class="product-form-title"><?= trans("edit_product"); ?></h1>
                <?php endif; ?>
                <div class="alert-message-lg aler-product-form">
                    <?= view('dashboard/includes/_messages'); ?>
                </div>
                <?php if ($product->product_type == 'digital'):
                    if ($product->listing_type != 'license_key'): ?>
                        <div class="row-custom">
                            <?= view('dashboard/product/_digital_file_upload'); ?>
                        </div>
                    <?php endif;
                endif; ?>
                <form action="<?= base_url('edit-product-details-post'); ?>" method="post" id="form_product_details" class="validate_price validate_terms" onkeypress="return event.keyCode != 13;">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id" value="<?= $product->id; ?>">
                    <input type="hidden" name="digital_file_download_link" id="inputDigitalFileLink" value="<?= esc($product->digital_file_download_link); ?>">
                    <?php if ($product->product_type == 'digital'): ?>
                        <?= view('dashboard/product/license/_license_keys', ['product' => $product, 'licenseKeys' => $licenseKeys]); ?>
                        <?php if ($product->listing_type != 'license_key'): ?>
                            <div class="form-box">
                                <div class="form-box-head">
                                    <h4 class="title"><?= trans('multiple_sale'); ?><br></h4>
                                </div>
                                <div class="form-box-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="multiple_sale" value="1" id="multiple_sale_1" class="custom-control-input" <?= $product->multiple_sale == 1 ? 'checked' : ''; ?> required>
                                                <label for="multiple_sale_1" class="custom-control-label"><?= trans('multiple_sale_option_1'); ?></label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 listing_ordinary_listing">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="multiple_sale" value="0" id="multiple_sale_2" class="custom-control-input" <?= $product->multiple_sale != 1 ? 'checked' : ''; ?> required>
                                                <label for="multiple_sale_2" class="custom-control-label"><?= trans('multiple_sale_option_2'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box-head">
                                    <h4 class="title">
                                        <?= trans('files_included'); ?><br>
                                        <small><?= trans("files_included_ext"); ?></small>
                                    </h4>
                                </div>
                                <div class="form-box-body">
                                    <input type="text" name="files_included" class="form-control form-input" value="<?= esc($product->files_included); ?>" placeholder="<?= trans("files_included"); ?>" required maxlength="250">
                                </div>
                            </div>
                        <?php endif;
                    endif;
                    if ($product->listing_type == 'license_key'): ?>
                        <input type="hidden" name="multiple_sale" value="1">
                    <?php endif;
                    if (!empty($customFields) || ($productSettings->brand_status == 1 && !empty($brands) && $product->product_type != 'digital')): ?>
                        <div class="form-box">
                            <div class="form-box-head">
                                <h4 class="title"><?= trans('details'); ?></h4>
                            </div>
                            <div class="form-box-body">
                                <div class="form-group">
                                    <?php if ($productSettings->brand_status == 1 && !empty($brands)): ?>
                                        <div class="row">
                                            <div class="col-md-12 col-lg-6 col-custom-field m-b-30">
                                                <label><?= trans("brands"); ?><?= $productSettings->is_brand_optional == 1 ? '(' . trans("optional") . ')' : ''; ?></label>
                                                <div class="custom-options-container" style="border: 0 !important;">
                                                    <div class="row">
                                                        <select name="brand_id" class="select2 form-control" <?= $productSettings->is_brand_optional != 1 ? 'required' : ''; ?>>
                                                            <option value=""><?= trans('select'); ?></option>
                                                            <?php foreach ($brands as $brand): ?>
                                                                <option value="<?= esc($brand->id); ?>" <?= $product->brand_id == $brand->id ? 'selected' : ''; ?>><?= esc(getBrandName($brand->name_data, selectedLangId())); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="row" id="custom_fields_container">
                                        <?= view('dashboard/product/_custom_fields', ['customFields' => $customFields, 'product' => $product]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-box">
                                <div class="row">
                                    <?php if ($product->product_type != 'digital' && $product->listing_type != 'ordinary_listing'): ?>
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-box-head">
                                                <h4 class="title"><?= trans('stock'); ?></h4>
                                            </div>
                                            <div class="form-box-body">
                                                <div class="form-group">
                                                    <input type="number" name="stock" class="form-control form-input" min="0" max="999999999" value="<?= $product->stock; ?>" placeholder="<?= trans("stock"); ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="stock" value="<?= $product->stock; ?>">
                                    <?php endif;
                                    if ($product->listing_type == 'ordinary_listing' || $productSettings->marketplace_sku == 1): ?>
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-box-head">
                                                <h4 class="title">
                                                    <?= trans('sku'); ?>&nbsp;<small style="width: auto;display: inline-block;margin-bottom: 0;margin-top:0;">(<?= trans("product_code"); ?>)</small>
                                                </h4>
                                            </div>
                                            <div class="form-box-body">
                                                <div class="form-group">
                                                    <div class="position-relative">
                                                        <input type="text" name="sku" id="input_sku" class="form-control form-input" value="<?= $product->sku; ?>" placeholder="<?= trans("sku"); ?>&nbsp;(<?= trans("optional"); ?>)" maxlength="90">
                                                        <button type="button" class="btn btn-default btn-generate-sku" onclick="$('#input_sku').val(generateUniqueString());"><?= trans("generate"); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="sku" value="">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= view('dashboard/product/_price');
                    if (($product->product_type == 'physical' && $productSettings->physical_demo_url == 1) || ($product->product_type == 'digital' && $productSettings->digital_demo_url == 1)): ?>
                        <div class="form-box">
                            <div class="form-box-head">
                                <h4 class="title">
                                    <?= trans('demo_url'); ?><br>
                                    <small><?= trans("demo_url_exp"); ?></small>
                                </h4>
                            </div>
                            <div class="form-box-body">
                                <input type="text" name="demo_url" class="form-control form-input" value="<?= esc($product->demo_url); ?>" placeholder="<?= trans("demo_url"); ?>" maxlength="990">
                            </div>
                        </div>
                    <?php endif;
                    $showVideoPrev = false;
                    $showAudioPrev = false;
                    if (($product->product_type == 'physical' && $productSettings->physical_video_preview == 1) || ($product->product_type == 'digital' && $productSettings->digital_video_preview == 1)):
                        $showVideoPrev = true;
                    endif;
                    if (($product->product_type == 'physical' && $productSettings->physical_audio_preview == 1) || ($product->product_type == 'digital' && $productSettings->digital_audio_preview == 1)):
                        $showAudioPrev = true;
                    endif; ?>
                    <?php if ($showVideoPrev || $showAudioPrev): ?>
                        <div class="form-box form-box-preview">
                            <div class="form-box-head">
                                <h4 class="title"><?= trans('preview'); ?></h4>
                            </div>
                            <div class="form-box-body">
                                <div class="row">
                                    <?php if ($showVideoPrev): ?>
                                        <div class="col-sm-12 col-md-6 m-b-30">
                                            <label><?= trans("video_preview"); ?></label>
                                            <small>(<?= trans("video_preview_exp"); ?>)</small>
                                            <?= view('dashboard/product/_video_upload', ['productVideo' => $productVideo]); ?>
                                        </div>
                                    <?php endif;
                                    if ($showAudioPrev):?>
                                        <div class="col-sm-12 col-md-6 m-b-30">
                                            <label><?= trans("audio_preview"); ?></label>
                                            <small>(<?= trans("audio_preview_exp"); ?>)</small>
                                            <?= view('dashboard/product/_audio_upload', ['productAudio' => $productAudio]); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif;
                    if ($product->listing_type == 'ordinary_listing' && $productSettings->classified_external_link == 1): ?>
                        <div class="form-box">
                            <div class="form-box-head">
                                <h4 class="title">
                                    <?= trans('external_link'); ?><br>
                                    <small><?= trans("external_link_exp"); ?></small>
                                </h4>
                            </div>
                            <div class="form-box-body">
                                <input type="text" name="external_link" class="form-control form-input" value="<?= esc($product->external_link); ?>" placeholder="<?= trans("external_link"); ?>" maxlength="990">
                            </div>
                        </div>
                    <?php endif;
                    if ($productSettings->marketplace_variations == 1 && $product->listing_type != 'ordinary_listing'): ?>
                        <div class="form-box">
                            <div class="form-box-head">
                                <h4 class="title">
                                    <?= trans('variations'); ?>
                                    <small><?= trans("variations_exp"); ?></small>
                                </h4>
                            </div>
                            <div class="form-box-body">
                                <div class="row">
                                    <div id="response_product_variations" class="col-sm-12">
                                        <?= view('dashboard/product/variation/_response_variations', ['productVariations' => $productVariations]); ?>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-md btn-info btn-variation m-b-5" data-toggle="modal" data-target="#addVariationModal"><?= trans("add_variation"); ?></button>
                                        <button type="button" class="btn btn-md btn-secondary btn-variation m-b-5" data-toggle="modal" data-target="#variationModalSelect"><?= trans("select_existing_variation"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif;
                    $isLocationEnabled = true;
                    if ($product->listing_type == 'ordinary_listing') {
                        if ($productSettings->classified_product_location != 1) {
                            $isLocationEnabled = false;
                        }
                    } else {
                        if ($productSettings->marketplace_product_location != 1) {
                            $isLocationEnabled = false;
                        }
                    }
                    if ($product->product_type != 'digital' && $isLocationEnabled == true):?>
                        <div class="form-box">
                            <div class="form-box-head">
                                <h4 class="title">
                                    <?= trans('location'); ?> &nbsp;(<?= trans("optional"); ?>)
                                    <small><?= trans("product_location_exp"); ?></small>
                                </h4>
                            </div>
                            <div class="form-box-body">
                                <div class="row">
                                    <?php $countries = getCountries();
                                    $countryId = $product->country_id;
                                    $states = !empty($countryId) ? getStatesByCountry($countryId) : array();
                                    $cities = !empty($product->state_id) ? getCitiesByState($product->state_id) : array(); ?>
                                    <?php if ($generalSettings->single_country_mode != 1): ?>
                                        <div class="col-md-12 col-lg-2 m-b-15">
                                            <select id="select_countries" name="country_id" class="select2 form-control" onchange="getStates(this.value);">
                                                <option value=""><?= trans('country'); ?></option>
                                                <?php if (!empty($countries)):
                                                    foreach ($countries as $item):
                                                        if ($item->status == 1):?>
                                                            <option value="<?= $item->id; ?>" <?= !empty($countryId) && $item->id == $countryId ? 'selected' : ''; ?>><?= esc($item->name); ?></option>
                                                        <?php endif;
                                                    endforeach;
                                                endif; ?>
                                            </select>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="country_id" value="<?= $generalSettings->single_country_id; ?>">
                                        <?php $countryId = $generalSettings->single_country_id;
                                        $states = getStatesByCountry($countryId);
                                    endif; ?>
                                    <div id="get_states_container" class="col-md-12 col-lg-2 m-b-15 <?= !empty($countryId) ? '' : 'display-none'; ?>">
                                        <select id="select_states" name="state_id" class="select2 form-control" onchange="getCities(this.value);">
                                            <option value=""><?= trans('state'); ?></option>
                                            <?php if (!empty($states)):
                                                foreach ($states as $item): ?>
                                                    <option value="<?= $item->id; ?>" <?= !empty($product->state_id) && $item->id == $product->state_id ? 'selected' : ''; ?>><?= esc($item->name); ?></option>
                                                <?php endforeach;
                                            endif; ?>
                                        </select>
                                    </div>
                                    <div id="get_cities_container" class="col-md-12 col-lg-2 m-b-15 <?= empty($cities) ? 'display-none' : ''; ?>">
                                        <select id="select_cities" name="city_id" class="select2 form-control">
                                            <option value=""><?= trans('city'); ?></option>
                                            <?php if (!empty($cities)):
                                                foreach ($cities as $item):?>
                                                    <option value="<?= $item->id; ?>" <?= !empty($product->city_id) && $item->id == $product->city_id ? 'selected' : ''; ?>><?= esc($item->name); ?></option>
                                                <?php endforeach;
                                            endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 col-lg-4 m-b-15">
                                        <input type="text" name="address" id="address_input" class="form-control form-input" value="<?= !empty($product->address) ? esc($product->address) : ''; ?>" placeholder="<?= trans("address") ?>" maxlength="499">
                                    </div>
                                    <div class="col-md-12 col-lg-2 m-b-15">
                                        <input type="text" name="zip_code" id="zip_code_input" class="form-control form-input" value="<?= !empty($product->zip_code) ? esc($product->zip_code) : ''; ?>" placeholder="<?= trans("zip_code") ?>" maxlength="49">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (empty($shippingClasses) && empty($shippingDeliveryTimes)) {
                        $shippingStatus = 0;
                    }
                    if ($shippingStatus == 1): ?>
                        <div class="form-box">
                            <div class="form-box-head">
                                <h4 class="title"><?= trans('shipping'); ?>&nbsp;(<?= trans("optional"); ?>)</h4>
                            </div>
                            <div class="row">
                                <?php if (!empty($shippingClasses)): ?>
                                    <div class="col-sm-12 col-md-6">
                                        <label><?= trans("shipping_class"); ?></label>
                                        <select name="shipping_class_id" class="form-control custom-select">
                                            <option value=""><?= trans("select"); ?></option>
                                            <?php if (!empty($shippingClasses)): ?>
                                                <?php foreach ($shippingClasses as $shippingClass): ?>
                                                    <option value="<?= $shippingClass->id; ?>" <?= $product->shipping_class_id == $shippingClass->id ? 'selected' : ''; ?>><?= @parseSerializedNameArray($shippingClass->name_array, selectedLangId()); ?></option>
                                                <?php endforeach;
                                            endif; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <div class="col-sm-12 col-md-6">
                                    <label><?= trans('delivery_time'); ?></label>
                                    <select name="shipping_delivery_time_id" class="form-control custom-select">
                                        <option value=""><?= trans("select"); ?></option>
                                        <?php if (!empty($shippingDeliveryTimes)): ?>
                                            <?php foreach ($shippingDeliveryTimes as $deliveryTime): ?>
                                                <option value="<?= $deliveryTime->id; ?>" <?= $product->shipping_delivery_time_id == $deliveryTime->id ? 'selected' : ''; ?>><?= @parseSerializedOptionArray($deliveryTime->option_array, selectedLangId()); ?></option>
                                            <?php endforeach;
                                        endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-sm-12 text-left m-t-15 m-b-15">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-validate-input">
                                    <?php if ($product->is_draft == 1): ?>
                                        <input type="checkbox" class="custom-control-input" name="terms_conditions" id="terms_conditions" value="1" required>
                                    <?php else: ?>
                                        <input type="checkbox" class="custom-control-input" name="terms_conditions" id="terms_conditions" value="1" checked>
                                    <?php endif; ?>
                                    <label for="terms_conditions" class="custom-control-label"><?= trans("terms_conditions_exp"); ?>&nbsp;
                                        <?php $pageTerms = getPageByDefaultName('terms_conditions', selectedLangId());
                                        if (!empty($pageTerms)): ?>
                                            <a href="<?= generateUrl($pageTerms->page_default_name); ?>" class="link-terms" target="_blank"><strong><?= esc($pageTerms->title); ?></strong></a>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group m-t-15 buttons-product-form">
                                <a href="<?= generateDashUrl('edit_product') . '/' . $product->id; ?>" class="btn btn-lg btn-dark pull-left"><i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;<?= trans("back"); ?></a>
                                <?php if ($product->is_draft == 1): ?>
                                    <button type="submit" name="submit" value="submit" class="btn btn-lg btn-success btn-form-product-details pull-right"><i class="fa fa-check"></i>&nbsp;&nbsp;<?= trans("submit"); ?></button>
                                    <button type="submit" name="submit" value="save_as_draft" class="btn btn-lg btn-secondary btn-form-product-details m-r-10 pull-right"><i class="fa fa-file"></i>&nbsp;&nbsp;<?= trans("save_as_draft"); ?></button>
                                <?php else: ?>
                                    <button type="submit" name="submit" value="save_changes" class="btn btn-lg btn-success btn-form-product-details pull-right"><i class="fa fa-check"></i>&nbsp;&nbsp;<?= trans("save_changes"); ?></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= view('dashboard/product/variation/_form_variations'); ?>

<style>
    .custom-options-container {
        border: 1px solid #f1f1f1;
        padding: 15px;
        max-height: 300px;
        overflow-y: auto;
    }
</style>

<script>
    const player = new Plyr('#player');
    $(document).ajaxStop(function () {
        const player = new Plyr('#player');
    });
    const audio_player = new Plyr('#audio_player');
    $(document).ajaxStop(function () {
        const player = new Plyr('#audio_player');
    });
    $(window).on("load", function () {
        $(".li-dm-media-preview").css("visibility", "visible");
    });

    $.fn.datepicker.dates['en'] = {
        days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        daysMin: ["<?= substr(trans("monday", true), 0, 3); ?>",
            "<?= substr(trans("tuesday", true), 0, 3); ?>",
            "<?= substr(trans("wednesday", true), 0, 3); ?>",
            "<?= substr(trans("thursday", true), 0, 3); ?>",
            "<?= substr(trans("friday", true), 0, 3); ?>",
            "<?= substr(trans("saturday", true), 0, 3); ?>",
            "<?= substr(trans("sunday", true), 0, 3); ?>"],
        months: ['<?= trans("january", true); ?>',
            "<?= trans("february", true); ?>",
            "<?= trans("march", true); ?>",
            "<?= trans("april", true); ?>",
            "<?= trans("may", true); ?>",
            "<?= trans("june", true); ?>",
            "<?= trans("july", true); ?>",
            "<?= trans("august", true); ?>",
            "<?= trans("september", true); ?>",
            "<?= trans("october", true); ?>",
            "<?= trans("november", true); ?>",
            "<?= trans("december", true); ?>"],
        monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        today: "Today",
        clear: "Clear",
        format: "mm/dd/yyyy",
        titleFormat: "MM yyyy",
        weekStart: 0
    };
    $('.datepicker').datepicker({
        language: 'en'
    });
    //validate checkbox
    $(document).on("click", ".btn-form-product-details ", function () {
        $('.checkbox-options-container').each(function () {
            var fieldId = $(this).attr('data-custom-field-id');
            var element = "#checkbox_options_container_" + fieldId + " .required-checkbox";
            if (!$(element).is(':checked')) {
                $(element).prop('required', true);
            } else {
                $(element).prop('required', false);
            }
        });
    });

    $(document).ready(function () {
        $('.validate_terms').submit(function (e) {
            $('.custom-control-validate-input p').remove();
            if (!$('.custom-control-validate-input input').is(":checked")) {
                e.preventDefault();
                $('.custom-control-validate-input').addClass('custom-control-validate-error');
                $('.custom-control-validate-input').append("<p class='text-danger'>" + MdsConfig.textAcceptTerms + "</p>");
            } else {
                $('.custom-control-validate-input').removeClass('custom-control-validate-error');
            }
        });
    });

    window.addEventListener('keydown', function (e) {
        if (e.keyIdentifier == 'U+000A' || e.keyIdentifier == 'Enter' || e.keyCode == 13) {
            if (e.target.nodeName == 'INPUT' && e.target.type == 'text') {
                e.preventDefault();
                return false;
            }
        }
    }, true);
</script>
