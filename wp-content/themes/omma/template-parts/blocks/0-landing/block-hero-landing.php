<?php

/**
 * Block: Hero Landing
 * Template: template-parts/blocks/0-landing/block-hero-landing.php
 */

$image   = get_field('hero_landing_image');
$title   = get_field('hero_landing_title');
$buttons = get_field('hero_landing_buttons');
?>

<section class="hero-landing position-relative overflow-hidden w-100 min-vh-100 d-flex align-items-center align-items-lg-end">

	<?php if ($image) : ?>
		<div class="hero-landing__bg position-absolute top-0 start-0 w-100"
			data-parallax="trigger"
			data-parallax-start="0"
			data-parallax-end="40"
			data-parallax-scroll-start="top top"
			data-parallax-disable="tablet">
			<?php echo wp_get_attachment_image(
				$image['ID'],
				'full',
				false,
				[
					'class'         => 'w-100 h-100 object-fit-cover',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'decoding'      => 'async',
					'sizes'         => '100vw',
					'alt'           => esc_attr(get_post_meta($image['ID'], '_wp_attachment_image_alt', true) ?: ($title ?: get_bloginfo('name'))),
				]
			); ?>
		</div>
	<?php endif; ?>

	<div class="container position-relative z-1 py-5">
		<div class="row justify-content-center justify-content-lg-start">
			<div class="col-12 col-lg-10 text-center text-lg-start mb-0 mb-lg-5 pb-0 pb-lg-5">

				<?php if ($title) : ?>
					<h1 class="hero-landing__title text-blue-800 mb-5 mb-lg-4 h-3 h-md-1 r-lg-0"
						data-anim="lines"
						data-anim-start="top bottom">
						<?php echo esc_html($title); ?>
					</h1>
				<?php endif; ?>

				<?php if ($buttons) : ?>
					<div class="hero-landing__buttons d-flex flex-column flex-sm-row flex-wrap gap-2 gap-sm-3 align-items-center align-items-lg-start justify-content-center justify-content-lg-start"
						data-anim="fade-up"
						data-anim-delay="0.5"
						data-anim-start="top bottom">
						<?php foreach ($buttons as $item) :
							$btn = $item['button'];
							if (!$btn) continue;
							$label = esc_html($btn['title']);
						?>
							<a
								href="<?php echo esc_url($btn['url']); ?>"
								target="<?php echo esc_attr($btn['target'] ?: '_self'); ?>"
								class="btn-omma-blue">
								<span class="button-020__inner">
									<span class="button-020__default">
										<span class="button-020__default-bg"></span>
										<span class="button-020__default-text"><?php echo $label; ?></span>
									</span>
									<span aria-hidden="true" class="button-020__hover">
										<span class="button-020__hover-bg"></span>
										<span class="button-020__hover-text"><?php echo $label; ?></span>
									</span>
								</span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

</section>