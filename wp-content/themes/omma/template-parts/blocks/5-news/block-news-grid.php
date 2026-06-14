<?php
$query = new WP_Query( [
  'post_type'      => 'news',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'post_status'    => 'publish',
] );

if ( ! $query->have_posts() ) return;
?>

<section class="block-news-grid bg-charcoal py-5 py-lg-6">
  <div class="container">
    <div class="row g-4 g-lg-5">

      <?php while ( $query->have_posts() ) : $query->the_post();
        $link  = get_permalink();
        $title = get_the_title();
        $date  = get_the_date( 'd M Y' );
      ?>
        <div class="col-12 col-md-6">
          <a href="<?php echo esc_url( $link ); ?>" class="block-news-grid__card">

            <?php if ( has_post_thumbnail() ) : ?>
              <div class="block-news-grid__thumb">
                <?php the_post_thumbnail( 'large', [ 'alt' => esc_attr( $title ) ] ); ?>
              </div>
            <?php endif; ?>

            <div class="block-news-grid__info mt-3">
              <p class="block-news-grid__date fs-5 mb-2">
                <?php echo esc_html( $date ); ?>
              </p>
              <h2 class="block-news-grid__title h-3 text-vanilla mb-0">
                <?php echo esc_html( $title ); ?>
              </h2>
            </div>

          </a>
        </div>

      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
