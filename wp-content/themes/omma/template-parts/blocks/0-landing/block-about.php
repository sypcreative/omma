<?php

/**
 * Block: Block About
 * Template: template-parts/blocks/0-landing/block-about.php
 */

$image = get_field('block_about_image');
$items = get_field('block_about_items');

if (! $items) {
	return;
}

$total = count($items);
?>

<section class="block-about" data-about-section="" data-progress-nav-anchor="">

	<!-- ── Image (left, fixed while panels scroll) ─────────────────────────── -->
	<?php if ($image) : ?>
		<div class="block-about__image-side">
			<?php echo wp_get_attachment_image(
				$image['ID'],
				'large',
				false,
				[
					'class'    => 'block-about__image',
					'loading'  => 'eager',
					'decoding' => 'async',
					'sizes'    => '(min-width: 992px) 42vw, 100vw',
				]
			); ?>
		</div>
	<?php endif; ?>

	<!-- ── Panels (right, scroll horizontally) ────────────────────────────── -->
	<div class="block-about__panels-container">

		<!-- Progress counter -->
		<div class="block-about__counter" aria-hidden="true">
			<span data-about-progress-current="">01</span>
			<span class="block-about__counter-sep"></span>
			<span><?php echo str_pad($total, 2, '0', STR_PAD_LEFT); ?></span>
		</div>

		<!-- Track -->
		<div class="block-about__track" data-about-track="">

			<?php foreach ($items as $i => $item) :
				$label = $item['block_about_item_label'] ?? '';
				$text  = $item['block_about_item_text']  ?? '';
				$num   = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
			?>
				<div class="block-about__panel" data-about-panel="">
					<div class="block-about__panel-inner">

						<span class="block-about__index" aria-hidden="true"><?php echo $num; ?></span>

						<div class="block-about__panel-content" data-about-panel-content="">

							<?php if ($label) : ?>
								<p class="block-about__panel-label h-6 text-vanilla text-uppercase mb-4">
									<?php echo esc_html($label); ?>
								</p>
							<?php endif; ?>

							<?php if ($text) : ?>
								<p class="block-about__panel-text fs-6 fs-md-5 text-vanilla mb-0">
									<?php echo nl2br(esc_html($text)); ?>
								</p>
							<?php endif; ?>
						</div>

					</div>
				</div>
			<?php endforeach; ?>

		</div>
	</div>

</section>