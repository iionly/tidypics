<?php
/**
 * Post comment on image river view
 */

$item = $vars['item'];

$comment = $item->getObjectEntity();
$subject = $item->getSubjectEntity();
$target = $item->getTargetEntity();

$subject_link = elgg_view('output/url', array(
        'href' => $subject->getURL(),
        'text' => $subject->name,
        'class' => 'elgg-river-subject',
        'is_trusted' => true,
));

$target_link = elgg_view('output/url', array(
        'href' => $target->getURL(),
        'text' => $target->getDisplayName(),
        'class' => 'elgg-river-target',
        'is_trusted' => true,
));

$river_comments_thumbnails = elgg_get_plugin_setting('river_comments_thumbnails', 'tidypics');
if ($river_comments_thumbnails == "small") {
        $image = $target;
        $attachments = elgg_view_entity_icon($image, 'small');
}
else if ($river_comments_thumbnails == "tiny") {
        $image = $target;
        $attachments = elgg_view_entity_icon($image, 'tiny');
}

$summary = elgg_echo('river:comment:object:image', array($subject_link, $target_link));

echo elgg_view('river/elements/layout', array(
        'item' => $vars['item'],
        'attachments' => $attachments,
        'message' => elgg_get_excerpt($comment->description),
        'summary' => $summary,
));
