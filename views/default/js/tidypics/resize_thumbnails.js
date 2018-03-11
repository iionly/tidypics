define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');

	// manage Spinner manually
	var ajax = new Ajax(false);

	$(document).on('submit', '.elgg-form-photos-admin-resize-thumbnails', function(e) {
		e.preventDefault();

		var $form = $(this);
		var action = $form.prop('action');

		// The total amount of images to be processed
		var total = $('#tidypics-resizethumbnails-total').text();

		// Initialize progressbar
		$('.elgg-progressbar').progressbar( {
			value: 0,
			max: total
		});

		// Replace button with spinner when processing starts
		$('#tidypics-resizethumbnails-run').addClass('hidden');
		$('#tidypics-resizethumbnails-spinner').removeClass('hidden');

		// Start processing from offset 0
		upgradeBatch(0, action);
	});

	function upgradeBatch(offset, action) {
		ajax.action(action, {
			data: {
				offset: offset,
				elgg_fetch_messages: 0
			}
		}).done(function(json, status, jqXHR) {
			if (jqXHR.AjaxData.status == -1) {
				$('#tidypics-resizethumbnails-spinner').addClass('hidden');
				location.reload();
				return;
			}

			// Increase success statistics
			var numSuccess = $('#tidypics-resizethumbnails-success-count');
			var successCount = parseInt(numSuccess.text()) + json.numSuccess;
			numSuccess.text(successCount);

			// Increase error statistics
			var numErrorsInvalidImage = $('#tidypics-resizethumbnails-error-invalid-image-count');
			var errorCountInvalidImage = parseInt(numErrorsInvalidImage.text()) + json.numErrorsInvalidImage;
			numErrorsInvalidImage.text(errorCountInvalidImage);

			var numErrorsRecreateFailed = $('#tidypics-resizethumbnails-error-recreate-failed-count');
			var errorCountRecreateFailed = parseInt(numErrorsRecreateFailed.text()) + json.numErrorsRecreateFailed;
			numErrorsRecreateFailed.text(errorCountRecreateFailed);

			var errorCount = errorCountInvalidImage + errorCountRecreateFailed;

			// Increase total amount of processed images
			var numProcessed = successCount + errorCount;
			$('#tidypics-resizethumbnails-count').text(numProcessed);

			// Increase the progress bar
			$('.elgg-progressbar').progressbar({ value: numProcessed });
			var total = $('#tidypics-resizethumbnails-total').text();

			if (numProcessed < total) {
				var percent = parseInt(numProcessed * 100 / total);

				/**
				* Start next upgrade call. Offset is the total amount of images processed so far.
				*/
				upgradeBatch(numProcessed, action);
			} else {
				$('#tidypics-resizethumbnails-spinner').addClass('hidden');
				percent = '100';

				if (errorCount > 0) {
					// Upgrade finished with errors. Give instructions on how to proceed.
					elgg.register_error(elgg.echo('tidypics:resize_thumbnails:finished_with_errors', [errorCountInvalidImage, errorCountRecreateFailed]));
				} else {
					// Upgrade is finished. Make one more call to mark it complete.
					elgg.system_message(elgg.echo('tidypics:resize_thumbnails:finished', [successCount]));
				}
			}

			// Increase percentage
			$('#tidypics-resizethumbnails-counter').text(percent + '%');
		});
		
		return;
	}
});
