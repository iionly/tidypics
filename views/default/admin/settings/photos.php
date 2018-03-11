<?php

$tab = get_input('tab', 'thumbnail');

echo elgg_view('photos/tabs', [
	'tab' => $tab,
]);

if (elgg_view_exists("photos/admin/{$tab}")) {
	echo elgg_view("photos/admin/{$tab}");
}
