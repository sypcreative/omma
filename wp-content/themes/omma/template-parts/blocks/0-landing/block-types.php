<?php

/**
 * Block: Block Types
 * Template: template-parts/blocks/0-landing/block-types.php
 */

$items = get_field('block_types_items');

if (! $items) {
	return;
}
?>

<section class="block-types py-5 vh-100" id="about" data-progress-nav-anchor="" data-stack-section="">
	<div class="container">
		<div class="d-flex flex-column gap-4" data-stack-wrap="">

			<?php foreach ($items as $i => $item) :
				$title    = $item['block_types_title']    ?? '';
				$link     = $item['block_types_link']     ?? null;
				$subtitle = $item['block_types_subtitle'] ?? '';
				$cards    = $item['block_types_cards']    ?? [];
			?>

				<div class="block-types__item bg-blue-800 rounded-3 border border-blue-800 p-4 p-lg-5" style="--bs-bg-opacity: .6"
					data-stack-card="">

					<!-- ── Fila 1: título + botón ─────────────────────────────── -->
					<div class=" row align-items-start mb-4 mb-lg-5">
						<div class="col">
							<?php if ($title) : ?>
								<h2 class="block-types__title r-3 text-vanilla m-0">
									<?php echo esc_html($title); ?>
								</h2>
							<?php endif; ?>
						</div>
						<?php if ($link) : ?>
							<?php $label = esc_html($link['title']); ?>
							<div class="col-auto">
								<a
									href="<?php echo esc_url($link['url']); ?>"
									target="<?php echo esc_attr($link['target'] ?: '_self'); ?>"
									class="btn-omma">
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
							</div>
						<?php endif; ?>
					</div>

					<!-- ── Fila 2: subtítulo + cards ──────────────────────────── -->
					<div class="row align-items-end">
						<div class="col-12 col-lg-5">
							<?php if ($subtitle) : ?>
								<p class="block-types__subtitle fs-5 text-vanilla mb-0">
									<?php echo esc_html($subtitle); ?>
								</p>
							<?php endif; ?>
						</div>
						<?php if ($cards) : ?>
							<div class="col-12 col-lg-7 mt-4 mt-lg-0">
								<ul class="block-types__cards list-unstyled m-0">
									<?php foreach ($cards as $c_i => $card) :
										$text = $card['block_types_card_text'] ?? '';
										if (! $text) continue;
									?>
										<li class="block-types__card-item d-flex align-items-center gap-3 py-3 border-bottom border-white border-opacity-25">
											<span class="block-types__bullet flex-shrink-0" aria-hidden="true"></span>
											<span class="block-types__card-text text-vanilla">
												<?php echo esc_html($text); ?>
											</span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>

				</div>

			<?php endforeach; ?>

		</div>
	</div>
</section>