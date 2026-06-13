<?php

/**
 * Block: Data
 * Fields: block_data_subtitle,
 *         block_data_items (repeater → text_1, text_2)
 */

$subtitle = get_field('block_data_subtitle');
$items    = get_field('block_data_items');

if (! $items) {
	return;
}

?>

<section class="block-data bg-charcoal py-5 py-lg-6">
	<div class="container">

		<?php if ($subtitle) : ?>
			<div class="row mb-4 mb-lg-5">
				<div class="col-12 col-lg-8">
					<p class="h-3 h-lg-2 text-vanilla mb-0" data-anim="lines">
						<?php echo esc_html($subtitle); ?>
					</p>
				</div>
			</div>
		<?php endif; ?>

		<div class="row g-4 g-lg-5">
			<?php foreach ($items as $item) :
				$text_1 = $item['block_data_item_text_1'] ?? '';
				$text_2 = $item['block_data_item_text_2'] ?? '';
			?>
				<div class="col-6 col-lg-5">

					<?php if ($text_1) : ?>
						<p class="text-pink mb-0 lh-1 m-display" data-anim="fade-up">
							<?php echo esc_html($text_1); ?>
						</p>
					<?php endif; ?>

					<?php if ($text_2) : ?>
						<p class="fs-5 text-vanilla mb-0">
							<?php echo esc_html($text_2); ?>
						</p>
					<?php endif; ?>

				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>