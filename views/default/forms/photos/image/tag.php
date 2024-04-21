<?php
/**
 * Tag select form body
 *
 * @uses $vars['entity']
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

echo elgg_view_field([
	'#type' => 'autocomplete',
	'name' => 'username',
	'match_on' => 'users',
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $vars['entity']->getGUID(),
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'coordinates',
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('tidypics:actiontag'),
]);

elgg_set_form_footer($footer);
