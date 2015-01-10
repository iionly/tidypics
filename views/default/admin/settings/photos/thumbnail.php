<?php
/**
 * Tidypics thumbnail creation tool
 */

elgg_load_js('tidypics:resize_thumbnails');

$title = elgg_echo('tidypics:settings:thumbnail');
$body = '<p>' . elgg_echo('tidypics:thumbnail_tool_blurb') . '</p>';
$im_id = elgg_echo('tidypics:settings:im_id');
$input = elgg_view('input/text', array(
	'name' => 'image_id'
));
$submit = elgg_view('input/submit', array(
	'value' => elgg_echo('submit'),
	'id' => 'elgg-tidypics-im-test'
));

$body .=<<<HTML
	<p>
		<label>$im_id $input</label>
	</p>
	<p>
		$submit
		<div id="elgg-tidypics-im-results"></div>
	</p>
HTML;

echo elgg_view_module('inline', $title, $body);


echo '<p class="mtm">';
echo "<label>" . elgg_echo('tidypics:settings:resize_thumbnails_label') . "</label><br>";
echo elgg_echo('tidypics:settings:resize_thumbnails_instructions');
echo '</p>';

echo elgg_view('output/url', array(
	'text' => elgg_echo('tidypics:settings:resize_thumbnails_start'),
	'href' => 'action/photos/admin/resize_thumbnails',
	'is_action' => true,
	'is_trusted' => true,
	'class' => 'elgg-button elgg-button-submit',
	'id' => 'tidypics-resize-thumbnails',
));

echo elgg_view('graphics/ajax_loader', array(
	'id' => 'tidypics-resize-thumbnails-ajax-spinner',
	'class' => 'elgg-content-thin',
));
?>

<div id="tidypics-resize-thumbnails-results" class="mtm"></div>

<script type="text/javascript">
	$(function() {
		$('#elgg-tidypics-im-test').click(function() {
			var image_id = $('input[name=image_id]').val();
			$("#elgg-tidypics-im-results").html('<div class="elgg-ajax-loader"></div>');
			elgg.action('photos/admin/create_thumbnails', {
				format: 'JSON',
				data: {guid: image_id},
				cache: false,
				success: function(result) {
					// error
					if (result.status < 0) {
						var html = '';
					} else {
						var html = '<img class="elgg-photo tidypics-photo" src="'
							+ result.output.thumbnail_src + '" alt="' + result.output.title
							+ '" />';
					}
					$("#elgg-tidypics-im-results").html(html);
				}
			});
		});
	});
</script>