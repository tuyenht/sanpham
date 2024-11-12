				</div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
			<footer class="footer">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">
							Copyright <script>document.write(new Date().getFullYear())</script> © <a href="https://baoson.net" target="_blank">BaoSon Ads</a>. All rights reserved.
						</div>
						<div class="col-sm-6">
							<div class="text-sm-end d-none d-sm-block"><?php echo date('l, d F Y, H:i');?></div>
						</div>
					</div>
				</div>
			</footer>
        </div>
        <!-- end main content-->
	
	</div>
    <!-- END layout-wrapper -->
	<?= $this->include('admin/includes/customizer'); ?>
	
	<?= $this->include('admin/includes/footer-script'); ?>
	
    <!-- apexcharts -->
    <script src="/assets/admin/libs/apexcharts/apexcharts.min.js"></script>
    <!-- Vector map-->
    <script src="/assets/admin/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="/assets/admin/libs/jsvectormap/maps/world-merc.js"></script>
    <!--Swiper slider js-->
    <script src="/assets/admin/libs/swiper/swiper-bundle.min.js"></script>
    <!-- Dashboard init -->
    <script src="/assets/admin/js/pages/dashboard-ecommerce.init.js"></script>
    <!-- App js -->
    <script src="/assets/admin/js/app.js"></script>
</body>
</html>