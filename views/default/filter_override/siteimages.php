<?php

if (elgg_is_logged_in()) {
	$base = elgg_get_site_url() . 'photos/';

	$tabs = [
		'all' => [
			'title' => elgg_echo('all'),
			'url' => $base . 'siteimagesall',
			'selected' => $vars['selected'] == 'all',
		],
		'mine' => [
			'title' => elgg_echo('mine'),
			'url' => $base . 'siteimagesowner',
			'selected' => $vars['selected'] == 'mine',
		],
		'friends' => [
			'title' => elgg_echo('friends'),
			'url' => $base . 'siteimagesfriends',
			'selected' => $vars['selected'] == 'friends',
		],
	];

	echo elgg_view('navigation/tabs', ['tabs' => $tabs]);
}
