<!-- JAVASCRIPT -->
	<script>document.querySelectorAll('form[method="post"]').forEach(form => form.appendChild(Object.assign(document.createElement('input'), {type: 'hidden', name: 'back_url', value: '<?= getCurrentUrl(false); ?>'})));</script>
	<script src="/assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="/assets/admin/libs/simplebar/simplebar.min.js"></script>
	<script src="/assets/admin/libs/node-waves/waves.min.js"></script>
	<script src="/assets/admin/libs/feather-icons/feather.min.js"></script>
	<script src="/assets/admin/js/pages/plugins/lord-icon-2.1.0.js"></script>
	<script src="/assets/admin/js/plugins.js"></script>