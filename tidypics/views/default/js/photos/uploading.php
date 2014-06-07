<?php
/**
 * AJAX uploading
 */
$maxfilesize = (int) elgg_get_plugin_setting('maxfilesize', 'tidypics');
$max_files = 10;
?>

elgg.provide('elgg.tidypics.uploading');

elgg.tidypics.uploading.init = function() {

	var fields = ['Elgg', 'user_guid', 'album_guid', 'batch', 'tidypics_token'];
	var data = elgg.security.token;

	$(fields).each(function(i, name) {
		var value = $('input[name=' + name + ']').val();
		if (value) {
			data[name] = value;
		}
	});

	$("#uploader").plupload({
		// General settings
		runtimes : 'html5,html4',
		url : elgg.config.wwwroot + 'action/photos/image/ajax_upload',
		file_data_name : 'Image',

		dragdrop: true,
		multipart_params : data,
		max_file_size : '<?php echo $maxfilesize; ?>mb',

		filters : [
			{title : "<?php echo elgg_echo('tidypics:uploader:filetype'); ?>", extensions : "jpg,gif,png"}
        ],

		// Views to activate
		views: {
			list: true,
			thumbs: true,
			active: 'thumbs'
		},

		init : {
			UploadComplete: function(up, files) {
				// Called when all files are either uploaded or failed
				elgg.action('photos/image/ajax_upload_complete', {
					data: {
						album_guid: data.album_guid,
						batch: data.batch
					},
					success: function(json) {
						var url = elgg.normalize_url('photos/edit/' + json.batch_guid)
						window.location.href = url;
					}
				});
			},
			FilesAdded: function(up, files) {
				var max_files = <?php echo $max_files;?>;
				plupload.each(files, function(file) {
					if (up.files.length > max_files) {
						alert("<?php echo elgg_echo('tidypics:exceedmax_number', array($max_files));?>");
						up.removeFile(file);
					}
				});
				if (up.files.length >= max_files) {
					$('#pickfiles').hide('slow');
				}
			},
			FilesRemoved: function(up, files) {
				if (up.files.length < max_files) {
					$('#pickfiles').fadeIn('slow');
				}
			}
		}
	});
};

elgg.register_hook_handler('init', 'system', elgg.tidypics.uploading.init);