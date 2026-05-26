<?php

/**
 * Block: Block Sectors
 * Template: template-parts/blocks/0-landing/block-sectors.php
 */

$title = get_field('block_sectors_title');
$items = get_field('block_sectors_items');

if (! $items) {
	return;
}

// First item index that has an image — used as initial active slide
$first_slide_i = null;
foreach ($items as $i => $item) {
	if (! empty($item['block_sectors_sector_image'])) {
		$first_slide_i = $i;
		break;
	}
}
?>

<section class="block-sectors bg-charcoal py-5" id="who" data-progress-nav-anchor="">
	<div class=" container">

		<?php if ($title) : ?>
			<p class="block-sectors__label h-6 text-vanilla text-uppercase mb-4 mb-lg-5"
				data-anim="fade-up">
				<?php echo esc_html($title); ?>
			</p>
		<?php endif; ?>

		<div class="row align-items-start g-0">

			<!-- ── Lista de sectores ──────────────────────────────── -->
			<div class="col-12 col-lg-8">
				<ul class="block-sectors__list list-unstyled m-0"
					data-directional-hover=""
					data-type="y">

					<?php foreach ($items as $i => $item) :
						$num  = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
						$name = $item['block_sectors_sector_name'];
					?>
						<li class="block-sectors__item d-flex align-items-center gap-3 gap-lg-4 border-bottom border-white border-opacity-10 py-2 py-lg-3"
							data-sector-index="<?php echo $i; ?>"
							data-directional-hover-item=""
							data-anim="fade-up"
							data-anim-delay="<?php echo number_format($i * 0.07, 2); ?>">

							<div data-directional-hover-tile="" class="block-sectors__hover-tile"></div>

							<span class="block-sectors__num h-1 text-vanilla lh-1 flex-shrink-0">
								<?php echo esc_html($num); ?>
							</span>

							<span class="block-sectors__name fs-5 text-vanilla">
								<?php echo esc_html($name); ?>
							</span>

						</li>
					<?php endforeach; ?>

				</ul>
			</div>

			<!-- ── Imagen (desktop) ──────────────────────────────── -->
			<?php if ($first_slide_i !== null) : ?>
				<div class="col-lg-4 d-none d-lg-block ps-4">
					<div class="block-sectors__image-wrap" data-sectors-image
						data-parallax="trigger"
						data-parallax-start="-10"
						data-parallax-end="10"
						data-parallax-disable="mobile">

						<?php foreach ($items as $i => $item) :
							$image = $item['block_sectors_sector_image'] ?? null;
							if (! $image) continue;
						?>
							<div class="block-sectors__slide<?php echo $i === $first_slide_i ? ' is--active' : ''; ?>"
								data-slide-index="<?php echo $i; ?>">
								<?php echo wp_get_attachment_image(
									$image['ID'],
									'large',
									false,
									[
										'class'    => 'block-sectors__img',
										'loading'  => $i === $first_slide_i ? 'eager' : 'lazy',
										'decoding' => 'async',
										'sizes'    => '(min-width: 992px) 33vw, 0px',
									]
								); ?>
							</div>
						<?php endforeach; ?>

					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>