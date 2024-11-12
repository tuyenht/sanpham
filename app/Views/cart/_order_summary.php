<div class="col-sm-12 col-lg-4 order-summary-container">
    <h2 class="cart-section-title"><?= trans("order_summary"); ?> (<?= getCartProductCount(); ?>)</h2>
    <div class="right">
        <?php $isPhysical = false; ?>
        <div class="cart-order-details">
            <?php if (!empty($cartItems)):
                foreach ($cartItems as $cartItem):
                    $product = getActiveProduct($cartItem->product_id);
                    if (!empty($product)):
                        if ($product->product_type == 'physical') {
                            $isPhysical = true;
                        } ?>
                        <div class="item">
                            <div class="item-left">
                                <div class="img-cart-product">
                                    <a href="<?= generateProductUrl($product); ?>">
                                        <img src="<?= base_url(IMG_BG_PRODUCT_SMALL); ?>" data-src="<?= esc($cartItem->product_image); ?>" alt="<?= getProductTitle($product); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?= base_url(IMG_BG_PRODUCT_SMALL); ?>'">
                                    </a>
                                </div>
                            </div>
                            <div class="item-right">
                                <?php if ($product->product_type == 'digital'): ?>
                                    <div class="list-item">
                                        <label class="badge badge-info-light badge-instant-download">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                                            </svg>&nbsp;&nbsp;<?= trans("instant_download"); ?>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <div class="list-item">
                                    <a href="<?= generateProductUrl($product); ?>"><?= esc($cartItem->product_title); ?></a>
                                </div>
                                <div class="list-item seller">
                                    <?= trans("by"); ?>&nbsp;<a href="<?= generateProfileUrl($product->user_slug); ?>"><?= esc($product->user_username); ?></a>
                                </div>
                                <div class="list-item m-t-15">
                                    <label><?= trans("quantity"); ?>:</label>
                                    <strong class="lbl-price"><?= $cartItem->quantity; ?></strong>
                                </div>
                                <div class="list-item">
                                    <label><?= trans("price"); ?>:</label>
                                    <strong class="lbl-price"><?= priceDecimal($cartItem->total_price, $cartItem->currency); ?></strong>
                                </div>
                                <?php if (!empty($cartItem->product_vat)): ?>
                                    <div class="list-item">
                                        <label><?= trans("vat"); ?>&nbsp;(<?= $cartItem->product_vat_rate; ?>%):</label>
                                        <strong><?= priceDecimal($cartItem->product_vat, $cartItem->currency); ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif;
                endforeach;
            endif; ?>
        </div>
        <div class="row-custom m-t-30 m-b-10">
            <strong><?= trans("subtotal"); ?><span class="float-right"><?= priceDecimal($cartTotal->subtotal, $cartTotal->currency); ?></span></strong>
        </div>
        <?php if ($cartTotal->affiliate_discount > 0): ?>
            <div class="row-custom m-b-10">
                <strong><?= trans("referral_discount"); ?>&nbsp;(<?= $cartTotal->affiliate_discount_rate; ?>%)<span class="float-right">-&nbsp;<?= priceDecimal($cartTotal->affiliate_discount, $cartTotal->currency); ?></span></strong>
            </div>
        <?php endif;
        if (!empty($cartTotal->vat)): ?>
            <div class="row-custom m-b-10">
                <strong><?= trans("vat"); ?><span class="float-right"><?= priceDecimal($cartTotal->vat, $cartTotal->currency); ?></span></strong>
            </div>
        <?php endif;
        if (!empty($showShippingCost) && !empty($cartTotal->shipping_cost)): ?>
            <div class="row-custom m-b-10">
                <strong><?= trans("shipping"); ?><span class="float-right"><?= priceDecimal($cartTotal->shipping_cost, $cartTotal->currency); ?></span></strong>
            </div>
        <?php endif;
        if ($cartTotal->coupon_discount > 0): ?>
            <div class="row-custom m-b-10">
                <strong><?= trans("coupon"); ?>&nbsp;&nbsp;[<?= getCartDiscountCoupon(); ?>]&nbsp;&nbsp;<a href="javascript:void(0)" class="font-weight-normal" onclick="removeCartDiscountCoupon();">[<?= trans("remove"); ?>]</a><span class="float-right">-&nbsp;<?= priceDecimal($cartTotal->coupon_discount, $cartTotal->currency); ?></span></strong>
            </div>
        <?php endif;
        if (!empty($cartTotal->global_taxes_array)):
            foreach ($cartTotal->global_taxes_array as $taxItem):?>
                <div class="row-custom m-b-10">
                    <strong><?= esc(getTaxName($taxItem['taxNameArray'], selectedLangId())); ?>&nbsp;(<?= $taxItem['taxRate']; ?>%)<span class="float-right"><?= priceDecimal($taxItem['taxTotal'], $cartTotal->currency); ?></span></strong>
                </div>
            <?php endforeach;
        endif;
        if (!empty($cartTotal->transaction_fee)): ?>
            <div class="row-custom m-b-15">
                <strong><?= trans("transaction_fee"); ?><?= $cartTotal->transaction_fee_rate ? ' (' . $cartTotal->transaction_fee_rate . '%)' : ''; ?><span class="float-right"><?= priceDecimal($cartTotal->transaction_fee, $cartTotal->currency); ?></span></strong>
            </div>
        <?php endif; ?>
        <div class="row-custom">
            <p class="line-seperator"></p>
        </div>
        <?php if (!empty($showShippingCost) && !empty($cartTotal->shipping_cost)): ?>
            <div class="row-custom">
                <strong><?= trans("total"); ?><span class="float-right"><?= priceDecimal($cartTotal->total, $cartTotal->currency); ?></span></strong>
            </div>
        <?php else: ?>
            <div class="row-custom">
                <strong><?= trans("total"); ?><span class="float-right"><?= priceDecimal($cartTotal->total_before_shipping, $cartTotal->currency); ?></span></strong>
            </div>
        <?php endif; ?>
    </div>
</div>