<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $title; ?></h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" role="grid">
                        <div class="row table-filter-container">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default filter-toggle collapsed m-b-10" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false">
                                    <i class="fa fa-filter"></i>&nbsp;&nbsp;<?= trans("filter"); ?>
                                </button>
                                <div class="collapse navbar-collapse" id="collapseFilter">
                                    <form action="<?= adminUrl('digital-sales'); ?>" method="get" id="formFilterDigitalSales">
                                        <div class="item-table-filter" style="width: 80px; min-width: 80px;">
                                            <label><?= trans("show"); ?></label>
                                            <select name="show" class="form-control">
                                                <option value="15" <?= inputGet('show') == '15' ? 'selected' : ''; ?>>15</option>
                                                <option value="30" <?= inputGet('show') == '30' ? 'selected' : ''; ?>>30</option>
                                                <option value="60" <?= inputGet('show') == '60' ? 'selected' : ''; ?>>60</option>
                                                <option value="100" <?= inputGet('show') == '100' ? 'selected' : ''; ?>>100</option>
                                            </select>
                                        </div>
                                        <div class="item-table-filter" style="width: 320px;">
                                            <label><?= trans("search"); ?></label>
                                            <div class="item-table-filter-search">
                                                <input name="q" class="form-control" placeholder="<?= trans("purchase_code"); ?>" type="search" value="<?= esc(inputGet('q')); ?>">
                                                <button type="submit" class="btn bg-purple"><?= trans("filter"); ?></button>
                                                <div class="btn-group table-export">
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-table-export" data-toggle="dropdown"><?= trans("export"); ?>&nbsp;&nbsp;<i class="fa fa-caret-down"></i></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li>
                                                            <button type="button" class="btn-export-data" data-export-form="formFilterDigitalSales" data-export-type="digital_sales" data-export-file-type="csv">CSV</button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="btn-export-data" data-export-form="formFilterDigitalSales" data-export-type="digital_sales" data-export-file-type="xml">XML</button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="btn-export-data" data-export-form="formFilterDigitalSales" data-export-type="digital_sales" data-export-file-type="excel"><?= trans("excel"); ?>&nbsp;(.xlsx)</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <thead>
                        <tr role="row">
                            <th><?= trans('id'); ?></th>
                            <th><?= trans('order'); ?></th>
                            <th><?= trans('purchase_code'); ?></th>
                            <th><?= trans('seller'); ?></th>
                            <th><?= trans('buyer'); ?></th>
                            <th><?= trans('total'); ?></th>
                            <th><?= trans('currency'); ?></th>
                            <th><?= trans('date'); ?></th>
                            <th class="max-width-120"><?= trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($digitalSales)):
                            foreach ($digitalSales as $item): ?>
                                <tr>
                                    <td><?= $item->id; ?></td>
                                    <td style="width: 120px;">
                                        #<?= esc($item->order_number); ?>
                                    </td>
                                    <td><?= esc($item->purchase_code); ?></td>
                                    <td>
                                        <a href="<?= generateProfileUrl($item->seller_slug); ?>" target="_blank" class="table-link"><?= esc($item->seller_username); ?></a>
                                    </td>
                                    <td>
                                        <?php if ($item->buyer_id == 0): ?>
                                            <div class="table-orders-user">
                                                <img src="<?= getUserAvatar(null); ?>" alt="buyer" class="img-responsive" style="height: 30px;">
                                                <span><?= esc($item->shipping_first_name) . ' ' . esc($item->shipping_last_name); ?></span>
                                                <label class="label bg-olive" style="position: absolute;top: 0; left: 0;"><?= trans("guest"); ?></label>
                                            </div>
                                        <?php else: ?>
                                            <a href="<?= generateProfileUrl($item->buyer_slug); ?>" target="_blank" class="table-link"><?= esc($item->buyer_username); ?></a>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= priceFormatted($item->price, $item->currency); ?></strong></td>
                                    <td><?= $item->currency; ?></td>
                                    <td><?= formatDate($item->purchase_date); ?></td>
                                    <td>
                                        <input type="hidden" name="id" value="<?= $item->id; ?>">
                                        <div class="dropdown">
                                            <button class="btn bg-purple dropdown-toggle btn-select-option" type="button" data-toggle="dropdown"><?= trans('select_option'); ?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu options-dropdown" style="min-width: 190px;">
                                                <li>
                                                    <a href="javascript:void(0)" onclick="deleteItem('OrderAdmin/deleteDigitalSalePost','<?= $item->id; ?>','<?= trans("confirm_delete", true); ?>');"><i class="fa fa-trash option-icon"></i><?= trans('delete'); ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                        </tbody>
                    </table>
                    <?php if (empty($digitalSales)): ?>
                        <p class="text-center">
                            <?= trans("no_records_found"); ?>
                        </p>
                    <?php endif; ?>
                    <div class="col-sm-12 table-ft">
                        <div class="row">
                            <div class="pull-right">
                                <?= $pager->links; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #formFilterDigitalSales {
        @media (max-width: 768px) {
            margin-top: 30px;
        }
    }
</style>