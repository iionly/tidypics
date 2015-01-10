<?php
/**
 * Tidypics Batch Thumbnail Re-sizing  JavaScript
 */
?>

elgg.provide('elgg.tidypics.resizethumbnails');

elgg.tidypics.resizethumbnails.init = function() {
	$("#tidypics-resize-thumbnails").click(elgg.tidypics.resizethumbnails.submit);
};

elgg.tidypics.resizethumbnails.submit = function(event) {

	$("#tidypics-resize-thumbnails-ajax-spinner").show();
	$("#tidypics-resize-thumbnails-results").html('');

	$.ajax({
		type: "GET",
		url: $(this).attr('href'),
		dataType: "html",
		success: function(htmlData) {
			$("#tidypics-resize-thumbnails-ajax-spinner").hide();

			if (htmlData.length > 0) {
				$("#tidypics-resize-thumbnails-results").html(htmlData);
			}
		}
	});

	event.preventDefault();
};

elgg.register_hook_handler('init', 'system', elgg.tidypics.resizethumbnails.init);
