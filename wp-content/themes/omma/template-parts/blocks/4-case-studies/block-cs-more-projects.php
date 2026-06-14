<?php
$titulo = get_field( 'block_cs_mpr_titulo' );
$posts  = get_field( 'block_cs_mpr_posts' );
if ( ! $posts ) return;

$first_thumb = get_the_post_thumbnail_url( $posts[0], 'large' );
?>

<section class="block-cs-more-projects bg-charcoal py-5 py-lg-6">
  <div class="container">

    <?php if ( $titulo ) : ?>
      <p class="block-cs-more-projects__section-label h-6 text-vanilla mb-4 mb-lg-5">
        <?php echo esc_html( $titulo ); ?>
      </p>
    <?php endif; ?>

    <div class="block-cs-more-projects__list" data-more-projects>

      <?php if ( $first_thumb ) : ?>
        <div class="block-cs-more-projects__preview" aria-hidden="true">
          <div class="block-cs-more-projects__preview-inner">
            <img src="<?php echo esc_url( $first_thumb ); ?>" alt="" data-preview-img>
          </div>
        </div>
      <?php endif; ?>

      <?php foreach ( $posts as $post ) :
        setup_postdata( $post );
        $thumb    = get_the_post_thumbnail_url( $post, 'large' );
        $link     = get_permalink( $post );
        $title    = get_the_title( $post );
        $services = get_the_terms( $post->ID, 'servicios' );
        $svc_str  = '';
        if ( $services && ! is_wp_error( $services ) ) {
          $svc_str = implode( ' • ', wp_list_pluck( $services, 'name' ) );
        }
      ?>
        <a href="<?php echo esc_url( $link ); ?>"
           class="block-cs-more-projects__item"
           data-cs-thumb="<?php echo esc_url( $thumb ); ?>">

          <div class="block-cs-more-projects__item-name h-2 text-vanilla">
            <?php echo esc_html( $title ); ?>
          </div>

          <div class="block-cs-more-projects__item-center">
            <?php if ( $thumb ) : ?>
              <div class="block-cs-more-projects__item-thumb">
                <img src="<?php echo esc_url( $thumb ); ?>"
                     alt="<?php echo esc_attr( $title ); ?>">
              </div>
            <?php endif; ?>
          </div>

          <div class="block-cs-more-projects__item-services fs-5">
            <?php echo esc_html( $svc_str ); ?>
          </div>

        </a>
      <?php endforeach; wp_reset_postdata(); ?>

    </div>
  </div>
</section>
