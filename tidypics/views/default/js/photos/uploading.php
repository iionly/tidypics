<?php
/**
 * AJAX uploading
 */
$maxfilesize = ( int ) elgg_get_plugin_setting ( 'maxfilesize', 'tidypics' );
$maxfilesize *= 1024;
?>

//<script>
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

	 $("#uploader").pluploadQueue({
	        // General settings
	        runtimes : 'html5,flash,silverlight,html4',
	        url : elgg.config.wwwroot + 'action/photos/image/ajax_upload',
	         
	        chunk_size : '1mb',
	        rename : true,
	        dragdrop: true,
	         
	        filters : {
	            // Maximum file size
	            max_file_size : '10mb',
	            // Specify what files to browse for
	            mime_types: [
	                {title : "Image files", extensions : "jpg,gif,png"}
	            ]
	        },
	        multipart_params : data,
// 	        // Resize images on clientside if we can
// 	        resize: {
// 	            width : 200,
// 	            height : 200,
// 	            quality : 90,
// 	            crop: true // crop to exact dimensions
// 	        },
	 
	 
	        // Flash settings
	        flash_swf_url : elgg.config.wwwroot + 'mod/tidypics/vendors/plupload/Moxie.swf',
	     
	        // Silverlight settings
	        silverlight_xap_url : elgg.config.wwwroot + 'mod/tidypics/vendors/plupload/Moxie.xap',
	        // Post init events, bound after the internal events
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
					
	            }
	 
	        }
	    });

	
};

elgg.register_hook_handler('init', 'system', elgg.tidypics.uploading.init);