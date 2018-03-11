<?php
/**
 * Instructions on how to peform photo tagging
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$button = elgg_view('output/url', [
	'text' => elgg_echo('tidypics:quit'),
	'href' => '#',
	'id' => 'tidypics-tagging-quit',
]);

$instructions = elgg_echo('tidypics:taginstruct', [$button]);

echo elgg_format_element('div', [
	'class' => 'elgg-module elgg-module-popup tidypics-tagging-help pam hidden',
	'id' => 'tidypics-tagging-help'], $instructions
);
