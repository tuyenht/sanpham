function setAjaxData(object = null) {
    var data = {
        'sysLangId': MdsConfig.sysLangId,
    };
    data[MdsConfig.csrfTokenName] = $('meta[name="X-CSRF-TOKEN"]').attr('content');
    if (object != null) {
        Object.assign(data, object);
    }
    return data;
}

function setSerializedData(serializedData) {
    serializedData.push({name: 'sysLangId', value: MdsConfig.sysLangId});
    serializedData.push({name: MdsConfig.csrfTokenName, value: $('meta[name="X-CSRF-TOKEN"]').attr('content')});
    return serializedData;
}

//run email queue
$(document).ready(function () {
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/runEmailQueue',
        data: setAjaxData({})
    });
});

function swalOptions(message) {
    return {
        text: message,
        icon: 'warning',
        buttons: true,
        buttons: [MdsConfig.textCancel, MdsConfig.textOk],
        dangerMode: true,
    };
}

//hide left side of the menu if there is image
var menu_elements = document.getElementsByClassName("mega-menu-content");
for (var i = 0; i < menu_elements.length; i++) {
    var id = menu_elements[i].id;
    if (document.getElementById(id).getElementsByClassName("col-category-images")[0]) {
        var content = document.getElementById(id).getElementsByClassName("col-category-images")[0].innerHTML;
        if (content.trim() == "") {
            document.getElementById(id).classList.add("mega-menu-content-no-image");
        }
    }
}

$(document).ready(function () {
    //main slider
    $('#main-slider').on('init', function (e, slick) {
        var $firstAnimatingElements = $('#main-slider .item:first-child').find('[data-animation]');
        doAnimations($firstAnimatingElements);
    });
    $('#main-slider').on('beforeChange', function (e, slick, currentSlide, nextSlide) {
        var $animatingElements = $('#main-slider .item[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
        doAnimations($animatingElements);
    });
    $('#main-slider').slick({
        autoplay: true,
        autoplaySpeed: 9000,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        speed: 500,
        fade: (MdsConfig.sliderFadeEffect == 1) ? true : false,
        swipeToSlide: true,
        rtl: MdsConfig.rtl,
        cssEase: 'linear',
        prevArrow: $('#main-slider-nav .prev'),
        nextArrow: $('#main-slider-nav .next'),
    });

    //main slider
    $('#main-mobile-slider').on('init', function (e, slick) {
        var $firstAnimatingElements = $('#main-mobile-slider .item:first-child').find('[data-animation]');
        doAnimations($firstAnimatingElements);
    });
    $('#main-mobile-slider').on('beforeChange', function (e, slick, currentSlide, nextSlide) {
        var $animatingElements = $('#main-mobile-slider .item[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
        doAnimations($animatingElements);
    });
    $('#main-mobile-slider').slick({
        autoplay: true,
        autoplaySpeed: 9000,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        speed: 500,
        fade: (MdsConfig.sliderFadeEffect == 1) ? true : false,
        swipeToSlide: true,
        rtl: MdsConfig.rtl,
        cssEase: 'linear',
        prevArrow: $('#main-mobile-slider-nav .prev'),
        nextArrow: $('#main-mobile-slider-nav .next')
    });

    function doAnimations(elements) {
        var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        elements.each(function () {
            var $this = $(this);
            var $animationDelay = $this.data('delay');
            var $animationType = 'animated ' + $this.data('animation');
            $this.css({
                'animation-delay': $animationDelay,
                '-webkit-animation-delay': $animationDelay
            });
            $this.addClass($animationType).one(animationEndEvents, function () {
                $this.removeClass($animationType);
            });
        });
    }

    if ($('#slider_special_offers').length != 0) {
        $('#slider_special_offers').slick({
            autoplay: false,
            autoplaySpeed: 4900,
            infinite: true,
            speed: 200,
            swipeToSlide: true,
            rtl: MdsConfig.rtl,
            cssEase: 'linear',
            lazyLoad: 'progressive',
            prevArrow: $('#slider_special_offers_nav .prev'),
            nextArrow: $('#slider_special_offers_nav .next'),
            slidesToShow: 5,
            slidesToScroll: 5,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }
            ]
        });
    }

    $('#product_slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 300,
        arrows: true,
        fade: true,
        infinite: false,
        swipeToSlide: true,
        cssEase: 'linear',
        lazyLoad: 'progressive',
        prevArrow: $('#product-slider-nav .prev'),
        nextArrow: $('#product-slider-nav .next'),
        asNavFor: '#product_thumbnails_slider'
    });

    $('#product_thumbnails_slider').slick({
        slidesToShow: 7,
        slidesToScroll: 1,
        speed: 300,
        focusOnSelect: true,
        arrows: false,
        infinite: false,
        vertical: true,
        centerMode: false,
        arrows: true,
        cssEase: 'linear',
        lazyLoad: 'progressive',
        prevArrow: $('#product-thumbnails-slider-nav .prev'),
        nextArrow: $('#product-thumbnails-slider-nav .next'),
        asNavFor: '#product_slider'
    });

    $(document).on('click', '#product_thumbnails_slider .slick-slide', function () {
        var index = $(this).attr("data-slick-index");
        $('#product_slider').slick('slickGoTo', parseInt(index));
    });

    $('#brand-slider').slick({
        autoplay: true,
        autoplaySpeed: 2500,
        infinite: true,
        speed: 200,
        swipeToSlide: true,
        rtl: MdsConfig.rtl,
        cssEase: 'linear',
        lazyLoad: 'progressive',
        prevArrow: $('#brand-slider-nav .prev'),
        nextArrow: $('#brand-slider-nav .next'),
        slidesToShow: 8,
        slidesToScroll: 8,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 6,
                    slidesToScroll: 6
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            }
        ]
    });

    $('#brand-slider').find('[aria-hidden]').removeAttr('aria-hidden');
    $('#brand-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
        setTimeout(() => {
            slick.$slides.removeAttr('aria-hidden');
        }, 0);
    });

    $('#blog-slider').slick({
        autoplay: false,
        autoplaySpeed: 4900,
        infinite: true,
        speed: 200,
        swipeToSlide: true,
        rtl: MdsConfig.rtl,
        cssEase: 'linear',
        prevArrow: $('#blog-slider-nav .prev'),
        nextArrow: $('#blog-slider-nav .next'),
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    //rate product
    $(document).on('click', '.rating-stars .label-star', function () {
        $('#user_rating').val($(this).attr('data-star'));
    });

    //mobile memu
    $(document).on('click', '.btn-open-mobile-nav', function () {
        if ($("#navMobile").hasClass('nav-mobile-open')) {
            $("#navMobile").removeClass('nav-mobile-open');
            $('#overlay_bg').hide();
        } else {
            $("#navMobile").addClass('nav-mobile-open');
            $('#overlay_bg').show();
        }
    });
    $(document).on('click', '#overlay_bg', function () {
        $("#navMobile").removeClass('nav-mobile-open');
        $('#overlay_bg').hide();
    });
    //close menu
    $(document).on('click', '.close-menu-click', function () {
        $("#navMobile").removeClass('nav-mobile-open');
        $('#overlay_bg').hide();
    });

});

//mobile menu
var obj_mobile_nav = {
    id: "",
    name: "",
    parent_id: "",
    parent_name: "",
    back_button: 1
};
$(document).on('click', '#navbar_mobile_categories li button', function () {
    obj_mobile_nav.id = $(this).attr('data-id');
    obj_mobile_nav.name = ($(this).text() != "") ? $(this).text() : '';
    obj_mobile_nav.parent_id = ($(this).attr('data-parent-id') != null) ? $(this).attr('data-parent-id') : 0;
    obj_mobile_nav.back_button = 1;
    mobile_menu();
});
$(document).on('click', '#navbar_mobile_back_button button', function () {
    obj_mobile_nav.id = $(this).attr('data-id');
    obj_mobile_nav.name = ($(this).attr('data-category-name') != null) ? $(this).attr('data-category-name') : '';
    obj_mobile_nav.parent_id = ($(this).attr('data-parent-id') != null) ? $(this).attr('data-parent-id') : 0;
    if (obj_mobile_nav.id == 0) {
        obj_mobile_nav.back_button = 0;
    }
    mobile_menu();
});

