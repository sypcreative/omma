<?php

/**
 * Block: Hero Landing
 * Template: template-parts/blocks/0-landing/block-hero-landing.php
 */

$image   = get_field('hero_landing_image');
$title   = get_field('hero_landing_title');
$buttons = get_field('hero_landing_buttons');
?>

<section class="hero-landing position-relative overflow-hidden w-100 min-vh-100 d-flex align-items-end">

	<?php if ($image) : ?>
		<div class="hero-landing__bg position-absolute top-0 start-0 w-100 h-100">
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
				]
			); ?>
		</div>
	<?php endif; ?>

	<div class="container position-relative z-1 pb-5">
		<div class="row">
			<div class="col-12 col-lg-10">

				<?php if ($title) : ?>
					<h1 class="hero-landing__title text-blue-800 mb-4 r-0">
						<?php echo esc_html($title); ?>
					</h1>
				<?php endif; ?>

				<?php if ($buttons) : ?>
					<div class="hero-landing__buttons d-flex flex-wrap gap-3">
						<?php foreach ($buttons as $item) :
							$btn = $item['button'];
							if (!$btn) continue;
						?>
							<a
								href="<?php echo esc_url($btn['url']); ?>"
								target="<?php echo esc_attr($btn['target'] ?: '_self'); ?>"
								class="btn btn-omma">
								<?php echo esc_html($btn['title']); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

</section>