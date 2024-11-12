<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= esc($title); ?></li>
                    </ol>
                </nav>
                <div class="row m-b-15">
                    <div class="col-6">
                        <?php if ($page->title_active == 1): ?>
                            <h1 class="page-title"><?= esc($page->title); ?></h1>
                        <?php endif; ?>
                    </div>
                    <div class="col-6">
                        <div class="shops-search-container">
                            <div class="search">
                                <form action="<?= generateUrl('shops'); ?>" method="get">
                                    <input type="text" name="q" class="form-control form-input" value="<?= esc(inputGet('q')); ?>" placeholder="<?= trans("search"); ?>">
                                    <button type="submit"><i class="icon-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if (!empty($shops)): ?>
                        <?php foreach ($shops as $shop):
                            $shopName = $shop->username;
                            if (empty($shopName)) {
                                $shopName = $shop->first_name . ' ' . $shop->last_name;
                            }
                            $showShop = true;
                            if ($shop->role_id == 1 && $shop->num_products <= 0):
                                $showShop = false;
                            endif;
                            if ($showShop):?>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                    <div class="member-list-item">
                                        <div class="left">
                                            <a href="<?= generateProfileUrl($shop->slug); ?>">
                                                <img src="<?= getUserAvatar($shop); ?>" alt="<?= esc($shopName); ?>" class="img-fluid img-profile lazyload">
                                            </a>
                                        </div>
                                        <div class="right">
                                            <a href="<?= generateProfileUrl($shop->slug); ?>">
                                                <p class="username"><?= esc($shopName); ?></p>
                                            </a>
                                            <p class="text-muted m-b-10"><?= trans("products") . ': ' . $shop->num_products; ?></p>
                                            <?php if (authCheck()): ?>
                                                <?php if ($shop->id != user()->id): ?>
                                                    <form action="<?= base_url('follow-unfollow-user-post'); ?>" method="post" class="form-inline">
                                                        <?= csrf_field(); ?>
                                                        <input type="hidden" name="user_id" value="<?= $shop->id; ?>">
                                                        <?php if (isUserFollows($shop->id, user()->id)): ?>
                                                            <p>
                                                                <button class="btn btn-md btn-outline-gray"><i class="icon-user-minus"></i><?= trans("unfollow"); ?></button>
                                                            </p>
                                                        <?php else: ?>
                                                            <p>
                                                                <button class="btn btn-md btn-outline-gray"><i class="icon-user-plus"></i><?= trans("follow"); ?></button>
                                                            </p>
                                                        <?php endif; ?>
                                                    </form>
                                                <?php endif;
                                            else: ?>
                                                <p>
                                                    <button class="btn btn-md btn-outline" data-toggle="modal" data-target="#loginModal"><i class="icon-user-plus"></i><?= trans("follow"); ?></button>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;
                        endforeach;
                    else: ?>
                        <div class="col-12">
                            <p class="no-records-found">
                                <?= trans("no_records_found"); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="float-right">
                            <?= $pager->links; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>