function mobile_menu() {
    var categories = $('.mega-menu li a[data-parent-id="' + obj_mobile_nav.id + '"]');
    if (categories.length > 0) {
        if (obj_mobile_nav.back_button != 1) {
            $("#navbar_mobile_back_button").empty();
        }
        $("#navbar_mobile_categories").empty();
        $("#navbar_mobile_back_button").empty();
        if (obj_mobile_nav.back_button == 1) {
            if (obj_mobile_nav.parent_id == 0) {
                document.getElementById("navbar_mobile_back_button").innerHTML = '<button type="button" class="nav-link button-link" data-id="0"><strong><i class="icon-angle-left"></i>' + obj_mobile_nav.name + '</strong></button>';
            } else {
                var item_parent_name = $('.mega-menu li a[data-id="' + obj_mobile_nav.parent_id + '"]').text();
                document.getElementById("navbar_mobile_back_button").innerHTML = '<button type="button" class="nav-link button-link" data-id="' + obj_mobile_nav.parent_id + '" data-category-name="' + item_parent_name + '"><strong><i class="icon-angle-left"></i>' + obj_mobile_nav.name + '</strong></button>';
            }
            var item_all_link = $('.mega-menu li a[data-id="' + obj_mobile_nav.id + '"]').attr("href");
            document.getElementById("navbar_mobile_categories").innerHTML = '<li class="nav-item"><a href="' + item_all_link + '" class="nav-link">' + MdsConfig.textAll + '</a></li>';
        }
        $('.mega-menu li a[data-parent-id="' + obj_mobile_nav.id + '"]').each(function () {
            var item_id = $(this).attr("data-id");
            var item_parent_id = obj_mobile_nav.id;
            var item_link = $(this).attr("href");
            var item_text = $(this).text();
            var item_has_sb = $(this).attr("data-has-sb");
            var has_sub = false;
            var sub_id = parseInt($('.navbar-nav a[data-parent-id="' + item_id + '"]').attr('data-id'));
            if (!isNaN(sub_id) && sub_id > 0) {
                has_sub = true;
            }
            if (item_has_sb == 1 && has_sub == true) {
                $("#navbar_mobile_categories").append('<li class="nav-item"><button type="button" class="nav-link button-link" data-id="' + item_id + '" data-parent-id="' + item_parent_id + '">' + item_text + '<i class="icon-arrow-right"></i></button></li>');
            } else {
                $("#navbar_mobile_categories").append('<li class="nav-item"><a href="' + item_link + '" class="nav-link">' + item_text + '</a></li>');
            }
        });

        $(".nav-mobile-links").addClass('slide-in-150s');
        setTimeout(function () {
            $(".nav-mobile-links").removeClass('slide-in-150s');
        }, 150);
    }
}

//search
$(document).on('click', '.nav-mobile-header-container .a-search-icon', function () {
    if ($(".mobile-search-form").hasClass("display-block")) {
        $(".mobile-search-form").removeClass("display-block");
        $("#searchIconMobile").removeClass("icon-close");
        $("#searchIconMobile").addClass("icon-search")
    } else {
        $(".mobile-search-form").addClass("display-block");
        $("#searchIconMobile").removeClass("icon-search");
        $("#searchIconMobile").addClass("icon-close")
    }
});

//custom scrollbar
$(function () {
    $('.filter-custom-scrollbar').overlayScrollbars({});
    $('.search-categories').overlayScrollbars({});
    $('.custom-scrollbar').overlayScrollbars({});
});

/*mega menu*/
$(".mega-menu .nav-item").hover(function () {
    var menu_id = $(this).attr('data-category-id');
    $("#mega_menu_content_" + menu_id).show();
    $(".large-menu-item").removeClass('active');
    $(".large-menu-item-first").addClass('active');
    $(".large-menu-content-first").addClass('active');
    //$("#menu-overlay").show();
}, function () {
    var menu_id = $(this).attr('data-category-id');
    $("#mega_menu_content_" + menu_id).hide();
    //$("#menu-overlay").hide();
});

$(".mega-menu .dropdown-menu").hover(function () {
    $(this).show();
}, function () {
});

$(".large-menu-item").hover(function () {
    var menu_id = $(this).attr('data-subcategory-id');
    $(".large-menu-item").removeClass('active');
    $(this).addClass('active');
    $(".large-menu-content").removeClass('active');
    $("#large_menu_content_" + menu_id).addClass('active');
}, function () {
});
$(document).ready(function () {
    $('.row-img-product-list').hover(
        function () {
            var $img = $(this).find('img.img-product');
            var secondImageSrc = $img.data('second');
            $img.stop().fadeTo(50, 0, function () {
                $img.attr('src', secondImageSrc).fadeTo(50, 1);
            });
        },
        function () {
            var $img = $(this).find('img.img-product');
            var firstImageSrc = $img.data('first');
            $img.stop().fadeTo(50, 0, function () {
                $img.attr('src', firstImageSrc).fadeTo(50, 1);
            });
        }
    );
});

//scrollup
$(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
        $(".scrollup").fadeIn()
    } else {
        $(".scrollup").fadeOut()
    }
});
$(".scrollup").click(function () {
    $("html, body").animate({scrollTop: 0}, 700);
    return false
});

$(document).on('click', '.quantity-select-product .dropdown-menu .dropdown-item', function () {
    $(".quantity-select-product .btn span").text($(this).text());
    $("input[name='product_quantity']").val($(this).text());
});

//show phone number
$(document).on('click', '#show_phone_number', function () {
    $(this).hide();
    $("#phone_number").show();
});

$(document).ready(function () {
    $(".select2").select2({
        placeholder: $(this).attr('data-placeholder'),
        height: 42,
        dir: MdsConfig.rtl == true ? "rtl" : "ltr",
        "language": {
            "noResults": function () {
                return MdsConfig.textNoResultsFound;
            }
        },
    });
});

$(document).bind('ready ajaxComplete', function () {
    var startFromLeft = true;
    if (MdsConfig.rtl == true) {
        startFromLeft = false;
    }
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: false,
        zoomable: false,
        draggable: false
    });
});

//on click product details reviews text
$(document).on('click', '#btnGoToReviews', function () {
    $('#tab_reviews').tab('show');
    var target = $('#product_description_content');
    $('html, body').animate({
        scrollTop: $(target).offset().top
    }, 400);
});

/*
 * --------------------------------------------------------------------
 * Auth Functions
 * --------------------------------------------------------------------
 */

//login
$(document).ready(function () {
    $("#form_login").submit(function (event) {
        var form = $(this);
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            var inputs = form.find("input, select, button, textarea");
            var serializedData = form.serializeArray();
            serializedData = setSerializedData(serializedData);
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/login-post',
                data: serializedData,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj.result == 1) {
                        location.reload();
                    } else if (obj.result == 0) {
                        document.getElementById("result-login").innerHTML = obj.errorMessage;
                    }
                }
            });
        }
        form[0].classList.add('was-validated');
    });
});

function checkRecaptchaRegisterForm(form) {
    var serializedData = $(form).serializeArray();
    var recaptcha = '';
    $.each(serializedData, function (i, field) {
        if (field.name == 'g-recaptcha-response') {
            recaptcha = field.value;
        }
    });
    if (recaptcha.length < 5) {
        $('.g-recaptcha>div').addClass('is-invalid');
        return false;
    } else {
        $('.g-recaptcha>div').removeClass('is-invalid');
    }
}

//send activation email
function sendActivationEmail(token, type) {
    document.getElementById("confirmation-result-" + type).innerHTML = '';
    var data = {
        'token': token,
        'type': type
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Auth/sendActivationEmailPost',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                setTimeout(function () {
                    document.getElementById("confirmation-result-" + type).innerHTML = obj.successMessage;
                }, 500);
            } else {
                location.reload();
            }
        }
    });
}

//delete cover image
function deleteCoverImage() {
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Profile/deleteCoverImagePost',
        data: setAjaxData({}),
        success: function (response) {
            location.reload();
        }
    });
}

