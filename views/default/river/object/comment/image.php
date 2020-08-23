<?php
/**
 * Post comment on image river view
 */

elgg_require_js('tidypics/tidypics');

$item = elgg_extract('item', $vars);
if (!($item instanceof ElggRiverItem)) {
	return;
}

$comment = $item->getObjectEntity();
if (!($comment instanceof ElggComment)) {
	return;
}

$image = $item->getTargetEntity();
if (!($image instanceof TidypicsImage)) {
	return;
}

$subject = $item->getSubjectEntity();
if (!($subject instanceof ElggUser)) {
	return;
}

$subject_link = elgg_view('output/url', [
	'href' => $subject->getURL(),
	'text' => $subject->name,
	'class' => 'elgg-river-subject',
	'is_trusted' => true,
]);

$target_link = elgg_view('output/url', [
	'href' => $image->getURL(),
	'text' => $image->getDisplayName(),
	'class' => 'elgg-river-target',
	'is_trusted' => true,
]);

$vars['summary'] = elgg_echo('river:object:comment:image', [$subject_link, $target_link]);

$vars['message'] = elgg_get_excerpt($comment->description);

$river_comments_thumbnails = elgg_get_plugin_setting('river_comments_thumbnails', 'tidypics');
if ($river_comments_thumbnails == "show") {
	$preview_size = elgg_get_plugin_setting('river_thumbnails_size', 'tidypics', 'tiny');

	$vars['attachments'] = elgg_format_element('ul', ['class' => 'tidypics-river-list'], 
		elgg_format_element('li', ['class' => 'tidypics-photo-item'], elgg_view_entity_icon($image, $preview_size, [
			'href' => 'ajax/view/photos/riverpopup?guid=' . $image->getGUID(),
			'title' => $image->title,
			'img_class' => 'tidypics-photo',
			'link_class' => 'tidypics-river-lightbox',
		]))
	);
}

echo elgg_view('river/elements/layout', $vars);
