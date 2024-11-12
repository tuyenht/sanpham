<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?= esc($title); ?></h3>
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
                                <form action="<?= generateDashUrl('products'); ?>" method="get" id="formVendorProducts">
                                    <?php if (!empty(inputGet('st'))): ?>
                                        <input type="hidden" name="st" value="<?= strSlug(inputGet('st')); ?>">
                                    <?php endif; ?>
                                    <div class="item-table-filter">
                                        <label><?= trans('listing_type'); ?></label>
                                        <select name="listing_type" class="form-control custom-select">
                                            <option value="" selected><?= trans("all"); ?></option>
                                            <option value="sell_on_site" <?= inputGet('listing_type') == 'sell_on_site' ? 'selected' : ''; ?>><?= trans("marketplace_selling_product_on_the_site"); ?></option>
                                            <option value="ordinary_listing" <?= inputGet('listing_type') == 'ordinary_listing' ? 'selected' : ''; ?>><?= trans("classified_ads_adding_product_as_listing"); ?></option>
                                            <option value="bidding" <?= inputGet('listing_type') == 'bidding' ? 'selected' : ''; ?>><?= trans("bidding_system_request_quote"); ?></option>
                                            <option value="license_key" <?= inputGet('listing_type') == 'license_key' ? 'selected' : ''; ?>><?= trans("selling_license_keys"); ?></option>
                                        </select>
                                    </div>
                                    <div class="item-table-filter">
                                        <label><?= trans('product_type'); ?></label>
                                        <select name="product_type" class="form-control custom-select">
                                            <option value="" selected><?= trans("all"); ?></option>
                                            <option value="physical" <?= inputGet('product_type') == 'physical' ? 'selected' : ''; ?>><?= trans("physical"); ?></option>
                                            <option value="digital" <?= inputGet('product_type') == 'digital' ? 'selected' : ''; ?>><?= trans("digital"); ?></option>
                                        </select>
                                    </div>
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
                                    <div class="item-table-filter">
                                        <label><?= trans('stock'); ?></label>
                                        <select name="stock" class="form-control custom-select">
                                            <option value="" selected><?= trans("all"); ?></option>
                                            <option value="in_stock" <?= inputGet("stock") == 'in_stock' ? 'selected' : ''; ?>><?= trans("in_stock"); ?></option>
                                            <option value="out_of_stock" <?= inputGet("stock") == 'out_of_stock' ? 'selected' : ''; ?>><?= trans("out_of_stock"); ?></option>
                                        </select>
                                    </div>
                                    <div class="item-table-filter item-table-filter-large">
                                        <label><?= trans("search"); ?></label>
                                        <div class="item-table-filter-search">
                                            <input name="q" class="form-control" placeholder="<?= trans("search"); ?>" type="search" value="<?= esc(inputGet('q')); ?>">
                                            <button type="submit" class="btn bg-purple"><?= trans("filter"); ?></button>
                                            <div class="btn-group table-export">
                                                <button type="button" class="btn btn-default dropdown-toggle btn-table-export" data-toggle="dropdown"><?= trans("export"); ?>&nbsp;&nbsp;<i class="fa fa-caret-down"></i></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <button type="button" class="btn-export-data" data-export-form="formVendorProducts" data-export-type="vendor_products" data-export-file-type="csv" data-section="vn">CSV</button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="btn-export-data" data-export-form="formVendorProducts" data-export-type="vendor_products" data-export-file-type="xml" data-section="vn">XML</button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="btn-export-data" data-export-form="formVendorProducts" data-export-type="vendor_products" data-export-file-type="excel" data-section="vn"><?= trans("excel"); ?>&nbsp;(.xlsx)</button>
                                                    </li>
                                                </ul>
                                            </div>
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
                            <th><?= trans('sku'); ?></th>
                            <th><?= trans('product_type'); ?></th>
                            <th><?= trans('category'); ?></th>
                            <?php if (!empty($generalSettings->promoted_products)): ?>
                                <th><?= trans('purchased_plan'); ?></th>
                            <?php endif; ?>
                            <th><?= trans('price'); ?></th>
                            <th><?= trans("stock") . '/' . trans("status"); ?></th>
                            <th><?= trans('page_views'); ?></th>
                            <th><?= trans('updated'); ?></th>
                            <th><?= trans('date'); ?></th>
                            <th class="max-width-120"><?= trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($products)):
                            foreach ($products as $item): ?>
                                <tr>
                                    <td><?= esc($item->id); ?></td>
                                    <td class="td-product">
                                        <?php if ($item->is_promoted == 1): ?>
                                            <label class="label label-success"><?= trans("featured"); ?></label>
                                        <?php endif; ?>
                                        <div class="img-table">
                                            <a href="<?= generateProductUrl($item); ?>" target="_blank">
                                                <img src="<?= getProductItemImage($item); ?>" data-src="" alt="" class="lazyload img-responsive post-image"/>
                                            </a>
                                        </div>
                                        <a href="<?= generateProductUrl($item); ?>" target="_blank" class="table-product-title"><?= getProductTitle($item); ?></a>
                                        <?php if ($generalSettings->affiliate_status == 1 && $generalSettings->affiliate_type == 'seller_based' && user()->vendor_affiliate_status == 2 && $item->is_affiliate == 1): ?>
                                            <div class="m-t-5"><span class="label label-primary"><?= trans("affiliate_program"); ?></span></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($item->sku); ?></td>
                                    <td><?= trans($item->product_type); ?></td>
                                    <td>
                                        <?php $category = new stdClass();
                                        $category->name = $item->category_name;
                                        echo getCategoryName($category, $activeLang->id); ?>
                                    </td>
                                    <?php if (!empty($generalSettings->promoted_products)): ?>
                                        <td>
                                            <?php if ($item->is_draft != 1):
                                                if ($item->is_promoted == 1 && $item->promote_plan != 'none'):
                                                    echo esc($item->promote_plan);
                                                else: ?>
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalPricing" onclick="$('.pricing_product_id').val(<?= $item->id; ?>);"><i class="fa fa-plus"></i>&nbsp;<?= trans("promote"); ?></button>
                                                <?php endif;
                                            endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td>
                                        <?php if (!empty($item->price_discounted)): ?>
                                            <span><?= priceFormatted($item->price_discounted, $item->currency); ?></span>
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="white-space-nowrap">
                                        <div class="m-b-5"><?= getProductStockStatus($item); ?></div>
                                        <?php if (!empty($productListStatus) && $productListStatus == 'pending'):
                                            if ($item->is_rejected == 1): ?>
                                                <div class="m-b-5">
                                                    <label class="label label-danger"><?= trans("rejected"); ?></label>
                                                </div>
                                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modalReason<?= $item->id; ?>"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;<?= trans("show_reason"); ?></button>
                                                <div id="modalReason<?= $item->id; ?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                                                                <h4 class="modal-title"><?= trans("reason"); ?></h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="m-t-10"><?= esc($item->reject_reason); ?></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal"><?= trans("close"); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <label class="label label-default"><?= trans("pending"); ?></label>
                                            <?php endif;
                                        endif; ?>
                                    </td>
                                    <td><?= $item->pageviews; ?></td>
                                    <td><?= !empty($item->updated_at) ? timeAgo($item->updated_at) : ''; ?></td>
                                    <td><?= formatDate($item->created_at); ?></td>
                                    <td style="width: 120px;">
                                        <form action="<?= base_url('Dashboard/addRemoveAffiliateProductPost'); ?>" method="post">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="product_id" value="<?= $item->id; ?>">
                                            <div class="btn-group btn-group-option">
                                                <a href="<?= generateDashUrl('edit_product') . '/' . $item->id; ?>" class="btn btn-sm btn-default btn-edit" data-toggle="tooltip" title="<?= trans('edit'); ?>"><i class="fa fa-edit"></i></a>
                                                <?php if ($generalSettings->affiliate_status == 1 && $generalSettings->affiliate_type == 'seller_based' && user()->vendor_affiliate_status == 2): ?>
                                                    <button type="submit" class="btn btn-sm btn-default btn-edit" data-toggle="tooltip" title="<?= trans('affiliate_program'); ?>"><i class="fa fa-check-square-o"></i></button>
                                                <?php endif; ?>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-default btn-delete" data-toggle="tooltip" title="<?= trans('delete'); ?>" onclick="deleteItem('Dashboard/deleteProduct','<?= $item->id; ?>','<?= trans("confirm_delete", true); ?>');"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </form>
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
<?= view('dashboard/product/_modal_promote'); ?>