define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');

	// manage Spinner manually
	var ajax = new Ajax(false);

	function init() {
		var fields = ['Elgg', 'user_guid', 'album_guid', 'batch', 'tidypics_token'];
		var data = elgg.security.token;

		$(fields).each(function(i, name) {
			var value = $('input[name=' + name + ']').val();
			if (value) {
				data[name] = value;
			}
		});
		
		var maxfilesize = $("#uploader").data('maxfilesize');
		var maxfiles = $("#uploader").data('maxnumber');
		
		var client_resizing = $("#uploader").data('client-resizing');

		if (client_resizing == true) {
			var client_width = $("#uploader").data('client-width');
			var client_height = $("#uploader").data('client-height');
			var remove_exif = $("#uploader").data('remove-exif');
			var preserve_exif = !remove_exif;

			var resizing_parameters = {
					width: client_width,
					height: client_height,
					quality: 100,
					preserve_headers: preserve_exif
				};
		} else {
			var resizing_parameters = false;
		}

		$("#uploader").plupload({
			// General settings
			runtimes : 'html5,html4',
			url : elgg.get_site_url() + 'action/photos/image/ajax_upload',
			file_data_name : 'Image',

			dragdrop: true,
			sortable: true,
			multipart_params : data,
			max_file_size : maxfilesize + 'mb',

			filters : [
				{title : elgg.echo('tidypics:uploader:filetype'), extensions : "jpg,jpeg,gif,png"}
			],

			// Views to activate
			views: {
				list: true,
				thumbs: true,
				active: 'thumbs'
			},
			
			resize : resizing_parameters,

			init : {
				UploadComplete: function(up, files) {
					// Called when all files are either uploaded or failed
					ajax.action('photos/image/ajax_upload_complete', {
						data: {
							album_guid: data.album_guid,
							batch: data.batch
						}
					}).done(function(json, status, jqXHR) {
						if (jqXHR.AjaxData.status == -1) {
							if (!json.error.message.length) {
								window.location.href = elgg.normalize_url('photos/siteimagesall');
							} else {
								location.reload();
							}
							return;
						}
						var url = elgg.normalize_url('photos/edit/' + json.batch_guid);
						window.location.href = url;
						return;
					});
				},

				FilesAdded: function(up, files) {
					if(up.files.length > maxfiles ) {
						alert(elgg.echo('tidypics:exceedmax_number', [maxfiles]));
					}
					if(up.files.length > maxfiles ) {
						up.splice(maxfiles);
					}
					if (up.files.length >= maxfiles) {
						up.disableBrowse(true);
					}
				},

				FilesRemoved: function(up, files) {
					if (up.files.length < maxfiles) {
						up.disableBrowse(false);
					}
				},

				FileUploaded: function(up, file, info) {
					var response = jQuery.parseJSON(info.response);
					if (response.error.message.length) {
						elgg.register_error(response.error.message);
						up.stop();
						return;
					}
				}
			}
		});
	}

	init();
});
