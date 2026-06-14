<?php
$query = new WP_Query( [
  'post_type'      => 'case-studies',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'post_status'    => 'publish',
] );

if ( ! $query->have_posts() ) return;
?>

<section class="block-cs-grid bg-charcoal py-5 py-lg-6">
  <div class="container">
    <div class="row g-4 g-lg-5">

      <?php while ( $query->have_posts() ) : $query->the_post();
        $link     = get_permalink();
        $title    = get_the_title();
        $thumb    = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        $services = get_the_terms( get_the_ID(), 'servicios' );
        $svc_str  = '';
        if ( $services && ! is_wp_error( $services ) ) {
          $svc_str = implode( ' · ', wp_list_pluck( $services, 'name' ) );
        }
      ?>
        <div class="col-12 col-md-6">
          <a href="<?php echo esc_url( $link ); ?>" class="block-cs-grid__card">

            <?php if ( $thumb ) : ?>
              <div class="block-cs-grid__thumb">
                <img src="<?php echo esc_url( $thumb ); ?>"
                     alt="<?php echo esc_attr( $title ); ?>">
              </div>
            <?php endif; ?>

            <div class="block-cs-grid__info mt-3">
              <?php if ( $svc_str ) : ?>
                <p class="block-cs-grid__services fs-5 mb-2">
                  <?php echo esc_html( $svc_str ); ?>
                </p>
              <?php endif; ?>
              <h2 class="block-cs-grid__title h-3 text-vanilla mb-0">
                <?php echo esc_html( $title ); ?>
              </h2>
            </div>

          </a>
        </div>

      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
