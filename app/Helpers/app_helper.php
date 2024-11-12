<?php

use App\Models\EarningsModel;
use Config\Globals;

//get default language id
if (!function_exists('defaultLangId')) {
    function defaultLangId()
    {
        if (!empty(Globals::$defaultLang)) {
            return Globals::$defaultLang->id;
        }
        return 0;
    }
}

//get active language id
if (!function_exists('selectedLangId')) {
    function selectedLangId()
    {
        if (!empty(Globals::$activeLang)) {
            return Globals::$activeLang->id;
        }
        return 0;
    }
}

//check language exist
if (!function_exists('checkLanguageExist')) {
    function checkLanguageExist($langId)
    {
        if (!empty(Globals::$languages)) {
            foreach (Globals::$languages as $language) {
                if ($langId == $language->id) {
                    return true;
                }
            }
        }
        return false;
    }
}

//get user avatar
if (!function_exists('getUserAvatar')) {
    function getUserAvatar($user)
    {
        if (!empty($user)) {
            if (!empty($user->avatar) && file_exists(FCPATH . $user->avatar)) {
                return base_url($user->avatar);
            } elseif (!empty($user->avatar) && !empty($user->user_type) && $user->user_type != 'registered') {
                return $user->avatar;
            }
        }
        return base_url('assets/img/user.png');
    }
}

//get user avatar by id
if (!function_exists('getUserAvatarById')) {
    function getUserAvatarById($userId)
    {
        $user = getUser($userId);
        if (!empty($user)) {
            if (!empty($user->avatar) && file_exists(FCPATH . $user->avatar)) {
                return base_url($user->avatar);
            } elseif (!empty($user->avatar) && $user->user_type != 'registered') {
                return $user->avatar;
            }
        }
        return base_url('assets/img/user.png');
    }
}

//get chat user avatar
if (!function_exists('getChatUserAvatar')) {
    function getChatUserAvatar($message)
    {
        if (!empty($message) && !empty($message->user_avatar)) {
            if (file_exists(FCPATH . $message->user_avatar)) {
                return base_url($message->user_avatar);
            }
        }
        return base_url('assets/img/user.png');
    }
}

//get user avatar by image url
if (!function_exists('getUserAvatarByImageURL')) {
    function getUserAvatarByImageURL($imageURL, $userType)
    {
        if (!empty($imageURL)) {
            if ($userType != 'registered') {
                return $imageURL;
            } else {
                return base_url($imageURL);
            }
        }
        return base_url('assets/img/user.png');
    }
}

//get page by default name
if (!function_exists('getPageByDefaultName')) {
    function getPageByDefaultName($defaultName, $langId)
    {
        $model = new \App\Models\PageModel();
        return $model->getPageByDefaultName($defaultName, $langId);
    }
}

//get continents
if (!function_exists('getContinents')) {
    function getContinents()
    {
        return array('EU' => 'Europe', 'AS' => 'Asia', 'AF' => 'Africa', 'NA' => 'North America', 'SA' => 'South America', 'OC' => 'Oceania', 'AN' => 'Antarctica');
    }
}

//get continent name by key
if (!function_exists('getContinentNameByKey')) {
    function getContinentNameByKey($continentKey)
    {
        $continents = getContinents();
        if (!empty($continents)) {
            foreach ($continents as $key => $value) {
                if ($key == $continentKey) {
                    return $value;
                }
            }
        }
        return '';
    }
}

//get countries
if (!function_exists('getCountries')) {
    function getCountries()
    {
        $model = new \App\Models\LocationModel();
        return $model->getCountries();
    }
}

//get country
if (!function_exists('getCountry')) {
    function getCountry($id)
    {
        $model = new \App\Models\LocationModel();
        return $model->getCountry($id);
    }
}

//get state
if (!function_exists('getState')) {
    function getState($id)
    {
        $model = new \App\Models\LocationModel();
        return $model->getState($id);
    }
}

//get city
if (!function_exists('getCity')) {
    function getCity($id)
    {
        $model = new \App\Models\LocationModel();
        return $model->getCity($id);
    }
}