//show image preview
function showImagePreview(input, showAsBackground) {
    var divId = $(input).attr('data-img-id');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (showAsBackground) {
                $('#' + divId).css('background-image', 'url(' + e.target.result + ')');
            } else {
                $('#' + divId).attr('src', e.target.result);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

/*
 * --------------------------------------------------------------------
 * Variation Functions
 * --------------------------------------------------------------------
 */

function selectProductVariationOption(variationId, variationType, selectedOptionId) {
    var arrayVariationValues = [];
    $('.input-product-variation').each(function () {
        var varId = $(this).attr('data-id');
        var varType = $(this).attr('data-type');
        var varOptionId = '';
        if (varType == 'radio_button') {
            varOptionId = $("input[name='variation" + varId + "']:checked").val();
        }
        if (varType == 'checkbox') {
            varOptionId = $("input[name='variation" + varId + "']:checked").val();
        }
        if (varType == 'dropdown') {
            varOptionId = $("#variation_dropdown_" + varId).val();
        }
        if (varOptionId == undefined) {
            varOptionId = '';
        }
        var item = {
            'var_id': $(this).attr('data-id'),
            'var_option_id': varOptionId
        };
        arrayVariationValues.push(item);
    });
    var data = {
        'variation_id': variationId,
        'selected_option_id': selectedOptionId,
        'variation_array': arrayVariationValues,
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/select-variation-option-post',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status == 1) {
                if (obj.htmlContentPrice != '') {
                    document.getElementById("product_details_price_container").innerHTML = obj.htmlContentPrice;
                }
                if (obj.htmlContentStock != '') {
                    document.getElementById("text_product_stock_status").innerHTML = obj.htmlContentStock;
                    if (obj.stockStatus == 0) {
                        $(".btn-product-cart").attr("disabled", true);
                    } else {
                        $(".btn-product-cart").attr("disabled", false);
                    }
                }
                if (obj.htmlContentSlider != '') {
                    $('#product_slider').slick('unslick');
                    $('#product_thumbnails_slider').slick('unslick');
                    document.getElementById("product_slider_container").innerHTML = obj.htmlContentSlider;
                    $('#product_slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        speed: 300,
                        arrows: true,
                        fade: true,
                        infinite: false,
                        swipeToSlide: true,
                        cssEase: 'linear',
                        lazyLoad: 'progressive',
                        prevArrow: $('#product-slider-nav .prev'),
                        nextArrow: $('#product-slider-nav .next'),
                        asNavFor: '#product_thumbnails_slider'
                    });
                    $('#product_thumbnails_slider').slick({
                        slidesToShow: 7,
                        slidesToScroll: 1,
                        speed: 300,
                        focusOnSelect: true,
                        arrows: false,
                        infinite: false,
                        vertical: true,
                        centerMode: false,
                        arrows: true,
                        cssEase: 'linear',
                        lazyLoad: 'progressive',
                        prevArrow: $('#product-thumbnails-slider-nav .prev'),
                        nextArrow: $('#product-thumbnails-slider-nav .next'),
                        asNavFor: '#product_slider'
                    });
                }
            }
            if (variationType == 'dropdown') {
                getSubVariationOptions(variationId, selectedOptionId);
            }
        }
    });
}

function getSubVariationOptions(variationId, selectedOptionId) {
    var data = {
        'variation_id': variationId,
        'selected_option_id': selectedOptionId,
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/get-sub-variation-options',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status == 1) {
                if (selectedOptionId == '') {
                    document.getElementById("variation_dropdown_" + obj.subVariationId).innerHTML = '';
                } else {
                    document.getElementById("variation_dropdown_" + obj.subVariationId).innerHTML = obj.htmlContent;
                }
            }
        }
    });
}

/*
 * --------------------------------------------------------------------
 * Number Spinner Functions
 * --------------------------------------------------------------------
 */

//number spinner
$(document).on('click', '.product-add-to-cart-container .number-spinner button', function () {
    update_number_spinner($(this));
});

function update_number_spinner(btn) {
    var btn = btn,
        oldValue = btn.closest('.number-spinner').find('input').val().trim(),
        newVal = 0;
    if (btn.attr('data-dir') == 'up') {
        newVal = parseInt(oldValue) + 1;
    } else {
        if (oldValue > 1) {
            newVal = parseInt(oldValue) - 1;
        } else {
            newVal = 1;
        }
    }
    btn.closest('.number-spinner').find('input').val(newVal);
}

$(document).on("input keyup paste change", ".number-spinner input", function () {
    var val = $(this).val();
    val = val.replace(",", "");
    val = val.replace(".", "");
    if (!$.isNumeric(val)) {
        val = 1;
    }
    if (isNaN(val)) {
        val = 1;
    }
    $(this).val(val);
});

$(document).on("input paste change", ".cart-item-quantity .number-spinner input", function () {
    var data = {
        'product_id': $(this).attr('data-product-id'),
        'cart_item_id': $(this).attr('data-cart-item-id'),
        'quantity': $(this).val(),
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/update-cart-product-quantity',
        data: setAjaxData(data),
        success: function (response) {
            location.reload();
        }
    });
});

$(document).on("click", ".cart-item-quantity .btn-spinner-minus", function () {
    update_number_spinner($(this));
    var cart_id = $(this).attr("data-cart-item-id");
    if ($("#q-" + cart_id).val() != 0) {
        $("#q-" + cart_id).change();
    }
});

$(document).on("click", ".cart-item-quantity .btn-spinner-plus", function () {
    update_number_spinner($(this));
    var cart_id = $(this).attr("data-cart-item-id");
    $("#q-" + cart_id).change();
});

function removeCartDiscountCoupon() {
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Cart/removeCartDiscountCoupon',
        data: setAjaxData({}),
        success: function (response) {
            location.reload();
        }
    });
}

/*
 * --------------------------------------------------------------------
 * Review Functions
 * --------------------------------------------------------------------
 */
$(document).on('click', '.rate-product .rating-stars label', function () {
    var productId = $(this).attr("data-product-id");
    $('.rate-product .rating-stars .label-rating-' + productId + ' i').removeClass("icon-star");
    $('.rate-product .rating-stars .label-rating-' + productId + ' i').addClass("icon-star-o");
    var selected_star = $(this).attr("data-star");
    $('.rate-product .rating-stars-modal label').each(function () {
        var star = $(this).attr("data-star");
        if (star <= selected_star) {
            $(this).find('i').removeClass("icon-star-o");
            $(this).find('i').addClass("icon-star");
        } else {
            $(this).find('i').removeClass("icon-star");
            $(this).find('i').addClass("icon-star-o");
        }
    });
});

$(document).on('click', '.rate-product .label-star-open-modal', function () {
    var productId = $(this).attr("data-product-id");
    var rate = $(this).attr("data-star");
    $("#rateProductModal #review_product_id").val(productId);
    $("#rateProductModal #user_rating").val(rate);
});

$(document).on('click', '.btn-add-review', function () {
    var product_id = $(this).attr("data-product-id");
    $("#rateProductModal #review_product_id").val(product_id);
});

function deleteReview(id, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var data = {
                'id': id
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Ajax/deleteReview',
                data: setAjaxData(data),
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
}

/*
 * --------------------------------------------------------------------
 * Product Comment Functions
 * --------------------------------------------------------------------
 */

//load more reviews & comments
let reviewsLoadOffset = MdsConfig.reviewsLoadLimit;
let commentsLoadOffset = MdsConfig.commentsLoadLimit;
let commentSubFormId = 0;

$(document).ready(function () {
    //add comment
    $("#formAddComment").submit(function (event) {
        event.preventDefault();
        var form = $("#formAddComment");
        addProductComment(form, 0);
    });
});

//add subcomment
$(document).on('click', '.btn-submit-subcomment', function (event) {
    event.preventDefault();
    var commentId = $(this).attr("data-comment-id");
    var form = $("#formAddSubcomment" + commentId);
    addProductComment(form, commentId);
});

//add comment
function addProductComment(form, commentId = 0) {
    var isLoggedIn = MdsConfig.isloggedIn == 1 ? true : false;
    var isValid = true;
    var formSerialized = form.serializeArray();
    objectSerialized = {};
    $(formSerialized).each(function (i, field) {
        objectSerialized[field.name] = field.value;
        if (field.name == "g-recaptcha-response") {
            gRecaptcha = field.value;
        }
    });
    if (isLoggedIn == false) {
        if (strLenght(objectSerialized.name) < 1) {
            form.find('input[name="name"]').addClass("is-invalid");
            isValid = false;
        } else {
            form.find('input[name="name"]').removeClass("is-invalid");
        }
        if (strLenght(objectSerialized.email) < 1) {
            form.find('input[name="email"]').addClass("is-invalid");
            isValid = false;
        } else {
            form.find('input[name="email"]').removeClass("is-invalid");
        }
        if (MdsConfig.isRecaptchaEnabled == true && isLoggedIn == false) {
            if (gRecaptcha == '') {
                form.find(".g-recaptcha").addClass("is-recaptcha-invalid");
                isValid = false;
            } else {
                form.find(".g-recaptcha").removeClass("is-recaptcha-invalid");
            }
        }
    }
    if (strLenght(objectSerialized.comment) < 1) {
        form.find('textarea[name="comment"]').addClass("is-invalid");
        isValid = false;
    } else {
        form.find('textarea[name="comment"]').removeClass("is-invalid");
    }
    if (!isValid) {
        return false;
    }
    formSerialized.push({name: 'limit', value: commentsLoadOffset});
    formSerialized = setSerializedData(formSerialized);
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/addComment',
        data: formSerialized,
        success: function (response) {
            if (MdsConfig.isRecaptchaEnabled == true && isLoggedIn == false) {
                grecaptcha.reset();
            }
            form[0].reset();
            var obj = JSON.parse(response);
            if (obj.status == 1) {
                if (obj.type == 'message') {
                    swal({text: obj.message, icon: "success", button: MdsConfig.textOk});
                } else {
                    document.getElementById("productCommentsListContainer").innerHTML = obj.htmlContent;
                }
                $('.visible-sub-comment').empty();
                $('.no-comments-found').hide();
            }
        }
    });
}

