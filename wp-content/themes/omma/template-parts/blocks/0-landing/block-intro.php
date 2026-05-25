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

<section class="block-intro">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-10 col-xl-8">
				<div class="block-intro__text text-white fs-3">
					<?php echo wp_kses_post($text); ?>
				</div>
			</div>
		</div>
	</div>
</section>