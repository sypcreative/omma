<?php

/**
 * Block: Ecosystem
 *
 * Campos consumidos:
 *   get_field('block_ecosystem_title')    → text
 *   get_field('block_ecosystem_subtitle') → text
 *   get_field('block_ecosystem_btn_text') → text
 *   get_field('block_ecosystem_btn_url')  → url
 *   get_field('block_ecosystem_cards')    → repeater
 *     block_ecosystem_card_title  (text)
 *     block_ecosystem_card_color  (color_picker)
 *     block_ecosystem_card_icon   (image, array)
 *     block_ecosystem_card_text   (textarea)
 */

$title     = function_exists('get_field') ? get_field('block_ecosystem_title')    : '';
$subtitle  = function_exists('get_field') ? get_field('block_ecosystem_subtitle') : '';
$btn_text  = function_exists('get_field') ? get_field('block_ecosystem_btn_text') : '';
$btn_url   = function_exists('get_field') ? get_field('block_ecosystem_btn_url')  : '';
$cards     = function_exists('get_field') ? get_field('block_ecosystem_cards')    : [];

$top_cards = array_slice($cards ?: [], 0, 3);
$bot_cards = array_slice($cards ?: [], 3);

?>

<section class="block-ecosystem bg-charcoal py-5 py-lg-6">
	<div class="container-fluid px-4 px-lg-5">

		<!-- Cabecera centrada -->
		<div class="row justify-content-center text-center mb-5">
			<div class="col-12 col-lg-7">

				<?php if ($title) : ?>
					<h2 class="block-ecosystem__title mb-4">
						<?php echo esc_html($title); ?>
					</h2>
				<?php endif; ?>

				<?php if ($subtitle) : ?>
					<p class="block-ecosystem__subtitle mb-0">
						<?php echo esc_html($subtitle); ?>
					</p>
				<?php endif; ?>

			</div>
		</div>

		<!-- Desktop: grid de cards (oculto en mobile) -->
		<div class="d-none d-md-block">

			<?php if ($top_cards) : ?>
				<div class="row g-3 g-lg-4 mb-3 mb-lg-4">
					<?php foreach ($top_cards as $card) :
						$card_title        = $card['block_ecosystem_card_title']        ?? '';
						$card_color        = $card['block_ecosystem_card_color']        ?? '#2e3a30';
						$card_border_color = $card['block_ecosystem_card_border_color'] ?? '#ffffff';
						$card_icon         = $card['block_ecosystem_card_icon']         ?? null;
						$card_text         = $card['block_ecosystem_card_text']         ?? '';
					?>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="block-ecosystem__card" style="--card-bg: <?php echo esc_attr($card_color); ?>; --card-border: <?php echo esc_attr($card_border_color); ?>">

								<?php if ($card_title) : ?>
									<p class="block-ecosystem__card-title msans-4 text-vanilla text-center"><?php echo esc_html($card_title); ?></p>
								<?php endif; ?>

								<?php if ($card_icon) : ?>
									<div class="block-ecosystem__card-icon">
										<img
											src="<?php echo esc_url($card_icon['url']); ?>"
											alt="<?php echo esc_attr($card_icon['alt'] ?: $card_title); ?>"
											width="<?php echo (int) $card_icon['width']; ?>"
											height="<?php echo (int) $card_icon['height']; ?>"
											loading="lazy"
											decoding="async">
									</div>
								<?php endif; ?>

								<?php if ($card_text) : ?>
									<p class="block-ecosystem__card-text text-vanilla"><?php echo nl2br(esc_html($card_text)); ?></p>
								<?php endif; ?>

							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ($bot_cards) : ?>
				<div class="row g-3 g-lg-4 justify-content-center">
					<?php foreach ($bot_cards as $card) :
						$card_title        = $card['block_ecosystem_card_title']        ?? '';
						$card_color        = $card['block_ecosystem_card_color']        ?? '#2e3a30';
						$card_border_color = $card['block_ecosystem_card_border_color'] ?? '#ffffff';
						$card_icon         = $card['block_ecosystem_card_icon']         ?? null;
						$card_text         = $card['block_ecosystem_card_text']         ?? '';
					?>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="block-ecosystem__card" style="--card-bg: <?php echo esc_attr($card_color); ?>; --card-border: <?php echo esc_attr($card_border_color); ?>">

								<?php if ($card_title) : ?>
									<p class="block-ecosystem__card-title msans-4 text-vanilla text-center"><?php echo esc_html($card_title); ?></p>
								<?php endif; ?>

								<?php if ($card_icon) : ?>
									<div class="block-ecosystem__card-icon">
										<img
											src="<?php echo esc_url($card_icon['url']); ?>"
											alt="<?php echo esc_attr($card_icon['alt'] ?: $card_title); ?>"
											width="<?php echo (int) $card_icon['width']; ?>"
											height="<?php echo (int) $card_icon['height']; ?>"
											loading="lazy"
											decoding="async">
									</div>
								<?php endif; ?>

								<?php if ($card_text) : ?>
									<p class="block-ecosystem__card-text text-vanilla"><?php echo nl2br(esc_html($card_text)); ?></p>
								<?php endif; ?>

							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</div>

		<!-- Mobile: stacking cards (oculto en desktop) -->
		<?php if ($cards) : ?>
			<div class="block-ecosystem__stack d-md-none" data-ecosystem-stack>
				<div class="block-ecosystem__stack-wrap" data-ecosystem-stack-wrap>
					<?php foreach ($cards as $card) :
						$card_title        = $card['block_ecosystem_card_title']        ?? '';
						$card_color        = $card['block_ecosystem_card_color']        ?? '#2e3a30';
						$card_border_color = $card['block_ecosystem_card_border_color'] ?? '#ffffff';
						$card_icon         = $card['block_ecosystem_card_icon']         ?? null;
						$card_text         = $card['block_ecosystem_card_text']         ?? '';
					?>
						<div class="block-ecosystem__stack-card" data-ecosystem-card style="--card-bg: <?php echo esc_attr($card_color); ?>; --card-border: <?php echo esc_attr($card_border_color); ?>">

							<?php if ($card_title) : ?>
								<p class="block-ecosystem__card-title msans-4 text-vanilla text-center"><?php echo esc_html($card_title); ?></p>
							<?php endif; ?>

							<?php if ($card_icon) : ?>
								<div class="block-ecosystem__card-icon">
									<img
										src="<?php echo esc_url($card_icon['url']); ?>"
										alt="<?php echo esc_attr($card_icon['alt'] ?: $card_title); ?>"
										width="<?php echo (int) $card_icon['width']; ?>"
										height="<?php echo (int) $card_icon['height']; ?>"
										loading="lazy"
										decoding="async">
								</div>
							<?php endif; ?>

							<?php if ($card_text) : ?>
								<p class="block-ecosystem__card-text text-vanilla"><?php echo nl2br(esc_html($card_text)); ?></p>
							<?php endif; ?>

						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<!-- CTA centrado -->
		<?php if ($btn_text && $btn_url) : ?>
			<div class="d-flex justify-content-center mt-5">
				<a href="<?php echo esc_url($btn_url); ?>" class="block-ecosystem__cta btn-omma-light">
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