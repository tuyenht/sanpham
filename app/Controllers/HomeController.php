<?php

namespace App\Controllers;

use App\Models\BlogModel;
use App\Models\ChatModel;
use App\Models\CurrencyModel;
use App\Models\EarningsModel;
use App\Models\FieldModel;
use App\Models\FileModel;
use App\Models\LocationModel;
use App\Models\MembershipModel;
use App\Models\NewsletterModel;
use App\Models\OrderModel;
use App\Models\ProfileModel;
use App\Models\PromoteModel;
use App\Models\ShippingModel;
use App\Models\SitemapModel;
use App\Models\UploadModel;
use App\Models\VariationModel;
use Config\Globals;

class HomeController extends BaseController
{
    protected $blogModel;
    protected $blogPerPage;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->blogModel = new BlogModel();
        $this->blogPerPage = 12;

        checkVendorCommissionDept();
    }

    /**
     * Index
     */
    public function index()
    {
        $data = [
            'title' => $this->settings->homepage_title,
            'description' => $this->settings->site_description,
            'keywords' => $this->settings->keywords
        ];
        $data['sliderItems'] = $this->commonModel->getSliderItemsByLang(selectedLangId());
        $data['featuredCategories'] = $this->categoryModel->getFeaturedCategories();
        $data['indexBannersArray'] = $this->commonModel->getIndexBannersArray();
        $data['specialOffers'] = $this->productModel->getSpecialOffers();
        $data['indexCategories'] = $this->categoryModel->getIndexCategories();
        $data['promotedProducts'] = $this->productModel->getPromotedProductsLimited($this->generalSettings->index_promoted_products_count, 0);
        $data['categoriesProductsArray'] = $this->productModel->getIndexCategoriesProducts($data['indexCategories']);
        $data['userSession'] = getUserSession();
        $data['latestProducts'] = $this->productModel->getProducts($this->generalSettings->index_latest_products_count);
        $data["brands"] = $this->commonModel->getBrandsSlider();
        $data["blogSliderPosts"] = $this->blogModel->getPosts(10);

        echo view('partials/_header', $data);
        echo view('index', $data);
        echo view('partials/_footer', $data);
    }

    /**
     * Dynamic Page by Name Slug
     */
    public function any($slug)
    {
        if (empty($slug)) {
            return redirect()->to(langBaseUrl());
        }
        $page = $this->pageModel->getPage($slug);
        $data['userSession'] = getUserSession();
        if (!empty($page)) {
            $this->page($page);
        } else {
            $category = $this->categoryModel->getCategoryBySlug($slug, 'parent');
            if (!empty($category)) {
                $this->category($category);
            } else {
                $this->product($slug);
            }
        }
    }

    /**
     * Page
     */
    private function page($page)
    {
        if (empty($page)) {
            return redirect()->to(langBaseUrl());
        }
        if ($page->visibility == 0 || !empty($page->page_default_name)) {
            $this->error404();
        } else {
            $data['title'] = $page->title;
            $data['description'] = $page->description;
            $data['keywords'] = $page->keywords;
            $data['page'] = $page;

            echo view('partials/_header', $data);
            echo view('page', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Products
     */
    public function products()
    {
        $data['title'] = trans("products");
        $data['description'] = trans("products") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("products") . ',' . $this->baseVars->appName;
        $data['categories'] = $this->parentCategories;
        $data['parentCategoriesTree'] = null;
        $data['customFilters'] = array();
        $data['queryStringArray'] = getQueryStringArray($data['customFilters']);
        $data['queryStringObjectArray'] = convertQueryStringToObjectArray($data['queryStringArray']);
        $data['brands'] = $this->commonModel->getBrandsByCategory(null);
        $data['userSession'] = getUserSession();

        $objParams = new \stdClass();
        $objParams->pageNumber = getValidPageNumber(inputGet('page'));
        $objParams->category = null;
        $objParams->userId = null;
        $objParams->customFilters = null;
        $objParams->arrayParams = null;
        $objParams->couponId = null;
        $objParams->langId = selectedLangId();
        $data['products'] = $this->productModel->loadProducts($objParams);

        echo view('partials/_header', $data);
        echo view('product/products', $data);
        echo view('partials/_footer');
    }

    /**
     * Category
     */
    private function category($category)
    {
        if (empty($category)) {
            return redirect()->to(langBaseUrl());
        }
        $data['parentCategoriesTree'] = $this->categoryModel->getCategoryParentTree($category);
        $pageTitle = '';
        if (!empty($data['parentCategoriesTree'])) {
            foreach ($data['parentCategoriesTree'] as $item) {
                if (!empty($pageTitle)) {
                    $pageTitle .= ' | ';
                }
                $pageTitle .= getCategoryName($item, $this->activeLang->id, false);
            }
        }
        $data['title'] = !empty($category->title_meta_tag) ? $category->title_meta_tag : $pageTitle;
        $data['description'] = $category->description;
        $data['keywords'] = $category->keywords;
        //og tags
        $data['showOgTags'] = true;
        $data['ogTitle'] = !empty($category->title_meta_tag) ? $category->title_meta_tag : $pageTitle;
        $data['ogDescription'] = $data['description'];
        $data['ogType'] = 'article';
        $data['ogUrl'] = generateCategoryUrl($category);
        $data['ogImage'] = getCategoryImageUrl($category);
        $data['ogWidth'] = '420';
        $data['ogHeight'] = '420';
        $data['ogCreator'] = $this->generalSettings->application_name;
        $data['category'] = $category;
        $data['parentCategory'] = null;
        if ($category->parent_id != 0) {
            $data['parentCategory'] = $this->categoryModel->getCategory($category->parent_id);
        }
        $data['categories'] = $this->categoryModel->getSubCategoriesByParentId($category->id);
        $fieldModel = new FieldModel();
        $data['customFilters'] = $fieldModel->getCustomFilters($category->id, $data['parentCategoriesTree']);
        $data['queryStringArray'] = getQueryStringArray($data['customFilters']);
        $data['queryStringObjectArray'] = convertQueryStringToObjectArray($data["queryStringArray"]);
        $data['brands'] = $this->commonModel->getBrandsByCategory($category);

        $objParams = new \stdClass();
        $objParams->pageNumber = getValidPageNumber(inputGet('page'));
        $objParams->category = $category;
        $objParams->userId = null;
        $objParams->customFilters = $data['customFilters'];
        $objParams->arrayParams = null;
        $objParams->couponId = null;
        $objParams->langId = selectedLangId();
        $data['products'] = $this->productModel->loadProducts($objParams);

        echo view('partials/_header', $data);
        echo view('product/products', $data);
        echo view('partials/_footer');
    }

    /**
     * SubCategory
     */
    public function subCategory($parentSlug, $slug)
    {
        $subCategory = $this->categoryModel->getCategoryBySlug($slug, 'sub');
        if (!empty($subCategory)) {
            $this->category($subCategory);
        } else {
            $this->error404();
        }
    }

    /**
     * Product
     */
    public function product($slug)
    {
        $data['product'] = $this->productModel->getProductBySlug($slug);
        if (empty($data['product'])) {
            $this->error404();
        } else {
            if ($data['product']->status == 0 || $data['product']->visibility == 0) {
                if (!authCheck()) {
                    $this->error404();
                }
                if ($data['product']->user_id != user()->id && !hasPermission('products')) {
                    $this->error404();
                }
            }
            $data['productDetails'] = $this->productModel->getProductDetails($data['product']->id, selectedLangId(), true);
            if (empty($data['productDetails'])) {
                $data['productDetails'] = array();
            }
            $data['parentCategoriesTree'] = array();
            $category = $this->categoryModel->getCategory($data['product']->category_id);
            if (!empty($category)) {
                $data['parentCategoriesTree'] = $this->categoryModel->getCategoryParentTree($category);
            }
            //images
            $data['productImages'] = getProductImages($data['product']->id);
            //related products
            $data['relatedProducts'] = $this->productModel->getRelatedProducts($data['product']->id, $data['product']->category_id);
            $data['user'] = $this->authModel->getUser($data['product']->user_id);
            //user products
            $data['userProducts'] = $this->productModel->getMoreProductsByUser($data['user']->id, $data['product']->id);
            $data['reviews'] = $this->commonModel->getProductReviewsByOffset($data['product']->id, REVIEWS_LOAD_LIMIT, 0);
            $data['reviewsCount'] = $this->commonModel->getReviewsCountByProductId($data['product']->id);
            $data['commentsArray'] = $this->commonModel->getProductCommentsByOffset($data['product']->id, COMMENTS_LOAD_LIMIT, 0);
            $data['commentsCount'] = $this->commonModel->getProductCommentCount($data['product']->id);
            $data['wishlistCount'] = $this->productModel->getProductWishlistCount($data['product']->id);
            $data['isProductInWishlist'] = $this->productModel->isProductInWishlist($data['product']->id);
            //brand
            $brand = '';
            if (!empty($data['product']->brand_name_data)) {
                $brand = getBrandName($data['product']->brand_name_data, selectedLangId());
            }
            $data['productBrand'] = $brand;
            //custom fields
            $fieldModel = new FieldModel();
            $data['productFilterValuesArray'] = $fieldModel->getProductFilterValuesArray($data['product']->id, $brand);

            //variations
            $variationModel = new VariationModel();
            $data['halfWidthProductVariations'] = $variationModel->getHalfWidthProductVariations($data['product']->id);
            $data['fullWidthProductVariations'] = $variationModel->getFullWidthProductVariations($data['product']->id);
            $fileModel = new FileModel();
            $data['video'] = $fileModel->getProductVideo($data['product']->id);
            $data['audio'] = $fileModel->getProductAudio($data['product']->id);
            $data["digitalSale"] = null;
            if ($data['product']->product_type == 'digital' && authCheck()) {
                $data["digitalSale"] = getDigitalSaleByBuyerId(user()->id, $data['product']->id);
            }
            //shipping
            $data['shippingStatus'] = $this->productSettings->marketplace_shipping;
            $data['productLocationStatus'] = $this->productSettings->marketplace_product_location;
            if ($data['product']->listing_type == 'ordinary_listing' || $data['product']->product_type != 'physical') {
                $data['shippingStatus'] = 0;
            }
            if ($data['product']->product_type == 'digital') {
                $data['productLocationStatus'] = 0;
            }
            $shippingModel = new ShippingModel();
            $data['deliveryTime'] = $shippingModel->getShippingDeliveryTime($data['product']->shipping_delivery_time_id);
            $data['estimatedDelivery'] = $shippingModel->getProductEstimatedDelivery($data['product'], selectedLangId());

            $data['title'] = !empty($data['productDetails']) ? $data['productDetails']->title : '';
            $data['description'] = !empty($data['productDetails']->short_description) ? $data['productDetails']->short_description : $data['title'];
            $data['keywords'] = getProductTagsString($data['product'], $this->activeLang->id);
            //og tags
            $data['showOgTags'] = true;
            $data['ogTitle'] = $data['title'];
            $data['ogDescription'] = $data['description'];
            $data['ogType'] = 'article';
            $data['ogUrl'] = generateProductUrl($data['product']);
            $data['ogImage'] = getProductMainImage($data['product']->id, 'image_default');
            $data['ogWidth'] = '750';
            $data['ogHeight'] = '500';
            if (!empty($data['user'])) {
                $data['ogCreator'] = getUsername($data['user']);
                $data['ogAuthor'] = getUsername($data['user']);
            } else {
                $data['ogCreator'] = '';
                $data['ogAuthor'] = '';
            }
            $data['ogPublishedTime'] = $data['product']->created_at;
            $data['ogModifiedTime'] = $data['product']->created_at;

            echo view('partials/_header', $data);
            echo view('product/details/product', $data);
            echo view('partials/_footer');
            //increase pageviews
            $this->productModel->increaseProductPageviews($data['product']);
        }
    }

    /**
     * Search
     */
    public function search()
    {
        $search = removeSpecialCharacters(inputGet('search'));
        if (empty($search)) {
            return redirect()->to(langBaseUrl());
        }
        return redirect()->to(generateUrl('products') . '?search=' . $search);
    }

    /**
     * Shops
     */
    public function shops()
    {
        if ($this->generalSettings->multi_vendor_system != 1) {
            return redirect()->to(langBaseUrl());
        }
        $page = $this->pageModel->getPageByDefaultName('shops', selectedLangId());
        if (empty($page)) {
            return redirect()->to(langBaseUrl());
        }
        if ($page->visibility == 0) {
            $this->error404();
        } else {
            $data['title'] = $page->title;
            $data['description'] = $page->description;
            $data['keywords'] = $page->keywords;
            $data['page'] = $page;
            $data['userSession'] = getUserSession();
            $numRows = $this->authModel->getVendorsCount();
            $data['pager'] = paginate(40, $numRows);
            $data['shops'] = $this->authModel->getVendorsPaginated(40, $data['pager']->offset);

            echo view('partials/_header', $data);
            echo view('shops', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Contact
     */
    public function contact()
    {
        $page = $this->pageModel->getPageByDefaultName('contact', selectedLangId());
        if (empty($page)) {
            return redirect()->to(langBaseUrl());
        }
        if ($page->visibility == 0) {
            $this->error404();
        } else {
            $data['title'] = $page->title;
            $data['description'] = $page->description . ' - ' . $this->baseVars->appName;
            $data['keywords'] = $page->keywords . ' - ' . $this->baseVars->appName;
            $data['page'] = $page;
            $data['userSession'] = getUserSession();
            echo view('partials/_header', $data);
            echo view('contact', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Contact Page Post
     */
    public function contactPost()
    {
        $contactUrl = inputPost('contact_url');
        if (!empty($contactUrl)) {
            exit();
        }
        $val = \Config\Services::validation();
        $val->setRule('name', trans("name"), 'required|max_length[255]');
        $val->setRule('email', trans("email_address"), 'required|valid_email|max_length[255]');
        $val->setRule('message', trans("message"), 'required|max_length[5000]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            if (reCAPTCHA('validate') == 'invalid') {
                setErrorMessage(trans("msg_recaptcha"));
                return redirect()->back()->withInput();
            } else {
                if ($this->commonModel->addContactMessage()) {
                    setSuccessMessage(trans("msg_contact_success"));
                    return redirect()->to(generateUrl('contact'));
                } else {
                    setErrorMessage(trans("msg_contact_error"));
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Affiliate
     */
    public function affiliate($slug)
    {
        if ($this->generalSettings->affiliate_status == 1) {
            if ($affiliateLink = $this->commonModel->getAffiliateLinkBySlug($slug)) {
                if (!empty($affiliateLink->link_short)) {
                    $url = $this->commonModel->convertAffiliateLink($affiliateLink);
                    if (!empty($url)) {
                        $this->commonModel->setAffiliateCookie($affiliateLink);
                        header('Location: ' . $url);
                        exit();
                    }
                }
            }
        }
        return redirect()->to(langBaseUrl());
    }

    /**
     * Affiliate Program
     */
    public function affiliateProgram()
    {
        if ($this->generalSettings->affiliate_status != 1) {
            return redirect()->to(langBaseUrl());
        }

        $data['title'] = trans("affiliate_program");
        $data['description'] = trans("affiliate_program");
        $data['keywords'] = trans("affiliate_program");
        $data['userSession'] = getUserSession();
        $data['affDesc'] = !empty($this->settings->affiliate_description) ? unserializeData($this->settings->affiliate_description) : '';
        $data['affContent'] = !empty($this->settings->affiliate_content) ? unserializeData($this->settings->affiliate_content) : '';
        $data['affWorks'] = !empty($this->settings->affiliate_works) ? unserializeData($this->settings->affiliate_works) : '';
        $data['affFaq'] = !empty($this->settings->affiliate_faq) ? unserializeData($this->settings->affiliate_faq) : '';

        if (authCheck() && !empty(user()->country_id)) {
            $data['states'] = $this->locationModel->getStatesByCountry(user()->country_id);
        }

        echo view('partials/_header', $data);
        echo view('affiliate_program', $data);
        echo view('partials/_footer');
    }

    /*
     * --------------------------------------------------------------------
     * Membership
     * --------------------------------------------------------------------
     */

    /**
     * Start Selling
     */
    public function startSelling()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        if (isVendor()) {
            return redirect()->to(langBaseUrl());
        }
        if ($this->generalSettings->email_verification == 1 && user()->email_status != 1) {
            setErrorMessage(trans("msg_confirmed_required"));
            return redirect()->to(generateUrl('settings', 'edit_profile'));
        }
        $data['title'] = trans("start_selling");
        $data['description'] = trans("start_selling") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("start_selling") . ',' . $this->baseVars->appName;
        $locationModel = new LocationModel();
        $data['states'] = $locationModel->getStatesByCountry(user()->country_id);
        $data['cities'] = $locationModel->getCitiesByState(user()->state_id);

        echo view('partials/_header', $data);
        echo view('product/start_selling', $data);
        echo view('partials/_footer');
    }

    /**
     * Start Selling Post
     */
    public function startSellingPost()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        if (isVendor()) {
            return redirect()->to(langBaseUrl());
        }
        $data = [
            'username' => removeSpecialCharacters(inputPost('username')),
            'first_name' => inputPost('first_name'),
            'last_name' => inputPost('last_name'),
            'phone_number' => inputPost('phone_number'),
            'country_id' => inputPost('country_id'),
            'state_id' => inputPost('state_id'),
            'city_id' => inputPost('city_id'),
            'about_me' => inputPost('about_me'),
            'vendor_documents' => '',
            'is_active_shop_request' => 1,
            'shop_request_date' => date('Y-m-d H:i:s')
        ];
        //is shop name unique
        if (!$this->authModel->isUniqueUsername($data['username'], user()->id)) {
            setErrorMessage(trans("msg_shop_name_unique_error"));
            redirectToBackUrl();
        }
        $membershipModel = new MembershipModel();
        //validate uploaded files
        if ($this->generalSettings->request_documents_vendors == 1) {
            $filesValid = true;
            if (!empty($_FILES['file'])) {
                for ($i = 0; $i < countItems($_FILES['file']['name']); $i++) {
                    if ($_FILES['file']['size'][$i] > 5242880) {
                        $filesValid = false;
                    }
                }
            }
            if ($filesValid == false) {
                setErrorMessage(trans("file_too_large") . ' 5MB');
                redirectToBackUrl();
            }
            $uploadModel = new UploadModel();
            $vendorDocs = $uploadModel->uploadVendorDocuments();
            if (!empty($vendorDocs)) {
                $data['vendor_documents'] = serialize($vendorDocs);
            }
        }
        if ($membershipModel->addShopOpeningRequest($data)) {
            //send email
            $membershipModel->addShopOpeningEmail(user()->id);
            $membershipModel->addShopOpeningEmailAdmin();
            setSuccessMessage(trans("msg_start_selling"));
        } else {
            setErrorMessage(trans("msg_error"));
        }
        redirectToBackUrl();
    }

    /**
     * Select Membership Plan
     */
    public function selectMembershipPlan()
    {
        if ($this->generalSettings->membership_plans_system != 1) {
            return redirect()->to(langBaseUrl());
        }
        if (!isVendor()) {
            return redirect()->to(langBaseUrl());
        }
        if ($this->generalSettings->email_verification == 1 && user()->email_status != 1) {
            setErrorMessage(trans("msg_confirmed_required"));
            return redirect()->to(generateUrl('settings', 'edit_profile'));
        }

        $data['title'] = trans("select_your_plan");
        $data['description'] = trans("select_your_plan") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("select_your_plan") . ',' . $this->baseVars->appName;
        $membershipModel = new MembershipModel();
        $data["membershipPlans"] = $membershipModel->getPlans();
        $data['userCurrentPlan'] = $membershipModel->getUserPlanByUserId(user()->id);
        $data['userAdsCount'] = $membershipModel->getUserAdsCount(user()->id);

        echo view('partials/_header', $data);
        echo view('product/select_membership_plan', $data);
        echo view('partials/_footer');
    }

    /**
     * Select Membership Plan Post
     */
    public function selectMembershipPlanPost()
    {
        if ($this->generalSettings->membership_plans_system != 1) {
            return redirect()->to(langBaseUrl());
        }
        if (!isVendor()) {
            return redirect()->to(langBaseUrl());
        }
        if ($this->generalSettings->email_verification == 1 && user()->email_status != 1) {
            setErrorMessage(trans("msg_confirmed_required"));
            return redirect()->to(generateUrl('settings', 'edit_profile'));
        }
        $membershipModel = new MembershipModel();
        $planId = inputPost('plan_id');
        if (empty($planId)) {
            return redirect()->back();
        }
        $plan = $membershipModel->getPlan($planId);
        if (empty($plan)) {
            return redirect()->back();
        }
        if ($plan->is_free == 1) {
            $membershipModel->addUserFreePlan($plan, user()->id);
            return redirect()->to(generateDashUrl('shop_settings'));
        }

        //check user has plan
        $requestType = 'new';
        if (!empty($membershipModel->getUserPlanByUserId(user()->id, false))) {
            $requestType = 'renew';
        }

        $serviceData = new \stdClass();
        $serviceData->paymentType = 'membership';
        $serviceData->paymentName = trans("membership_plan_payment");
        $serviceData->planRequestType = $requestType;
        $serviceData->planId = $plan->id;
        $serviceData->paymentAmountBeforeTaxes = getPrice($plan->price, 'decimal');
        $serviceData->paymentAmount = getPrice($plan->price, 'decimal');
        $serviceData->currency = $this->defaultCurrency->code;
        helperSetSession('mds_service_payment', $serviceData);

        return redirect()->to(generateUrl('cart', 'payment_method') . '?payment_type=service');
    }

    /*
     * --------------------------------------------------------------------
     * Blog
     * --------------------------------------------------------------------
     */

    /**
     * Blog
     */
    public function blog()
    {
        $page = $this->pageModel->getPageByDefaultName('blog', selectedLangId());
        if (empty($page)) {
            return redirect()->to(langBaseUrl());
        }
        if ($page->visibility == 0) {
            $this->error404();
        } else {
            $data['title'] = $page->title;
            $data['description'] = $page->description;
            $data['keywords'] = $page->keywords;
            $data["activeCategory"] = 'all';
            $data['userSession'] = getUserSession();
            $numRows = $this->blogModel->getPostCount();
            $data['pager'] = paginate($this->blogPerPage, $numRows);
            $data['posts'] = $this->blogModel->getPostsPaginated($this->blogPerPage, $data['pager']->offset);
            $data['categories'] = $this->blogModel->getCategoriesByLang(selectedLangId());

            echo view('partials/_header', $data);
            echo view('blog/index', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Blog Category
     */
    public function blogCategory($slug)
    {
        $data['category'] = $this->blogModel->getCategoryBySlug($slug);
        if (empty($data['category'])) {
            return redirect()->to(generateUrl('blog'));
        }
        $data['title'] = $data['category']->name;
        $data['description'] = $data['category']->description;
        $data['keywords'] = $data['category']->keywords;
        $data["activeCategory"] = $data['category']->slug;
        $data['userSession'] = getUserSession();
        $numRows = $this->blogModel->getPostCountByCategory($data['category']->id);
        $data['pager'] = paginate($this->blogPerPage, $numRows);
        $data['posts'] = $this->blogModel->getCategoryPostsPaginated($data['category']->id, $this->blogPerPage, $data['pager']->offset);
        $data['categories'] = $this->blogModel->getCategoriesByLang(selectedLangId());

        echo view('partials/_header', $data);
        echo view('blog/index', $data);
        echo view('partials/_footer');
    }

    /**
     * Tag
     */
    public function tag($tagSlug)
    {
        $data['tag'] = $this->blogModel->getPostTag($tagSlug);
        if (empty($data['tag'])) {
            return redirect()->to(generateUrl('blog'));
        }
        $data['title'] = $data['tag']->tag;
        $data['description'] = trans("tag") . ': ' . $data['tag']->tag . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("tag") . ',' . $data['tag']->tag . ',' . $this->baseVars->appName;
        $numRows = $this->blogModel->getTagPostsCount($tagSlug);
        $data['pager'] = paginate($this->blogPerPage, $numRows);
        $data['posts'] = $this->blogModel->getTagPostsPaginated($tagSlug, $this->blogPerPage, $data['pager']->offset);

        echo view('partials/_header', $data);
        echo view('blog/tag', $data);
        echo view('partials/_footer');
    }

    /**
     * Post
     */
    public function post($categorySlug, $slug)
    {
        $data['post'] = $this->blogModel->getPostBySlug($slug);
        if (empty($data['post'])) {
            return redirect()->to(generateUrl('blog'));
        }
        $data['title'] = $data['post']->title;
        $data['description'] = $data['post']->summary;
        $data['keywords'] = $data['post']->keywords;
        $data['relatedPosts'] = $this->blogModel->getRelatedPosts($data['post']->category_id, $data['post']->id);
        $data['latestPosts'] = $this->blogModel->getPostsPaginated(3, 0);
        $data['randomTags'] = $this->blogModel->getRandomPostTags();
        $data['postTags'] = $this->blogModel->getPostTags($data['post']->id);
        $data['comments'] = $this->blogModel->getCommentsByPostId($data['post']->id, COMMENTS_LOAD_LIMIT);
        $data['commentsCount'] = $this->blogModel->getActiveCommentsCountByPostId($data['post']->id);
        $data['commentLimit'] = COMMENTS_LOAD_LIMIT;
        $data['postUser'] = getUser($data['post']->user_id);
        $data["category"] = $this->blogModel->getCategory($data['post']->category_id);
        //og tags
        $data['showOgTags'] = true;
        $data['ogTitle'] = $data['post']->title;
        $data['ogDescription'] = $data['post']->summary;
        $data['ogType'] = 'article';
        $data['ogUrl'] = generateUrl('blog') . '/' . $data['post']->category_slug . '/' . $data['post']->slug;
        $data['ogImage'] = getBlogImageURL($data['post'], 'image_default');
        $data['ogWidth'] = '750';
        $data['ogHeight'] = '500';
        $data['ogCreator'] = '';
        $data['ogAuthor'] = '';
        if (!empty($data['postUser'])) {
            $data['ogCreator'] = getUsername($data['postUser']);
            $data['ogAuthor'] = getUsername($data['postUser']);
        }
        $data['ogPublishedTime'] = $data['post']->created_at;
        $data['ogModifiedTime'] = $data['post']->created_at;
        $data['ogTags'] = $data['postTags'];

        echo view('partials/_header', $data);
        echo view('blog/post', $data);
        echo view('partials/_footer');
    }

    /**
     * Terms & Conditions
     */
    public function termsConditions()
    {
        $page = $this->pageModel->getPageByDefaultName('terms_conditions', selectedLangId());
        if (empty($page)) {
            return redirect()->to(langBaseUrl());
        }
        if ($page->visibility == 0) {
            $this->error404();
        } else {
            $data['title'] = $page->title;
            $data['description'] = $page->description . ' - ' . $this->baseVars->appName;
            $data['keywords'] = $page->keywords . ' - ' . $this->baseVars->appName;
            $data['page'] = $page;

            echo view('partials/_header', $data);
            echo view('page', $data);
            echo view('partials/_footer');
        }
    }

    /*
     * --------------------------------------------------------------------
     * Reviews
     * --------------------------------------------------------------------
     */

    /**
     * Add Review
     */
    public function addReviewPost()
    {
        if (authCheck() && $this->generalSettings->reviews == 1) {
            $rating = inputPost('rating');
            $productId = inputPost('product_id');
            $reviewText = inputPost('review');
            $product = $this->productModel->getProduct($productId);
            if (!empty($product)) {
                $addReview = false;
                if ($product->user_id != user()->id) {
                    if ($product->listing_type == 'ordinary_listing') {
                        $addReview = true;
                    } else {
                        if ($product->is_free_product) {
                            $addReview = true;
                        } else {
                            if (checkUserBoughtProduct(user()->id, $product->id)) {
                                $addReview = true;
                            }
                        }
                    }
                }
                if ($addReview) {
                    $review = $this->commonModel->getReview($productId, user()->id);
                    if (!empty($review)) {
                        $this->commonModel->updateReview($review->id, $rating, $productId, $reviewText);
                    } else {
                        $this->commonModel->addReview($rating, $productId, $reviewText);
                    }
                }
            }
        }
        $this->session->setFlashdata('review_added', 1);
        redirectToBackUrl();
    }

    /**
     * Wishlist
     */
    public function wishlist()
    {
        $data['title'] = trans("wishlist");
        $data['description'] = trans("wishlist") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("wishlist") . ',' . $this->baseVars->appName;
        $perPage = $this->productSettings->pagination_per_page;
        if (authCheck()) {
            $data['user'] = user();
            $data['userSession'] = getUserSession();
            $numRows = $this->productModel->getUserWishlistProductsCount($data['user']->id);
            $data['pager'] = paginate($perPage, $numRows);
            $data['products'] = $this->productModel->getPaginatedUserWishlistProducts($data['user']->id, $perPage, $data['pager']->offset);
        } else {
            $numRows = $this->productModel->getGuestWishlistProductsCount();
            $data['pager'] = paginate($perPage, $numRows);
            $data['products'] = $this->productModel->getGuestWishlistProductsPaginated($perPage, $data['pager']->offset);
        }

        echo view('partials/_header', $data);
        echo view('wishlist', $data);
        echo view('partials/_footer');
    }

    /*
     * --------------------------------------------------------------------
     * Chat
     * --------------------------------------------------------------------
     */

    /**
     * Chat
     */
    public function chat()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $chatModel = new ChatModel();
        $data['title'] = trans("messages");
        $data['description'] = trans("messages") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("messages") . ',' . $this->baseVars->appName;
        $data['chats'] = $chatModel->getChats(user()->id);

        echo view('partials/_header', $data);
        echo view('chat/chat', $data);
        echo view('partials/_footer');
    }

    /**
     * Invoice
     */
    public function invoice($orderNumber)
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $data['title'] = trans("invoice");
        $data['description'] = trans("invoice") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("invoice") . ',' . $this->baseVars->appName;
        $data['userSession'] = getUserSession();
        $isValidReq = true;
        $type = inputGet('type');
        if (empty($type) || ($type != 'admin' && $type != 'seller' && $type != 'buyer')) {
            $isValidReq = false;
        }
        if ($type == 'admin') {
            if (!empty($this->session->get('mds_control_panel_lang'))) {
                Globals::setActiveLanguage($this->session->get('mds_control_panel_lang'));
            }
        }
        $orderModel = new OrderModel();
        $data['order'] = $orderModel->getOrderByOrderNumber($orderNumber);
        if (empty($data['order'])) {
            return redirect()->to(langBaseUrl());
        }
        $data['invoice'] = $orderModel->getInvoiceByOrderNumber($orderNumber);
        if (empty($data['invoice'])) {
            $orderModel->addInvoice($data['order']->id);
        }
        if (empty($data['invoice'])) {
            return redirect()->to(langBaseUrl());
        }
        $data['invoiceItems'] = unserializeData($data['invoice']->invoice_items);
        $data['orderProducts'] = $orderModel->getOrderProducts($data['order']->id);

        $isSeller = false;
        $isBuyer = false;
        if (!empty($data['orderProducts'])) {
            foreach ($data['orderProducts'] as $item) {
                if ($item->seller_id == user()->id) {
                    $isSeller = true;
                }
                if ($item->buyer_id == user()->id) {
                    $isBuyer = true;
                }
            }
        }
        //check permission
        if ($type == 'admin' && !hasPermission('orders')) {
            $isValidReq = false;
        }
        if ($type == 'seller' && $isSeller == false) {
            $isValidReq = false;
        }
        if ($type == 'buyer' && $isBuyer == false) {
            $isValidReq = false;
        }
        if (!$isValidReq) {
            return redirect()->to(langBaseUrl());
        }
        if ($type == 'admin' || $type == 'buyer') {
            echo view('invoice/invoice', $data);
        } elseif ($type == 'seller') {
            echo view('invoice/invoice_seller', $data);
        }
    }

    /**
     * Invoice Membership
     */
    public function invoiceMembership($id)
    {
        $data['title'] = trans("invoice");
        $data['description'] = trans("invoice") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("invoice") . ',' . $this->baseVars->appName;
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $membershipModel = new MembershipModel();
        $data['transaction'] = $membershipModel->getMembershipTransaction($id);
        if (empty($data['transaction'])) {
            return redirect()->to(langBaseUrl());
        }
        if (!hasPermission('membership') && !hasPermission('payments')) {
            if (user()->id != $data['transaction']->user_id) {
                return redirect()->to(langBaseUrl());
            }
        }
        $data['user'] = getUser($data['transaction']->user_id);
        if (empty($data['user'])) {
            return redirect()->to(langBaseUrl());
        }
        echo view('invoice/invoice_membership', $data);
    }

    /**
     * Invoice Promotion
     */
    public function invoicePromotion($id)
    {
        $data['title'] = trans("invoice");
        $data['description'] = trans("invoice") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("invoice") . ',' . $this->baseVars->appName;
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $promoteModel = new PromoteModel();
        $data['transaction'] = $promoteModel->getTransaction($id);
        if (empty($data['transaction'])) {
            return redirect()->to(langBaseUrl());
        }
        if (!hasPermission('products') && !hasPermission('payments')) {
            if (user()->id != $data['transaction']->user_id) {
                return redirect()->to(langBaseUrl());
            }
        }
        $data['user'] = getUser($data['transaction']->user_id);
        if (empty($data['user'])) {
            return redirect()->to(langBaseUrl());
        }
        echo view('invoice/invoice_promotion', $data);
    }

    /**
     * Invoice Wallet Deposit
     */
    public function invoiceWalletDeposit($id)
    {
        $data['title'] = trans("invoice");
        $data['description'] = trans("invoice") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("invoice") . ',' . $this->baseVars->appName;
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $earningsModel = new EarningsModel();
        $data['transaction'] = $earningsModel->getDepositTransaction($id);
        if (empty($data['transaction'])) {
            return redirect()->to(langBaseUrl());
        }
        if (!hasPermission('payments')) {
            if (user()->id != $data['transaction']->user_id) {
                return redirect()->to(langBaseUrl());
            }
        }
        $data['user'] = getUser($data['transaction']->user_id);
        if (empty($data['user'])) {
            return redirect()->to(langBaseUrl());
        }
        echo view('invoice/invoice_deposit', $data);
    }

    /**
     * Invoice Expense
     */
    public function invoiceExpense($id)
    {
        $data['title'] = trans("invoice");
        $data['description'] = trans("invoice") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("invoice") . ',' . $this->baseVars->appName;
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $earningsModel = new EarningsModel();
        $data['transaction'] = $earningsModel->getExpense($id);
        if (empty($data['transaction'])) {
            return redirect()->to(langBaseUrl());
        }
        if (!hasPermission('payments')) {
            if (user()->id != $data['transaction']->user_id) {
                return redirect()->to(langBaseUrl());
            }
        }
        $data['user'] = getUser($data['transaction']->user_id);
        if (empty($data['user'])) {
            return redirect()->to(langBaseUrl());
        }
        echo view('invoice/invoice_expense', $data);
    }

    /**
     * Bank Transfer Payment Report Post
     */
    public function bankTransferPaymentReportPost()
    {
        if (authCheck()) {
            $this->commonModel->addBankTransferPaymentReport();
        }
        redirectToBackUrl();
    }

    /**
     * Unsubscribe
     */
    public function unSubscribe()
    {
        $data['title'] = trans("unsubscribe");
        $data['description'] = trans("unsubscribe");
        $data['keywords'] = trans("unsubscribe");

        $newsletterModel = new NewsletterModel();
        $token = removeSpecialCharacters(inputGet('token'));
        $subscriber = $newsletterModel->getSubscriberByToken($token);
        if (empty($subscriber)) {
            redirectToUrl(langBaseUrl());
        }
        $newsletterModel->unSubscribeEmail($subscriber->email);

        echo view('partials/_header', $data);
        echo view('unsubscribe');
        echo view('partials/_footer');
    }

    /**
     * Set Location Post
     */
    public function setDefaultLocationPost()
    {
        if (inputPost('form_type') == 'set_user_location') {
            if (authCheck()) {
                $profileModel = new ProfileModel();
                $profileModel->updateLocation();
            } else {
                $data = [
                    'country_id' => inputPost('country_id'),
                    'state_id' => inputPost('state_id')
                ];
                helperSetSession('mds_estimated_delivery_location', $data);
            }
        } else {
            $this->locationModel->setDefaultLocation();
        }
        return redirect()->back();
    }

    /**
     * Cron Update Sitemap
     */
    public function cronUpdateSitemap()
    {
        $model = new SitemapModel();
        $model->generateSitemap();
    }

    /**
     * Set currency
     */
    public function setSelectedCurrency()
    {
        $currencyModel = new CurrencyModel();
        $currencyModel->setSelectedCurrency();
        return redirect()->back();
    }

    /**
     * Error 404
     */
    public function error404()
    {
        header("HTTP/1.0 404 Not Found");
        $data['title'] = trans("page_not_found");
        $data['description'] = trans("page_not_found") . ' - ' . $this->baseVars->appName;
        $data['keywords'] = trans("page_not_found") . ',' . $this->baseVars->appName;
        $data['isPage404'] = true;

        echo view('partials/_header', $data);
        echo view('errors/html/error_404');
        echo view('partials/_footer', $data);
    }
}
