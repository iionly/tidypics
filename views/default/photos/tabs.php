<?php

$selected_tab = elgg_extract('tab', $vars);

$base_url = 'admin/settings/photos';

$tabs = [
	'settings' => [
		'href' => 'admin/plugin_settings/tidypics',
	],
	'thumbnail' => [],
	'delete_image' => [],
	'imtest' => [],
	'server_info' => [],
	'server_config' => [],
	'help' => [],
];

$params = [
	'tabs' => [],
];

foreach ($tabs as $tab => $tab_settings) {

	$href = elgg_extract('href', $tab_settings);
	if (empty($href)) {
		$href = elgg_http_add_url_query_elements($base_url, [
			'tab' => $tab,
		]);
	}

	$params['tabs'][] = [
		'text' => elgg_echo("tidypics:{$tab}"),
		'href' => $href,
		'selected' => ($tab === $selected_tab),
	];
}

echo elgg_view('navigation/tabs', $params);