//show comment box
function showCommentForm(commentId) {
    if (commentSubFormId == commentId) {
        $('#subCommentForm' + commentId).empty();
        commentSubFormId = 0;
    } else {
        $('.visible-sub-comment').empty();
        var data = {
            'comment_id': commentId,
        };
        $.ajax({
            type: 'POST',
            url: MdsConfig.baseURL + '/Ajax/loadSubCommentForm',
            data: setAjaxData(data),
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.status == 1) {
                    commentSubFormId = commentId;
                    $('#subCommentForm' + commentId).append(obj.htmlContent);
                }
            }
        });
    }
}

//load more reviews
$(document).on('click', '#btnLoadMoreProductReviews', function () {
    var button = $(this);
    var total = Number($(this).attr('data-total'));
    reviewsLoadOffset = Number(reviewsLoadOffset);
    var data = {
        'product_id': $(this).attr('data-product'),
        'offset': reviewsLoadOffset
    };
    button.find('svg').toggleClass('rotate');
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/loadMoreReviews',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status == 1) {
                setTimeout(function () {
                    button.find('svg').toggleClass('rotate');
                    $('#productReviewsListContainer').append(obj.htmlContent);
                    reviewsLoadOffset += Number(MdsConfig.reviewsLoadLimit);

                    if (reviewsLoadOffset >= total) {
                        button.hide();
                    }

                }, 300);
            }
        }
    });
});

//load more comments
$(document).on('click', '#btnLoadMoreProductComments', function () {
    var button = $(this);
    var total = Number($(this).attr('data-total'));
    commentsLoadOffset = Number(commentsLoadOffset);
    var data = {
        'product_id': $(this).attr('data-product'),
        'offset': commentsLoadOffset
    };
    button.find('svg').toggleClass('rotate');
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/loadMoreComments',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status == 1) {
                setTimeout(function () {
                    button.find('svg').toggleClass('rotate');
                    $('#productCommentsListContainer').append(obj.htmlContent);
                    commentsLoadOffset += Number(MdsConfig.commentsLoadLimit);

                    if (commentsLoadOffset >= total) {
                        button.hide();
                    }

                }, 300);
            }
        }
    });
});

//delete comment
function deleteComment(commentId, type, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var data = {
                'id': commentId
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Ajax/deleteComment',
                data: setAjaxData(data),
                success: function (response) {
                    $('#li-' + type + '-' + commentId).remove();
                }
            });
        }
    });
}

//create affiliate link
$(document).on('click', '#btnCreateAffiliateLink', function () {
    var data = {
        'product_id': $(this).attr('data-id'),
        'lang_id': MdsConfig.sysLangId
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/createAffiliateLink',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status == 1) {
                $('#spanAffLink').text(obj.response);
                $('#affliateLinkModal').modal('show');
            }
        }
    });
});

//copy affiliate link
$(document).on('click', '#btnCopyAffLink', function () {
    var link = $('#spanAffLink').text();
    navigator.clipboard.writeText(link);
    $('#btnCopyAffLink').text(MdsConfig.textCopied);
    setTimeout(function () {
        $('#btnCopyAffLink').text(MdsConfig.textCopyLink);
    }, 2000);
});

//validate email
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    } else {
        return true;
    }
}

//get string lenght
function strLenght(str) {
    if (str == '' || str == null) {
        return 0;
    }
    str = str.trim();
    return str.length;
}

/*
 * --------------------------------------------------------------------
 * Blog Comments Functions
 * --------------------------------------------------------------------
 */

$(document).ready(function () {
    //add comment
    $("#form_add_blog_comment").submit(function (event) {
        event.preventDefault();
        var isLoggedIn = true;
        var isValid = true;
        var formSerialized = $("#form_add_blog_comment").serializeArray();
        objectSerialized = {};
        $(formSerialized).each(function (i, field) {
            objectSerialized[field.name] = field.value;
            if (field.name == 'g-recaptcha-response') {
                gRecaptcha = field.value;
            }
        });
        if ($("#form_add_blog_comment").find("#comment_name").length > 0) {
            isLoggedIn = false;
        }
        if (isLoggedIn == false) {
            if (strLenght(objectSerialized.name) < 1) {
                $('#comment_name').addClass('is-invalid');
                isValid = false;
            } else {
                $('#comment_name').removeClass('is-invalid');
            }
            if (strLenght(objectSerialized.email) < 1) {
                $('#comment_email').addClass('is-invalid');
                isValid = false;
            } else {
                $('#comment_email').removeClass('is-invalid');
            }
            if (MdsConfig.isRecaptchaEnabled == true && isLoggedIn == false) {
                if (gRecaptcha == '') {
                    $('#form_add_blog_comment .g-recaptcha').addClass('is-recaptcha-invalid');
                    isValid = false;
                } else {
                    $('#form_add_blog_comment .g-recaptcha').removeClass('is-recaptcha-invalid');
                }
            }
        }
        if (strLenght(objectSerialized.comment) < 1) {
            $('#comment_text').addClass('is-invalid');
            isValid = false;
        } else {
            $('#comment_text').removeClass('is-invalid');
        }
        if (!isValid) {
            return false;
        }
        formSerialized.push({name: 'limit', value: parseInt($("#blog_comment_limit").val())});
        formSerialized = setSerializedData(formSerialized);
        $.ajax({
            type: 'POST',
            url: MdsConfig.baseURL + '/Ajax/addBlogComment',
            data: formSerialized,
            success: function (response) {
                if (MdsConfig.isRecaptchaEnabled == true && isLoggedIn == false) {
                    grecaptcha.reset();
                }
                $("#form_add_blog_comment")[0].reset();
                var obj = JSON.parse(response);
                if (obj.type == 'message') {
                    swal({text: obj.message, icon: "success", button: MdsConfig.textOk});
                } else {
                    document.getElementById("comment-result").innerHTML = obj.htmlContent;
                }
            }
        });
    });
});

//load more blog comment
function loadMoreBlogComments(postId) {
    var limit = parseInt($("#blog_comment_limit").val());
    var data = {
        'post_id': postId,
        'limit': limit
    };
    $("#load_comment_spinner").show();
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/loadMoreBlogComments',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.type == 'comments') {
                setTimeout(function () {
                    $("#load_comment_spinner").hide();
                    document.getElementById("comment-result").innerHTML = obj.htmlContent;
                }, 500);
            }
        }
    });
}

//delete blog comment
function deleteBlogComment(commentId, postId, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var limit = parseInt($("#blog_comment_limit").val());
            var data = {
                'comment_id': commentId,
                'post_id': postId,
                'limit': limit
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Ajax/deleteBlogComment',
                data: setAjaxData(data),
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj.type == 'comments') {
                        document.getElementById("comment-result").innerHTML = obj.htmlContent;
                    }
                }
            });
        }
    });
}

/*
 * --------------------------------------------------------------------
 * Chat Functions
 * --------------------------------------------------------------------
 */


//send message
$("#formSendChatMessage").submit(function (event) {
    event.preventDefault();
    var inputSubject = $("#formSendChatMessage input[name=subject]");
    var inputMessage = $("#formSendChatMessage textarea[name=message]");
    if (inputSubject.val().length < 1) {
        inputSubject.addClass("is-invalid");
        return false;
    } else {
        inputSubject.removeClass("is-invalid");
    }
    if (inputMessage.val().length < 1) {
        inputMessage.addClass("is-invalid");
        return false;
    } else {
        inputMessage.removeClass("is-invalid");
    }
    $("#formSendChatMessage .form-group :input").prop("disabled", true);
    var data = {
        'subject': inputSubject.val(),
        'message': inputMessage.val(),
        'receiver_id': $("#formSendChatMessage input[name=receiver_id]").val(),
        'product_id': $("#formSendChatMessage input[name=product_id]").val()
    }
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/addChatPost',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("chatSendMessageResult").innerHTML = obj.htmlContent;
                inputMessage.val('');
            }
            $("#formSendChatMessage .form-group :input").prop("disabled", false);
        }
    });
});

