<?php

/**
 * Block: About Intro
 *
 * Campos consumidos:
 *   get_field('block_abt_intro_title')    → text     (heading principal)
 *   get_field('block_abt_intro_subtitle') → wysiwyg  (cuerpo inferior izquierda)
 *   get_field('block_abt_intro_text')     → textarea (etiqueta sobre el logo, derecha)
 *   get_field('block_abt_intro_image')    → image array (logo card inferior derecha)
 */

$title    = function_exists('get_field') ? get_field('block_abt_intro_title')    : '';
$subtitle = function_exists('get_field') ? get_field('block_abt_intro_subtitle') : '';
$image    = function_exists('get_field') ? get_field('block_abt_intro_image')    : null;
$text     = function_exists('get_field') ? get_field('block_abt_intro_text')     : '';

?>

<section class="block-about-intro bg-charcoal pb-5 pb-lg-6">
	<div class="container-fluid px-4 px-lg-5">

		<?php if ($title) : ?>
			<div class="row">
				<div class="col-12 col-lg-11">
					<h2 class="h-4 h-lg-2 h-xl-1 text-vanilla mb-4 mb-lg-5 text-uppercase">
						<?php echo esc_html($title); ?>
					</h2>
				</div>
			</div>
		<?php endif; ?>

		<div class="row g-4 g-lg-5 align-items-end py-3">

			<?php if ($subtitle) : ?>
				<div class="col-12 col-lg-8 col-xl-6">
					<div class="block-about-intro__body fs-6 fs-xl-5 text-justify">
						<?php echo wp_kses_post($subtitle); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ($text || $image) : ?>
				<div class="col-12 col-lg-3 col-xl-4 offset-lg-1 block-about-intro__aside">

					<?php if ($text) : ?>
						<p class="block-about-intro__eyebrow fs-small mb-3">
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
			<?php endif; ?>

		</div>

	</div>
</section>