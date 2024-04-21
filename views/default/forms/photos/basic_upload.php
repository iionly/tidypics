<?php
/**
 * Basic uploader form
 *
 * This only handled uploading the images. Editing the titles and descriptions
 * are handled with the edit forms.
 *
 * @uses $vars['entity']
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$album = elgg_extract('entity', $vars);

$max_uploads = (int) elgg_get_plugin_setting('max_uploads', 'tidypics');
$maxfilesize = (float) elgg_get_plugin_setting('maxfilesize', 'tidypics');

echo elgg_autop(elgg_echo('tidypics:uploader:basic', [$max_uploads, $maxfilesize]));

$list = '';
for ($x = 0; $x < $max_uploads; $x++) {
	$list .= elgg_format_element('li', [], elgg_view_field([
		'#type' => 'file',
		'name' => 'images[]',
		'required' => (($x == 0) ? true : false),
	]));
}
$list = elgg_format_element('ol', [], $list);

echo elgg_format_element('div', [], $list);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $album->getGUID(),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo("photos:addphotos"),
]);

elgg_set_form_footer($footer);
