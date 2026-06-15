<?php
$items = get_field('block_srv_list_items');
if (! $items) return;
?>

<section class="block-services-list bg-charcoal">
	<?php foreach ($items as $i => $item) :
		$title    = $item['block_srv_list_item_title'] ?? '';
		$icon     = $item['block_srv_list_item_icon']  ?? null;
		$desc     = $item['block_srv_list_item_desc']  ?? '';
		$buttons  = $item['block_srv_list_item_btns']  ?? [];
		$features = $item['block_srv_list_item_feats'] ?? [];
	?>

		<div class="block-services-list__item">
			<?php if ($i > 0) : ?>
				<div class="block-services-list__line" aria-hidden="true"></div>
			<?php endif; ?>

			<!-- Header: siempre visible -->
			<div class="block-services-list__header">
				<div class="container">
					<div class="row align-items-center py-5 py-lg-6">

						<?php if ($icon) : ?>
							<div class="col-12 col-lg-4 offset-lg-2 order-1 order-lg-2 d-flex justify-content-lg-end mb-4 mb-lg-0">
								<div class="block-services-list__icon">
									<img src="<?php echo esc_url($icon['url']); ?>"
										alt="<?php echo esc_attr($icon['alt']); ?>">
								</div>
							</div>
						<?php endif; ?>

						<div class="col-12 <?php echo $icon ? 'col-lg-6 order-2 order-lg-1' : ''; ?>">

							<?php if ($title) : ?>
								<h2 class="msans-3 text-vanilla mb-3"><?php echo esc_html($title); ?></h2>
							<?php endif; ?>

							<?php if ($desc) : ?>
								<p class="block-services-list__desc fs-5 text-vanilla mb-4">
									<?php echo nl2br(esc_html($desc)); ?>
								</p>
							<?php endif; ?>

							<?php if ($buttons || $features) : ?>
								<div class="block-services-list__buttons">

									<?php foreach ($buttons as $btn) :
										$label = $btn['block_srv_list_btn_label'] ?? '';
										$url   = $btn['block_srv_list_btn_url']   ?? '';
										if (! $label || ! $url) continue;
									?>
										<a href="<?php echo esc_url($url); ?>" class="btn-omma-light">
											<span class="button-020__inner">
												<span class="button-020__default">
													<span class="button-020__default-bg"></span>
													<span class="button-020__default-text"><?php echo esc_html($label); ?> <?php echo omma_btn_arrow(); ?></span>
												</span>
												<span aria-hidden="true" class="button-020__hover">
													<span class="button-020__hover-bg"></span>
													<span class="button-020__hover-text"><?php echo esc_html($label); ?> <?php echo omma_btn_arrow(); ?></span>
												</span>
											</span>
										</a>
									<?php endforeach; ?>

									<?php if ($features) : ?>
										<button type="button" class="block-services-list__toggle btn-omma-outline-dark border-0" aria-expanded="false">
											<span class="button-020__inner">
												<span class="button-020__default">
													<span class="button-020__default-bg"></span>
													<span class="button-020__default-text">More Information ↓</span>
												</span>
												<span aria-hidden="true" class="button-020__hover">
													<span class="button-020__hover-bg"></span>
													<span class="button-020__hover-text">More Information ↓</span>
												</span>
											</span>
										</button>
									<?php endif; ?>

								</div>
							<?php endif; ?>

						</div>

					</div>
				</div>
			</div>

			<!-- Body: acordeón con características -->
			<?php if ($features) : ?>
				<div class="block-services-list__body" aria-hidden="true">
					<div class="container pb-5 pb-lg-6">
						<div class="row g-3">
							<?php foreach ($features as $feat) :
								$ftitle = $feat['block_srv_list_feat_title'] ?? '';
								$fdesc  = $feat['block_srv_list_feat_desc']  ?? '';
								$fbg    = $feat['block_srv_list_feat_bg']    ?? '';
							?>
								<div class="col-12 col-md-6">
									<div class="block-services-list__feature rounded-3"
										<?php if ($fbg) echo 'style="background-color:' . esc_attr($fbg) . '"'; ?>>
										<?php if ($ftitle) : ?>
											<p class="block-services-list__feature-title h-5 text-vanilla mb-2">
												<?php echo esc_html($ftitle); ?>
											</p>
										<?php endif; ?>
										<?php if ($fdesc) : ?>
											<p class="block-services-list__feature-desc text-vanilla fs-6">
												<?php echo nl2br(esc_html($fdesc)); ?>
											</p>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

		</div>

	<?php endforeach; ?>
</section>