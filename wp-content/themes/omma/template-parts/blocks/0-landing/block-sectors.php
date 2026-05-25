<?php

/**
 * Block: Block Sectors
 * Template: template-parts/blocks/0-landing/block-sectors.php
 */

$title = get_field('block_sectors_title');
$items = get_field('block_sectors_items');

if ( ! $items ) {
	return;
}

// Primera imagen disponible — se muestra por defecto en desktop
$default_image = null;
foreach ( $items as $item ) {
	if ( ! empty( $item['block_sectors_sector_image'] ) ) {
		$default_image = $item['block_sectors_sector_image'];
		break;
	}
}
?>

<section class="block-sectors bg-charcoal py-5">
	<div class="container">

		<?php if ( $title ) : ?>
			<p class="block-sectors__label h-6 text-vanilla text-uppercase mb-4 mb-lg-5">
				<?php echo esc_html( $title ); ?>
			</p>
		<?php endif; ?>

		<div class="row align-items-start g-0">

			<!-- ── Lista de sectores ──────────────────────────────── -->
			<div class="col-12 col-lg-8">
				<ul class="block-sectors__list list-unstyled m-0">

					<?php foreach ( $items as $i => $item ) :
						$num   = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
						$name  = $item['block_sectors_sector_name'];
						$image = $item['block_sectors_sector_image'] ?? null;
					?>
						<li class="block-sectors__item d-flex align-items-center gap-3 gap-lg-4 border-bottom border-white border-opacity-10 py-2 py-lg-3"
							<?php if ( $image ) : ?>
								data-sector-img="<?php echo esc_url( $image['url'] ); ?>"
								data-sector-alt="<?php echo esc_attr( $image['alt'] ?: $name ); ?>"
								data-sector-id="<?php echo esc_attr( $image['ID'] ); ?>"
							<?php endif; ?>>

							<span class="block-sectors__num h-1 text-vanilla lh-1 flex-shrink-0">
								<?php echo esc_html( $num ); ?>
							</span>

							<span class="block-sectors__name fs-5 text-vanilla">
								<?php echo esc_html( $name ); ?>
							</span>

						</li>
					<?php endforeach; ?>

				</ul>
			</div>

			<!-- ── Imagen (desktop) ──────────────────────────────── -->
			<?php if ( $default_image ) : ?>
				<div class="col-lg-4 d-none d-lg-flex align-items-start justify-content-end ps-4">
					<div class="block-sectors__image-wrap" data-sectors-image>
						<?php echo wp_get_attachment_image(
							$default_image['ID'],
							'large',
							false,
							[
								'class'   => 'block-sectors__img',
								'loading' => 'lazy',
								'decoding' => 'async',
								'sizes'   => '(min-width: 992px) 33vw, 0px',
							]
						); ?>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
