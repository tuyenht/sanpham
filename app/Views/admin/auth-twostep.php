<?= $this->include('admin/includes/head-main') ?>

<head>
	<?php echo view('admin/includes/head-meta', array('title'=>'Two Step Verification')); ?>
	
	<?= $this->include('admin/includes/head-css'); ?>

</head>
<body>
	<div class="auth-page-wrapper pt-5">
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-2 mb-4 text-white-50">
                            <div>
                                <a href="https://baoson.net/" target="_blank" class="d-inline-block auth-logo">
                                    <img src="data:image/png;base64,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" alt="" height="68px" width="186px">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 card-bg-fill">

                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <div class="avatar-lg mx-auto">
                                        <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                            <i class="ri-mail-line"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2 mt-4">
                                    <div class="text-muted text-center mb-4 mx-lg-3">
                                        <h4>Verify Your Email</h4>
                                        <p>Please enter the 4 digit code sent to <span class="fw-semibold">example@abc.com</span></p>
                                    </div>

                                    <form autocomplete="off">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit1-input" class="visually-hidden">Digit 1</label>
                                                    <input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(1, event)" maxLength="1" name="digit1-input" id="digit1-input">
                                                </div>
                                            </div>
                                    
                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit2-input" class="visually-hidden">Digit 2</label>
                                                    <input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(2, event)" maxLength="1" name="digit2-input" id="digit2-input">
                                                </div>
                                            </div>
                                    
                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit3-input" class="visually-hidden">Digit 3</label>
                                                    <input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(3, event)" maxLength="1" name="digit3-input" id="digit3-input">
                                                </div>
                                            </div>
                                    
                                            <div class="col-3">
                                                <div class="mb-3">
                                                    <label for="digit4-input" class="visually-hidden">Digit 4</label>
                                                    <input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(4, event)" maxLength="1" name="digit4-input" id="digit4-input">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="mt-3">
                                        <button type="button" class="btn btn-success w-100">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="mb-0">Didn't receive a code ? <a href="auth-pass-reset-basic" class="fw-semibold text-primary text-decoration-underline">Resend</a> </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                Copyright <script>document.write(new Date().getFullYear())</script> © <a href="https://baoson.net" title="Thiết kế website" target="_blank">BaoSon Ads</a>. All rights reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
	<?= $this->include('admin/includes/footer-script'); ?>
	
	<!-- particles js -->
    <script src="/assets/admin/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="/assets/admin/js/pages/particles.app.js"></script>
    <!-- two-step-verification js -->
    <script src="/assets/admin/js/pages/two-step-verification.init.js"></script>
</body>
</html>