<?php

/**
 * Block: Block Graph
 * Template: template-parts/blocks/0-landing/block-graph.php
 */

$nodes = $args['nodes'] ?? get_field('block_graph_nodes');

if (! $nodes || count($nodes) < 2) {
	return;
}

$last_i   = count($nodes) - 1;
$center_i = (int) floor($last_i / 2);
?>

<section class="block-graph" data-graph-section="">
	<div class="block-graph__track d-flex align-items-center">

		<?php foreach ($nodes as $i => $node) :
			$icon     = $node['block_graph_node_icon']     ?? null;
			$label    = $node['block_graph_node_label']    ?? '';
			$fwd_text = $node['block_graph_node_fwd_text'] ?? '';
			$bwd_text = $node['block_graph_node_bwd_text'] ?? '';
		?>

			<?php if ($i > 0) :
				$prev     = $nodes[$i - 1];
				$fwd_text = $prev['block_graph_node_fwd_text'] ?? '';
				$bwd_text = $prev['block_graph_node_bwd_text'] ?? '';
			?>
				<!-- ── Connector ──────────────────────────────────────────── -->
				<div class="block-graph__connector" data-graph-connector="" aria-hidden="true">

					<?php if ($fwd_text) : ?>
						<span class="block-graph__conn-label block-graph__conn-label--fwd" data-graph-label="">
							<?php echo esc_html($fwd_text); ?>
						</span>
					<?php endif; ?>

					<div class="block-graph__arrow block-graph__arrow--fwd d-flex align-items-center">
						<span class="block-graph__shaft"></span>
						<span class="block-graph__tip block-graph__tip--fwd"></span>
					</div>

					<div class="block-graph__arrow block-graph__arrow--bwd d-flex align-items-center">
						<span class="block-graph__tip block-graph__tip--bwd"></span>
						<span class="block-graph__shaft"></span>
					</div>

					<?php if ($bwd_text) : ?>
						<span class="block-graph__conn-label block-graph__conn-label--bwd" data-graph-label="">
							<?php echo esc_html($bwd_text); ?>
						</span>
					<?php endif; ?>

				</div>
			<?php endif; ?>

			<!-- ── Node ──────────────────────────────────────────────────── -->
			<div class="block-graph__node d-flex align-items-center gap-3 rounded-3"
				data-graph-node="">

				<?php if ($icon) : ?>
					<div class="block-graph__icon-wrap flex-shrink-0">
						<?php echo wp_get_attachment_image(
							$icon['ID'],
							[48, 48],
							false,
							[
								'class'   => 'block-graph__icon',
								'loading' => 'lazy',
								'alt'     => esc_attr($label),
							]
						); ?>
					</div>
				<?php endif; ?>

				<?php if ($label) : ?>
					<span class="block-graph__label text-vanilla text-uppercase">
						<?php echo esc_html($label); ?>
					</span>
				<?php endif; ?>

			</div>

		<?php endforeach; ?>

	</div>
</section>