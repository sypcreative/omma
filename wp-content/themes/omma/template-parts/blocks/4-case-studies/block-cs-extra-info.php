<?php
$titulo = get_field( 'block_cs_ext_titulo' );
$desc   = get_field( 'block_cs_ext_desc' );
?>

<section class="block-cs-extra-info bg-charcoal py-5 py-lg-6">
  <div class="container">
    <div class="row align-items-start">

      <div class="col-12 col-lg-4 mb-4 mb-lg-0">
        <?php if ( $titulo ) : ?>
          <h2 class="h-3 text-vanilla mb-0"><?php echo esc_html( $titulo ); ?></h2>
        <?php endif; ?>
      </div>

      <div class="col-12 col-lg-8">
        <?php if ( $desc ) : ?>
          <p class="block-cs-extra-info__desc fs-5 text-vanilla mb-0">
            <?php echo nl2br( esc_html( $desc ) ); ?>
          </p>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>