//get states by country
if (!function_exists('getStatesByCountry')) {
    function getStatesByCountry($countryId)
    {
        $model = new \App\Models\LocationModel();
        return $model->getStatesByCountry($countryId);
    }
}

//get cities by state
if (!function_exists('getCitiesByState')) {
    function getCitiesByState($stateId)
    {
        $model = new \App\Models\LocationModel();
        return $model->getCitiesByState($stateId);
    }
}

//get role
if (!function_exists('getRoleById')) {
    function getRoleById($id)
    {
        $model = new \App\Models\MembershipModel();
        return $model->getRole($id);
    }
}

//get role name
if (!function_exists('getRoleName')) {
    function getRoleName($role)
    {
        $name = '';
        $nameDefault = '';
        $nameFirst = '';
        if (!empty($role)) {
            $nameArray = unserializeData($role->role_name);
            if (!empty($nameArray) && countItems($nameArray) > 0) {
                $i = 0;
                foreach ($nameArray as $item) {
                    if (!empty($item['lang_id']) && !empty($item['name'])) {
                        if ($item['lang_id'] == selectedLangId()) {
                            $name = $item['name'];
                        }
                        if ($item['lang_id'] == Globals::$defaultLang->id) {
                            $nameDefault = $item['name'];
                        }
                        if ($i == 0) {
                            $nameFirst = $item['name'];
                        }
                    }
                    $i++;
                }
            }
        }
        if (empty($name)) {
            $name = $nameDefault;
        }
        if (empty($name)) {
            $name = $nameFirst;
        }
        return $name;
    }
}

//get membership plan
if (!function_exists('getMembershipPlan')) {
    function getMembershipPlan($id)
    {
        $model = new \App\Models\MembershipModel();
        return $model->getPlan($id);
    }
}

//get membership plan title
if (!function_exists('getMembershipPlanTitle')) {
    function getMembershipPlanTitle($id)
    {
        $model = new \App\Models\MembershipModel();
        return $model->getMembershipPlanTitle($id);
    }
}

//get membership plan name
if (!function_exists('getMembershipPlanName')) {
    function getMembershipPlanName($titleArray, $langId)
    {
        if (!empty($titleArray)) {
            $array = unserializeData($titleArray);
            if (!empty($array)) {
                $main = '';
                foreach ($array as $item) {
                    if ($item['lang_id'] == $langId) {
                        return $item['title'];
                    }
                    if ($item['lang_id'] == Globals::$generalSettings->site_lang) {
                        $main = $item['title'];
                    }
                }
                return $main;
            }
        }
        return '';
    }
}

//get membership plan features
if (!function_exists('getMembershipPlanFeatures')) {
    function getMembershipPlanFeatures($featuresArray, $langId)
    {
        if (!empty($featuresArray)) {
            $array = unserializeData($featuresArray);
            if (!empty($array)) {
                $main = '';
                foreach ($array as $item) {
                    if ($item['lang_id'] == $langId) {
                        if (!empty($item['features'])) {
                            return $item['features'];
                        }
                    }
                    if ($item['lang_id'] == Globals::$defaultLang->id) {
                        if (!empty($item['features'])) {
                            $main = $item['features'];
                        }
                    }
                }
                return $main;
            }
        }
        return '';
    }
}

//get user payout account
if (!function_exists('getUserPayoutAccount')) {
    function getUserPayoutAccount($userId)
    {
        $model = new \App\Models\EarningsModel();
        return $model->getUserPayoutAccount($userId);
    }
}

