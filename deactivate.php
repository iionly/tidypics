<?php
/**
 * Deactivate Tidypics
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

update_subtype('object', TidypicsAlbum::SUBTYPE);
update_subtype('object', TidypicsImage::SUBTYPE);
update_subtype('object', TidypicsBatch::SUBTYPE);
