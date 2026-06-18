<?php
$items = get_field( 'block_cs_img_items' );
if ( ! $items ) return;

$count = count( $items );
$col   = match ( $count ) {
	1       => 'col-12',
	2       => 'col-12 col-md-6',
	default => 'col-12 col-md-4',
};
?>

<section class="block-cs-images py-5">
  <div class="container">
    <div class="row g-3">
      <?php foreach ( $items as $i => $item ) :
        $img = $item['block_cs_img_image'] ?? null;
        if ( ! $img ) continue;
        $hide_mobile = ( $count > 1 && $i > 0 ) ? ' d-none d-md-block' : '';
      ?>
        <div class="<?php echo $col . $hide_mobile; ?>">
          <div class="block-cs-images__item">
            <img src="<?php echo esc_url( $img['url'] ); ?>"
                 alt="<?php echo esc_attr( $img['alt'] ); ?>"
                 width="<?php echo esc_attr( $img['width'] ); ?>"
                 height="<?php echo esc_attr( $img['height'] ); ?>">
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
