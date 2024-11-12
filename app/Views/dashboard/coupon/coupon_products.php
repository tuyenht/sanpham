<?php $currentPageLink = generateDashUrl('coupon_products') . '/' . $coupon->id;
$categoryIds = $coupon->category_ids;
$arraySelectedCategories = [];
if (!empty($categoryIds)) {
    $arraySelectedCategories = explode(',', $categoryIds);
} ?>
<div class="row">
    <div class="col-sm-12">
        <?= view('dashboard/includes/_messages'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 title-section">
        <h3><?= trans("select_products"); ?><br><span class="text-muted" style="font-size: 13px;"><?= trans("coupon"); ?>:&nbsp;<?= esc($coupon->coupon_code); ?></span></h3>
    </div>
    <div class="col-md-12 col-lg-4">
        <div class="box">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans("categories"); ?></h3>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive" style="max-height: 925px; overflow-y: scroll">
                            <table class="table table-bordered table-striped table-products" role="grid">
                                <thead>
                                <tr role="row">
                                    <th><?= trans('category'); ?></th>
                                    <th class="max-width-120"><?= trans('select_for_coupon'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($vendorCategories)):
                                    foreach ($vendorCategories as $item): ?>
                                        <tr>
                                            <td>
                                                <?php $category = new stdClass();
                                                $category->name = $item['name'];
                                                echo getCategoryName($category, selectedLangId()); ?></td>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="<?= $item['id']; ?>" id="chCategory<?= $item['id']; ?>" class="custom-control-input checkbox-category" <?= in_array($item['id'], $arraySelectedCategories) ? 'checked' : ''; ?>>
                                                    <label for="chCategory<?= $item['id']; ?>" class="custom-control-label">&nbsp;</label>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($vendorCategories)): ?>
                            <p class="text-center">
                                <?= trans("no_records_found"); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-8">
        <div class="box">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans("products"); ?></h3>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <div class="row table-filter-container">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-default filter-toggle collapsed m-b-10" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false">
                                        <i class="fa fa-filter"></i>&nbsp;&nbsp;<?= trans("filter"); ?>
                                    </button>
                                    <div class="collapse navbar-collapse" id="collapseFilter">
                                        <form action="<?= $currentPageLink; ?>" method="get" id="formVendorProducts">
                                            <?php if (!empty(inputGet('st'))): ?>
                                                <input type="hidden" name="st" value="<?= strSlug(inputGet('st')); ?>">
                                            <?php endif; ?>
                                            <div class="item-table-filter">
                                                <label><?= trans('category'); ?></label>
                                                <select id="categories" name="category" class="form-control custom-select" onchange="getFilterSubCategoriesDashboard(this.value);">
                                                    <option value=""><?= trans("all"); ?></option>
                                                    <?php if (!empty($parentCategories)):
                                                        foreach ($parentCategories as $item): ?>
                                                            <option value="<?= $item->id; ?>" <?= inputGet('category', true) == $item->id ? 'selected' : ''; ?>><?= getCategoryName($item, $activeLang->id); ?></option>
                                                        <?php endforeach;
                                                    endif; ?>
                                                </select>
                                            </div>
                                            <div class="item-table-filter">
                                                <label class="control-label"><?= trans('subcategory'); ?></label>
                                                <select id="subcategories" name="subcategory" class="form-control custom-select">
                                                    <option value=""><?= trans("all"); ?></option>
                                                    <?php if (!empty(inputGet('category'))):
                                                        $subCategories = getSubCategories(inputGet('category'));
                                                        if (!empty($subCategories)):
                                                            foreach ($subCategories as $item):?>
                                                                <option value="<?= $item->id; ?>" <?= inputGet('subcategory', true) == $item->id ? 'selected' : ''; ?>><?= getCategoryName($item, $activeLang->id); ?></option>
                                                            <?php endforeach;
                                                        endif;
                                                    endif; ?>
                                                </select>
                                            </div>
                                            <div class="item-table-filter item-table-filter-large">
                                                <label><?= trans("search"); ?></label>
                                                <div class="item-table-filter-search">
                                                    <input name="q" class="form-control" placeholder="<?= trans("search"); ?>" type="search" value="<?= esc(inputGet('q')); ?>">
                                                    <button type="submit" class="btn bg-purple"><?= trans("filter"); ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-products" role="grid">
                                <thead>
                                <tr role="row">
                                    <th width="20"><?= trans('id'); ?></th>
                                    <th><?= trans('product'); ?></th>
                                    <th><?= trans('category'); ?></th>
                                    <th class="max-width-120"><?= trans('select_for_coupon'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($products)):
                                    foreach ($products as $item): ?>
                                        <tr>
                                            <td><?= esc($item->id); ?></td>
                                            <td class="td-product">
                                                <a href="<?= generateProductUrl($item); ?>" target="_blank" class="table-product-title"><?= getProductTitle($item); ?></a>
                                            </td>
                                            <td>
                                                <?php $category = new stdClass();
                                                $category->name = $item->category_name;
                                                echo getCategoryName($category, $activeLang->id); ?>
                                            </td>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="<?= $item->id; ?>" id="chProduct<?= $item->id; ?>" class="custom-control-input checkbox-product" <?= !empty($item->is_selected) ? 'checked' : ''; ?>>
                                                    <label for="chProduct<?= $item->id; ?>" class="custom-control-label">&nbsp;</label>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($products)): ?>
                            <p class="text-center">
                                <?= trans("no_records_found"); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php if (!empty($products)): ?>
                            <div class="number-of-entries">
                                <span><?= trans("number_of_entries"); ?>:</span>&nbsp;&nbsp;<strong><?= $numRows; ?></strong>
                            </div>
                        <?php endif; ?>
                        <div class="table-pagination">
                            <?= $pager->links; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.checkbox-category').change(function () {
            var action = 'delete';
            var value = $(this).val();
            if ($(this).is(':checked')) {
                action = 'add';
            }
            var data = {
                'coupon_id': "<?= $coupon->id; ?>",
                'category_id': value,
                'action': action
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Ajax/selectCouponCategoryPost',
                data: setAjaxData(data),
                success: function (response) {
                    location.reload();
                }
            });
        });
        $('.checkbox-product').change(function () {
            var action = 'delete';
            var value = $(this).val();
            if ($(this).is(':checked')) {
                action = 'add';
            }
            var data = {
                'coupon_id': "<?= $coupon->id; ?>",
                'product_id': value,
                'action': action
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Ajax/selectCouponProductPost',
                data: setAjaxData(data),
                success: function (response) {
                }
            });
        });
    });
</script>
