<?php
$title = get_field( 'block_cs_pg_title' );
$desc  = get_field( 'block_cs_pg_desc' );
?>

<section class="block-cs-page-header bg-charcoal pb-5 pb-lg-6">
  <div class="container">

    <?php if ( $title ) : ?>
      <div class="row">
        <div class="col-12">
          <h1 class="block-cs-page-header__title h-3 h-lg-1 text-vanilla">
            <?php echo esc_html( $title ); ?>
          </h1>
        </div>
      </div>
    <?php endif; ?>

    <?php if ( $desc ) : ?>
      <div class="row mt-3 mt-lg-4">
        <div class="col-12 col-lg-6">
          <div class="block-cs-page-header__desc fs-5 text-vanilla">
            <?php echo nl2br( esc_html( $desc ) ); ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>
