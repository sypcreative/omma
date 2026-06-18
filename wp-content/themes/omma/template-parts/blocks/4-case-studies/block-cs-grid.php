<?php
$query = new WP_Query([
	'post_type'      => 'case-studies',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'post_status'    => 'publish',
]);

if (! $query->have_posts()) return;

$cs_links_enabled = (bool) get_field('cs_links_enabled', 'option');
?>

<section class="block-cs-grid bg-charcoal py-5 py-lg-6">
	<div class="container">
		<div class="row g-4 g-lg-5">

			<?php while ($query->have_posts()) : $query->the_post();
				$link     = get_permalink();
				$title    = get_the_title();
				$thumb    = get_the_post_thumbnail_url(get_the_ID(), 'large');
				$clientes      = get_the_terms(get_the_ID(), 'cliente');
				$servicios     = get_the_terms(get_the_ID(), 'servicios');
				$cliente_str   = ($clientes  && ! is_wp_error($clientes))  ? $clientes[0]->name  : '';
				$servicio_str  = ($servicios && ! is_wp_error($servicios)) ? $servicios[0]->name : '';
			?>
				<div class="col-12 col-sm-6 col-lg-4">
					<?php if ($cs_links_enabled) : ?>
					<a href="<?php echo esc_url($link); ?>" class="block-cs-grid__card">
					<?php else : ?>
					<div class="block-cs-grid__card">
					<?php endif; ?>

						<?php if ($thumb) : ?>
							<div class="block-cs-grid__thumb">
								<img src="<?php echo esc_url($thumb); ?>"
									alt="<?php echo esc_attr($title); ?>"
									loading="lazy"
									decoding="async">
							</div>
						<?php endif; ?>

						<div class="block-cs-grid__info mt-3 d-flex flex-row justify-content-between align-items-start">
							<div>
								<h3 class="block-cs-grid__title fs-5 text-vanilla mb-0">
									<?php echo esc_html($title); ?>
								</h3>
								<?php if ($cliente_str) : ?>
									<p class="block-cs-grid__client fs-6 mb-0">
										(<?php echo esc_html($cliente_str); ?>)
									</p>
								<?php endif; ?>
							</div>
							<?php if ($servicio_str) : ?>
								<p class="block-cs-grid__services fs-6 mb-0 ms-3 flex-shrink-0">
									<?php echo esc_html($servicio_str); ?>
								</p>
							<?php endif; ?>
						</div>

					<?php echo $cs_links_enabled ? '</a>' : '</div>'; ?>
				</div>

			<?php endwhile;
			wp_reset_postdata(); ?>
		</div>
	</div>
</section>