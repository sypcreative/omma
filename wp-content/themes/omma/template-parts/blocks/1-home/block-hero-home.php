<?php

/**
 * Block: Hero Home
 * Fields: block_hero_home_media_type, block_hero_home_image,
 *         block_hero_home_video, block_hero_home_title,
 *         block_hero_home_subtitle, block_hero_home_buttons
 */

$media_type = get_field('block_hero_home_media_type') ?: 'image';
$image      = get_field('block_hero_home_image');
$video      = get_field('block_hero_home_video');
$title      = get_field('block_hero_home_title');
$subtitle   = get_field('block_hero_home_subtitle');
$buttons    = get_field('block_hero_home_buttons');

?>

<section class="block-hero-home overflow-hidden vh-100 d-flex flex-column">

	<!-- Fondo: imagen o vídeo -->
	<?php if ($media_type === 'video' && $video) : ?>
		<div class="block-hero-home__bg position-absolute top-0 start-0 w-100 h-100">
			<video
				class="w-100 h-100 object-fit-cover"
				autoplay
				muted
				loop
				playsinline
				preload="auto"
			>
				<source src="<?php echo esc_url($video['url']); ?>" type="<?php echo esc_attr($video['mime_type']); ?>">
			</video>
		</div>
	<?php elseif ($image) : ?>
		<div class="block-hero-home__bg position-absolute top-0 start-0 w-100 h-100">
			<?php echo wp_get_attachment_image(
				$image['ID'],
				'full',
				false,
				[
					'class'         => 'w-100 h-100 object-fit-cover',
					'loading'       => 'eager',
					'decoding'      => 'async',
					'fetchpriority' => 'high',
					'sizes'         => '100vw',
				]
			); ?>
		</div>
	<?php endif; ?>

	<!-- Overlay -->
	<div class="block-hero-home__overlay position-absolute top-0 start-0 bottom-0 end-0" aria-hidden="true"></div>

	<!-- Contenido — flex-column ocupa toda la altura, mt-auto empuja al fondo -->
	<div class="container position-relative z-1 d-flex flex-column flex-grow-1">

		<!-- Título: top-left -->
		<?php if ($title) : ?>
			<div class="row">
				<div class="col-12 col-lg-11">
					<h1 class="block-hero-home__title h-4 h-md-1 text-vanilla mb-0">
						<?php echo esc_html($title); ?>
					</h1>
				</div>
			</div>
		<?php endif; ?>

		<!-- Subtítulo + botones: bottom-right -->
		<?php if ($subtitle || $buttons) : ?>
			<div class="mt-auto">
				<div class="row justify-content-end">
					<div class="col-12 col-lg-6">

						<?php if ($subtitle) : ?>
							<p class="block-hero-home__subtitle fs-6 fs-md-5 text-vanilla mb-4 text-end text-sm-start">
								<?php echo esc_html($subtitle); ?>
							</p>
						<?php endif; ?>

						<?php if ($buttons) : ?>
							<div class="block-hero-home__actions d-flex flex-wrap gap-3 justify-content-end justify-content-sm-start">
								<?php foreach ($buttons as $btn) :
									$text = $btn['block_hero_home_btn_text'] ?? '';
									$url  = $btn['block_hero_home_btn_url']  ?? '';
									if (! $text || ! $url) continue;
								?>
									<a href="<?php echo esc_url($url); ?>" class="btn-omma-light">
										<span class="button-020__inner">
											<span class="button-020__default">
												<span class="button-020__default-bg"></span>
												<span class="button-020__default-text">
													<?php echo esc_html($text); ?> <?php echo omma_btn_arrow(); ?>
												</span>
											</span>
											<span aria-hidden="true" class="button-020__hover">
												<span class="button-020__hover-bg"></span>
												<span class="button-020__hover-text">
													<?php echo esc_html($text); ?> <?php echo omma_btn_arrow(); ?>
												</span>
											</span>
										</span>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

					</div>
				</div>
			</div>
		<?php endif; ?>

	</div>

</section>