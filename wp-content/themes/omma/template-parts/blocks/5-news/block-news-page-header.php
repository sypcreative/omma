<?php
$titulo = get_field('block_news_pg_title');
$desc   = get_field('block_news_pg_desc');
?>

<section class="block-news-page-header bg-charcoal pb-5 pb-lg-6">
  <div class="container">
    <div class="row">
      <?php if ( $titulo ) : ?>
        <div class="col-12">
          <h1 class="h-1 text-vanilla mb-0">
            <?php echo esc_html( $titulo ); ?>
          </h1>
        </div>
      <?php endif; ?>
      <?php if ( $desc ) : ?>
        <div class="col-12 col-lg-6 mt-4">
          <p class="block-news-page-header__desc fs-5 mb-0">
            <?php echo wp_kses_post( $desc ); ?>
          </p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
