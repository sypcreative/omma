<?php

/**
 * Block: Logos
 *
 * Campos consumidos:
 *   get_field('block_logos_images') → repeater
 *     block_logos_image      (image, array)
 *     block_logos_item_name  (text)
 *   get_field('block_logos_btn_text') → text
 *   get_field('block_logos_btn_url')  → url
 */

$images   = function_exists('get_field') ? get_field('block_logos_images')  : [];
$btn_text = function_exists('get_field') ? get_field('block_logos_btn_text') : '';
$btn_url  = function_exists('get_field') ? get_field('block_logos_btn_url')  : '';

if (! $images) {
	return;
}

// Mezcla aleatoriamente en cada carga y distribuye en 3 columnas
shuffle($images);
$slots = [[], [], []];
foreach ($images as $i => $item) {
	$slots[$i % 3][] = $item;
}

?>

<section class="block-logos bg-charcoal py-5 py-lg-6">
	<div class="container-fluid px-4 px-lg-5">

		<!-- Grid de cards -->
		<div class="row g-3 g-lg-4 mb-3 mb-lg-4">
			<?php foreach ($slots as $slot) :
				if (empty($slot)) continue;
			?>
				<div class="col-12 col-sm-4">
					<div class="block-logos__card" data-logos-slot>

						<?php foreach ($slot as $item) :
							$img  = $item['block_logos_image']     ?? null;
							$name = $item['block_logos_item_name'] ?? '';
							if (! $img) continue;
						?>
							<div class="block-logos__logo">
								<img
									src="<?php echo esc_url($img['url']); ?>"
									alt="<?php echo esc_attr($img['alt'] ?: $name); ?>"
									width="<?php echo (int) $img['width']; ?>"
									height="<?php echo (int) $img['height']; ?>"
									loading="lazy"
									decoding="async"
								>
							</div>
						<?php endforeach; ?>

					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- CTA -->
		<?php if ($btn_text && $btn_url) : ?>
			<div class="d-flex justify-content-end">
				<a href="<?php echo esc_url($btn_url); ?>" class="block-logos__cta btn-omma-light">
					<span class="button-020__inner">
						<span class="button-020__default">
							<span class="button-020__default-bg"></span>
							<span class="button-020__default-text"><?php echo esc_html($btn_text); ?> <?php echo omma_btn_arrow(); ?></span>
						</span>
						<span aria-hidden="true" class="button-020__hover">
							<span class="button-020__hover-bg"></span>
							<span class="button-020__hover-text"><?php echo esc_html($btn_text); ?> <?php echo omma_btn_arrow(); ?></span>
						</span>
					</span>
				</a>
			</div>
		<?php endif; ?>

	</div>
</section>