//get location
if (!function_exists('getLocation')) {
    function getLocation($object, $isForEstimatedDelivery = false)
    {
        $model = new \App\Models\LocationModel();
        $location = '';
        if (!empty($object)) {
            if ($isForEstimatedDelivery == false) {
                if (!empty($object->address)) {
                    $location = $object->address;
                }
                if (!empty($object->zip_code)) {
                    $location .= ' ' . $object->zip_code;
                }
                if (!empty($object->city_id)) {
                    $city = $model->getCity($object->city_id);
                    if (!empty($city)) {
                        if (!empty($object->address) || !empty($object->zip_code)) {
                            $location .= " ";
                        }
                        $location .= $city->name;
                    }
                }
            }
            if (!empty($object->state_id)) {
                $state = $model->getState($object->state_id);
                if (!empty($state)) {
                    if (!empty($object->address) || !empty($object->zip_code) || !empty($object->city_id)) {
                        $location .= ', ';
                    }
                    if ($isForEstimatedDelivery == true) {
                        $location = '';
                    }
                    $location .= $state->name;
                }
            }
            if (!empty($object->country_id)) {
                $country = $model->getCountry($object->country_id);
                if (!empty($country)) {
                    if (!empty($object->state_id) || !empty($object->city_id) || !empty($object->address) || !empty($object->zip_code)) {
                        $location .= ', ';
                    }
                    $location .= $country->name;
                }
            }
        }
        return $location;
    }
}

//get estimated delivery location
if (!function_exists('getEstimatedDeliveryLocation')) {
    function getEstimatedDeliveryLocation()
    {
        $objUser = new stdClass();
        if (authCheck()) {
            $objUser = user();
        } else {
            $location = helperGetSession('mds_estimated_delivery_location');
            if (!empty($location) && !empty($location['country_id']) && !empty($location['state_id'])) {
                $objUser->country_id = $location['country_id'];
                $objUser->state_id = $location['state_id'];
            }
        }
        if (!empty($objUser) && !empty($objUser->country_id) && !empty($objUser->state_id)) {
            return getLocation($objUser, true);
        }
        return '';
    }
}

//add to email queue
if (!function_exists('addToEmailQueue')) {
    function addToEmailQueue($data)
    {
        $model = new \App\Models\EmailModel();
        return $model->addToEmailQueue($data);
    }
}

//get order
if (!function_exists('getOrder')) {
    function getOrder($id)
    {
        $model = new \App\Models\OrderModel();
        return $model->getOrder($id);
    }
}

//get order by order number
if (!function_exists('getOrderByOrderNumber')) {
    function getOrderByOrderNumber($orderNumber)
    {
        $model = new \App\Models\OrderModel();
        return $model->getOrderByOrderNumber($orderNumber);
    }
}

//get order product
if (!function_exists('getOrderProduct')) {
    function getOrderProduct($id)
    {
        $model = new \App\Models\OrderModel();
        return $model->getOrderProduct($id);
    }
}

//get earning by order product
if (!function_exists('getEarningByOrderProductId')) {
    function getEarningByOrderProductId($orderProductId, $orderNumber)
    {
        $model = new \App\Models\EarningsModel();
        return $model->getEarningByOrderProductId($orderProductId, $orderNumber);
    }
}

//check if user bought product
if (!function_exists('checkUserBoughtProduct')) {
    function checkUserBoughtProduct($userId, $productId)
    {
        $model = new \App\Models\OrderModel();
        return $model->checkUserBoughtProduct($userId, $productId);
    }
}

//get currency by code
if (!function_exists('getCurrencyByCode')) {
    function getCurrencyByCode($currencyCode)
    {
        if (!empty(Globals::$currencies[$currencyCode])) {
            return Globals::$currencies[$currencyCode];
        }
    }
}

//get currency symbol
if (!function_exists('getCurrencySymbol')) {
    function getCurrencySymbol($currencyCode)
    {
        if (!empty(Globals::$currencies)) {
            if (isset(Globals::$currencies[$currencyCode])) {
                return Globals::$currencies[$currencyCode]->symbol;
            }
        }
        return '';
    }
}

//get shipping locations by zone
if (!function_exists('getShippingLocationsByZone')) {
    function getShippingLocationsByZone($zoneId)
    {
        $model = new \App\Models\ShippingModel();
        return $model->getShippingLocationsByZone($zoneId);
    }
}

