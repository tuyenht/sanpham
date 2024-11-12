<?php if ($product->is_sold == 1): ?>
    <strong class="lbl-price lbl-price-sold">
        <b class="discount-original-price">
            <?= priceFormatted($price, $product->currency); ?>
            <span class="price-line"></span>
        </b>
    </strong>
<?php else:
    if ($product->is_free_product == 1):?>
        <strong class="lbl-free text-success"><?= trans("free"); ?></strong>
    <?php else:
        if (!empty($price)):
            if ($product->listing_type == 'sell_on_site' || $product->listing_type == 'license_key' || $product->listing_type == 'ordinary_listing'):
                $convertCurreny = true;
                if ($product->listing_type == 'ordinary_listing') {
                    $convertCurreny = false;
                }
                if (!empty($discountRate)): ?>
                    <strong class="lbl-price">
                        <?= priceFormatted($priceDiscounted, $product->currency, $convertCurreny); ?>
                        <b class="discount-original-price">
                            <?= priceFormatted($price, $product->currency, $convertCurreny); ?>
                            <span class="price-line"></span>
                        </b>
                    </strong>
                    <div class="discount-rate">
                        -<?= discountRateFormat($discountRate); ?>
                    </div>
                <?php else: ?>
                    <strong class="lbl-price">
                        <?= priceFormatted($price, $product->currency, $convertCurreny); ?>
                    </strong>
                <?php endif;
            endif;
        endif;
    endif;
endif; ?>