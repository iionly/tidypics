define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');

	// manage Spinner manually
	var ajax = new Ajax(false);
	
	$(document).ready(function() {
		// broken images check
		$(document).on('submit', '.elgg-form-photos-admin-broken-images', function(e) {
			var $form = $(this);
			$form.hide();
			var time = $.now();

			$("#elgg-tidypics-broken-images-results").html('<div><div class="elgg-ajax-loader"></div><div id="broken-image-log"></div><div>');

			ajax.action($form.prop('action'), {
				timeout: 30000000,
				data: {
					delete: 0,
					time: time,
				}
			}).done(function(json, status, jqXHR) {
				if (jqXHR.AjaxData.status == -1) {
					console.log(status);
					return;
				}

				if (json && (typeof json === 'string')) {
					console.log(json);
				}
			});

			window.setTimeout(function() {
				refresh_deleteimage_log(time);
			}, 5000);

			e.preventDefault();
		});
		

		// broken images delete
		$(document).on('click', '#elgg-tidypics-broken-images-delete', function() {
			if (!confirm(elgg.echo('question:areyousure'))) {
				return false;
			}

			var time = $("#elgg-tidypics-broken-images-delete").data('time');

			$("#elgg-tidypics-broken-images-results").html('<div><div class="elgg-ajax-loader"></div><div id="broken-image-log"></div><div>');

			ajax.action('photos/admin/broken_images', {
				timeout: 30000000,
				data: {
					delete: 1,
					time: time,
				}
			}).done(function(json, status, jqXHR) {
				if (jqXHR.AjaxData.status == -1) {
					console.log(status);
					return;
				}

				if (json && (typeof json === 'string')) {
					console.log(json);
				}
			});

			window.setTimeout(function() {
				refresh_deleteimage_log(time);
			}, 15000);
		});

		function refresh_deleteimage_log(time) {

			ajax.view('photos/broken_images_delete_log', {
				data: {
					time: time
				}
			}).done(function (output, statusText, jqXHR) {
				if (jqXHR.AjaxData.status == -1) {
					return;
				}

				if ($('#elgg-tidypics-broken-images-results div.done').length) {
					return; // all done!
				}

				if (output) {
					$('#elgg-tidypics-broken-images-results').html(output);
				}

				window.setTimeout(function() {
					refresh_deleteimage_log(time);
				}, 5000);
			});
		}
	});
});
