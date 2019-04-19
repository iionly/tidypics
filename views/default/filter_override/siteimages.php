<?php

if (elgg_is_logged_in()) {
	$base = elgg_get_site_url() . 'photos/';

	$tabs = [
		'all' => [
			'text' => elgg_echo('all'),
			'href' => $base . 'siteimagesall',
			'selected' => $vars['selected'] == 'all',
		],
		'mine' => [
			'text' => elgg_echo('mine'),
			'href' => $base . 'siteimagesowner',
			'selected' => $vars['selected'] == 'mine',
		],
		'friends' => [
			'text' => elgg_echo('friends'),
			'href' => $base . 'siteimagesfriends',
			'selected' => $vars['selected'] == 'friends',
		],
	];

	echo elgg_view('navigation/tabs', ['tabs' => $tabs]);
}
