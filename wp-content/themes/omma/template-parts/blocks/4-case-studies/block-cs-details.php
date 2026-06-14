<?php
$titulo = get_field( 'block_cs_dtl_titulo' );
$items  = get_field( 'block_cs_dtl_items' );
?>

<section class="block-cs-details bg-charcoal py-5 py-lg-6">
  <div class="container">
    <div class="row">

      <div class="col-12 col-lg-4 mb-5 mb-lg-0">
        <?php if ( $titulo ) : ?>
          <h2 class="h-3 text-vanilla"><?php echo esc_html( $titulo ); ?></h2>
        <?php endif; ?>
      </div>

      <div class="col-12 col-lg-8">
        <?php if ( $items ) : ?>
          <?php foreach ( $items as $item ) :
            $item_titulo = $item['block_cs_dtl_item_titulo'] ?? '';
            $item_desc   = $item['block_cs_dtl_item_desc']   ?? '';
          ?>
            <div class="block-cs-details__item row py-4">
              <div class="col-12 col-md-4 mb-2 mb-md-0">
                <?php if ( $item_titulo ) : ?>
                  <p class="block-cs-details__item-titulo h-6 text-vanilla mb-0">
                    <?php echo esc_html( $item_titulo ); ?>
                  </p>
                <?php endif; ?>
              </div>
              <div class="col-12 col-md-8">
                <?php if ( $item_desc ) : ?>
                  <p class="block-cs-details__item-desc fs-5 mb-0">
                    <?php echo nl2br( esc_html( $item_desc ) ); ?>
                  </p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
          <div class="block-cs-details__border-bottom"></div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>
