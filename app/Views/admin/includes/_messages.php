<?php $session = session();
if ($session->getFlashdata('errors')):
    $errors = $session->getFlashdata('errors'); ?>
    <div class="alert alert-danger alert-dismissable alert-error-list">
		<ul class="mb-0">
		<?php foreach ($errors as $error) : ?>
			<li><?= esc($error) ?></li>
		<?php endforeach ?>
		</ul>
	</div>
<?php endif;
if ($session->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissable">
		<strong><i class="icon fa fa-times"></i></strong> <?= session()->getFlashdata('error') ?>
	</div>
<?php elseif ($session->getFlashdata('success')): ?>
	<div class="alert alert-success alert-dismissable">
		<strong><i class="icon fa fa-check"></i></strong> <?= session()->getFlashdata('success') ?>
	</div>
<?php endif; ?>