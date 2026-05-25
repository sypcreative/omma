<?php

/**
 * Columnas personalizadas en el listado de CPTs del admin.
 *
 * @package omma
 */

// ── PROYECTOS ────────────────────────────────────────────────────────────────

add_filter('manage_proyectos_posts_columns', function (array $columns): array {
	$new = [];
	foreach ($columns as $key => $label) {
		if ($key === 'title') {
			$new['thumbnail'] = __('Imagen', 'omma');
		}
		$new[$key] = $label;
	}
	$new['menu_order'] = __('Orden', 'omma');
	unset($new['date']);
	return $new;
});

add_action('manage_proyectos_posts_custom_column', function (string $column, int $post_id): void {
	if ($column === 'thumbnail') {
		$thumb = get_the_post_thumbnail($post_id, [60, 60]);
		echo $thumb ?: '<span style="color:#aaa">—</span>';
	}
	if ($column === 'menu_order') {
		echo (int) get_post_field('menu_order', $post_id);
	}
}, 10, 2);

add_filter('manage_edit-proyectos_sortable_columns', function (array $columns): array {
	$columns['menu_order'] = 'menu_order';
	return $columns;
});

add_action('pre_get_posts', function (WP_Query $query): void {
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}
	if ($query->get('post_type') !== 'proyectos') {
		return;
	}
	if ($query->get('orderby') === 'menu_order') {
		$query->set('order', 'ASC');
	}
});

add_action('admin_head', function (): void {
	global $post_type;
	if ($post_type !== 'proyectos') {
		return;
	}
	echo '<style>
		.column-thumbnail { width: 70px; }
		.column-thumbnail img { border-radius: 4px; object-fit: cover; }
		.column-menu_order { width: 60px; text-align: center; }
	</style>';
});