//load chat
$(document).on('click', '.chat .chat-contact', function () {
    $('.chat-contact').removeClass('active');
    $(this).addClass('active');
    let chatId = $(this).attr('data-chat-id');
    var data = {
        'chat_id': chatId,
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/loadChatPost',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status === 1) {
                $('#inputChatId').val(chatId);
                $('#inputChatReceiverId').val(obj.receiverId);
                $('#inputChatMessage').val('');
                updateChatContacts(obj.arrayChats);
                document.getElementById("chatUserContainer").innerHTML = obj.htmlchatUser;
                document.getElementById("chatMessagesContainer").innerHTML = obj.htmlContentMessages;
                document.getElementById("chatInputContainer").innerHTML = obj.htmlChatForm;
                $('#mdsChat').addClass('chat-mobile-open');
                $('#mdsChat').removeClass('chat-empty');
                $("#messagesContainer" + chatId).scrollTop($("#messagesContainer" + chatId)[0].scrollHeight);
                $('#chatBadge' + chatId).hide();
            }
        }
    });
});

//update chat
const mdsChat = document.getElementById('mdsChat');
if (mdsChat) {
    setInterval(function () {
        var chatId = $('#inputChatId').val();
        var data = {
            'chat_id': chatId,
        };
        $.ajax({
            type: 'GET',
            url: MdsConfig.baseURL + '/Ajax/updateChatGet',
            data: data,
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.status === 1) {
                    updateChatContacts(obj.arrayChats);
                    if (chatId) {
                        appendNewChatMessages(obj.arrayMessages, obj.chatId);
                        $("#messagesContainer" + chatId).scrollTop($("#messagesContainer" + chatId)[0].scrollHeight);
                    }
                }
            }
        });
    }, MdsConfig.chatUpdateTime * 1000);
}

//send message
$(document).on('click', '#btnChatSubmit', function () {
    sendChatMessage();
});

function sendChatMessage() {
    var chatId = $("#formChat input[name=chat_id]").val();
    var receiverId = $("#formChat input[name=receiver_id]").val();
    var message = $("#formChat input[name=message]").val();
    if (message.trim().length < 1) {
        return false;
    }
    $("#inputChatMessage").prop('disabled', true);
    $("#formChat button").prop('disabled', true);
    var data = {
        'chat_id': chatId,
        'receiver_id': receiverId,
        'message': message
    }
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/sendMessagePost',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status === 1) {
                updateChatContacts(obj.arrayChats);
                appendNewChatMessages(obj.arrayMessages, obj.chatId);
                $("#messagesContainer" + obj.chatId).scrollTop($("#messagesContainer" + obj.chatId)[0].scrollHeight);
                $("#inputChatMessage").prop('disabled', false);
                $("#formChat button").prop('disabled', false);
                $("#inputChatMessage").val('');
            }
        }
    });
}

//update contacts
function updateChatContacts(chats) {
    var chatContactsContainer = document.getElementById("chatContactsContainer");
    var htmlContact = '';
    for (let i = 0; i < chats.length; i++) {
        htmlContact += '<div class="item">\n' +
            '<div class="chat-contact ' + chats[i].class + '" data-chat-id="' + chats[i].chatId + '">\n' +
            '<div class="flex-item">\n' +
            '<div class="item-img">\n' +
            '<img src="' + chats[i].avatar + '" alt="' + chats[i].username + '">\n' +
            '</div>\n' +
            '</div>\n' +
            '<div class="flex-item flex-item-center">\n' +
            '<h6 class="username">' + chats[i].username + '</h6>\n' +
            '<p class="subject">' + chats[i].subject + '</p>\n';
        if (chats[i].updatedAt) {
            htmlContact += '<div class="time"><span>' + chats[i].updatedAt + '</span></div>\n';
        }
        htmlContact += '</div>\n';
        if (chats[i].numUnreadMessages > 0) {
            htmlContact += '<div class="flex-item">\n' +
                '<label id="chatBadge' + chats[i].chatId + '" class="badge badge-success">' + chats[i].numUnreadMessages + '</label>\n' +
                '</div>\n';
        }
        htmlContact += '</div>\n' +
            '</div>';
    }
    chatContactsContainer.innerHTML = htmlContact;
    searchContacts();
}

//append new messages
function appendNewChatMessages(messages, chatId) {
    if (messages !== undefined && messages !== null) {
        if (document.getElementById('messagesContainer' + chatId)) {
            for (let i = 0; i < messages.length; i++) {
                if (!document.getElementById('chatMessage' + messages[i].id)) {
                    if (messages[i].isRight === true) {
                        var htmlContent = '<div id="chatMessage' + messages[i].id + '" class="message message-right">\n' +
                            '<div class="flex-item">\n' +
                            '<div class="message-text">' + messages[i].message + '</div>\n' +
                            '<div class="time">\n' +
                            '<span>' + messages[i].time + '</span>\n' +
                            '</div>\n' +
                            '</div>\n' +
                            '<div class="flex-item item-user">\n' +
                            '<div class="user-img">\n' +
                            '<img src="' + messages[i].avatar + '" alt="" class="img-profile">\n' +
                            '</div>\n' +
                            '</div>\n' +
                            '</div>';
                    } else {
                        var htmlContent = '<div id="chatMessage' + messages[i].id + '" class="message">\n' +
                            '<div class="flex-item item-user">\n' +
                            '<div class="user-img">\n' +
                            '<img src="' + messages[i].avatar + '" alt="" class="img-profile">\n' +
                            '</div>\n' +
                            '</div>\n' +
                            '<div class="flex-item">\n' +
                            '<div class="message-text">' + messages[i].message + '</div>\n' +
                            '<div class="time">\n' +
                            '<span>' + messages[i].time + '</span>\n' +
                            '</div>\n' +
                            '</div>\n' +
                            '</div>';
                    }
                    $('#messagesContainer' + chatId).append(htmlContent);
                }
            }
        }
    }
}

//search product filters
$(document).on('change keyup paste', '#chatSearchContacts', function () {
    searchContacts();
});

function searchContacts() {
    var input = $('#chatSearchContacts').val().toLowerCase();
    var listItems = $('.chat-contacts .chat-contact');
    listItems.each(function (idx, item) {
        var username = $(this).find('.username').text().toLowerCase();
        var subject = $(this).find('.subject').text().toLowerCase();
        if (username.indexOf(input) > -1 || subject.indexOf(input) > -1) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

$(document).on('click', '#btnOpenChatContacts', function () {
    $('#mdsChat').removeClass('chat-mobile-open');
});

//delete chat
function deleteChat(chatId, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var data = {
                'chat_id': chatId,
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Ajax/deleteChatPost',
                data: setAjaxData(data),
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
}


/*
 * --------------------------------------------------------------------
 * Cart Functions
 * --------------------------------------------------------------------
 */

$("#form_add_cart").submit(function (event) {
    $('#modalAddToCart').modal('hide');
    var form = $(this);
    if (form[0].checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        setTimeout(function () {
            validateVariations('form_add_cart');
        }, 50);
    } else {
        event.preventDefault();
        $('#form_add_cart .btn-product-cart').prop('disabled', true);
        $('#form_add_cart .btn-product-cart .btn-cart-icon').html('<span class="spinner-border spinner-border-add-cart"></span>');
        var serializedData = form.serializeArray();
        serializedData = setSerializedData(serializedData);
        $.ajax({
            type: 'POST',
            url: MdsConfig.baseURL + '/cart/add-to-cart',
            data: serializedData,
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.result == 1) {
                    setTimeout(function () {
                        document.getElementById("contentModalCartProduct").innerHTML = obj.htmlCartProduct;
                        $('#form_add_cart .btn-product-cart').html('<i class="icon-check"></i>' + MdsConfig.textAddedtoCart);
                        $('.span_cart_product_count').html(obj.productCount);
                        $('.span_cart_product_count').removeClass('visibility-hidden');
                        $('.span_cart_product_count').addClass('visibility-visible');
                        $('#modalAddToCart').modal('show');
                    }, 400);
                    setTimeout(function () {
                        $('#form_add_cart .btn-product-cart').html('<span class="btn-cart-icon"><i class="icon-cart-solid"></i></span>' + MdsConfig.textAddtoCart);
                        $('#form_add_cart .btn-product-cart').prop('disabled', false);
                    }, 1000);
                }
            }
        });
    }
    form[0].classList.add('was-validated');
});

$(document).on('click', '.btn-item-add-to-cart', function () {
    var productId = $(this).attr("data-product-id");
    var buttonId = $(this).attr("data-id");
    document.getElementById("btn_add_cart_" + buttonId).innerHTML = '<div class="spinner-border spinner-border-add-cart-list"></div>';
    var data = {
        'product_id': productId,
        'is_ajax': true
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/cart/add-to-cart',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                setTimeout(function () {
                    document.getElementById("contentModalCartProduct").innerHTML = obj.htmlCartProduct;
                    $('#btn_add_cart_' + buttonId).css('background-color', 'rgb(40, 167, 69, .7)');
                    document.getElementById("btn_add_cart_" + buttonId).innerHTML =
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">\n' +
                        '<path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>\n' +
                        '</svg>';
                    $('.span_cart_product_count').html(obj.productCount);
                    $('.span_cart_product_count').removeClass('visibility-hidden');
                    $('.span_cart_product_count').addClass('visibility-visible');
                    $('#modalAddToCart').modal('show');
                }, 400);
                setTimeout(function () {
                    $('#btn_add_cart_' + buttonId).css('background-color', 'rgba(255, 255, 255, .7)');
                    document.getElementById("btn_add_cart_" + buttonId).innerHTML = '<i class="icon-cart"></i>';
                }, 2000);
            }
        }
    });
});