//get shipping payment methods by zone
if (!function_exists('getShippingPaymentMethodsByZone')) {
    function getShippingPaymentMethodsByZone($zoneId)
    {
        $model = new \App\Models\ShippingModel();
        return $model->getShippingPaymentMethodsByZone($zoneId);
    }
}

//get shipping methods
if (!function_exists('getShippingMethods')) {
    function getShippingMethods()
    {
        return ['flat_rate', 'local_pickup', 'free_shipping'];
    }
}

//get shipping class cost by method
if (!function_exists('getShippingClassCostByMethod')) {
    function getShippingClassCostByMethod($costArray, $classId)
    {
        if (!empty($costArray) && !empty($classId)) {
            $model = new \App\Models\ShippingModel();
            $shippingClass = $model->getShippingClass($classId);
            if (!empty($shippingClass) && $shippingClass->status == 1) {
                $costArray = unserializeData($costArray);
                if (!empty($costArray)) {
                    foreach ($costArray as $item) {
                        if ($item['class_id'] == $classId && !empty($item['cost'])) {
                            return esc($item['cost']);
                        }
                    }
                }
            }
        }
    }
}

//get coupon
if (!function_exists('getCouponById')) {
    function getCouponById($id)
    {
        $model = new \App\Models\CouponModel();
        return $model->getCoupon($id);
    }
}

//get coupon by code
if (!function_exists('getCouponByCode')) {
    function getCouponByCode($code)
    {
        $model = new \App\Models\CouponModel();
        return $model->getCouponByCode($code);
    }
}

//get used coupons count
if (!function_exists('getUsedCouponsCount')) {
    function getUsedCouponsCount($couponCode)
    {
        $model = new \App\Models\CouponModel();
        return $model->getUsedCouponsCount($couponCode);
    }
}

//get subcategories
if (!function_exists('getSubCategories')) {
    function getSubCategories($parentId)
    {
        $model = new \App\Models\CategoryModel();
        return $model->getSubCategoriesByParentId($parentId);
    }
}

//get coupon products by category
if (!function_exists('getCouponProductsByCategory')) {
    function getCouponProductsByCategory($userId, $categoryId)
    {
        $model = new \App\Models\CouponModel();
        return $model->getCouponProductsByCategory($userId, $categoryId);
    }
}

//get user plan
if (!function_exists('getUserPlanByUserId')) {
    function getUserPlanByUserId($userId)
    {
        $model = new \App\Models\MembershipModel();
        return $model->getUserPlanByUserId($userId);
    }
}

//calculate user rating
if (!function_exists('calculateUserRating')) {
    function calculateUserRating($userId)
    {
        $model = new \App\Models\CommonModel();
        return $model->calculateUserRating($userId);
    }
}

//get user drafts count
if (!function_exists('getUserDownloadsCount')) {
    function getUserDownloadsCount($userId)
    {
        $model = new \App\Models\ProductModel();
        return $model->getUserDownloadsCount($userId);
    }
}

//get followers count
if (!function_exists('getFollowersCount')) {
    function getFollowersCount($followingId)
    {
        $model = new \App\Models\ProfileModel();
        return $model->getFollowersCount($followingId);
    }
}

//get following users count
if (!function_exists('getFollowingUsersCount')) {
    function getFollowingUsersCount($followerId)
    {
        $model = new \App\Models\ProfileModel();
        return $model->getFollowingUsersCount($followerId);
    }
}

//get my reviews count
if (!function_exists('getMyReviewsCount')) {
    function getMyReviewsCount($userId)
    {
        $model = new \App\Models\CommonModel();
        return $model->getMyReviewsCount($userId);
    }
}

if (!function_exists('isUserOnline')) {
    function isUserOnline($timestamp)
    {
        $timeAgo = strtotime($timestamp);
        $currentTime = time();
        $timeDifference = $currentTime - $timeAgo;
        $seconds = $timeDifference;
        $minutes = round($seconds / 60);
        if ($minutes <= 2) {
            return true;
        } else {
            return false;
        }
    }
}

