<?php
$post_id  = get_the_ID();
$title    = get_the_title();
$excerpt  = get_the_excerpt();
$sectors  = get_the_terms( $post_id, 'sector' );
$services = get_the_terms( $post_id, 'servicios' );
?>

<section class="block-cs-header bg-charcoal pb-5 pb-lg-6">
  <div class="container">

    <div class="row mb-5">
      <div class="col-12">
        <?php if ( $title ) : ?>
          <h1 class="h-1 text-vanilla mb-3"><?php echo esc_html( $title ); ?></h1>
        <?php endif; ?>
        <?php if ( $excerpt ) : ?>
          <p class="block-cs-header__excerpt fs-5"><?php echo esc_html( $excerpt ); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="block-cs-header__meta">

      <?php if ( $sectors && ! is_wp_error( $sectors ) ) : ?>
        <div class="block-cs-header__sector">
          <p class="h-6 text-vanilla mb-2"><?php esc_html_e( 'Sector', 'omma' ); ?></p>
          <p class="block-cs-header__desc fs-5 mb-0">
            <?php echo esc_html( implode( ', ', wp_list_pluck( $sectors, 'name' ) ) ); ?>
          </p>
        </div>
      <?php endif; ?>

      <?php if ( $services && ! is_wp_error( $services ) ) : ?>
        <div class="block-cs-header__services">
          <p class="h-6 text-vanilla mb-2"><?php esc_html_e( 'Services Provided', 'omma' ); ?></p>
          <?php foreach ( $services as $service ) : ?>
            <p class="block-cs-header__desc fs-5 mb-0">
              <?php echo esc_html( $service->name ); ?>
            </p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>

  </div>
</section>