//remove from cart
function removeFromCart(cartItemId) {
    var data = {
        'cart_item_id': cartItemId
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Cart/removeFromCart',
        data: setAjaxData(data),
        success: function (response) {
            location.reload();
        }
    });
};

function validateVariations(formId) {
    $('#' + formId + ' .custom-control-variation input').each(function () {
        if ($(this).hasClass('error')) {
            var id = $(this).attr('id');
            $('#' + formId + ' .custom-control-variation label').each(function () {
                if ($(this).attr('for') == id) {
                    $(this).addClass('is-invalid');
                }
            });
        } else {
            var id = $(this).attr('id');
            $('#' + formId + ' .custom-control-variation label').each(function () {
                if ($(this).attr('for') == id) {
                    $(this).removeClass('is-invalid');
                }
            });
        }
    });
}

$(document).ready(function () {
    $('#use_same_address_for_billing').change(function () {
        if ($(this).is(":checked")) {
            $('.cart-form-billing-address').hide();
            $('.cart-form-billing-address select').removeClass('select2-req');
        } else {
            $('.cart-form-billing-address').show();
            $('.cart-form-billing-address select').addClass('select2-req');
        }
    });
});

//approve order product
function approveOrderProduct(id, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var data = {
                'order_product_id': id,
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Order/approveOrderProductPost',
                data: setAjaxData(data),
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//cancel order
function cancelOrder(id, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var data = {
                'order_id': id
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Order/cancelOrderPost',
                data: setAjaxData(data),
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//delete affiliate link
function deleteAffiliateLink(id, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var data = {
                'link_id': id
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Profile/deleteAffiliateLinkPost',
                data: setAjaxData(data),
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//location modal
$(document).on("click", ".btn-modal-location-header", function () {
    $('#locationModal').removeClass('location-modal-estimated-delivery');
    $('#locationModal').find('input[name="form_type"]').val("filter");
});

$(document).on("click", ".btn-modal-location-product", function () {
    $('#locationModal').addClass('location-modal-estimated-delivery');
    $('#locationModal').find('input[name="form_type"]').val("set_user_location");
});

//get shipping methods by location
function getShippingMethodsByLocation(stateId) {
    $('#cart_shipping_methods_container').hide();
    $('.cart-shipping-loader').show();
    var data = {
        'state_id': stateId,
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Cart/getShippingMethodsByLocation',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("cart_shipping_methods_container").innerHTML = obj.htmlContent;
                setTimeout(function () {
                    $('#cart_shipping_methods_container').show();
                    $('.cart-shipping-loader').hide();
                }, 400);
            }
        }
    });
};

$(document).on("click", "#btnShowCartShippingError", function () {
    $("#cartShippingError").show();
    setTimeout(function () {
        $("#cartShippingError").hide();
    }, 5000);
});

function validateFileInput(id) {
    var val = $('#' + id).val();
    if (!val) {
        $('#' + id + '_flash_error').show();
        setTimeout(function () {
            $('#' + id + '_flash_error').hide();
        }, 5000);
    }
}

/*
 * --------------------------------------------------------------------
 * Abuse Reports
 * --------------------------------------------------------------------
 */

//report product
$("#form_report_product").submit(function (event) {
    event.preventDefault();
    reportAbuse("form_report_product", "product");
});

//report seller
$("#form_report_seller").submit(function (event) {
    event.preventDefault();
    reportAbuse("form_report_seller", "seller");
});

//report review
$("#form_report_review").submit(function (event) {
    event.preventDefault();
    reportAbuse("form_report_review", "review");
});

//report comment
$("#form_report_comment").submit(function (event) {
    event.preventDefault();
    reportAbuse("form_report_comment", "comment");
});

function reportAbuse(form_id, item_type) {
    var formSerialized = $("#" + form_id).serializeArray();
    formSerialized.push({name: "item_type", value: item_type});
    formSerialized = setSerializedData(formSerialized);
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/reportAbusePost',
        data: formSerialized,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.message != '') {
                document.getElementById("response_" + form_id).innerHTML = obj.message;
                $("#" + form_id)[0].reset();
            }
        }
    });
}

if ($(".profile-cover-image")[0]) {
    document.addEventListener('lazybeforeunveil', function (e) {
        var bg = e.target.getAttribute('data-bg-cover');
        if (bg) {
            e.target.style.backgroundImage = 'url(' + bg + ')';
        }
    });
}

/*
 * --------------------------------------------------------------------
 * Other Functions
 * --------------------------------------------------------------------
 */

//AJAX search
$(document).on("input", "#input_search_main", function () {
    var inputValue = $(this).val();
    if (inputValue) {
        search(inputValue, 'desktop');
    }
});

$(document).on("input", "#input_search_mobile", function () {
    var inputValue = $(this).val();
    if (inputValue) {
        search(inputValue, 'mobile');
    }
});

function search(inputValue, device) {
    var contentId = 'response_search_results';
    if (device == 'mobile') {
        contentId = contentId + '_mobile';
    }
    if (inputValue.length < 3) {
        $('#' + contentId).hide();
        return false;
    }
    var data = {
        'input_value': inputValue,
        'lang_base_url': MdsConfig.langBaseURL
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/ajaxSearch',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1 && obj.rowCount > 0) {
                document.getElementById(contentId).innerHTML = obj.response;
                $('#' + contentId).show();
            }else{
                document.getElementById(contentId).innerHTML = '';
            }
        }
    });
}

$(document).on('click', function (e) {
    if ($(e.target).closest(".top-search-bar").length === 0) {
        $("#response_search_results").hide();
    }
});

