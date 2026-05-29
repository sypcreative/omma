<?php

/**
 * Block: Block Intro
 * Template: template-parts/blocks/0-landing/block-intro.php
 */

$text = get_field('block_intro_text');

if (! $text) {
	return;
}
?>

<section class="block-intro py-5 my-5" id="intro" data-progress-nav-anchor="">
	<div class=" container py-5 mb-5">
		<div class="row">
			<div class="col-12 col-lg-10 col-xl-8"
				data-parallax="trigger"
				data-parallax-start="6"
				data-parallax-end="-6"
				data-parallax-disable="mobile">
				<div class="block-intro__text text-white fs-3"
					data-anim="lines"
					data-anim-stagger="0.1">
					<?php echo wp_kses_post($text); ?>
				</div>
			</div>
		</div>
	</div>
</section>