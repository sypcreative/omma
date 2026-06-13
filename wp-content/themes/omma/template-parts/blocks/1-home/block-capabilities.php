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
		<div class="row g-5 g-lg-6">

			<!-- Título sticky -->
			<div class="col-12 col-lg-5">
				<div class="block-capabilities__title-wrap">
					<?php if ($title) : ?>
						<h2 class="block-capabilities__title">
							<?php echo esc_html($title); ?>
						</h2>
					<?php endif; ?>
				</div>
			</div>

			<!-- Lista de items -->
			<div class="col-12 col-lg-7">
				<div class="block-capabilities__list">

					<?php foreach ($items as $item) :
						$item_title = $item['block_capabilities_item_title']       ?? '';
						$item_desc  = $item['block_capabilities_item_description'] ?? '';
					?>
						<div class="block-capabilities__item">
							<div class="block-capabilities__item-line"></div>
							<div class="block-capabilities__item-body">

								<?php if ($item_title) : ?>
									<p class="block-capabilities__item-title">
										<?php echo esc_html($item_title); ?>
									</p>
								<?php endif; ?>

								<?php if ($item_desc) : ?>
									<p class="block-capabilities__item-desc">
										<?php echo nl2br(esc_html($item_desc)); ?>
									</p>
								<?php endif; ?>

							</div>
						</div>
					<?php endforeach; ?>

					<div class="block-capabilities__item-line block-capabilities__item-line--last"></div>

				</div>
			</div>

		</div>
	</div>
</section>