//search in filter options
$(document).on("change keyup paste", ".filter-search-input", function () {
    var filter_id = $(this).attr('data-filter-id');
    var input = $(this).val().toLowerCase();
    var list_items = $("#" + filter_id + " li");
    list_items.each(function (idx, li) {
        var text = $(this).find('label').text().toLowerCase();
        if (text.indexOf(input) > -1) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

//load products
$(document).ready(function () {
    let prLoadRunning = false;
    let prLoadHasMore = $('#productListContent').attr('data-has-more');
    let prLoadUserId = $('#productListContent').attr('data-user-id');
    let prLoadCouponId = $('#productListContent').attr('data-coupon-id');

    function loadProducts() {
        if (prLoadRunning == true) {
            return false;
        }
        if (prLoadHasMore == false) {
            return false;
        }
        const urlParams = new URLSearchParams(window.location.search);
        let params = {};
        for (const [key, value] of urlParams.entries()) {
            params[key] = value;
        }
        if (params['page'] === undefined) {
            params['page'] = 1;
        } else {
            if (isNaN(params['page']) || params['page'] <= 0) {
                return false;
            }
        }
        params['page'] = Number(params['page']) + 1;
        prLoadRunning = true;
        $('#loadProductsSpinner').show();
        var data = {
            'category_id': $('#productListContent').attr('data-category'),
            'user_id': prLoadUserId,
            'coupon_id': prLoadCouponId,
            'params': params,
            'sysLangId': MdsConfig.sysLangId
        };
        $.ajax({
            type: 'GET',
            url: MdsConfig.baseURL + '/mds/load-products',
            data: data,
            success: function (response) {
                setTimeout(function () {
                    var obj = JSON.parse(response);
                    if (obj.result == 1) {
                        $('#productListResultContainer').append(obj.htmlContent);
                        updatePageNumberInUrl(obj.pageNumber);
                    }
                    prLoadHasMore = obj.hasMore;
                    prLoadRunning = false;
                    $('#loadProductsSpinner').hide();
                }, 150);
            },
            error: function () {
                prLoadRunning = false;
                $('#loadProductsSpinner').hide();
            }
        });
    }

    if ($('#productListContent').length && prLoadHasMore == 1) {
        const prLoadMoreTrigger = document.querySelector('#footer');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadProducts();
                }
            });
        }, {
            rootMargin: '0px',
            threshold: 0.1
        });
        observer.observe(prLoadMoreTrigger);
    }
});

//show previous products
$(document).on("click", "#btnShowPreviousProducts", function () {
    const currentUrl = window.location.href;
    const currentUrlObj = new URL(window.location.href);
    let previousURL = '';

    const params = new URLSearchParams(currentUrlObj.search);
    let currentPage = parseInt(params.get('page')) || 1;
    const previousPage = currentPage > 1 ? currentPage - 1 : 1;

    previousURL = removeQueryParam(currentUrl, 'page');
    previousURL = addQueryParam(previousURL, 'page', previousPage);
    window.location.href = previousURL;
});

$(document).on("click", ".dropdownSortOptions button", function () {
    var pageUrl = window.location.href;
    var action = $(this).attr('data-action');
    if (action == "most_recent" || action == "lowest_price" || action == "highest_price" || action == "highest_rating") {
        pageUrl = removeQueryParam(pageUrl, 'sort');
        pageUrl = removeQueryParam(pageUrl, 'page');
        pageUrl = addQueryParam(pageUrl, 'sort', action);
        if ($('#productListProfile').length) {
            if (!pageUrl.includes('#products')) {
                pageUrl = pageUrl + '#products';
            }
        }
        window.location.href = pageUrl;
    }
});

$(document).on("click", "#btnFilterByKeyword", function () {
    var currentPageUrl = window.location.href;
    var pageUrl = currentPageUrl;
    var keyword = $('#input_filter_keyword').val().trim();
    // Check if the input is invalid
    let isPriceValid = true;
    $('#price_min').removeClass('is-invalid');
    $('#price_max').removeClass('is-invalid');
    if (!$('#price_min')[0].validity.valid) {
        isPriceValid = false;
        $('#price_min').addClass('is-invalid');
    }
    if (!$('#price_max')[0].validity.valid) {
        isPriceValid = false;
        $('#price_max').addClass('is-invalid');
    }
    var priceMin = parseFloat($('#price_min').val());
    var priceMax = parseFloat($('#price_max').val());
    if (priceMin >= priceMax) {
        isPriceValid = false;
        $('#price_min').addClass('is-invalid');
        $('#price_max').addClass('is-invalid');
    }
    if (isPriceValid == false) {
        return false;
    }
    pageUrl = removeQueryParam(pageUrl, 'p_min');
    pageUrl = removeQueryParam(pageUrl, 'p_max');
    pageUrl = removeQueryParam(pageUrl, 'search');
    if (priceMin !== '' && priceMin > 0) {
        pageUrl = addQueryParam(pageUrl, 'p_min', priceMin);
    }
    if (priceMax !== '' && priceMax > 0) {
        pageUrl = addQueryParam(pageUrl, 'p_max', priceMax);
    }
    if (keyword !== '') {
        keyword = removeUnsafeCharacters(keyword);
        pageUrl = addQueryParam(pageUrl, 'search', keyword);
    }
    if ($('#productListProfile').length) {
        if (!pageUrl.includes('#products')) {
            pageUrl = pageUrl + '#products';
        }
    }
    if (pageUrl != currentPageUrl) {
        pageUrl = removeQueryParam(pageUrl, 'page');
    }
    window.location.href = pageUrl;
});

//add query param to url
function addQueryParam(url, param, value) {
    const urlObj = new URL(url);
    const searchParams = new URLSearchParams(urlObj.search);
    searchParams.set(param, value);
    urlObj.search = searchParams.toString();
    return urlObj.toString();
}

//remove param from a url
function removeQueryParam(url, paramToRemove) {
    const urlObj = new URL(url);
    const searchParams = new URLSearchParams(urlObj.search);
    searchParams.delete(paramToRemove);
    urlObj.search = searchParams.toString();
    return urlObj.toString();
}

//update page number in url
function updatePageNumberInUrl(pageNumber) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', pageNumber);
    window.history.replaceState({}, '', url);
}

function removeUnsafeCharacters(input) {
    return input.replace(/[&<>"'/#%?=@+,:;*[\]{}|\\^~`()[\]$!]/g, '');
}

//load more products
var pagePromotedProducts = 1;

function loadMorePromotedProducts() {
    $("#load_promoted_spinner").show();
    pagePromotedProducts++;
    var data = {
        'page': pagePromotedProducts
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/loadMorePromotedProducts',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                setTimeout(function () {
                    $("#row_promoted_products").append(obj.htmlContent);
                    $("#load_promoted_spinner").hide();
                    if (obj.hasMore == false) {
                        $(".promoted-load-more-container").hide();
                    }
                }, 200);
            } else {
                setTimeout(function () {
                    $("#load_promoted_spinner").hide();
                    if (obj.hasMore == false) {
                        $(".promoted-load-more-container").hide();
                    }
                }, 200);
            }
        }
    });
}

function getStates(val, idSuffix = '') {
    if (idSuffix != '') {
        idSuffix = '_' + idSuffix;
    }
    $('#select_states' + idSuffix).children('option').remove();
    $('#get_states_container' + idSuffix).hide();
    if ($('#select_cities' + idSuffix).length) {
        $('#select_cities' + idSuffix).children('option').remove();
        $('#get_cities_container' + idSuffix).hide();
    }
    var data = {
        'country_id': val,
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/getStates',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("select_states" + idSuffix).innerHTML = obj.content;
                $('#get_states_container' + idSuffix).show();
            } else {
                document.getElementById("select_states" + idSuffix).innerHTML = '';
                $('#get_states_container' + idSuffix).hide();
            }
        }
    });
}

function getCities(val, idSuffix = '') {
    if (idSuffix != '') {
        idSuffix = '_' + idSuffix;
    }
    var data = {
        'state_id': val
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/getCities',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("select_cities" + idSuffix).innerHTML = obj.content;
                $('#get_cities_container' + idSuffix).show();
            } else {
                document.getElementById("select_cities" + idSuffix).innerHTML = '';
                $('#get_cities_container' + idSuffix).hide();
            }
        }
    });
}

$(document).on('click', '.btn-add-remove-wishlist', function () {
    var productId = $(this).attr("data-product-id");
    var dataType = $(this).attr("data-type");
    if (dataType == 'list') {
        if ($(this).find("i").hasClass("icon-heart-o")) {
            $(this).find("i").removeClass("icon-heart-o");
            $(this).find("i").addClass("icon-heart");
        } else {
            $(this).find("i").removeClass("icon-heart");
            $(this).find("i").addClass("icon-heart-o");
        }
    }
    if (dataType == 'details') {
        if ($(this).find("i").hasClass("icon-heart-o")) {
            $('.product-add-to-cart-container .btn-add-remove-wishlist').html('<i class="icon-heart"></i><span>' + MdsConfig.textRemoveFromWishlist + '</span>');
        } else {
            $('.product-add-to-cart-container .btn-add-remove-wishlist').html('<i class="icon-heart-o"></i><span>' + MdsConfig.textAddtoWishlist + '</span>');
        }
    }
    var data = {
        'product_id': productId,
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/add-remove-wishlist-post',
        data: setAjaxData(data),
        success: function (response) {
        }
    });
});

