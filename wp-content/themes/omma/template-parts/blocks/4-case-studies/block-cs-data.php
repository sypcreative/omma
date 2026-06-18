<?php
$titulo = get_field('block_cs_dat_titulo');
$items  = get_field('block_cs_dat_items');
if (! $items) return;
?>

<section class="block-cs-data bg-charcoal py-5 py-lg-6">
	<div class="container">

		<?php if ($titulo) : ?>
			<p class="block-cs-data__titulo h-4 text-vanilla mb-4 mb-lg-5">
				<?php echo esc_html($titulo); ?>
			</p>
		<?php endif; ?>

		<div class="row g-4">
			<?php foreach ($items as $item) :
				$numero = $item['block_cs_dat_numero'] ?? '';
				$texto  = $item['block_cs_dat_texto']  ?? '';
			?>
				<div class="col-6 col-md-4">
					<div class="block-cs-data__item">
						<?php if ($numero) : ?>
							<p class="block-cs-data__numero msans-3 msans-lg-display text-vanilla mb-0">
								<?php echo esc_html($numero); ?>
							</p>
						<?php endif; ?>
						<?php if ($texto) : ?>
							<p class="block-cs-data__texto fs-6 fs-md-5 mt-2 mb-0">
								<?php echo esc_html($texto); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>