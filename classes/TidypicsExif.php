<?php
/**
 * Exif Processing class
 *
 * @package TidypicsExif
 */

class TidypicsExif {
	/**
	* Pull EXIF data from image file
	*
	* @param TidypicsImage $image
	*/
	public static function td_get_exif($image) {

		// catch for those who don't have exif module loaded
		if (!is_callable('exif_read_data')) {
			return;
		}

		$mime = $image->mimetype;
		if ($mime != 'image/jpeg' && $mime != 'image/pjpeg') {
			return;
		}

		$filename = $image->getFilenameOnFilestore();
		// @ added as a fix to prevent PHP Warning: exif_read_data(...): Illegal IFD size
		$exif = @exif_read_data($filename, "ANY_TAG", true);
		if (is_array($exif)) {
			// What data is in the image file?
			$data = false; // We start with no data
			if (is_array($exif['IFD0']) && is_array($exif['EXIF'])) {
				$data = array_merge($exif['IFD0'], $exif['EXIF']);
			} else if (is_array($exif['IFD0'])) {
				$data = $exif['IFD0'];
			} else if (is_array($exif['EXIF'])) {
				$data = $exif['EXIF'];
			}

			if ($data && is_array($data) && count($data) > 0) {
				foreach ($data as $key => $value) {
					if (is_string($value)) {
						// there are sometimes unicode characters that cause problems with serialize
						$data[$key] = preg_replace( '/[^[:print:]]/', '', $value);
					}
				}
			}

			if (is_array($exif['GPS'])) {
				// GPS data
				$gps_exif = array_intersect_key($exif['GPS'], array_flip(['GPSLatitudeRef', 'GPSLatitude', 'GPSLongitudeRef', 'GPSLongitude']));

				if (count($gps_exif) == 4) {
					if (
						is_array($gps_exif['GPSLatitude']) && in_array($gps_exif['GPSLatitudeRef'], ['S', 'N']) &&
						is_array($gps_exif['GPSLongitude']) && in_array($gps_exif['GPSLongitudeRef'], ['W', 'E'])
					) {
						$data['latitude'] = self::parse_exif_gps_data($gps_exif['GPSLatitude'], $gps_exif['GPSLatitudeRef']);
						$data['longitude'] = self::parse_exif_gps_data($gps_exif['GPSLongitude'], $gps_exif['GPSLongitudeRef']);
					}
				}
			}

			if ($data && is_array($data) && count($data) > 0) {
				$image->tp_exif = serialize($data);
			}
		}
	}

	/**
	* Grab array of EXIF data for display
	*
	* @param TidypicsImage $image
	* @return array|false
	*/
	public static function tp_exif_formatted($image) {

		$exif = $image->tp_exif;
		if (!$exif) {
			return false;
		}

		$exif = unserialize($exif);

		$model = $exif['Model'];
		if (!$model) {
			$model = "N/A";
		}
		$exif_data['Model'] = $model;

		$exposure = $exif['ExposureTime'];
		if (!$exposure) {
			$exposure = "N/A";
		}
		$exif_data['Shutter'] = $exposure;

		//got the code snippet below from http://www.zenphoto.org/support/topic.php?id=17
		//convert the raw values to understandible values
		$Fnumber = explode("/", $exif['FNumber']);
		if ($Fnumber[1] != 0) {
			$Fnumber = $Fnumber[0] / $Fnumber[1];
		} else {
			$Fnumber = 0;
		}
		if (!$Fnumber) {
			$Fnumber = "N/A";
		} else {
			$Fnumber = "f/$Fnumber";
		}
		$exif_data['Aperture'] = $Fnumber;

		$iso = $exif['ISOSpeedRatings'];
		if (!$iso) {
			$iso = "N/A";
		}
		$exif_data['ISO Speed'] = $iso;

		$Focal = explode("/", $exif['FocalLength']);
		if ($Focal[1] != 0) {
			$Focal = $Focal[0] / $Focal[1];
		} else {
			$Focal = 0;
		}
		if (!$Focal || round($Focal) == "0") {
			$Focal = 0;
		}
		if (round($Focal) == 0) {
			$Focal = "N/A";
		} else {
			$Focal = round($Focal) . "mm";
		}
		$exif_data['Focal Length'] = $Focal;

		$captured = $exif['DateTime'];
		if (!$captured) {
			$captured = "N/A";
		}
		$exif_data['Captured'] = $captured;

		// uncomment the following lines if you want to get the GPS position displayed - used only for testing now
	// 	if ($exif['latitude'] && $exif['longitude']) {
	// 		$exif_data['latitude'] = $exif['latitude'];
	// 		$exif_data['longitude'] = $exif['longitude'];
	// 	}

		return $exif_data;
	}

	/**
	* Converts EXIF GPS format to a float value.
	*
	* @param string[] $raw eg:
	*    - 41/1
	*    - 54/1
	*    - 9843/500
	* @param string $ref 'S', 'N', 'E', 'W'. eg: 'N'
	* @return float eg: 41.905468
	*/
	public static function parse_exif_gps_data($raw, $ref) {
		foreach ($raw as &$i) {
			$i = explode('/', $i);
			$i = $i[1] == 0 ? 0 : $i[0] / $i[1];
		}
		unset($i);

		$v = $raw[0] + $raw[1] / 60 + $raw[2] / 3600;

		$ref = strtoupper($ref);
		if ($ref == 'S' || $ref == 'W') {
			$v= -$v;
		}

		return $v;
	}
}