<?php
$query = new WP_Query([
	'post_type'      => ['news', 'podcast'],
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
]);

if (! $query->have_posts()) return;

// Detectar qué tipos tienen contenido
$has_news    = false;
$has_podcast = false;
foreach ($query->posts as $p) {
	if ($p->post_type === 'news')    $has_news    = true;
	if ($p->post_type === 'podcast') $has_podcast = true;
}

$show_filter  = $has_news && $has_podcast;
$default_type = $has_news ? 'news' : 'podcast';
?>

<section class="block-news-grid bg-charcoal py-5 py-lg-6">
	<div class="container">

		<?php if ($show_filter) : ?>
			<div class="block-news-filter mb-5" role="tablist" aria-label="Filtrar contenido">
				<button class="block-news-filter__btn btn-omma-light border-0" role="tab" aria-selected="true" data-filter="news" data-anim="fade-up">
					<span class="button-020__inner">
						<span class="button-020__default">
							<span class="button-020__default-bg"></span>
							<span class="button-020__default-text">News</span>
						</span>
					</span>
				</button>
				<button class="block-news-filter__btn btn-omma-outline-dark border-0" role="tab" aria-selected="false" data-filter="podcast" data-anim="fade-up" data-anim-delay="0.1">
					<span class="button-020__inner">
						<span class="button-020__default">
							<span class="button-020__default-bg"></span>
							<span class="button-020__default-text">Podcast</span>
						</span>
					</span>
				</button>
			</div>
		<?php endif; ?>

		<div class="row g-4 g-lg-5" data-news-default-type="<?php echo esc_attr($default_type); ?>">

			<?php while ($query->have_posts()) : $query->the_post();
				$post_type = get_post_type();
				$title     = get_the_title();
				$date      = get_the_date('d M Y');

				if ($post_type === 'podcast') {
					$yt_url    = get_post_meta(get_the_ID(), 'podcast_youtube_url', true) ?: '';
					$yt_id     = omma_youtube_id($yt_url);
					$link      = $yt_url ?: '#';
					$thumb_url = $yt_id ? "https://img.youtube.com/vi/{$yt_id}/maxresdefault.jpg" : '';
				} else {
					$ext_url   = get_post_meta(get_the_ID(), 'news_external_url', true) ?: '';
					$link      = $ext_url ?: '#';
					$thumb_url = '';
				}
			?>
				<div class="col-12 col-md-6 block-news-grid__item" data-filter-type="<?php echo esc_attr($post_type); ?>">
					<a href="<?php echo esc_url($link); ?>" class="block-news-grid__card"
						<?php if (($post_type === 'podcast' && $yt_url) || ($post_type === 'news' && $ext_url)) : ?>
						target="_blank" rel="noopener noreferrer"
						<?php endif; ?>>

						<?php if ($post_type === 'podcast') : ?>
							<?php if ($thumb_url) : ?>
								<div class="block-news-grid__thumb">
									<img src="<?php echo esc_url($thumb_url); ?>"
										alt="<?php echo esc_attr($title); ?>"
										loading="lazy" decoding="async">
								</div>
							<?php endif; ?>
							<div class="block-news-grid__info mt-3">
								<h2 class="block-news-grid__title fs-6 fs-md-5 text-vanilla mb-0">
									<?php echo esc_html($title); ?>
								</h2>
							</div>
						<?php else : ?>

							<?php if (has_post_thumbnail()) : ?>
								<div class="block-news-grid__thumb">
									<?php the_post_thumbnail('large', ['alt' => esc_attr($title)]); ?>
								</div>
							<?php endif; ?>

							<div class="block-news-grid__info d-flex flex-column flex-md-row justify-content-between align-items-baseline gap-2 gap-md-3 mt-3">
								<h2 class="block-news-grid__title fs-6 fs-md-5 text-vanilla mb-0">
									<?php echo esc_html($title); ?>
								</h2>
								<p class="block-news-grid__date fs-6 mb-0 flex-shrink-0">
									<?php echo esc_html($date); ?>
								</p>
							</div>

						<?php endif; ?>

					</a>
				</div>

			<?php endwhile;
			wp_reset_postdata(); ?>
		</div>

	</div>
</section>