//check user follows
if (!function_exists('isUserFollows')) {
    function isUserFollows($followingId, $followerId)
    {
        $model = new \App\Models\ProfileModel();
        return $model->isUserFollows($followingId, $followerId);
    }
}

//get review
if (!function_exists('getReview')) {
    function getReview($productId, $userId)
    {
        $model = new \App\Models\CommonModel();
        return $model->getReview($productId, $userId);
    }
}

//get digital sale by buyer id
if (!function_exists('getDigitalSaleByBuyerId')) {
    function getDigitalSaleByBuyerId($buyerId, $productId)
    {
        $model = new \App\Models\ProductModel();
        return $model->getDigitalSaleByBuyerId($buyerId, $productId);
    }
}

//get digital sale by order id
if (!function_exists('getDigitalSaleByOrderId')) {
    function getDigitalSaleByOrderId($buyerId, $productId, $orderId)
    {
        $model = new \App\Models\ProductModel();
        return $model->getDigitalSaleByOrderId($buyerId, $productId, $orderId);
    }
}

//get order products
if (!function_exists('getOrderProducts')) {
    function getOrderProducts($orderId)
    {
        $model = new \App\Models\OrderModel();
        return $model->getOrderProducts($orderId);
    }
}

//cart discount coupon
if (!function_exists('getCartDiscountCoupon')) {
    function getCartDiscountCoupon()
    {
        if (!empty(helperGetSession('mds_cart_coupon_code'))) {
            return helperGetSession('mds_cart_coupon_code');
        }
    }
}

//get payment gateway
if (!function_exists('getPaymentGateway')) {
    function getPaymentGateway($nameKey)
    {
        $model = new \App\Models\SettingsModel();
        return $model->getPaymentGateway($nameKey);
    }
}

//get payment method
if (!function_exists('getPaymentMethod')) {
    function getPaymentMethod($paymentMethod)
    {
        if ($paymentMethod == 'Bank Transfer') {
            return trans("bank_transfer");
        } elseif ($paymentMethod == 'Cash On Delivery') {
            return trans("cash_on_delivery");
        } else {
            return $paymentMethod;
        }
    }
}

//get payment status
if (!function_exists('getPaymentStatus')) {
    function getPaymentStatus($paymentStatus)
    {
        if ($paymentStatus == "payment_received") {
            return trans("payment_received");
        } elseif ($paymentStatus == "awaiting_payment") {
            return trans("awaiting_payment");
        } elseif ($paymentStatus == "Completed") {
            return trans("completed");
        } else {
            return $paymentStatus;
        }
    }
}

//get active payment gateways
if (!function_exists('getActivePaymentGateways')) {
    function getActivePaymentGateways()
    {
        $model = new \App\Models\SettingsModel();
        return $model->getActivePaymentGateways();
    }
}

//get transaction by order id
if (!function_exists('getTransactionByOrderId')) {
    function getTransactionByOrderId($orderId)
    {
        $model = new \App\Models\OrderAdminModel();
        return $model->getTransactionByOrderId($orderId);
    }
}

//get cart customer data
if (!function_exists('getCartCustomerData')) {
    function getCartCustomerData()
    {
        $user = null;
        if (authCheck()) {
            $user = user();
        } else {
            $user = new stdClass();
            $user->id = 0;
            $user->first_name = '';
            $user->last_name = '';
            $user->email = "unknown@domain.com";
            $user->phone_number = "11111111";
            $cartShipping = helperGetSession('mds_cart_shipping');
            if (!empty($cartShipping)) {
                if (!empty($cartShipping->sFirstName)) {
                    $user->first_name = $cartShipping->sFirstName;
                }
                if (!empty($cartShipping->sLastName)) {
                    $user->last_name = $cartShipping->sLastName;
                }
                if (!empty($cartShipping->sEmail)) {
                    $user->email = $cartShipping->sEmail;
                }
                if (!empty($cartShipping->sPhoneNumber)) {
                    $user->phone_number = $cartShipping->sPhoneNumber;
                }
            }
        }
        return $user;
    }
}

