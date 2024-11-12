<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?= adminUrl(); ?>" class="logo logo-dark">
            <span class="logo-sm">
                <img src="/assets/images/logo-sm.png" alt="" height="40">
            </span>
            <span class="logo-lg">
                <img src="/assets/images/logo-dark.png" alt="" height="48">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="<?= adminUrl(); ?>" class="logo logo-light">
            <span class="logo-sm">
                <img src="/assets/images/logo-sm.png" alt="" height="40">
            </span>
            <span class="logo-lg">
                <img src="/assets/images/logo-light.png" alt="" height="48">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link<?php isAdminNavActive(['']); ?>" href="<?= adminUrl(); ?>">
						<i class="ri-dashboard-2-line"></i> <span data-key="t-home"><?= trans("dashboard"); ?></span>
					</a>
                </li><?php  
				
				if (hasPermission('orders')): ?> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarOrders" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarOrders">
                        <i class="bx bxs-basket"></i> <span data-key="t-orders"><?= trans("orders"); ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarOrders">
                        <ul class="nav nav-sm flex-column">
							<li class="nav-item">
                                <a href="<?= adminUrl('orders'); ?>" class="nav-link<?php isAdminNavActive(['orders', 'order-details']); ?>"><?= trans("orders"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('transactions'); ?>" class="nav-link<?php isAdminNavActive(['transactions']); ?>"><?= trans("transactions"); ?></a>
                            </li>
						</ul>
                    </div>
                </li><?php endif;
				
                if (hasPermission('comments_contact')): ?> 
				<li class="nav-item">
                    <a class="nav-link menu-link<?php isAdminNavActive(['contact-messages']); ?>" href="<?= adminUrl('contact-messages'); ?>"><i class="ri-message-3-fill"></i> <span data-key="t-contact_messages"><?= trans("contact_messages"); ?></span></a>
                </li><?php endif;
				
				if (hasPermission('comments')): ?> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarComments" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarComments">
                        <i class="ri-article-line"></i> <span data-key="t-comments"><?= trans("comments"); ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarComments">
                        <ul class="nav nav-sm flex-column"><?php if ($generalSettings->comment_approval_system == 1): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('pending-product-comments'); ?>" class="nav-link<?php isAdminNavActive(['pending-product-comments', 'product-comments']); ?>"><?= trans("product_comments"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('pending-blog-comments'); ?>" class="nav-link<?php isAdminNavActive(['pending-blog-comments', 'blog-comments']); ?>"><?= trans("blog_comments"); ?></a>
                            </li>
							<?php else: ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('product-comments'); ?>" class="nav-link<?php isAdminNavActive(['pending-product-comments', 'product-comments']); ?>"><?= trans("product_comments"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('blog-comments'); ?>" class="nav-link<?php isAdminNavActive(['pending-blog-comments', 'blog-comments']); ?>"><?= trans("blog_comments"); ?></a>
                            </li>
							<?php endif; ?>
						</ul>
                    </div>
                </li><?php endif;
				
				
				if (hasPermission('products')): ?> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarProducts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProducts">
                        <i class="ri-article-line"></i> <span data-key="t-posts"><?= trans("products"); ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarProducts">
                        <ul class="nav nav-sm flex-column"><?php if (hasPermission('add_product')): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('add-post'); ?>" class="nav-link<?php isAdminNavActive(['add-post']); ?>"><?= trans("add_product"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('bulk_product_upload'); ?>" class="nav-link<?php isAdminNavActive(['bulk-product-upload']); ?>"><?= trans("bulk_product_upload"); ?></a>
                            </li><?php endif; ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('products'); ?>" class="nav-link<?php isAdminNavActive(['products', 'edit-product']); ?>"><?= trans("all_products"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('categories'); ?>" class="nav-link<?php isAdminNavActive(['categories', 'add-category', 'edit-category', 'bulk-category-upload']); ?>"><?= trans("collections"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('brands'); ?>" class="nav-link<?php isAdminNavActive(['brands', 'add-brand', 'edit-brand']); ?>"><?= trans("brands"); ?></a>
                            </li>
						</ul>
                    </div>
                </li><?php endif;
				
				
				if (hasPermission('blog')): ?> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarPosts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPosts">
                        <i class="ri-article-line"></i> <span data-key="t-posts"><?= trans("posts"); ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarPosts">
                        <ul class="nav nav-sm flex-column"><?php if (hasPermission('add_post')): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('add-post'); ?>" class="nav-link<?php isAdminNavActive(['add-post']); ?>"><?= trans("add_post"); ?></a>
                            </li><?php endif; ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('posts'); ?>" class="nav-link<?php isAdminNavActive(['posts', 'edit-post']); ?>"><?= trans("all_posts"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('topics'); ?>" class="nav-link<?php isAdminNavActive(['topics', 'add-topic', 'edit-topic']); ?>"><?= trans("topics"); ?></a>
                            </li>
						</ul>
                    </div>
                </li><?php endif;
				
				if (hasPermission('pages')): ?> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPages">
                        <i class="ri-pages-fill"></i> <span data-key="t-pages"><?= trans("pages"); ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarPages">
                        <ul class="nav nav-sm flex-column">
							<li class="nav-item">
                                <a href="<?= adminUrl('add-page'); ?>" class="nav-link<?php isAdminNavActive(['add-page']); ?>"><?= trans("add_page"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('pages'); ?>" class="nav-link<?php isAdminNavActive(['pages', 'edit-page']); ?>"><?= trans("all_pages"); ?></a>
                            </li>				
						</ul>
                    </div>
                </li><?php endif;
				
				if (hasPermission('navigation') || isAdmin()): ?> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarThemes" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarThemes">
                        <i class="ri-apps-fill"></i> <span data-key="t-contact_messages"><?= trans("theme_settings"); ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarThemes">
                        <ul class="nav nav-sm flex-column"> <?php if (hasPermission('homepage_manager')): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('homepage-manager'); ?>" class="nav-link<?php isAdminNavActive(['homepage-manager']); ?>"><?= trans("homepage_manager"); ?></a>
                            </li><?php endif; if (hasPermission('slider')): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('slider'); ?>" class="nav-link<?php isAdminNavActive(['slider']); ?>"><?= trans("slider"); ?></a>
                            </li><?php endif; if (hasPermission('navigation')): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('navigation?lang=' . $activeLang->id); ?>" class="nav-link<?php isAdminNavActive(['navigation']); ?>"><?= trans("navigation"); ?></a>
                            </li><?php endif; if (isAdmin()): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('themes'); ?>" class="nav-link<?php isAdminNavActive(['themes']); ?>"><?= trans("themes"); ?></a>
                            </li><?php endif; ?> 
						</ul>
                    </div>
                </li><?php endif;
				
				if (hasPermission('preferences') || hasPermission('general_settings') || hasPermission('product_settings') || hasPermission('payment_settings') || hasPermission('seo_tools') || isAdmin()): ?> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSettings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSettings">
                        <i class="ri-settings-5-fill"></i> <span data-key="t-settings"><?= trans("settings"); ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column"><?php if (hasPermission('general_settings')): ?>	
							<li class="nav-item">
                                <a href="<?= adminUrl('general-settings'); ?>" class="nav-link<?php isAdminNavActive(['general-settings']); ?>"><?= trans("general_settings"); ?></a>
                            </li><?php endif;
							if (hasPermission('product_settings')): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('product-settings'); ?>" class="nav-link<?php isAdminNavActive(['product-settings']); ?>"><?= trans("product_settings"); ?></a>
                            </li><?php endif;
							if (hasPermission('payment_settings')): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('payment-settings'); ?>" class="nav-link<?php isAdminNavActive(['payment-settings']); ?>"><?= trans("payment_settings"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('currency-settings'); ?>" class="nav-link<?php isAdminNavActive(['currency-settings']); ?>"><?= trans("currency_settings"); ?></a>
                            </li><?php endif;
							if (hasPermission('seo_tools')): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('seo-tools'); ?>" class="nav-link<?php isAdminNavActive(['seo-tools']); ?>"><?= trans("seo_tools"); ?></a>
                            </li><?php endif; ?>
							<?php if (hasPermission('preferences')): ?> 							
							<li class="nav-item">
                                <a href="<?= adminUrl('preferences'); ?>" class="nav-link<?php isAdminNavActive(['preferences']); ?>"><?= trans("preferences"); ?></a>
                            </li><?php endif; ?>
							<?php if (hasPermission('general_settings')): ?>
							<li class="nav-item">
                                <a href="<?= adminUrl('email-settings'); ?>" class="nav-link<?php isAdminNavActive(['email-settings']); ?>"><?= trans("email_settings"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('font-settings'); ?>" class="nav-link<?php isAdminNavActive(['font-settings']); ?>"><?= trans("font_settings"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('visual-settings'); ?>" class="nav-link<?php isAdminNavActive(['visual-settings']); ?>"><?= trans("visual_settings"); ?></a>
                            </li>
							<?php endif; ?>
							<?php if (isAdmin()): ?> 
							<li class="nav-item">
                                <a href="<?= adminUrl('route-settings'); ?>" class="nav-link<?php isAdminNavActive(['route-settings']); ?>"><?= trans("route_settings"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('storage'); ?>" class="nav-link<?php isAdminNavActive(['storage']); ?>"><?= trans("storage"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('language-settings'); ?>" class="nav-link<?php isAdminNavActive(['language-settings', 'update-language', 'edit-translations']); ?>"><?= trans("language_settings"); ?></a>
                            </li>
							<li class="nav-item">
                                <a href="<?= adminUrl('cache-system'); ?>" class="nav-link<?php isAdminNavActive(['cache-system']); ?>"><?= trans("cache_system"); ?></a>
                            </li><?php endif; ?> 
						</ul>
                    </div>
                </li><?php endif; 
				
				if (isSuperAdmin()): ?> 
				<li class="nav-item">
					<form id="downloadData" action="<?= base_url('Admin/downloadDatabaseBackup'); ?>" method="post">
					<?= csrf_field(); ?>
                    <a class="nav-link menu-link" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('downloadData').submit();">
                        <i class="bx bxs-cloud-download"></i> <span data-key="t-download"><?= trans("download_database_backup"); ?></span>
                    </a>
					</form>
                </li>
				<?php endif; ?>
				






				

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="ri-layout-3-line"></i> <span data-key="t-layouts">Layouts</span> <span class="badge badge-pill bg-danger" data-key="t-hot">Hot</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarLayouts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="layouts-horizontal" target="_blank" class="nav-link" data-key="t-horizontal">Horizontal</a>
                            </li>
                            <li class="nav-item">
                                <a href="layouts-detached" target="_blank" class="nav-link" data-key="t-detached">Detached</a>
                            </li>
                            <li class="nav-item">
                                <a href="layouts-two-column" target="_blank" class="nav-link" data-key="t-two-column">Two Column</a>
                            </li>
                            <li class="nav-item">
                                <a href="layouts-vertical-hovered" target="_blank" class="nav-link" data-key="t-hovered">Hovered</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->

                <!--<li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span></li>-->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                        <i class="ri-account-circle-line"></i> <span data-key="t-authentication">Authentication</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAuth">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#sidebarSignIn" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignIn" data-key="t-signin"> Sign In
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarSignIn">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="auth-signin-basic" class="nav-link" data-key="t-basic"> Basic
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-signin-cover" class="nav-link" data-key="t-cover"> Cover
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarSignUp" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignUp" data-key="t-signup"> Sign Up
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarSignUp">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="auth-signup-basic" class="nav-link" data-key="t-basic"> Basic
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-signup-cover" class="nav-link" data-key="t-cover"> Cover
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a href="#sidebarResetPass" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarResetPass" data-key="t-password-reset">
                                    Password Reset
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarResetPass">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="auth-pass-reset-basic" class="nav-link" data-key="t-basic">
                                                Basic </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-pass-reset-cover" class="nav-link" data-key="t-cover">
                                                Cover </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a href="#sidebarchangePass" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarchangePass" data-key="t-password-create">
                                    Password Create
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarchangePass">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="auth-pass-change-basic" class="nav-link" data-key="t-basic">
                                                Basic </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-pass-change-cover" class="nav-link" data-key="t-cover">
                                                Cover </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a href="#sidebarLockScreen" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLockScreen" data-key="t-lock-screen">
                                    Lock Screen
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarLockScreen">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="auth-lockscreen-basic" class="nav-link" data-key="t-basic">
                                                Basic </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-lockscreen-cover" class="nav-link" data-key="t-cover">
                                                Cover </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a href="#sidebarLogout" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLogout" data-key="t-logout"> Logout
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarLogout">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="auth-logout-basic" class="nav-link" data-key="t-basic"> Basic
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-logout-cover" class="nav-link" data-key="t-cover"> Cover
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarSuccessMsg" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSuccessMsg" data-key="t-success-message"> Success Message
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarSuccessMsg">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="auth-success-msg-basic" class="nav-link" data-key="t-basic">
                                                Basic </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-success-msg-cover" class="nav-link" data-key="t-cover">
                                                Cover </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarTwoStep" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTwoStep" data-key="t-two-step-verification"> Two Step Verification
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarTwoStep">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="auth-twostep-basic" class="nav-link" data-key="t-basic"> Basic
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-twostep-cover" class="nav-link" data-key="t-cover"> Cover
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarErrors" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarErrors" data-key="t-errors"> Errors
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarErrors">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="auth-404-basic" class="nav-link" data-key="t-404-basic"> 404
                                                Basic </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-404-cover" class="nav-link" data-key="t-404-cover"> 404
                                                Cover </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-404-alt" class="nav-link" data-key="t-404-alt"> 404 Alt
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-500" class="nav-link" data-key="t-500"> 500 </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="auth-offline" class="nav-link" data-key="t-offline-page"> Offline Page </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarLanding" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLanding">
                        <i class="ri-rocket-line"></i> <span data-key="t-landing">Landing</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarLanding">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="landing" class="nav-link" data-key="t-one-page"> One Page </a>
                            </li>
                            <li class="nav-item">
                                <a href="nft-landing" class="nav-link" data-key="t-nft-landing"> NFT Landing </a>
                            </li>
                            <li class="nav-item">
                                <a href="job-landing" class="nav-link" data-key="t-job">Job</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!--<li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">Components</span></li>-->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarUI">
                        <i class="ri-pencil-ruler-2-line"></i> <span data-key="t-base-ui">Base UI</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarUI">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-alerts" class="nav-link" data-key="t-alerts">Alerts</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-badges" class="nav-link" data-key="t-badges">Badges</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-buttons" class="nav-link" data-key="t-buttons">Buttons</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-colors" class="nav-link" data-key="t-colors">Colors</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-cards" class="nav-link" data-key="t-cards">Cards</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-carousel" class="nav-link" data-key="t-carousel">Carousel</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-dropdowns" class="nav-link" data-key="t-dropdowns">Dropdowns</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-grid" class="nav-link" data-key="t-grid">Grid</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-images" class="nav-link" data-key="t-images">Images</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-tabs" class="nav-link" data-key="t-tabs">Tabs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-accordions" class="nav-link" data-key="t-accordion-collapse">Accordion & Collapse</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-modals" class="nav-link" data-key="t-modals">Modals</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-offcanvas" class="nav-link" data-key="t-offcanvas">Offcanvas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-placeholders" class="nav-link" data-key="t-placeholders">Placeholders</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-progress" class="nav-link" data-key="t-progress">Progress</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-notifications" class="nav-link" data-key="t-notifications">Notifications</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-media" class="nav-link" data-key="t-media-object">Media
                                            object</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-embed-video" class="nav-link" data-key="t-embed-video">Embed
                                            Video</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-typography" class="nav-link" data-key="t-typography">Typography</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-lists" class="nav-link" data-key="t-lists">Lists</a>
                                    </li>
                                    <li class="nav-item">
										<a href="ui-links" class="nav-link"><span data-key="t-links">Links</span> <span class="badge badge-pill bg-success" data-key="t-new">New</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-general" class="nav-link" data-key="t-general">General</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-ribbons" class="nav-link" data-key="t-ribbons">Ribbons</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-utilities" class="nav-link" data-key="t-utilities">Utilities</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAdvanceUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                        <i class="ri-stack-line"></i> <span data-key="t-advance-ui">Advance UI</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAdvanceUI">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="advance-ui-sweetalerts" class="nav-link" data-key="t-sweet-alerts">Sweet
                                    Alerts</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-nestable" class="nav-link" data-key="t-nestable-list">Nestable
                                    List</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-scrollbar" class="nav-link" data-key="t-scrollbar">Scrollbar</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-animation" class="nav-link" data-key="t-animation">Animation</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-tour" class="nav-link" data-key="t-tour">Tour</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-swiper" class="nav-link" data-key="t-swiper-slider">Swiper
                                    Slider</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-ratings" class="nav-link" data-key="t-ratings">Ratings</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-highlight" class="nav-link" data-key="t-highlight">Highlight</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-scrollspy" class="nav-link" data-key="t-scrollSpy">ScrollSpy</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="widgets">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Widgets</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarForms" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarForms">
                        <i class="ri-file-list-3-line"></i> <span data-key="t-forms">Forms</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarForms">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="forms-elements" class="nav-link" data-key="t-basic-elements">Basic
                                    Elements</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-select" class="nav-link" data-key="t-form-select"> Form Select </a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-checkboxs-radios" class="nav-link" data-key="t-checkboxs-radios">Checkboxs & Radios</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-pickers" class="nav-link" data-key="t-pickers"> Pickers </a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-masks" class="nav-link" data-key="t-input-masks">Input Masks</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-advanced" class="nav-link" data-key="t-advanced">Advanced</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-range-sliders" class="nav-link" data-key="t-range-slider"> Range
                                    Slider </a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-validation" class="nav-link" data-key="t-validation">Validation</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-wizard" class="nav-link" data-key="t-wizard">Wizard</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-editors" class="nav-link" data-key="t-editors">Editors</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-file-uploads" class="nav-link" data-key="t-file-uploads">File
                                    Uploads</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-layouts" class="nav-link" data-key="t-form-layouts">Form Layouts</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-select2" class="nav-link" data-key="t-select2">Select2</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarTables" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTables">
                        <i class="ri-layout-grid-line"></i> <span data-key="t-tables">Tables</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarTables">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="tables-basic" class="nav-link" data-key="t-basic-tables">Basic Tables</a>
                            </li>
                            <li class="nav-item">
                                <a href="tables-gridjs" class="nav-link" data-key="t-grid-js">Grid Js</a>
                            </li>
                            <li class="nav-item">
                                <a href="tables-listjs" class="nav-link" data-key="t-list-js">List Js</a>
                            </li>
                            <li class="nav-item">
                                <a href="tables-datatables" class="nav-link" data-key="t-datatables">Datatables</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCharts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCharts">
                        <i class="ri-pie-chart-line"></i> <span data-key="t-charts">Charts</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCharts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#sidebarApexcharts" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarApexcharts" data-key="t-apexcharts">
                                    Apexcharts
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarApexcharts">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="charts-apex-line" class="nav-link" data-key="t-line"> Line
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-area" class="nav-link" data-key="t-area"> Area
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-column" class="nav-link" data-key="t-column">
                                                Column </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-bar" class="nav-link" data-key="t-bar"> Bar </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-mixed" class="nav-link" data-key="t-mixed"> Mixed
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-timeline" class="nav-link" data-key="t-timeline">
                                                Timeline </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-range-area" class="nav-link"><span data-key="t-range-area">Range Area</span> <span class="badge badge-pill bg-success" data-key="t-new">New</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-funnel" class="nav-link"><span data-key="t-funnel">Funnel</span> <span class="badge badge-pill bg-success" data-key="t-new">New</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-candlestick" class="nav-link" data-key="t-candlstick"> Candlstick </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-boxplot" class="nav-link" data-key="t-boxplot">
                                                Boxplot </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-bubble" class="nav-link" data-key="t-bubble">
                                                Bubble </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-scatter" class="nav-link" data-key="t-scatter">
                                                Scatter </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-heatmap" class="nav-link" data-key="t-heatmap">
                                                Heatmap </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-treemap" class="nav-link" data-key="t-treemap">
                                                Treemap </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-pie" class="nav-link" data-key="t-pie"> Pie </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-radialbar" class="nav-link" data-key="t-radialbar"> Radialbar </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-radar" class="nav-link" data-key="t-radar"> Radar
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="charts-apex-polar" class="nav-link" data-key="t-polar-area">
                                                Polar Area </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="charts-chartjs" class="nav-link" data-key="t-chartjs"> Chartjs </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-echarts" class="nav-link" data-key="t-echarts"> Echarts </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarIcons" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarIcons">
                        <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Icons</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarIcons">
                    <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="icons-remix" class="nav-link"><span data-key="t-remix">Remix</span> <span class="badge badge-pill bg-info">v3.5</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-boxicons" class="nav-link"><span data-key="t-boxicons">Boxicons</span> <span class="badge badge-pill bg-info">v2.1.4</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-materialdesign" class="nav-link"><span data-key="t-material-design">Material Design</span> <span class="badge badge-pill bg-info">v7.2.96</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-lineawesome" class="nav-link" data-key="t-line-awesome">Line Awesome</a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-feather" class="nav-link"><span data-key="t-feather">Feather</span> <span class="badge badge-pill bg-info">v4.29</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-crypto" class="nav-link"> <span data-key="t-crypto-svg">Crypto SVG</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMaps" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMaps">
                        <i class="ri-map-pin-line"></i> <span data-key="t-maps">Maps</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMaps">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="maps-google" class="nav-link" data-key="t-google">
                                    Google
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="maps-vector" class="nav-link" data-key="t-vector">
                                    Vector
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="maps-leaflet" class="nav-link" data-key="t-leaflet">
                                    Leaflet
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMultilevel">
                        <i class="ri-share-line"></i> <span data-key="t-multi-level">Multi Level</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMultilevel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-key="t-level-1.1"> Level 1.1 </a>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarAccount" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAccount" data-key="t-level-1.2"> Level
                                    1.2
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAccount">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link" data-key="t-level-2.1"> Level 2.1 </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#sidebarCrm" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCrm" data-key="t-level-2.2"> Level 2.2
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarCrm">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="<?= adminUrl('reward-system'); ?>" class="nav-link<?php isAdminNavActive(['reward-system']); ?>" data-key="t-level-3.1"> <?= trans("reward_system"); ?>
                                                        </a>
														
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#" class="nav-link" data-key="t-level-3.2"> Level 3.2
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>