$("#form_validate").submit(function () {
    $('.custom-control-validate-input').removeClass('custom-control-validate-error');
    setTimeout(function () {
        $('.custom-control-validate-input .error').each(function () {
            var name = $(this).attr('name');
            if ($(this).is(":visible")) {
                name = name.replace('[]', '');
                $('.label_validate_' + name).addClass('custom-control-validate-error');
            }
        });
    }, 100);
});

$('.custom-control-validate-input input').click(function () {
    var name = $(this).attr('name');
    name = name.replace('[]', '');
    $('.label_validate_' + name).removeClass('custom-control-validate-error');
});

//hide cookies warning
function hideCookiesWarning() {
    $(".cookies-warning").hide();
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/hideCookiesWarning',
        data: setAjaxData({}),
        success: function (response) {
        }
    });
}

$(document).ready(function () {
    if ($(".validate-form").length > 0) {
        $('.validate-form').each(function (i, obj) {
            var id = $(this).attr('id');
            $("#" + id).validate();
        });
    }
});

//validate select2
$(".validate-form").submit(function () {
    $('.select2-req').each(function (i, obj) {
        var id = $(this).attr('id');
        var val = $(this).val();
        if (val == "" || val == null || val == undefined) {
            $('.select2-selection[aria-controls="select2-' + id + '-container"]').addClass('error');
        } else {
            $('.select2-selection[aria-controls="select2-' + id + '-container"]').removeClass('error');
        }
    });
});

$(document).on('change', '.select2-req', function () {
    var id = $(this).attr('id');
    if ($('.select2-selection[aria-controls="select2-' + id + '-container"]').hasClass("error")) {
        $('.select2-selection[aria-controls="select2-' + id + '-container"]').removeClass('error');
    }
});

function checkStateSelected(id) {
    var val = $('#' + id).val();
    if (!val) {
        $("[aria-controls='select2-" + id + "-container']").addClass('error');
    } else {
        $("[aria-controls='select2-" + id + "-container']").removeClass('error');
    }
}

$('#input_vendor_files').on('change', function (e) {
    $('#label_vendor_files').html("");
    var files = $(this).prop('files');
    for (var i = 0; i < files.length; i++) {
        var item = "<span class='badge badge-secondary'>" + files[i].name + "</span><br>";
        $('#container_vendor_files').append(item);
    }
});

$("#form_validate").validate();
$("#form_add_cart").validate();
$("#form_request_quote").validate();
$("#form_validate_checkout").validate();

$("#form_request_quote").submit(function (event) {
    setTimeout(function () {
        validateVariations('form_request_quote');
    }, 50);
});

$(document).on('click', '.custom-control-variation input', function () {
    var name = $(this).attr('name');
    $('.custom-control-variation label').each(function () {
        if ($(this).attr('data-input-name') == name) {
            $(this).removeClass('is-invalid');
        }
    });
});

$(document).ready(function () {
    $('.validate_terms').submit(function (e) {
        $('.custom-control-validate-input p').remove();
        if (!$('.custom-control-validate-input input').is(":checked")) {
            e.preventDefault();
            $('.custom-control-validate-input').addClass('custom-control-validate-error');
            $('.custom-control-validate-input').append("<p class='text-danger'>" + MdsConfig.textAcceptTerms + "</p>");
        } else {
            $('.custom-control-validate-input').removeClass('custom-control-validate-error');
        }
    });
});

$(document).on("input keyup paste change", ".validate_price .price-input", function () {
    var val = $(this).val();
    val = val.replace(',', '.');
    if ($.isNumeric(val) && val != 0) {
        $(this).removeClass('is-invalid');
    } else {
        $(this).addClass('is-invalid');
    }
});

$(document).ready(function () {
    $('.validate_price').submit(function (e) {
        $('.validate_price .validate-price-input').each(function () {
            var val = $(this).val();
            if (val != '') {
                val = val.replace(',', '.');
                if ($.isNumeric(val) && val != 0) {
                    $(this).removeClass('is-invalid');
                } else {
                    e.preventDefault();
                    $(this).addClass('is-invalid');
                    $(this).focus();
                }
            }
        });
    });
});

$(document).on("input keyup paste change", ".price-input", function () {
    var val = $(this).val();
    var subst = '';
    var regex = /[^\d.]|\.(?=.*\.)/g;
    val = val.replace(regex, subst);
    $(this).val(val);
});

//full screen
$(document).ready(function () {
    $("iframe").attr("allowfullscreen", "")
});

//delete quote request
function deleteQuoteRequest(id, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var data = {
                'id': id,
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Order/deleteQuoteRequest',
                data: setAjaxData(data),
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
}
function getProductShippingCost(val, productId) {
    $("#product_shipping_cost_container").empty();
    $(".product-shipping-loader").show();
    var data = {
        'state_id': val,
        'product_id': productId
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Ajax/getProductShippingCost',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                setTimeout(function () {
                    document.getElementById("product_shipping_cost_container").innerHTML = obj.response;
                    $(".product-shipping-loader").hide();
                }, 300);
            }
        }
    });
}
function deleteShippingAddress(id, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var data = {
                'id': id
            };
            $.ajax({
                type: 'POST',
                url: MdsConfig.baseURL + '/Profile/deleteShippingAddressPost',
                data: setAjaxData(data),
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
}

//delete attachment
function deleteSupportAttachment(id) {
    var data = {
        'id': id,
        'ticket_type': 'client'
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/Support/deleteSupportAttachmentPost',
        data: setAjaxData(data),
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("response_uploaded_files").innerHTML = obj.response;
            }
        }
    });
}

//close support ticket
function closeSupportTicket(id) {
    var data = {
        'id': id,
    };
    $.ajax({
        type: 'POST',
        url: MdsConfig.baseURL + '/close-ticket-post',
        data: setAjaxData(data),
        success: function (response) {
            location.reload();
        }
    });
}

$(document).ready(function () {
    $('#form_newsletter_footer').submit(function (event) {
        event.preventDefault();
        var serializedData = $(this).serializeArray();
        serializedData = setSerializedData(serializedData);
        $.ajax({
            type: 'POST',
            url: MdsConfig.baseURL + '/Ajax/addToNewsletter',
            data: serializedData,
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.result == 1) {
                    if (obj.isSuccess == 1) {
                        swal({text: obj.message, icon: "success", button: MdsConfig.textOk});
                    } else {
                        swal({text: obj.message, icon: "warning", button: MdsConfig.textOk});
                    }
                    $('#form_newsletter_footer input').val('');
                }
            }
        });
    });
});

$(document).ready(function () {
    $('#form_newsletter_modal').submit(function (event) {
        event.preventDefault();
        var serializedData = $(this).serializeArray();
        serializedData = setSerializedData(serializedData);
        $.ajax({
            type: 'POST',
            url: MdsConfig.baseURL + '/Ajax/addToNewsletter',
            data: serializedData,
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.result == 1) {
                    if (obj.isSuccess == 1) {
                        swal({text: obj.message, icon: "success", button: MdsConfig.textOk});
                    } else {
                        swal({text: obj.message, icon: "warning", button: MdsConfig.textOk});
                    }
                    $('#form_newsletter_modal input').val('');
                }
            }
        });
    });
});

$(document).on("change", ".input-show-selected", function () {
    var id = $(this).attr("data-id");
    var val = $(this).val();
    $("#" + id).html(val.replace(/.*[\/\\]/, ''));
});

if ($('.fb-comments').length > 0) {
    $(".fb-comments").attr("data-href", window.location.href);
}

if ($('.post-text-responsive').length > 0) {
    $(function () {
        $('.post-text-responsive iframe').wrap('<div class="embed-responsive embed-responsive-16by9"></div>');
        $('.post-text-responsive iframe').addClass('embed-responsive-item');
    });
}

//load product shop location map
function loadProductShopLocationMap() {
    var address = $("#span_shop_location_address").text();
    address = encodeURIComponent(address);
    var mapLang = 'en';
    if (MdsConfig.langShort) {
        mapLang = MdsConfig.langShort;
    }
    document.getElementById("iframe_shop_location_address").setAttribute("src", "https://maps.google.com/maps?width=100%&height=600&hl=" + mapLang + "&q=" + address + "&ie=UTF8&t=&z=8&iwloc=B&output=embed&disableDefaultUI=true");
}

//player modal preview
$('#productVideoModal').on('hidden.bs.modal', function (e) {
    $(this).find('video')[0].pause();
});

$('#productAudioModal').on('hidden.bs.modal', function (e) {
    Amplitude.pause();
});

//payment completed circle
$(document).ready(function () {
    $('.circle-loader').toggleClass('load-complete');
    $('.checkmark').toggle();
});

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});