//get tax name
if (!function_exists('getTaxName')) {
    function getTaxName($nameData, $langId)
    {
        if (!empty($nameData)) {
            $nameArray = unserializeData($nameData);
            if (!empty($nameArray[$langId])) {
                return $nameArray[$langId];
            }
            if (!empty($nameArray[Globals::$defaultLang->id])) {
                return $nameArray[Globals::$defaultLang->id];
            }
        }
        return 'Global Tax';
    }
}

//can user pay with balance
if (!function_exists('canPayWithBalance')) {
    function canPayWithBalance($total, $currencyCode)
    {
        if (Globals::$paymentSettings->pay_with_wallet_balance && authCheck() && !empty($total) && !empty($currencyCode)) {
            $balance = user()->balance;
            $total = convertToDefaultCurrency($total, $currencyCode);
            $total = getPrice($total, 'database');
            if ($balance >= $total) {
                return true;
            }
        }
        return false;
    }
}

//check if product is an active affiliate product
if (!function_exists('isActiveAffiliateProduct')) {
    function isActiveAffiliateProduct($product, $vendor)
    {
        $status = false;
        if (Globals::$generalSettings->affiliate_status == 1 && !empty($product) && !empty($vendor)) {
            if (authCheck() && user()->is_affiliate == 1 && $product->listing_type != 'ordinary_listing' && $product->listing_type != 'bidding' && $product->is_free_product != 1) {
                $status = true;
            }
            if (Globals::$generalSettings->affiliate_type == 'seller_based') {
                if ($vendor->vendor_affiliate_status == 0) {
                    $status = false;
                } elseif ($vendor->vendor_affiliate_status == 2 && $product->is_affiliate != 1) {
                    $status = false;
                }
            }
            if (authCheck() && user()->id == $product->user_id) {
                $status = false;
            }
        }
        return $status;
    }
}

//get affiliate rates
if (!function_exists('getAffiliateRates')) {
    function getAffiliateRates($product)
    {
        $data = new stdClass();
        $data->commissionRate = 0;
        $data->discountRate = 0;
        if (!empty($product)) {
            $generalSettings = Globals::$generalSettings;
            if ($generalSettings->affiliate_status == 1) {
                if ($generalSettings->affiliate_type == 'seller_based') {
                    $seller = getUser($product->user_id);
                    if (!empty($seller)) {
                        if (($seller->vendor_affiliate_status == 1) || ($seller->vendor_affiliate_status == 2 && $product->is_affiliate == 1)) {
                            $data->commissionRate = $seller->affiliate_commission_rate;
                            $data->discountRate = $seller->affiliate_discount_rate;
                        }
                    }
                } else {
                    $data->commissionRate = $generalSettings->affiliate_commission_rate;
                    $data->discountRate = $generalSettings->affiliate_discount_rate;
                }
            }
        }
        return $data;
    }
}

//get brand name
if (!function_exists('getBrandName')) {
    function getBrandName($nameData, $langId)
    {
        $nameArray = unserializeData($nameData);
        if (!empty($nameArray[$langId])) {
            return $nameArray[$langId];
        }
        if (!empty($nameArray[Globals::$defaultLang->id])) {
            return $nameArray[Globals::$defaultLang->id];
        }
        return 'Brand';
    }
}

//get branda
if (!function_exists('getBrands')) {
    function getBrands($limit = null)
    {
        $model = new \App\Models\CommonModel();
        return $model->getBrands($limit);
    }
}

//get brand name by id
if (!function_exists('getBrandNameById')) {
    function getBrandNameById($id, $brands)
    {
        $brand = null;
        if (!empty($brands)) {
            $brand = array_filter($brands, function ($item) use ($id) {
                return $item->id == $id;
            });
            if (!empty($brand)) {
                foreach ($brand as $key => $value) {
                    $brand = $value;
                    break;
                }
            }
        }
        if (!empty($brand)) {
            return getBrandName($brand->name_data, selectedLangId());
        }
        return '';
    }
}

