<?php
/**
 * Re-create the thumbnails of all images
 */

elgg_require_js('tidypics/resize_thumbnails');

/* @var $count Integer */
$count = elgg_extract('count', $vars);

echo elgg_format_element('span', ['class' => 'hidden', 'id' => 'tidypics-resizethumbnails-total'], $count);
echo elgg_format_element('span', ['class' => 'hidden', 'id' => 'tidypics-resizethumbnails-count'], '0');
echo elgg_format_element('div', ['class' => 'elgg-progressbar mvl'],
	elgg_format_element('span', ['class' => 'elgg-progressbar-counter', 'id' => 'tidypics-resizethumbnails-counter'], '0%')
);
echo elgg_format_element('ul', ['class' => 'mvl'],
	elgg_format_element('li', [], elgg_echo('tidypics:resize_thumbnails:success_processed')
		. elgg_format_element('span', ['id' => 'tidypics-resizethumbnails-success-count'], '0'))
	. elgg_format_element('li', [], elgg_echo('tidypics:resize_thumbnails:error_invalid_image_info')
		. elgg_format_element('span', ['id' => 'tidypics-resizethumbnails-error-invalid-image-count'], '0'))
	. elgg_format_element('li', [], elgg_echo('tidypics:resize_thumbnails:error_recreate_failed')
		. elgg_format_element('span', ['id' => 'tidypics-resizethumbnails-error-recreate-failed-count'], '0'))
);
echo elgg_format_element('div', ['class' => 'elgg-ajax-loader hidden', 'id' => 'tidypics-resizethumbnails-spinner'], '');
echo elgg_format_element('ul', ['class' => 'mvl', 'id' => 'tidypics-resizethumbnails-messages'], '');

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('tidypics:settings:resize_thumbnails_start'),
	'id' => 'tidypics-resizethumbnails-run',
]);

elgg_set_form_footer($footer);
