<?php

/**
 * Block: What Is
 * Template: template-parts/blocks/0-landing/block-what-is.php
 */

$title      = get_field('block_what_is_title');
$text_left  = get_field('block_what_is_text_left');
$text_right = get_field('block_what_is_text_right');
$graph      = get_field('block_what_is_graph');
$items      = get_field('block_what_is_items');

if (! $items) {
	return;
}
?>

<section class="block-what-is py-5" id="about" data-progress-nav-anchor="" data-stack-section="">
	<div class="container">

		<!-- Title -->
		<?php if ($title) : ?>
			<div class="row mb-3 mb-lg-4">
				<div class="col-12">
					<h2 class="block-what-is__title m-0 h-4 h-lg-3"
						data-anim="lines">
						<?php echo esc_html($title); ?>
					</h2>
				</div>
			</div>
		<?php endif; ?>

		<!-- Two-column text -->
		<?php if ($text_left || $text_right) : ?>
			<div class="row mb-3 mb-lg-5">
				<?php if ($text_left) : ?>
					<div class="col-12 col-lg-6">
						<p class="block-what-is__text fs-6 fs-md-5 mb-3 mb-md-0"
							data-anim="fade-up"
							data-anim-delay="0.1">
							<?php echo nl2br(esc_html($text_left)); ?>
						</p>
					</div>
				<?php endif; ?>
				<?php if ($text_right) : ?>
					<div class="col-12 col-lg-6">
						<p class="block-what-is__text fs-6 fs-md-5 mb-0"
							data-anim="fade-up"
							data-anim-delay="0.2">
							<?php echo nl2br(esc_html($text_right)); ?>
						</p>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>


		<!-- Graph -->
		<?php get_template_part('template-parts/blocks/0-landing/block-graph', null, ['nodes' => $graph]); ?>

		<!-- Stacking cards -->
		<div class="block-what-is__cards-section pt-3 pt-lg-5">
			<div class="d-flex flex-column flex-lg-row gap-2 gap-lg-3 align-items-lg-stretch" data-stack-wrap="">

				<?php foreach ($items as $i => $item) :
					$item_title    = $item['block_what_is_item_title']    ?? '';
					$item_link     = $item['block_what_is_item_link']     ?? null;
					$item_subtitle = $item['block_what_is_item_subtitle'] ?? '';
					$item_cards    = $item['block_what_is_item_cards']    ?? [];
				?>

					<div class="block-what-is__item rounded-3 border border-blue-800" style="background: #13405F"
						data-stack-card="">

						<div class="block-what-is__content p-4 p-lg-5">

							<div class="row align-items-start mb-3 mb-lg-5">
								<div class="col">
									<?php if ($item_title) : ?>
										<h3 class="block-what-is__card-title h-4 r-lg-3 text-vanilla m-0">
											<?php echo esc_html($item_title); ?>
										</h3>
									<?php endif; ?>
								</div>
								<?php if ($item_link) :
									$btn_label = esc_html($item_link['title']); ?>
									<div class="col-auto d-none d-lg-block">
										<a
											href="<?php echo esc_url($item_link['url']); ?>"
											target="<?php echo esc_attr($item_link['target'] ?: '_self'); ?>"
											class="btn-omma">
											<span class="button-020__inner">
												<span class="button-020__default">
													<span class="button-020__default-bg"></span>
													<span class="button-020__default-text"><?php echo $btn_label; ?></span>
												</span>
												<span aria-hidden="true" class="button-020__hover">
													<span class="button-020__hover-bg"></span>
													<span class="button-020__hover-text"><?php echo $btn_label; ?></span>
												</span>
											</span>
										</a>
									</div>
								<?php endif; ?>
							</div>

							<div class="row align-items-lg-end">
								<div class="col-12 col-lg-5">
									<?php if ($item_subtitle) : ?>
										<p class="block-what-is__card-subtitle fs-6 fs-lg-5 text-vanilla mb-3 mb-lg-0"
											<?php echo $i === 0 ? 'data-anim="fade-up" data-anim-delay="0.15"' : ''; ?>>
											<?php echo esc_html($item_subtitle); ?>
										</p>
									<?php endif; ?>
								</div>
								<?php if ($item_cards) : ?>
									<div class="col-12 col-lg-7 mt-0">
										<ul class="block-what-is__card-list list-unstyled m-0"
											<?php echo $i === 0 ? 'data-anim="fade-up" data-anim-delay="0.25"' : ''; ?>>
											<?php foreach ($item_cards as $card) :
												$card_text = $card['block_what_is_item_card_text'] ?? '';
												if (! $card_text) continue;
											?>
												<li class="d-flex align-items-center gap-3 py-2 py-lg-3 border-bottom border-white border-opacity-25">
													<span class="block-what-is__bullet flex-shrink-0" aria-hidden="true"></span>
													<span class="text-vanilla"><?php echo esc_html($card_text); ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>
							</div>

							<?php if ($item_link) :
								$btn_label = esc_html($item_link['title']); ?>
								<div class="d-lg-none mt-4 text-end">
									<a
										href="<?php echo esc_url($item_link['url']); ?>"
										target="<?php echo esc_attr($item_link['target'] ?: '_self'); ?>"
										class="btn-omma">
										<span class="button-020__inner">
											<span class="button-020__default">
												<span class="button-020__default-bg"></span>
												<span class="button-020__default-text"><?php echo $btn_label; ?></span>
											</span>
											<span aria-hidden="true" class="button-020__hover">
												<span class="button-020__hover-bg"></span>
												<span class="button-020__hover-text"><?php echo $btn_label; ?></span>
											</span>
										</span>
									</a>
								</div>
							<?php endif; ?>

						</div><!-- /.block-what-is__content -->

					</div><!-- /.block-what-is__item -->

				<?php endforeach; ?>

			</div>
		</div><!-- /.block-what-is__cards-section -->

	</div>
</section>