//get additional invoice info
if (!function_exists('getAdditionalInvoiceInfo')) {
    function getAdditionalInvoiceInfo($langId)
    {
        $info = '';
        $paymentSettings = Globals::$paymentSettings;
        if (!empty($paymentSettings->additional_invoice_info)) {
            $data = unserializeData($paymentSettings->additional_invoice_info);
            if (!empty($data)) {
                foreach ($data as $item) {
                    if (!empty($item['lang_id']) && !empty($item['text'])) {
                        if ($item['lang_id'] == $langId) {
                            $info = $item['text'];
                        }
                    }
                }
            }
        }
        return $info;
    }
}


//get email option status
if (!function_exists('getEmailOptionStatus')) {
    function getEmailOptionStatus($generalSettings, $key)
    {
        if (!empty($generalSettings->email_options)) {
            $data = unserializeData($generalSettings->email_options);
            if (!empty($data) && !empty($data[$key]) && $data[$key] == 1) {
                return 1;
            }
        }
        return 0;
    }
}

//get last bank transfer record
if (!function_exists('getLastBankTransfer')) {
    function getLastBankTransfer($reportType, $itemId)
    {
        $model = new \App\Models\CommonModel();
        return $model->getLastBankTransfer($reportType, $itemId);
    }
}

//check vendor commission debt
if (!function_exists('checkVendorCommissionDept')) {
    function checkVendorCommissionDept()
    {
        if (authCheck()) {
            if (user()->commission_debt > 0) {
                $earningsModel = new EarningsModel();
                $earningsModel->deductCommissionDebtFromWallet();
            }
        }
    }
}

//get vendor pages
if (!function_exists('getVendorPages')) {
    function getVendorPages($userId)
    {
        $model = new \App\Models\PageModel();
        return $model->getVendorPagesByUserId($userId);
    }
}


//create form checkbox
if (!function_exists('formCheckbox')) {
    function formCheckbox($inputName, $val, $text, $checkedValue = null)
    {
        $id = 'c' . generateToken(true);
        $check = $checkedValue == $val ? ' checked' : '';
        return '<div class="custom-control custom-checkbox">' . PHP_EOL .
            '<input type="checkbox" name="' . $inputName . '" value="' . $val . '" id="' . $id . '" class="custom-control-input"' . $check . '>' . PHP_EOL .
            '<label for="' . $id . '" class="custom-control-label">' . $text . '</label>' . PHP_EOL .
            '</div>';
    }
}


//create form radio button
if (!function_exists('formRadio')) {
    function formRadio($inputName, $val1, $val2, $op1Text, $op2Text, $checkedValue = null, $colClass = 'col-md-6')
    {
        $id1 = 'r' . generateToken(true);
        $id2 = 'r' . generateToken(true);
        $op1Check = $checkedValue == $val1 ? ' checked' : '';
        $op2Check = $checkedValue != $val1 ? ' checked' : '';
        return
            '<div class="row">' . PHP_EOL .
            '    <div class="' . $colClass . ' col-sm-12">' . PHP_EOL .
            '        <div class="custom-control custom-radio">' . PHP_EOL .
            '            <input type="radio" name="' . $inputName . '" value="' . $val1 . '" id="' . $id1 . '" class="custom-control-input"' . $op1Check . '>' . PHP_EOL .
            '            <label for="' . $id1 . '" class="custom-control-label">' . $op1Text . '</label>' . PHP_EOL .
            '        </div>' . PHP_EOL .
            '    </div>' . PHP_EOL .
            '    <div class="' . $colClass . ' col-sm-12">' . PHP_EOL .
            '         <div class="custom-control custom-radio">' . PHP_EOL .
            '             <input type="radio" name="' . $inputName . '" value="' . $val2 . '" id="' . $id2 . '" class="custom-control-input"' . $op2Check . '>' . PHP_EOL .
            '             <label for="' . $id2 . '" class="custom-control-label">' . $op2Text . '</label>' . PHP_EOL .
            '        </div>' . PHP_EOL .
            '    </div>' . PHP_EOL .
            '</div>';
    }
}