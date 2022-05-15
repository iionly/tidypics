<?php
/**
 * Events for Tidypics
 */

class TidypicsEvents {

	/**
	* custom layout for comments on tidypics river entries
	* Overriding generic_comment view
	*/
	public static function tidypics_comments_handler(\Elgg\Event $event) {
		$item = $event->getObject();

		if ($item->action_type != 'comment') {
			return;
		}

		$target_guid = $item->target_guid;
		if (!$target_guid) {
			return;
		}
		$target_entity = get_entity($target_guid);
		if ($target_entity instanceof TidypicsImage) {
			$item->view = "river/object/comment/image";
		} else if ($target_entity instanceof TidypicsAlbum) {
			$item->view = "river/object/comment/album";
		}

		return;
	}
}
