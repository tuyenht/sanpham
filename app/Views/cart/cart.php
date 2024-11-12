<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ($cartItems != null): ?>
                    <div class="shopping-cart">
                        <div class="row">
                            <div class="col-sm-12 col-lg-8">
                                <div class="left">
                                    <h1 class="cart-section-title"><?= trans("my_cart"); ?> (<?= getCartProductCount(); ?>)</h1>
                                    <?php if (!empty($cartItems)):
                                        foreach ($cartItems as $cartItem):
                                            $product = getActiveProduct($cartItem->product_id);
                                            if (!empty($product)): ?>
                                                <div class="item">
                                                    <div class="cart-item-image">
                                                        <div class="img-cart-product">
                                                            <a href="<?= generateProductUrl($product); ?>">
                                                                <img src="<?= base_url(IMG_BG_PRODUCT_SMALL); ?>" data-src="<?= esc($cartItem->product_image); ?>" alt="<?= esc($cartItem->product_title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?= base_url(IMG_BG_PRODUCT_SMALL); ?>'">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="cart-item-details">
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
                                                            <?php if (empty($cartItem->is_stock_available)): ?>
                                                                <div class="lbl-enough-quantity"><?= trans("out_of_stock"); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="list-item seller">
                                                            <?= trans("by"); ?>&nbsp;<a href="<?= generateProfileUrl($product->user_slug); ?>"><?= esc($product->user_username); ?></a>
                                                        </div>
                                                        <?php if ($cartItem->purchase_type != 'bidding'): ?>
                                                            <div class="list-item m-t-15">
                                                                <label><?= trans("unit_price"); ?>:</label>
                                                                <strong class="lbl-price">
                                                                    <?= priceDecimal($cartItem->unit_price, $cartItem->currency); ?>
                                                                </strong>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="list-item">
                                                            <label><?= trans("total"); ?>:</label>
                                                            <strong class="lbl-price"><?= priceDecimal($cartItem->total_price, $cartItem->currency); ?></strong>
                                                        </div>
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-gray btn-cart-remove" onclick="removeFromCart('<?= $cartItem->cart_item_id; ?>');"><i class="icon-close"></i> <?= trans("remove"); ?></a>
                                                    </div>
                                                    <div class="cart-item-quantity">
                                                        <?php if ($cartItem->purchase_type == 'bidding'): ?>
                                                            <span><?= trans("quantity") . ': ' . $cartItem->quantity; ?></span>
                                                        <?php else:
                                                            if ($product->listing_type != 'license_key' && $product->product_type != 'digital'):?>
                                                                <div class="number-spinner">
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn">
                                                                            <button type="button" class="btn btn-default btn-spinner-minus" data-cart-item-id="<?= $cartItem->cart_item_id; ?>" data-dir="dwn">-</button>
                                                                        </span>
                                                                        <input type="text" id="q-<?= $cartItem->cart_item_id; ?>" class="form-control text-center" value="<?= $cartItem->quantity; ?>" data-product-id="<?= $cartItem->product_id; ?>" data-cart-item-id="<?= $cartItem->cart_item_id; ?>">
                                                                        <span class="input-group-btn">
                                                                            <button type="button" class="btn btn-default btn-spinner-plus" data-cart-item-id="<?= $cartItem->cart_item_id; ?>" data-dir="up">+</button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            <?php endif;
                                                        endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif;
                                        endforeach;
                                    endif; ?>
                                </div>
                                <a href="<?= langBaseUrl(); ?>" class="btn btn-md btn-custom m-t-15"><i class="icon-arrow-left m-r-2"></i><?= trans("keep_shopping") ?></a>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <div class="right">
                                    <div class="row-custom m-b-15">
                                        <strong><?= trans("subtotal"); ?><span class="float-right"><?= priceDecimal($cartTotal->subtotal, $cartTotal->currency); ?></span></strong>
                                    </div>
                                    <?php if ($cartTotal->affiliate_discount > 0): ?>
                                        <div class="row-custom m-b-10">
                                            <strong><?= trans("referral_discount"); ?>&nbsp;(<?= $cartTotal->affiliate_discount_rate; ?>%)<span class="float-right">-&nbsp;<?= priceDecimal($cartTotal->affiliate_discount, $cartTotal->currency); ?></span></strong>
                                        </div>
                                    <?php endif;
                                    if ($cartTotal->coupon_discount > 0): ?>
                                        <div class="row-custom">
                                            <strong><?= trans("coupon"); ?>&nbsp;&nbsp;[<?= getCartDiscountCoupon(); ?>]&nbsp;&nbsp;<a href="javascript:void(0)" class="font-weight-normal" onclick="removeCartDiscountCoupon();">[<?= trans("remove"); ?>]</a><span class="float-right">-&nbsp;<?= priceDecimal($cartTotal->coupon_discount, $cartTotal->currency); ?></span></strong>
                                        </div>
                                    <?php endif; ?>
                                    <div class="row-custom">
                                        <p class="line-seperator"></p>
                                    </div>
                                    <div class="row-custom m-b-10">
                                        <strong><?= trans("total"); ?><span class="float-right"><?= priceDecimal($cartTotal->total_before_shipping, $cartTotal->currency); ?></span></strong>
                                    </div>
                                    <div class="row-custom m-t-30 m-b-15">
                                        <?php if (empty($cartTotal->is_stock_available)): ?>
                                            <a href="javascript:void(0)" class="btn btn-block"><?= trans("continue_to_checkout"); ?></a>
                                        <?php else:
                                            $showCheckoutBtn = false;
                                            if (authCheck()) {
                                                $showCheckoutBtn = true;
                                            } else {
                                                if ($generalSettings->guest_checkout == 1) {
                                                    $showCheckoutBtn = true;
                                                }
                                                if($cartHasDigitalProduct){
                                                    $showCheckoutBtn = false;
                                                }
                                            }
                                            if ($showCheckoutBtn):
                                                if ($cartHasPhysicalProduct == true && $productSettings->marketplace_shipping == 1): ?>
                                                    <a href="<?= generateUrl('cart', 'shipping'); ?>" class="btn btn-block"><?= trans("continue_to_checkout"); ?></a>
                                                <?php else: ?>
                                                    <a href="<?= generateUrl('cart', 'payment_method'); ?>" class="btn btn-block"><?= trans("continue_to_checkout"); ?></a>
                                                <?php endif;
                                            else:?>
                                                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#loginModal"><?= trans("continue_to_checkout"); ?></a>
                                            <?php endif;
                                        endif; ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr class="m-t-30 m-b-30">
                                    <form action="<?= base_url('cart/coupon-code-post'); ?>" method="post" id="form_validate" class="m-0">
                                        <?= csrf_field(); ?>
                                        <label class="font-600"><?= trans("discount_coupon") ?></label>
                                        <div class="cart-discount-coupon">
                                            <input type="text" name="coupon_code" class="form-control form-input" value="<?= esc(old('coupon_code')); ?>" maxlength="254" placeholder="<?= trans("coupon_code") ?>" required>
                                            <button type="submit" class="btn btn-custom m-l-5"><?= trans("apply") ?></button>
                                        </div>
                                    </form>
                                    <div class="cart-coupon-error">
                                        <?php if (!empty(helperGetSession('error_coupon_code'))): ?>
                                            <div class="text-danger">
                                                <?= helperGetSession('error_coupon_code'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="shopping-cart-empty">
                        <p><strong class="font-600"><?= trans("your_cart_is_empty"); ?></strong></p>
                        <a href="<?= langBaseUrl(); ?>" class="btn btn-lg btn-custom"><i class="icon-arrow-left"></i>&nbsp;<?= trans("shop_now"); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>