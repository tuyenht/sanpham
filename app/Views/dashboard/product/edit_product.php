<?php if ($product->is_draft == 1): ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="wizard-product">
                <h1 class="product-form-title"><?= esc($title); ?></h1>
                <div class="row">
                    <div class="col-md-12 wizard-add-product">
                        <ul class="wizard-progress">
                            <li class="active" id="step_general"><strong><?= trans("general_information"); ?></strong></li>
                            <li id="step_dedails"><strong><?= trans("details"); ?></strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-add-product">
                <div class="box-body">
                    <?php if ($product->is_draft != 1): ?>
                        <h1 class="product-form-title"><?= esc($title); ?></h1>
                    <?php endif; ?>
                    <div class="alert-message-lg">
                        <?= view('dashboard/includes/_messages'); ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 clearfix m-b-30">
                            <label class="control-label"><?= trans("images"); ?></label>
                            <?= view('dashboard/product/_image_update'); ?>
                        </div>
                    </div>
                    <form action="<?= base_url('edit-product-post'); ?>" method="post" id="form_validate" class="validate_price">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="id" value="<?= $product->id; ?>">
                        <input type="hidden" name="back_url" value="<?= getCurrentUrl(); ?>">
                        <div class="form-group">
                            <label class="control-label"><?= trans('product_type'); ?></label>
                            <div class="row">
                                <?php if ($generalSettings->physical_products_system == 1): ?>
                                    <div class="col-12 col-sm-6 col-custom-field">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="product_type" value="physical" id="product_type_1" class="custom-control-input" <?= $product->product_type == 'physical' ? 'checked' : ''; ?> required>
                                            <label for="product_type_1" class="custom-control-label"><?= trans('physical'); ?></label>
                                            <p class="form-element-exp"><?= trans('physical_exp'); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($generalSettings->digital_products_system == 1): ?>
                                    <div class="col-12 col-sm-6 col-custom-field">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="product_type" value="digital" id="product_type_2" class="custom-control-input" <?= $product->product_type == 'digital' ? 'checked' : ''; ?> required>
                                            <label for="product_type_2" class="custom-control-label"><?= trans('digital'); ?></label>
                                            <p class="form-element-exp"><?= trans('digital_exp'); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?= trans('listing_type'); ?></label>
                            <div class="row">
                                <?php if ($generalSettings->marketplace_system == 1): ?>
                                    <div class="col-12 col-sm-6 col-custom-field listing_sell_on_site">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="listing_type" value="sell_on_site" id="listing_type_1" class="custom-control-input" <?= $product->listing_type == 'sell_on_site' ? 'checked' : ''; ?> required>
                                            <label for="listing_type_1" class="custom-control-label"><?= trans('add_product_for_sale'); ?></label>
                                            <p class="form-element-exp"><?= trans('add_product_for_sale_exp'); ?></p>
                                        </div>
                                    </div>
                                <?php endif;
                                if ($generalSettings->classified_ads_system == 1 && $generalSettings->physical_products_system == 1): ?>
                                    <div class="col-12 col-sm-6 col-custom-field listing_ordinary_listing" <?= $product->product_type == 'digital' ? 'style="display:none;"' : ''; ?>>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="listing_type" value="ordinary_listing" id="listing_type_2" class="custom-control-input" <?= $product->listing_type == 'ordinary_listing' ? 'checked' : ''; ?> required>
                                            <label for="listing_type_2" class="custom-control-label"><?= trans('add_product_services_listing'); ?></label>
                                            <p class="form-element-exp"><?= trans('add_product_services_listing_exp'); ?></p>
                                        </div>
                                    </div>
                                <?php endif;
                                if ($generalSettings->bidding_system == 1 && $generalSettings->physical_products_system == 1): ?>
                                    <div class="col-12 col-sm-6 col-custom-field listing_bidding" <?= $product->product_type == 'digital' ? 'style="display:none;"' : ''; ?>>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="listing_type" value="bidding" id="listing_type_3" class="custom-control-input" <?= $product->listing_type == 'bidding' ? 'checked' : ''; ?> required>
                                            <label for="listing_type_3" class="custom-control-label"><?= trans('add_product_get_price_requests'); ?></label>
                                            <p class="form-element-exp"><?= trans('add_product_get_price_requests_exp'); ?></p>
                                        </div>
                                    </div>
                                <?php endif;
                                if ($generalSettings->digital_products_system == 1 && $generalSettings->selling_license_keys_system == 1): ?>
                                    <div class="col-12 col-sm-6 col-custom-field listing_license_keys" <?= $product->product_type == 'physical' ? 'style="display:none;"' : ''; ?>>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="listing_type" value="license_key" id="listing_type_4" class="custom-control-input" <?= $product->listing_type == 'license_key' ? 'checked' : ''; ?> required>
                                            <label for="listing_type_4" class="custom-control-label"><?= trans('add_product_sell_license_keys'); ?></label>
                                            <p class="form-element-exp"><?= trans('add_product_sell_license_keys_exp'); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group form-group-category">
                            <label class="control-label"><?= trans("category"); ?></label>
                            <div id="category_select_container">
                                <?php if (!empty($category)):
                                    $parentArray = array();
                                    if (!empty($category->parent_tree)) {
                                        $parentArray = explode(',', $category->parent_tree);
                                    }
                                    array_push($parentArray, $category->id);
                                    $level = 1;
                                    foreach ($parentArray as $parentId):
                                        $parentItem = getCategory($parentId);
                                        if (!empty($parentItem)):
                                            $subCategories = getSubCategoriesByParentId($parentItem->parent_id);;
                                            if (!empty($subCategories)): ?>
                                                <div class="subcategory-select-container m-t-5" data-level="<?= $level; ?>">
                                                    <select name="category_id[]" class="select2 form-control subcategory-select <?= $level == 1 ? ' category-select-first' : ''; ?>" data-level="<?= $level; ?>" onchange="getSubCategoriesDashboard(this.value, <?= $level; ?>, <?= selectedLangId(); ?>);" <?= $level == 1 ? 'required' : ''; ?>>
                                                        <option value=""><?= trans('select_category'); ?></option>
                                                        <?php foreach ($subCategories as $subCategory): ?>
                                                            <option value="<?= $subCategory->id; ?>" <?= $subCategory->id == $parentItem->id ? 'selected' : ''; ?>><?= getCategoryName($subCategory, $activeLang->id); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            <?php endif;
                                        endif;
                                        $level++;
                                    endforeach;
                                    if (!empty($category)):
                                        $subCategories = getSubCategoriesByParentId($category->id);
                                        if (!empty($subCategories)): ?>
                                            <div class="subcategory-select-container m-t-5" data-level="<?= $level; ?>">
                                                <select name="category_id[]" class="select2 form-control subcategory-select" data-level="<?= $level; ?>" onchange="getSubCategoriesDashboard(this.value, <?= $level; ?>, <?= selectedLangId(); ?>);">
                                                    <option value=""><?= trans('select_category'); ?></option>
                                                    <?php foreach ($subCategories as $subCategory): ?>
                                                        <option value="<?= $subCategory->id; ?>"> <?= getCategoryName($subCategory, $activeLang->id); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php endif;
                                    endif;
                                else:?>
                                    <select id="categories" name="category_id[]" class="form-control custom-select m-0" onchange="getSubCategoriesDashboard(this.value, 1, <?= selectedLangId(); ?>);" required>
                                        <option value=""><?= trans('select_category'); ?></option>
                                        <?php if (!empty($parentCategories)):
                                            foreach ($parentCategories as $item): ?>
                                                <option value="<?= esc($item->id); ?>"><?= getCategoryName($item, $activeLang->id); ?></option>
                                            <?php endforeach;
                                        endif; ?>
                                    </select>
                                    <div id="category_select_container"></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (isAdmin()): ?>
                            <div class="form-group">
                                <label class="control-label"><?= trans('slug'); ?></label>
                                <input type="text" name="slug" class="form-control form-input" value="<?= esc($product->slug); ?>" placeholder="<?= trans("slug"); ?>" maxlength="200">
                            </div>
                        <?php endif; ?>

                        <?php if ($product->is_draft != 1 && $product->status == 1): ?>
                            <div class="form-group">
                                <label class="control-label"><?= trans('status'); ?></label>
                                <select name="is_sold" class="form-control custom-select" required>
                                    <option value="0" <?= $product->is_sold != 1 ? 'selected' : ''; ?>><?= trans('active'); ?></option>
                                    <option value="1" <?= $product->is_sold == 1 ? 'selected' : ''; ?>><?= trans('sold'); ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?= trans('visibility'); ?></label>
                                <select name="visibility" class="form-control custom-select" required>
                                    <option value="1" <?= $product->visibility == 1 ? 'selected' : ''; ?>><?= trans('visible'); ?></option>
                                    <option value="0" <?= $product->visibility == 0 ? 'selected' : ''; ?>><?= trans('hidden'); ?></option>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="panel-group panel-group-product">
                            <?php $languages = array();
                            array_push($languages, $activeLang);
                            if (!empty($activeLanguages)):
                                foreach ($activeLanguages as $language):
                                    if (!empty($language->id != selectedLangId())) {
                                        array_push($languages, $language);
                                    }
                                endforeach;
                            endif;
                            if (!empty($languages)):
                                foreach ($languages as $language):
                                    $productDetails = getProductDetails($product->id, $language->id, false); ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" href="#collapse_<?= $language->id; ?>"><?= trans("details"); ?><?= countItems($activeLanguages) > 1 ? ':&nbsp;' . esc($language->name) : ''; ?>&nbsp;<?= selectedLangId() != $language->id ? '(' . trans("optional") . ')' : ''; ?><i class="fa fa-caret-down pull-right"></i></a>
                                            </h4>
                                        </div>
                                        <div id="collapse_<?= $language->id; ?>" class="panel-collapse collapse <?= selectedLangId() == $language->id ? 'in' : ''; ?>">
                                            <div class="panel-body">
                                                <div class="form-group m-b-15">
                                                    <label class="control-label"><?= trans("title"); ?></label>
                                                    <input type="text" name="title_<?= $language->id; ?>" value="<?= !empty($productDetails) ? esc($productDetails->title) : ''; ?>" class="form-control form-input" placeholder="<?= trans("title"); ?>" <?= selectedLangId() == $language->id ? 'required' : ''; ?> maxlength="499">
                                                </div>
                                                <div class="form-group m-b-15">
                                                    <label class="control-label"><?= trans("short_description"); ?></label>
                                                    <input type="text" name="short_description_<?= $language->id; ?>" value="<?= !empty($productDetails) ? esc($productDetails->short_description) : ''; ?>" class="form-control form-input" placeholder="<?= trans("short_description"); ?>" maxlength="499">
                                                </div>
                                                <div class="form-group m-b-15">
                                                    <label class="control-label"><?= trans("tags"); ?>&nbsp;<small>(<?= trans("tags_product_exp"); ?>)</small></label>
                                                    <input type="text" name="tags_<?= $language->id; ?>" value="<?= esc(getProductTagsString($product, $language->id)); ?>" class="tags-input form-control" placeholder="<?= trans("type_tag"); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label"><?= trans("description"); ?></label>
                                                    <div class="row">
                                                        <div class="col-sm-12 m-b-5">
                                                            <button type="button" id="btn_add_image_editor" class="btn btn-sm btn-info" data-editor-id="editor_<?= $language->id; ?>" data-toggle="modal" data-target="#fileManagerModal"><i class="icon-image"></i>&nbsp;&nbsp;<?= trans("add_image"); ?></button>
                                                        </div>
                                                    </div>
                                                    <textarea name="description_<?= $language->id; ?>" id="editor_<?= $language->id; ?>" class="tinyMCE text-editor"><?= !empty($productDetails) ? $productDetails->description : ''; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;
                            endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 m-t-30 buttons-product-form">
                                <?php if ($product->is_draft == 1): ?>
                                    <button type="submit" class="btn btn-lg btn-success pull-right"><i class="fa fa-check"></i>&nbsp;&nbsp;<?= trans("save_and_continue"); ?></button>
                                <?php else: ?>
                                    <a href="<?= generateDashUrl('product', 'product_details') . '/' . $product->id; ?>" class="btn btn-lg btn-primary pull-right"><?= trans("edit_details"); ?>&nbsp;&nbsp;<i class="fa fa-long-arrow-right"></i> </a>
                                    <button type="submit" class="btn btn-lg btn-success pull-right m-r-10"><i class="fa fa-check"></i>&nbsp;&nbsp;<?= trans("save_changes"); ?></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?= view('dashboard/product/_product_part'); ?>