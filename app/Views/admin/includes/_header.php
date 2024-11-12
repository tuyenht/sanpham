<?= $this->include('admin/includes/head-main') ?>
<head>
    <?= $this->include('admin/includes/head-meta'); ?>
	<!-- jsvectormap css -->
    <link href="/assets/admin/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <!--Swiper slider css-->
    <link href="/assets/admin/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />
	<?= $this->include('admin/includes/head-css'); ?>
</head>
<body>
	<!-- Begin page -->
    <div id="layout-wrapper">
		<?= $this->include('admin/includes/topbar'); ?>
		<?= $this->include('admin/includes/sidebar'); ?>
		<!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">