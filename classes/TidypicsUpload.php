<?php
/**
 * Helper class for working with uploads
 */

class TidypicsUpload {

	/**
	* Guess on the mimetype based on file extension
	*
	* @param string $originalName
	* @return string
	*/
	public static function tp_upload_get_mimetype($originalName) {
		$extension = substr(strrchr($originalName, '.'), 1);
		switch (strtolower($extension)) {
			case 'png':
				return 'image/png';
				break;
			case 'gif':
				return 'image/gif';
				break;
			case 'webp':
				return 'image/webp';
				break;
			case 'jpg':
			case 'jpeg':
				return 'image/jpeg';
				break;
			default:
				return 'unknown';
				break;
		}
	}

	/**
	* Check if this is an image
	*
	* @param string $mime
	* @return bool false = not image
	*/
	public static function tp_upload_check_format($mime) {
		$accepted_formats = [
			'image/jpeg',
			'image/png',
			'image/gif',
			'image/pjpeg',
			'image/x-png',
		];
		
		$imageLib = elgg_get_plugin_setting('image_lib', 'tidypics');
		if ($imageLib == 'ImageMagick') {
			$accepted_formats[] = 'image/webp';
		}

		if (!in_array($mime, $accepted_formats)) {
			return false;
		}
		return true;
	}

	/**
	* Check if there is enough memory to process this image
	*
	* @param string $image_lib
	* @param int $requiredMemory
	* @return bool false = not enough memory
	*/
	public static function tp_upload_memory_check($image_lib, $mem_required) {
		if ($image_lib !== 'GD') {
			return true;
		}

		$mem_avail = elgg_get_ini_setting_in_bytes('memory_limit');
		$mem_used = memory_get_usage();

		$mem_avail = $mem_avail - $mem_used - 2097152; // 2 MB buffer
		if ($mem_required > $mem_avail) {
			return false;
		}

		return true;
	}

	/**
	* Check if image is within limits
	*
	* @param int $image_size
	* @return bool false = too large
	*/
	public static function tp_upload_check_max_size($image_size) {
		$max_file_size = (float) elgg_get_plugin_setting('maxfilesize', 'tidypics');

		// convert to bytes from MBs
		$max_file_size = 1024 * 1024 * $max_file_size;
		return $image_size <= $max_file_size;
	}

	/**
	* Check if this image pushes user over quota
	*
	* @param int $image_size
	* @param int $owner_guid
	* @return bool false = exceed quota
	*/
	public static function tp_upload_check_quota($image_size, $owner_guid) {
		static $quota;

		if (!isset($quota)) {
			$quota = elgg_get_plugin_setting('quota', 'tidypics');
			$quota = 1024 * 1024 * $quota;
		}

		if ($quota == 0) {
			// no quota
			return true;
		}

		$owner = get_entity($owner_guid);
		$image_repo_size = (int)$owner->image_repo_size;

		return ($image_repo_size + $image_size) < $quota;
	}
}