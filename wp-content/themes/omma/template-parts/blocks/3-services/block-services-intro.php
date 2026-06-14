<?php
$title = get_field('block_srv_intro_title');
$desc  = get_field('block_srv_intro_desc');
?>

<section class="block-services-intro bg-charcoal pb-5 pb-lg-6">
	<div class="container">

		<?php if ($title) : ?>
			<div class="row">
				<div class="col-12">
					<h2 class="h-1 text-vanilla block-services-intro__title"><?php echo esc_html($title); ?></h2>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($desc) : ?>
			<div class="row mt-3 mt-lg-4">
				<div class="col-12 col-lg-6">
					<div class="block-services-intro__desc fs-5 text-vanilla">
						<?php echo nl2br(esc_html($desc)); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>