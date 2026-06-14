<?php

/**
 * Block: About Intro
 *
 * Campos consumidos:
 *   get_field('block_abt_intro_title')    → text  (heading izquierda)
 *   get_field('block_abt_intro_subtitle') → text  (label "Part of Group")
 *   get_field('block_abt_intro_image')    → image array (logo en card blanca)
 *   get_field('block_abt_intro_text')     → textarea (párrafos de cuerpo, derecha)
 */

$title    = function_exists('get_field') ? get_field('block_abt_intro_title')    : '';
$subtitle = function_exists('get_field') ? get_field('block_abt_intro_subtitle') : '';
$image    = function_exists('get_field') ? get_field('block_abt_intro_image')    : null;
$text     = function_exists('get_field') ? get_field('block_abt_intro_text')     : '';

?>

<section class="block-about-intro bg-charcoal pb-5 pb-lg-6">
	<div class="container-fluid px-4 px-lg-5">
		<div class="row g-4 g-lg-5 align-items-start">

			<!-- Columna izquierda: título + logo -->
			<div class="col-12 col-lg-6">

				<?php if ($title) : ?>
					<h2 class="h-2 h-lg-1 text-vanilla mb-4 mb-lg-5">
						<?php echo esc_html($title); ?>
					</h2>
				<?php endif; ?>

				<?php if ($subtitle) : ?>
					<p class="block-about-intro__eyebrow fs-small mb-2">
						<?php echo esc_html($text); ?>
					</p>
				<?php endif; ?>

				<?php if ($image) : ?>
					<div class="block-about-intro__logo-card">
						<img
							src="<?php echo esc_url($image['url']); ?>"
							alt="<?php echo esc_attr($image['alt']); ?>"
							width="<?php echo (int) $image['width']; ?>"
							height="<?php echo (int) $image['height']; ?>"
							loading="lazy"
							decoding="async">
					</div>
				<?php endif; ?>

			</div>

			<!-- Columna derecha: texto de cuerpo -->
			<?php if ($subtitle) : ?>
				<div class="col-12 col-lg-6">
					<div class="block-about-intro__body fs-5">
						<?php echo wpautop(esc_html($subtitle)); ?>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>