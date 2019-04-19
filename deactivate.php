<?php
/**
 * Deactivate Tidypics
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_set_entity_class('object', TidypicsAlbum::SUBTYPE);
elgg_set_entity_class('object', TidypicsImage::SUBTYPE);
elgg_set_entity_class('object', TidypicsBatch::SUBTYPE);
