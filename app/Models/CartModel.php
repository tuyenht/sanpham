<?php namespace App\Models;

class CartModel extends BaseModel
{
    protected $cartProductIds;
    protected $cartCustomerLocation;

    public function __construct()
    {
        parent::__construct();
        $this->cartProductIds = array();
        $this->cartCustomerLocation = $this->getCartCustomerLocation();
    }

    //add to cart
    public function addToCart($product)
    {
        $cart = $this->getSessCartItems();
        $cartItemId = null;
        $quantity = clrNum(inputPost('product_quantity'));
        if ($quantity < 1) {
            $quantity = 1;
        }
        if ($product->product_type == 'digital') {
            $quantity = 1;
        }
        $selectedVariations = $this->getSelectedVariations($product->id);
        $appendedVariations = $selectedVariations->str;
        $optionsArray = $selectedVariations->options_array;
        $productId = $product->id;
        $productTitle = getProductTitle($product, false) . ' ' . $appendedVariations;
        //check if item exists
        $updateQuantity = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                if ($item->product_id == $productId && $item->product_title == $productTitle) {
                    if ($product->listing_type != 'license_key' && $product->product_type != 'digital') {
                        $cartItemId = $item->cart_item_id;
                        $item->quantity += $quantity;
                    }
                    $updateQuantity = 1;
                    $cartItemId = $item->cart_item_id;
                }
            }
        }
        if ($updateQuantity == 1) {
            helperSetSession('mds_shopping_cart', $cart);
        } else {
            $cartItemId = generateToken(true);
            $item = new \stdClass();
            $item->cart_item_id = $cartItemId;
            $item->product_id = $product->id;
            $item->product_type = $product->product_type;
            $item->product_title = getProductTitle($product, false) . ' ' . $appendedVariations;
            $item->product_option_image = $selectedVariations->image;
            $item->options_array = $optionsArray;
            $item->quantity = $quantity;
            $item->unit_price = null;
            $item->total_price = null;
            $item->discount_rate = 0;
            $item->currency = $this->selectedCurrency->code;
            $item->product_vat = 0;
            $item->product_vat_rate = 0;
            $item->is_stock_available = null;
            $item->purchase_type = 'product';
            $item->quote_request_id = 0;
            array_push($cart, $item);
            helperSetSession('mds_shopping_cart', $cart);
        }
        return $cartItemId;
    }

    //add to cart quote
    public function addToCartQuote($quoteRequestId)
    {
        $biddingModel = new BiddingModel();
        $quoteRequest = $biddingModel->getQuoteRequest($quoteRequestId);
        $optionImageUrl = '';
        if (!empty($quoteRequest->variation_option_ids)) {
            $optionArray = unserializeData($quoteRequest->variation_option_ids);
            if (!empty($optionArray) && countItems($optionArray) > 0) {
                $variationModel = new VariationModel();
                $optionImage = $variationModel->getVariationOptionImageByIdArray($optionArray);
                if (!empty($optionImage) && !empty($optionImage->image_small)) {
                    if ($optionImage->storage == 'aws_s3') {
                        $optionImageUrl = getAWSBaseUrl() . 'uploads/images/' . $optionImage->image_small;
                    } else {
                        $optionImageUrl = base_url('uploads/images/' . $optionImage->image_small);
                    }
                }
            }
        }
        if (!empty($quoteRequest)) {
            $product = getActiveProduct($quoteRequest->product_id);
            if (!empty($product)) {
                $cart = $this->getSessCartItems();
                $item = new \stdClass();
                $item->cart_item_id = generateToken();
                $item->product_id = $product->id;
                $item->product_type = $product->product_type;
                $item->product_title = $quoteRequest->product_title;
                $item->product_option_image = !empty($optionImageUrl) ? $optionImageUrl : '';
                $item->options_array = !empty($quoteRequest->variation_option_ids) ? unserializeData($quoteRequest->variation_option_ids) : array();
                $item->quantity = $quoteRequest->product_quantity;
                $item->unit_price = null;
                $item->total_price = null;
                $item->currency = $this->selectedCurrency->code;
                $item->product_vat = 0;
                $item->product_vat_rate = 0;
                $item->is_stock_available = 1;
                $item->purchase_type = 'bidding';
                $item->quote_request_id = $quoteRequest->id;
                array_push($cart, $item);
                helperSetSession('mds_shopping_cart', $cart);
                return true;
            }
        }
        return false;
    }

    //remove from cart
    public function removeFromCart($cartItemId)
    {
        $cart = $this->getSessCartItems();
        if (!empty($cart)) {
            $newCart = array();
            foreach ($cart as $item) {
                if ($item->cart_item_id != $cartItemId) {
                    array_push($newCart, $item);
                }
            }
            helperSetSession('mds_shopping_cart', $newCart);
        }
    }

    //get selected variations
    public function getSelectedVariations($productId)
    {
        $variationModel = new VariationModel();
        $object = new \stdClass();
        $object->str = '';
        $object->options_array = array();
        $object->image = '';
        $variations = $variationModel->getProductVariations($productId);
        $str = '';
        if (!empty($variations)) {
            foreach ($variations as $variation) {
                $appendText = '';
                if (!empty($variation) && $variation->is_visible == 1) {
                    $variationVal = inputPost('variation' . $variation->id);
                    if (!empty($variationVal)) {
                        if ($variation->variation_type == 'text' || $variation->variation_type == 'number') {
                            $appendText = $variationVal;
                        } else {
                            //check multiselect
                            if (is_array($variationVal)) {
                                $i = 0;
                                foreach ($variationVal as $item) {
                                    $option = $variationModel->getVariationOption($item);
                                    if (!empty($option)) {
                                        if ($i == 0) {
                                            $appendText .= getVariationOptionName($option->option_names, selectedLangId());
                                        } else {
                                            $appendText .= ' - ' . getVariationOptionName($option->option_names, selectedLangId());
                                        }
                                        $i++;
                                        array_push($object->options_array, $option->id);
                                    }
                                }
                            } else {
                                $option = $variationModel->getVariationOption($variationVal);
                                $optionImage = $this->db->table('images_variation')->where('product_id', clrNum($variation->product_id))->where('variation_option_id', clrNum($option->id))->orderBy('is_main DESC')->get(1)->getRow();
                                if (!empty($optionImage) && !empty($optionImage->image_small)) {
                                    if ($optionImage->storage == 'aws_s3') {
                                        $object->image = getAWSBaseUrl() . 'uploads/images/' . $optionImage->image_small;
                                    } else {
                                        $object->image = base_url('uploads/images/' . $optionImage->image_small);
                                    }
                                }
                                if (!empty($option)) {
                                    $appendText .= getVariationOptionName($option->option_names, selectedLangId());
                                    array_push($object->options_array, $option->id);
                                }
                            }
                        }
                        if (empty($str)) {
                            $str .= '(' . getVariationLabel($variation->label_names, selectedLangId()) . ': ' . $appendText;
                        } else {
                            $str .= ', ' . getVariationLabel($variation->label_names, selectedLangId()) . ': ' . $appendText;
                        }
                    }
                }
            }
            if (!empty($str)) {
                $str = $str . ')';
            }
        }
        $object->str = $str;
        return $object;
    }

    //get product price and stock
    public function getProductPriceAndStock($product, $cartProductTitle, $optionsArray)
    {
        $object = new \stdClass();
        $object->price = 0;
        $object->discount_rate = 0;
        $object->price_calculated = 0;
        $object->is_stock_available = 0;
        if (!empty($product)) {
            //quantity in cart
            $quantityInCart = 0;
            if (!empty(helperGetSession('mds_shopping_cart'))) {
                foreach (helperGetSession('mds_shopping_cart') as $item) {
                    if (($item->product_id == $product->id && $item->product_title == $cartProductTitle) || ($item->product_id == $product->id && empty($item->options_array))) {
                        $quantityInCart += $item->quantity;
                    }
                }
            }
            $stock = $product->stock;
            $price = getPrice($product->price_discounted, 'decimal');
            if (!empty($optionsArray)) {
                $variationModel = new VariationModel();
                foreach ($optionsArray as $optionId) {
                    $option = $variationModel->getVariationOption($optionId);
                    if (!empty($option)) {
                        $variation = $variationModel->getVariation($option->variation_id);
                        if ($variation->use_different_price == 1) {
                            $optionPrice = $option->price_discounted;
                            if (!empty($optionPrice)) {
                                $price = getPrice($optionPrice, 'decimal');
                            }
                        }
                        if ($option->is_default != 1) {
                            $stock = $option->stock;
                        }
                    }
                }
            }
            if (empty($price)) {
                $object->price = $price;
            }
            if (!empty($price)) {
                $object->price_calculated = number_format($price, 2, '.', '');
            }
            if ($stock >= $quantityInCart) {
                $object->is_stock_available = 1;
            }
            if ($product->product_type == 'digital') {
                $object->is_stock_available = 1;
            }
        }
        return $object;
    }

    //update cart product quantity
    public function updateCartProductQuantity($productId, $cartItemId, $quantity)
    {
        if ($quantity < 1) {
            $quantity = 1;
        }
        $cart = $this->getSessCartItems();
        if (!empty($cart)) {
            foreach ($cart as $item) {
                if ($item->cart_item_id == $cartItemId) {
                    $item->quantity = $quantity;
                }
            }
        }
        helperSetSession('mds_shopping_cart', $cart);
    }

    //get cart items session
    public function getSessCartItems($includeTaxes = false, $includeTransactionFee = false)
    {
        $cart = array();
        $newCart = array();
        $this->cartProductIds = array();
        if (!empty(helperGetSession('mds_shopping_cart'))) {
            $cart = helperGetSession('mds_shopping_cart');
        }
        if (!empty($cart)) {
            foreach ($cart as $cartItem) {
                $product = getActiveProduct($cartItem->product_id);
                if (!empty($product)) {
                    //if purchase type is bidding
                    if ($cartItem->purchase_type == 'bidding') {
                        $biddingModel = new BiddingModel();
                        $quoteRequest = $biddingModel->getQuoteRequest($cartItem->quote_request_id);
                        if (!empty($quoteRequest) && $quoteRequest->status == 'pending_payment') {
                            $priceOffered = getPrice($quoteRequest->price_offered, 'decimal');
                            //convert currency
                            $baseCurrency = $this->selectedCurrency;
                            if ($this->paymentSettings->currency_converter == 1) {
                                $baseCurrency = $this->selectedCurrency;
                                if (!empty($baseCurrency)) {
                                    $priceOffered = convertCurrencyByExchangeRate($priceOffered, $baseCurrency->exchange_rate);
                                }
                            }
                            $item = new \stdClass();
                            $item->cart_item_id = $cartItem->cart_item_id;
                            $item->product_id = $product->id;
                            $item->product_type = $cartItem->product_type;
                            $item->product_title = $cartItem->product_title;
                            $item->product_option_image = !empty($cartItem->product_option_image) ? $cartItem->product_option_image : '';
                            $item->product_image = !empty($cartItem->product_option_image) ? $cartItem->product_option_image : getProductMainImage($product->id, 'image_small');
                            $item->options_array = $cartItem->options_array;
                            $item->quantity = $cartItem->quantity;
                            $item->unit_price = $priceOffered / $quoteRequest->product_quantity;
                            $item->total_price = $priceOffered;
                            $item->discount_rate = 0;
                            $item->currency = $baseCurrency->code;
                            $item->product_vat = 0;
                            $item->product_vat_rate = 0;
                            $item->purchase_type = $cartItem->purchase_type;
                            $item->quote_request_id = $cartItem->quote_request_id;
                            $item->seller_id = $product->user_id;
                            $item->shipping_class_id = $product->shipping_class_id;
                            $item->is_stock_available = 1;
                            array_push($newCart, $item);
                        }
                    } else {
                        $object = $this->getProductPriceAndStock($product, $cartItem->product_title, $cartItem->options_array);
                        //convert currency
                        $baseCurrency = $this->selectedCurrency;
                        if ($this->paymentSettings->currency_converter == 1) {
                            $baseCurrency = $this->selectedCurrency;
                            if (!empty($baseCurrency)) {
                                $object->price_calculated = convertCurrencyByExchangeRate($object->price_calculated, $baseCurrency->exchange_rate);
                            }
                        }
                        $item = new \stdClass();
                        $item->cart_item_id = $cartItem->cart_item_id;
                        $item->product_id = $product->id;
                        $item->product_type = $cartItem->product_type;
                        $item->product_title = $cartItem->product_title;
                        $item->product_option_image = $cartItem->product_option_image;
                        $item->product_image = !empty($cartItem->product_option_image) ? $cartItem->product_option_image : getProductMainImage($product->id, 'image_small');
                        $item->options_array = $cartItem->options_array;
                        $item->quantity = $cartItem->quantity;
                        $item->unit_price = $object->price_calculated;
                        $item->total_price = $object->price_calculated * $cartItem->quantity;
                        $item->discount_rate = $object->discount_rate;
                        $item->currency = $product->currency;
                        $item->product_vat = 0;
                        $item->product_vat_rate = 0;
                        if ($includeTaxes) {
                            $productVat = $this->calculateProductVat($object->price_calculated, $product, $cartItem->quantity);
                            $item->product_vat = !empty($productVat) && !empty($productVat['vat']) ? $productVat['vat'] : 0;
                            $item->product_vat_rate = !empty($productVat) && !empty($productVat['vatRate']) ? $productVat['vatRate'] : 0;
                        }
                        $item->purchase_type = $cartItem->purchase_type;
                        $item->quote_request_id = $cartItem->quote_request_id;
                        $item->seller_id = $product->user_id;
                        $item->shipping_class_id = $product->shipping_class_id;
                        $item->is_stock_available = $object->is_stock_available;
                        array_push($newCart, $item);
                    }
                }
            }
        }
        //convert currency
        if ($this->paymentSettings->currency_converter == 1 && !empty($baseCurrency)) {
            if (!empty($newCart)) {
                foreach ($newCart as $item) {
                    $item->currency = $baseCurrency->code;
                }
            }
        }

        helperSetSession('mds_shopping_cart', $newCart);
        $this->calculateCartTotal($newCart, null, true, $includeTaxes, $includeTransactionFee);
        return $newCart;
    }

    //get cart item session
    public function getSessCartItem($cartItemId)
    {
        $cartItems = $this->getSessCartItems();
        if (!empty($cartItems)) {
            foreach ($cartItems as $cartItem) {
                if ($cartItem->cart_item_id == $cartItemId) {
                    return $cartItem;
                }
            }
        }
        return null;
    }

    //calculate cart total
    public function calculateCartTotal($cartItems, $currencyCode = null, $setSession = true, $includeTaxes = false, $includeTransactionFee = false)
    {
        if (empty($currencyCode)) {
            $currencyCode = $this->selectedCurrency->code;
        }
        $cartTotal = new \stdClass();
        $cartTotal->subtotal = 0;
        $cartTotal->vat = 0;
        $cartTotal->shipping_cost = 0;
        $cartTotal->total_before_shipping = 0;
        $cartTotal->total = 0;
        $cartTotal->is_stock_available = 1;
        $cartTotal->currency = $currencyCode;
        $cartTotal->transaction_fee = 0;
        $cartTotal->transaction_fee_rate = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                if ($item->purchase_type == 'bidding') {
                    $cartTotal->subtotal += $item->total_price;
                } else {
                    $cartTotal->subtotal += $item->total_price;
                    $cartTotal->vat += $item->product_vat;
                }
                if ($item->is_stock_available != 1) {
                    $cartTotal->is_stock_available = 0;
                }
            }
        }
        //set shipping cost
        if (!empty(helperGetSession('mds_cart_shipping')) && !empty(helperGetSession('mds_cart_shipping')->totalCost)) {
            $shippingCost = helperGetSession('mds_cart_shipping')->totalCost;
            $currency = getCurrencyByCode($currencyCode);
            if (!empty($currency)) {
                $shippingCost = convertCurrencyByExchangeRate($shippingCost, $currency->exchange_rate);
            }
            $cartTotal->shipping_cost = $shippingCost;
        }
        $cartTotal->total_before_shipping = $cartTotal->subtotal + $cartTotal->vat;
        $cartTotal->total = $cartTotal->subtotal + $cartTotal->vat + $cartTotal->shipping_cost;

        //calculate affiliate discount
        $affiliateDiscount = $this->calculateAffiliateDiscount($cartItems);
        $cartTotal->affiliate_id = $affiliateDiscount['id'];
        $cartTotal->affiliate_referrer_id = $affiliateDiscount['referrerId'];
        $cartTotal->affiliate_seller_id = $affiliateDiscount['sellerId'];
        $cartTotal->affiliate_product_id = $affiliateDiscount['productId'];
        $cartTotal->affiliate_commission_rate = $affiliateDiscount['commissionRate'];
        $cartTotal->affiliate_commission = $affiliateDiscount['commission'];
        $cartTotal->affiliate_discount_rate = $affiliateDiscount['discountRate'];
        $cartTotal->affiliate_discount = $affiliateDiscount['discount'];
        $cartTotal->total_before_shipping = $cartTotal->total_before_shipping - $cartTotal->affiliate_discount;
        $cartTotal->total = $cartTotal->total - $cartTotal->affiliate_discount;

        //discount coupon
        $arrayDiscount = $this->calculateCouponDiscount($cartItems);
        $cartTotal->coupon_discount_products = $arrayDiscount['product_ids'];
        if (!empty($cartTotal->coupon_discount_products)) {
            $cartTotal->coupon_discount_products = trim($cartTotal->coupon_discount_products, ',');
        }
        $cartTotal->coupon_discount_rate = $arrayDiscount['discount_rate'];
        $cartTotal->coupon_discount = $arrayDiscount['total_discount'];
        $cartTotal->coupon_seller_id = $arrayDiscount['seller_id'];
        $cartTotal->total_before_shipping = $cartTotal->total_before_shipping - $cartTotal->coupon_discount;
        $cartTotal->total = $cartTotal->total - $cartTotal->coupon_discount;

        //set global taxes
        if ($includeTaxes) {
            $cartTotal->global_taxes_array = $this->getGlobalTaxArray($cartTotal->subtotal, 'product_sales');
            if (!empty($cartTotal->global_taxes_array) && countItems($cartTotal->global_taxes_array) > 0) {
                foreach ($cartTotal->global_taxes_array as $tax) {
                    if (!empty($tax['taxTotal'])) {
                        $cartTotal->total = number_format($cartTotal->total + $tax['taxTotal'], 2, '.', '');
                        $cartTotal->total_before_shipping = number_format($cartTotal->total_before_shipping + $tax['taxTotal'], 2, '.', '');
                    }
                }
            }
        }
        //set transaction fee
        if ($includeTransactionFee) {
            $cartTotal = $this->setTransactionFee($cartTotal, $cartItems);
        }

        if ($setSession == true) {
            helperSetSession('mds_shopping_cart_total', $cartTotal);
        } else {
            return $cartTotal;
        }
    }

    //get global tax array
    public function getGlobalTaxArray($total, $taxType)
    {
        $taxes = $this->db->table('taxes')->get()->getResult();
        $taxArray = array();
        if (!empty($taxes)) {
            foreach ($taxes as $tax) {
                $taxTypeStatus = false;
                if ($taxType == 'product_sales' && $tax->product_sales == 1) {
                    $taxTypeStatus = true;
                } elseif ($taxType == 'service_payments' && $tax->service_payments == 1) {
                    $taxTypeStatus = true;
                }
                if (!empty($tax) && $tax->status == 1 && $taxTypeStatus) {
                    $applyTax = false;
                    if ($tax->is_all_countries == 1) {
                        $applyTax = true;
                    } else {
                        $taxCountryIds = !empty($tax->country_ids) ? unserializeData($tax->country_ids) : array();
                        $taxStateIds = !empty($tax->state_ids) ? unserializeData($tax->state_ids) : array();
                        if (!empty($taxCountryIds) && countItems($taxCountryIds) && in_array($this->cartCustomerLocation->countryId, $taxCountryIds)) {
                            $applyTax = true;
                        }
                        if (!empty($taxStateIds) && countItems($taxStateIds) && in_array($this->cartCustomerLocation->stateId, $taxStateIds)) {
                            $applyTax = true;
                        }
                    }
                    if ($applyTax == true && $tax->tax_rate > 0) {
                        $taxTotal = ($total * $tax->tax_rate) / 100;
                        if (!empty($taxTotal)) {
                            $taxTotal = number_format($taxTotal, 2, '.', '');
                            $taxItem = [
                                'taxNameArray' => $tax->name_data,
                                'taxRate' => $tax->tax_rate,
                                'taxTotal' => $taxTotal
                            ];
                            array_push($taxArray, $taxItem);
                        }
                    }
                }
            }
        }
        return $taxArray;
    }

    //add service payments taxes
    public function setServicePaymentsTaxes($servicePayment)
    {
        $servicePayment->globalTaxesArray = $this->getGlobalTaxArray($servicePayment->paymentAmountBeforeTaxes, 'service_payments');
        if (!empty($servicePayment->globalTaxesArray) && countItems($servicePayment->globalTaxesArray) > 0) {
            $taxTotal = 0;
            foreach ($servicePayment->globalTaxesArray as $tax) {
                $taxTotal += $tax['taxTotal'];
            }
            $servicePayment->paymentAmount = $servicePayment->paymentAmountBeforeTaxes + $taxTotal;
        }
        return $servicePayment;
    }

    //convert service taxes currency
    public function convertServiceTaxesCurrency($globalTaxesArray, $currencyCode)
    {
        if ($this->defaultCurrency->code == $currencyCode) {
            return $globalTaxesArray;
        }
        if (!empty($globalTaxesArray)) {
            $currency = getCurrencyByCode($currencyCode);
            if (!empty($currency)) {
                for ($i = 0; $i < countItems($globalTaxesArray); $i++) {
                    $total = $globalTaxesArray[$i]['taxTotal'];
                    $total = convertCurrencyByExchangeRate($total, $currency->exchange_rate);
                    $globalTaxesArray[$i]['taxTotal'] = $total;
                }
            }
        }
        return $globalTaxesArray;
    }

    //calculate product vat
    public function calculateProductVat($price, $product, $quantity)
    {
        if ($this->paymentSettings->vat_status != 1) {
            return ['vat' => 0, 'vatRate' => 0];
        }
        $vat = 0;
        $vatRate = 0;
        if (!empty($price)) {
            if (!empty($product->vat_rate)) {
                $vatRate = $product->vat_rate;
            } else {
                $user = getUser($product->user_id);
                if ($user->is_fixed_vat == 1) {
                    $vatRate = $user->fixed_vat_rate;
                } else {
                    $stateVat = 0;
                    $countryVat = 0;
                    if (!empty($user->vat_rates_data_state)) {
                        $vatArray = unserializeData($user->vat_rates_data_state);
                        if (!empty($vatArray) && !empty($vatArray[$this->cartCustomerLocation->stateId])) {
                            $stateVat = $vatArray[$this->cartCustomerLocation->stateId];
                        }
                    }
                    if (!empty($user->vat_rates_data)) {
                        $vatArray = unserializeData($user->vat_rates_data);
                        if (!empty($vatArray) && !empty($vatArray[$this->cartCustomerLocation->countryId])) {
                            $countryVat = $vatArray[$this->cartCustomerLocation->countryId];
                        }
                    }
                    if (!empty($stateVat)) {
                        $vatRate = $stateVat;
                    } else {
                        $vatRate = $countryVat;
                    }
                }
            }
            if (!empty($vatRate)) {
                $vat = (($price * $vatRate) / 100) * $quantity;
                if (filter_var($vat, FILTER_VALIDATE_INT) === false) {
                    $vat = number_format($vat, 2, '.', '');
                }
            }
        }
        return ['vat' => $vat, 'vatRate' => $vatRate];
    }

    //calculate affiliate discount
    public function calculateAffiliateDiscount($cartItems)
    {
        $data = [
            'id' => '',
            'referrerId' => '',
            'sellerId' => '',
            'productId' => '',
            'commissionRate' => 0,
            'commission' => 0,
            'discountRate' => 0,
            'discount' => 0
        ];
        if ($this->generalSettings->affiliate_status == 1) {
            $affId = helperGetCookie(AFFILIATE_COOKIE_NAME);
            if (!empty($affId)) {
                $affiliate = $this->db->table('affiliate_links')->where('id', clrNum($affId))->get()->getRow();
                if (!empty($affiliate)) {
                    $user = getUser($affiliate->referrer_id);
                    if (!empty($user) && $user->is_affiliate == 1) {
                        if (!empty($cartItems)) {
                            foreach ($cartItems as $cartItem) {
                                if ($affiliate->product_id == $cartItem->product_id) {
                                    $product = getProduct($cartItem->product_id);
                                    if (!empty($product)) {
                                        $data['id'] = $affiliate->id;
                                        $data['referrerId'] = $affiliate->referrer_id;
                                        $data['sellerId'] = $affiliate->seller_id;
                                        $data['productId'] = $affiliate->product_id;
                                        $data['commissionRate'] = getAffiliateRates($product)->commissionRate;
                                        if (!empty($data['commissionRate']) && $data['commissionRate'] > 0 && $data['commissionRate'] < 100) {
                                            $commission = ($cartItem->total_price * $data['commissionRate']) / 100;
                                            $data['commission'] = number_format($commission, 2, '.', '');
                                        }
                                        $data['discountRate'] = getAffiliateRates($product)->discountRate;
                                        if (!empty($data['discountRate']) && $data['discountRate'] > 0 && $data['discountRate'] < 100) {
                                            $discount = ($cartItem->total_price * $data['discountRate']) / 100;
                                            $data['discount'] = number_format($discount, 2, '.', '');
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    //check cart has physical products
    public function checkCartHasPhysicalProduct()
    {
        $cartItems = $this->getSessCartItems();
        if (!empty($cartItems)) {
            foreach ($cartItems as $cartItem) {
                if ($cartItem->product_type == 'physical') {
                    return true;
                }
            }
        }
        return false;
    }

    //check cart has digital products
    public function checkCartHasDigitalProduct()
    {
        $cartItems = $this->getSessCartItems();
        if (!empty($cartItems)) {
            foreach ($cartItems as $cartItem) {
                if ($cartItem->product_type == 'digital') {
                    return true;
                }
            }
        }
        return false;
    }

    //validate cart
    public function validateCart()
    {
        $cartTotal = $this->getSessCartTotal();
        if (!empty($cartTotal) && !empty($cartTotal->total)) {
            if ($cartTotal->total <= 0 || $cartTotal->is_stock_available != 1) {
                redirectToUrl(generateUrl('cart'));
            }
        }
    }

    //get cart total session
    public function getSessCartTotal()
    {
        $cartTotal = new \stdClass();
        if (!empty(helperGetSession('mds_shopping_cart_total'))) {
            $cartTotal = helperGetSession('mds_shopping_cart_total');
        }
        return $cartTotal;
    }

    //set cart payment method option session
    public function setSessCartPaymentMethod()
    {
        $std = new \stdClass();
        $std->payment_option = inputPost('payment_option');
        $std->terms_conditions = inputPost('terms_conditions');
        helperSetSession('mds_cart_payment_method', $std);
    }

    //get cart payment method option session
    public function getSessCartPaymentMethod()
    {
        if (!empty(helperGetSession('mds_cart_payment_method'))) {
            return helperGetSession('mds_cart_payment_method');
        }
    }

    //unset cart payment method option session
    public function unsetSessCartPaymentMethod()
    {
        if (!empty(helperGetSession('mds_cart_payment_method'))) {
            helperDeleteSession('mds_cart_payment_method');
        }
    }

    //get cart total by currency
    public function getCartTotalByCurrency($currency)
    {
        $cart = array();
        $newCart = array();
        $this->cartProductIds = array();
        if (!empty(helperGetSession('mds_shopping_cart'))) {
            $cart = helperGetSession('mds_shopping_cart');
        }
        foreach ($cart as $cartItem) {
            $product = getActiveProduct($cartItem->product_id);
            if (!empty($product)) {
                //if purchase type is bidding
                if ($cartItem->purchase_type == 'bidding') {
                    $biddingModel = new BiddingModel();
                    $quoteRequest = $biddingModel->getQuoteRequest($cartItem->quote_request_id);
                    if (!empty($quoteRequest) && $quoteRequest->status == 'pending_payment') {
                        $priceOffered = getPrice($quoteRequest->price_offered, 'decimal');
                        //convert currency
                        if (!empty($currency)) {
                            $priceOffered = convertCurrencyByExchangeRate($priceOffered, $currency->exchange_rate);
                        }
                        $item = new \stdClass();
                        $item->purchase_type = $cartItem->purchase_type;
                        $item->quantity = $cartItem->quantity;
                        $item->product_id = $product->id;
                        $item->unit_price = $priceOffered / $quoteRequest->product_quantity;
                        $item->total_price = $priceOffered;
                        $item->discount_rate = 0;
                        $item->product_vat = 0;
                        $item->is_stock_available = $cartItem->is_stock_available;
                        array_push($newCart, $item);
                    }
                } else {
                    $object = $this->getProductPriceAndStock($product, $cartItem->product_title, $cartItem->options_array);
                    //convert currency
                    if (!empty($currency)) {
                        $object->price_calculated = convertCurrencyByExchangeRate($object->price_calculated, $currency->exchange_rate);
                    }
                    $item = new \stdClass();
                    $item->purchase_type = $cartItem->purchase_type;
                    $item->product_id = $product->id;
                    $item->quantity = $cartItem->quantity;
                    $item->unit_price = $object->price_calculated;
                    $item->total_price = $object->price_calculated * $cartItem->quantity;
                    $item->discount_rate = $object->discount_rate;
                    $productVat = $this->calculateProductVat($object->price_calculated, $product, $cartItem->quantity);
                    $item->product_vat = !empty($productVat) && !empty($productVat['vat']) ? $productVat['vat'] : 0;
                    $item->product_vat_rate = !empty($productVat) && !empty($productVat['vatRate']) ? $productVat['vatRate'] : 0;
                    $item->is_stock_available = $cartItem->is_stock_available;
                    array_push($newCart, $item);
                }
            }
        }
        return $this->calculateCartTotal($newCart, $currency->code, false, true, true);
    }

    //convert currency by payment gateway
    public function convertCurrencyByPaymentGateway($total, $paymentType)
    {
        $data = new \stdClass();
        $data->total = $total;
        $data->currency = $this->selectedCurrency->code;
        $paymentMethod = $this->getSessCartPaymentMethod();
        if ($this->paymentSettings->currency_converter != 1) {
            return $data;
        }
        if (empty($paymentMethod)) {
            return $data;
        }
        if (empty($paymentMethod->payment_option) || $paymentMethod->payment_option == 'bank_transfer' || $paymentMethod->payment_option == 'cash_on_delivery') {
            return $data;
        }
        $paymentGateway = getPaymentGateway($paymentMethod->payment_option);
        if (!empty($paymentGateway)) {
            if (empty($paymentGateway->base_currency) || $paymentGateway->base_currency == "all") {
                $newCurrency = $this->selectedCurrency;
            } else {
                $newCurrency = getCurrencyByCode($paymentGateway->base_currency);
            }
            if ($paymentType == 'sale') {
                if ($paymentGateway->base_currency != $this->selectedCurrency->code && $paymentGateway->base_currency != 'all') {
                    if (!empty($newCurrency)) {
                        $newTotal = $this->getCartTotalByCurrency($newCurrency);
                        if (!empty($newTotal)) {
                            $data->total = $newTotal->total;
                            $data->currency = $newCurrency->code;
                        }
                    }
                }
            } elseif ($paymentType == 'service') {
                $newTotal = convertCurrencyByExchangeRate($total, $newCurrency->exchange_rate);
                if (!empty($newTotal)) {
                    $data->total = $newTotal;
                    $data->currency = $newCurrency->code;
                }
            }
        }
        return $data;
    }

    //apply coupon
    public function applyCoupon($couponCode, $cartItems)
    {
        $couponModel = new CouponModel();
        $couponCode = removeSpecialCharacters($couponCode);
        if ($this->verifyCouponCode($couponCode, true)) {
            helperSetSession('mds_cart_coupon_code', $couponCode);
            return true;
        }
        return false;
    }

    //calculate coupon discount
    public function calculateCouponDiscount($cartItems)
    {
        $couponCode = '';
        $totalDiscount = 0;
        $discountRate = 0;
        $sellerId = 0;
        $productIds = '';
        if (!empty(helperGetSession('mds_cart_coupon_code'))) {
            $couponCode = helperGetSession('mds_cart_coupon_code');
        }
        if (!empty($couponCode)) {
            $coupon = $this->verifyCouponCode($couponCode, false);
            if (!empty($coupon)) {
                $sellerId = $coupon->seller_id;
                if (!empty($coupon) && !empty($coupon->product_ids)) {
                    $discountRate = $coupon->discount_rate;
                    $ids_array = explode(',', $coupon->product_ids);
                    if (!empty($ids_array) && is_array($ids_array) && countItems($ids_array) > 0) {
                        if (!empty($cartItems)) {
                            foreach ($cartItems as $cartItem) {
                                if (!empty($cartItem->product_id) && in_array($cartItem->product_id, $ids_array)) {
                                    $productIds .= $cartItem->product_id . ',';
                                    $discount = ($cartItem->total_price * $coupon->discount_rate) / 100;
                                    $discount = number_format($discount, 2, ".", "");
                                    $totalDiscount += $discount;
                                }
                            }
                        }
                    }
                }
            }
        }
        return ['discount_rate' => $discountRate, 'total_discount' => $totalDiscount, 'seller_id' => $sellerId, 'product_ids' => $productIds];
    }

    //verify coupon code
    public function verifyCouponCode($couponCode, $setMessage)
    {
        $couponModel = new CouponModel();
        $coupon = $couponModel->getCouponByCodeCart($couponCode);
        if (!empty($coupon)) {
            if (date('Y-m-d H:i:s') > $coupon->expiry_date) {
                $this->removeCoupon();
                if ($setMessage) {
                    $this->session->setFlashdata('error_coupon_code', trans("msg_invalid_coupon"));
                }
                return false;
            }
            if ($coupon->coupon_count <= $coupon->used_coupon_count) {
                $this->removeCoupon();
                if ($setMessage) {
                    $this->session->setFlashdata('error_coupon_code', trans("msg_coupon_limit"));
                }
                return false;
            }
            if ($coupon->coupon_count <= $coupon->used_coupon_count) {
                $this->removeCoupon();
                if ($setMessage) {
                    $this->session->setFlashdata('error_coupon_code', trans("msg_coupon_limit"));
                }
                return false;
            }
            if ($coupon->usage_type == 'single') {
                if (!authCheck()) {
                    $this->removeCoupon();
                    if ($setMessage) {
                        $this->session->setFlashdata('error_coupon_code', trans("msg_coupon_auth"));
                    }
                    return false;
                }
                if ($couponModel->isCouponUsed(user()->id, $couponCode) > 0) {
                    $this->removeCoupon();
                    if ($setMessage) {
                        $this->session->setFlashdata('error_coupon_code', trans("msg_coupon_used"));
                    }
                    return false;
                }
            }
            $cartTotal = $this->getSessCartTotal();
            $sellerCartTotal = 0;
            $cartItems = helperGetSession('mds_shopping_cart');
            if (!empty($cartItems)) {
                foreach ($cartItems as $cartItem) {
                    if ($cartItem->seller_id == $coupon->seller_id) {
                        $sellerCartTotal += $cartItem->total_price;
                    }
                }
            }
            $minAmount = getPrice($coupon->minimum_order_amount, 'decimal');
            $currency = getCurrencyByCode($cartTotal->currency);
            if (!empty($currency)) {
                $minAmount = convertCurrencyByExchangeRate($minAmount, $currency->exchange_rate);
            }
            if ($sellerCartTotal < $minAmount) {
                $this->removeCoupon();
                if ($setMessage) {
                    $this->session->setFlashdata('error_coupon_code', trans("msg_coupon_cart_total") . " " . priceCurrencyFormat($minAmount, $cartTotal->currency));
                }
                return false;
            }
            return $coupon;
        }
        $this->removeCoupon();
        if ($setMessage) {
            $this->session->setFlashdata('error_coupon_code', trans("msg_invalid_coupon"));
        }
        return false;
    }

    //remove coupon
    public function removeCoupon()
    {
        if (!empty(helperGetSession('mds_cart_coupon_code'))) {
            helperDeleteSession('mds_cart_coupon_code');
        }
    }

    //set shipping address
    public function setShippingAddress($totalCost)
    {
        $isSame = !empty(inputPost('use_same_address_for_billing')) ? 1 : 0;
        $data = new \stdClass();
        $data->totalCost = $totalCost;
        $data->useSameAddressForBilling = $isSame;
        $data->isGuest = 0;
        if (authCheck()) {
            $profileModel = new ProfileModel();
            $sAddressId = inputPost('shipping_address_id');
            $bAddressId = inputPost('billing_address_id');
            $sAddress = $profileModel->getShippingAddressById($sAddressId, user()->id);
            if ($isSame) {
                $bAddressId = 0;
                $bAddress = $sAddress;
            } else {
                $bAddress = $profileModel->getShippingAddressById($bAddressId, user()->id);
                if (empty($bAddress)) {
                    $bAddress = $sAddress;
                    $data->useSameAddressForBilling = 1;
                }
            }
            if (!empty($sAddress)) {
                $country = getCountry($sAddress->country_id);
                $state = getState($sAddress->state_id);
                $data->shippingAddressId = $sAddressId;
                $data->shippingStateId = $sAddress->state_id;
                $data->sTitle = $sAddress->title;
                $data->sFirstName = $sAddress->first_name;
                $data->sLastName = $sAddress->last_name;
                $data->sEmail = $sAddress->email;
                $data->sPhoneNumber = $sAddress->phone_number;
                $data->sAddress = $sAddress->address;
                $data->sCountryId = !empty($country) ? $country->id : 0;
                $data->sCountry = !empty($country) ? $country->name : '';
                $data->sStateId = !empty($state) ? $state->id : 0;
                $data->sState = !empty($state) ? $state->name : '';
                $data->sCity = $sAddress->city;
                $data->sZipCode = $sAddress->zip_code;
            }
            if (!empty($bAddress)) {
                $country = getCountry($bAddress->country_id);
                $state = getState($bAddress->state_id);
                $data->billingAddressId = $bAddressId;
                $data->bTitle = $bAddress->title;
                $data->bFirstName = $bAddress->first_name;
                $data->bLastName = $bAddress->last_name;
                $data->bEmail = $bAddress->email;
                $data->bPhoneNumber = $bAddress->phone_number;
                $data->bAddress = $bAddress->address;
                $data->bCountryId = !empty($country) ? $country->id : 0;
                $data->bCountry = !empty($country) ? $country->name : '';
                $data->bStateId = !empty($state) ? $state->id : 0;
                $data->bState = !empty($state) ? $state->name : '';
                $data->bCity = $bAddress->city;
                $data->bZipCode = $bAddress->zip_code;
            }
        } else {
            $sCountry = getCountry(inputPost('shipping_country_id'));
            $sState = getState(inputPost('shipping_state_id'));
            $bCountry = $sCountry;
            $bState = $sState;
            if (!$isSame) {
                $bCountry = getCountry(inputPost('billing_country_id'));
                $bState = getState(inputPost('billing_state_id'));
            }
            $data->isGuest = 1;
            $data->shippingAddressId = 0;
            $data->shippingStateId = !empty($sState) ? $sState->id : 0;
            $data->sTitle = 'Main';
            $data->sFirstName = inputPost('shipping_first_name');
            $data->sLastName = inputPost('shipping_last_name');
            $data->sEmail = inputPost('shipping_email');
            $data->sPhoneNumber = inputPost('shipping_phone_number');
            $data->sAddress = inputPost('shipping_address');
            $data->sCountryId = !empty($sCountry) ? $sCountry->id : '';
            $data->sCountry = !empty($sCountry) ? $sCountry->name : '';
            $data->sStateId = !empty($sState) ? $sState->id : '';
            $data->sState = !empty($sState) ? $sState->name : '';
            $data->sCity = inputPost('shipping_city');
            $data->sZipCode = inputPost('shipping_zip_code');
            $data->bTitle = 'Main';
            $data->bFirstName = $isSame ? $data->sFirstName : inputPost('billing_first_name');
            $data->bLastName = $isSame ? $data->sLastName : inputPost('billing_last_name');
            $data->bEmail = $isSame ? $data->sEmail : inputPost('billing_email');
            $data->bPhoneNumber = $isSame ? $data->sPhoneNumber : inputPost('billing_phone_number');
            $data->bAddress = $isSame ? $data->sAddress : inputPost('billing_address');
            $data->bCountryId = !empty($bCountry) ? $bCountry->id : '';
            $data->bCountry = !empty($bCountry) ? $bCountry->name : '';
            $data->bStateId = !empty($bState) ? $bState->id : '';
            $data->bState = !empty($bState) ? $bState->name : '';
            $data->bCity = $isSame ? $data->sCity : inputPost('billing_city');
            $data->bZipCode = $isSame ? $data->sZipCode : inputPost('billing_zip_code');
        }
        helperSetSession('mds_cart_shipping', $data);
    }

    //set transaction fee
    public function setTransactionFee($cartTotal, $cartItems)
    {
        $cartPaymentMethod = $this->getSessCartPaymentMethod();
        if (!empty($cartPaymentMethod) && !empty($cartPaymentMethod->payment_option)) {
            if (!empty($cartTotal)) {
                $total = $cartTotal->total;
                $totalBeforeShipping = $cartTotal->total_before_shipping;
                $transactionFee = 0;
                $transactionFeeRate = 0;
                if ($cartPaymentMethod->payment_option == 'cash_on_delivery') {
                    $cartVendorArray = [];
                    if (!empty($cartItems)) {
                        foreach ($cartItems as $item) {
                            if (!in_array($item->seller_id, $cartVendorArray)) {
                                array_push($cartVendorArray, $item->seller_id);
                            }
                        }
                        if (!empty($transactionFee)) {
                            $transactionFee = getPrice($transactionFee, 'decimal');
                        }
                        $currency = getCurrencyByCode($this->selectedCurrency->code);
                        if (!empty($currency)) {
                            $transactionFee = convertCurrencyByExchangeRate($transactionFee, $currency->exchange_rate);
                        }
                    }
                } else {
                    if ($cartPaymentMethod->payment_option != 'bank_transfer') {
                        $paymentGateway = getPaymentGateway($cartPaymentMethod->payment_option);
                        if (!empty($paymentGateway) && !empty($paymentGateway->transaction_fee) && $paymentGateway->transaction_fee > 0) {
                            $transactionFee = ($total * $paymentGateway->transaction_fee) / 100;
                            $transactionFeeRate = $paymentGateway->transaction_fee;
                        }
                    }
                }
                //update cart
                if (!empty($transactionFee)) {
                    $transactionFee = number_format($transactionFee, 2, '.', '');
                    $cartTotal->transaction_fee = $transactionFee;
                    $cartTotal->transaction_fee_rate = $transactionFeeRate;
                    $cartTotal->total = number_format($total + $transactionFee, 2, '.', '');
                    $cartTotal->total_before_shipping = number_format($totalBeforeShipping + $transactionFee, 2, '.', '');
                }
            }
        }
        return $cartTotal;
    }

    //set cart customer location
    public function setCartCustomerLocation()
    {
        $cartShipping = helperGetSession('mds_cart_shipping');
        $location = new \stdClass();
        $location->countryId = 0;
        $location->stateId = 0;
        if (!empty($cartShipping)) {
            if (!empty($cartShipping->sCountryId)) {
                $location->countryId = $cartShipping->sCountryId;
            }
            if (!empty($cartShipping->sStateId)) {
                $location->stateId = $cartShipping->sStateId;
            }
        }
        if (empty($location->countryId) || empty($location->stateId)) {
            if (authCheck()) {
                $location->countryId = user()->country_id;
                $location->stateId = user()->state_id;
            }
        }

        if (empty($location->countryId) || empty($location->stateId)) {
            $servicePayment = helperGetSession('mds_service_payment');
            if(!empty($servicePayment) && $servicePayment->paymentType == 'add_funds'){
                $location->countryId = 1;
                $location->stateId = 1;
            }
        }

        helperSetSession('cartCustomerLocation', $location);
    }

    //get cart customer location
    public function getCartCustomerLocation()
    {
        $location = new \stdClass();
        $location->countryId = 0;
        $location->stateId = 0;
        $sessionLocation = helperGetSession('cartCustomerLocation');
        if (!empty($sessionLocation)) {
            if (!empty($sessionLocation->countryId)) {
                $location->countryId = $sessionLocation->countryId;
            }
            if (!empty($sessionLocation->stateId)) {
                $location->stateId = $sessionLocation->stateId;
            }
        }
        return $location;
    }

    //clear cart
    public function clearCart()
    {
        helperDeleteSession('mds_shopping_cart');
        helperDeleteSession('mds_shopping_cart_total');
        helperDeleteSession('mds_shopping_cart_final');
        helperDeleteSession('mds_shopping_cart_total_final');
        helperDeleteSession('mds_cart_shipping');
        $this->unsetSessCartPaymentMethod();
        $this->removeCoupon();
    }
}
