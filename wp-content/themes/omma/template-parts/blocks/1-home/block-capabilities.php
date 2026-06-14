<?php

/**
 * Block: Capabilities
 *
 * Campos consumidos:
 *   get_field('block_capabilities_title') → text
 *   get_field('block_capabilities_items') → repeater
 *     block_capabilities_item_title       (text)
 *     block_capabilities_item_description (textarea)
 */

$title = function_exists('get_field') ? get_field('block_capabilities_title') : '';
$items = function_exists('get_field') ? get_field('block_capabilities_items') : [];

if (! $items) {
	return;
}

?>

<section class="block-capabilities bg-charcoal py-5 py-lg-6">
	<div class="container-fluid px-4 px-lg-5">

		<!-- Título full width -->
		<?php if ($title) : ?>
			<div class="row mb-5 mb-lg-6">
				<div class="col-12 col-lg-8">
					<h2 class="block-capabilities__title">
						<?php echo esc_html($title); ?>
					</h2>
				</div>
			</div>
		<?php endif; ?>

		<!-- Lista de items con directional hover -->
		<div class="block-capabilities__list" data-directional-hover data-type="y">

			<?php foreach ($items as $item) :
				$item_title = $item['block_capabilities_item_title']       ?? '';
				$item_desc  = $item['block_capabilities_item_description'] ?? '';
			?>
				<div class="block-capabilities__item" data-directional-hover-item>

					<!-- Tile para el efecto hover (JS lo mueve) -->
					<div class="block-capabilities__item-tile" data-directional-hover-tile></div>

					<!-- Línea separadora animada al scrollear -->
					<div class="block-capabilities__item-line"></div>

					<!-- Contenido: título izq + descripción der -->
					<div class="block-capabilities__item-body row g-0 align-items-baseline">
						<div class="col-12 col-lg-4">
							<?php if ($item_title) : ?>
								<p class="block-capabilities__item-title">
									<?php echo esc_html($item_title); ?>
								</p>
							<?php endif; ?>
						</div>
						<div class="col-12 col-lg-8">
							<?php if ($item_desc) : ?>
								<p class="block-capabilities__item-desc">
									<?php echo nl2br(esc_html($item_desc)); ?>
								</p>
							<?php endif; ?>
						</div>
					</div>

				</div>
			<?php endforeach; ?>

			<!-- Línea de cierre -->
			<div class="block-capabilities__item-line block-capabilities__item-line--last"></div>

		</div>

	</div>
</section>
