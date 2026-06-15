<?php

/**
 * Block: About CEO
 *
 * Campos consumidos:
 *   get_field('block_abt_ceo_title')       → text    (nombre — grande izquierda)
 *   get_field('block_abt_ceo_subtitle')    → text    (cargo — pequeño bajo el nombre)
 *   get_field('block_abt_ceo_description') → textarea (párrafos de cuerpo)
 *   get_field('block_abt_ceo_image')       → image array (retrato derecha, sangra al fondo)
 */

$title       = function_exists('get_field') ? get_field('block_abt_ceo_title')       : '';
$subtitle    = function_exists('get_field') ? get_field('block_abt_ceo_subtitle')    : '';
$description = function_exists('get_field') ? get_field('block_abt_ceo_description') : '';
$image       = function_exists('get_field') ? get_field('block_abt_ceo_image')       : null;

?>

<section class="block-about-ceo bg-charcoal pt-5 pt-lg-6 pb-5 pb-lg-0">
	<div class="container-fluid px-4 px-lg-5 pt-0 py-md-5">
		<div class="row g-4 g-lg-5">

			<!-- Columna izquierda: nombre + cargo + descripción -->
			<div class="col-12 col-lg-6 pb-lg-6 block-about-ceo__text-col">

				<?php if ($title) : ?>
					<h2 class="h-2 h-lg-3 text-vanilla mb-2">
						<?php echo esc_html($title); ?>
					</h2>
				<?php endif; ?>

				<?php if ($subtitle) : ?>
					<p class="block-about-ceo__eyebrow fs-small mb-4 mb-lg-5">
						<?php echo esc_html($subtitle); ?>
					</p>
				<?php endif; ?>

				<?php if ($description) : ?>
					<div class="block-about-ceo__body fs-6">
						<?php echo wpautop(esc_html($description)); ?>
					</div>
				<?php endif; ?>

			</div>

			<!-- Columna derecha: retrato -->
			<?php if ($image) : ?>
				<div class="col-12 col-lg-5 offset-lg-1">
					<figure class="block-about-ceo__figure m-0 rounded-2">
						<img
							src="<?php echo esc_url($image['url']); ?>"
							alt="<?php echo esc_attr($image['alt']); ?>"
							width="<?php echo (int) $image['width']; ?>"
							height="<?php echo (int) $image['height']; ?>"
							loading="lazy"
							decoding="async">
					</figure>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>