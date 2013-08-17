Tidypics plugin for Elgg 1.9
Latest Version: 1.9.1beta10
Released: 2013-08-17
Contact: iionly@gmx.de
License: GNU General Public License version 2
Copyright: (c) iionly 2013, (C) Cash Costello 2011-2013



This is a slightly improved version of the Tidypics plugin for Elgg 1.9. Regarding code base it's currently on the same level as 1.8.1beta10 for Elgg 1.8 with only changes necessary to work on Elgg 1.9.

ATTENTION:

Requires Elgg 1.9 at minimum! Please upgrade your Elgg installation first before upgrading the Tidypics plugin.

Currently still in beta! Most things should work. If you notice any issues, please tell me!


Known issues:

- watermarking not fully working,
- slightshow not fully working.



Installation and configuration:

IMPORTANT: If you have a previous version of the tidypics plugin installed then disable the plugin on your site and remove the tidypics folder from the mod folder on your server before installing the new version!
1. copy the tidypics plugin folder into the mod folder on your server,
2. enable the plugin in the admin section of your site,
3. configure the plugin settings. Especially, check if there's an "Upgrade" button visible on the Tidypics plugin settings page and if yes, execute the upgrade.



Changelog:

Changes for release 1.9.1beta10 (by iionly):

- Same code base as 1.8.1beta10 with necessary modifications to work on Elgg 1.9.


Changes for release 1.8.1beta10 (by iionly):
- Some preparations for compatibility with Elgg 1.9 (though I will release a separate version of Tidypics for Elgg 1.9!),
- Replacement of a deprecated function,
- Small improvement in Flash uploader error handling,
- New plugin option: use of slideshow optional,
- Fixed check of memory requirement for image re-sizing on upload when using GD php extension,
- Slightly better catching of missing images / thumbnail situations,
- Improved image orientation correction on image upload. When using GD library it will only be done when memory requirement is fullfilled. Additionally, Imagick php extension or ImageMagick library is used when defined as image library in Tidypics plugin settings,
- New tab on Tidypics plugin settings: image deletion by providing GUID of image (in case the image entry can't be deleted via site front-end),
- Includes the following changes in Tidypics from official Tidypics repo at https://github.com/cash/Tidypics:
    * correction of text in notifications about image uploads in case the uploader is not the owner of the album (by Jerome Bakker).


Changes for release 1.8.1beta9 (by iionly):
- Fixed php syntax error introduced in beta8 preventing group profile pages to be rendered (thanks to Pasley70 for reporting).


Changes for release 1.8.1beta8 (by iionly):
 - Requires Elgg 1.8.16 due to bugfix https://github.com/Elgg/Elgg/issues/5564 for the pagination on list pages to work,
 - Pagination support for the list pages (like "Most views" / "Recently commented" etc.) to show more than only a hardcoded number of photos in each list view,
 - List pages (like "Most views" / "Recently commented" etc.) to work correctly when logged out and to show only photos that the viewer is allowed to see based on access level settings,
 - "All", "Friends", "Mine" tabs hidden on "All photos" page when logged-out,
 - "Upload photos" button hidden when logged-out,
 - "Photos you are tagged in" sidebar entry hidden when logged-out,
 - "Tag" entity menu entry hidden when logged out,
 - "Photos you are tagged in" page revised,
 - List of members tagged in a image in sidebar when viewing an image (by including a code snippet of the Tagged People plugin by Kevin Jardine),
 - Fix in image and album save actions for deleting all image/album tags to work (referring to the usual Elgg Entity tags),
 - Improvements in handling Tidypics user and word tags of images (including CSS improvements) to play well together with the image entity tags (avoiding double tags to be added, removal of corresponding image entity tags when an Tidypics image word tag is removed),
 - River entry on adding word tags to an image,
 - Includes the following changes in Tidypics from official Tidypics repo at https://github.com/cash/Tidypics:
    * made the albums notifications overridable rather than calling object_notifications() directly (by Cash Costello),
    * fixed: security issue with showing malicious exif data (by Jerome Bakker).


Changes for release 1.8.1beta7 (by iionly):
 - auto-correction of image orientation on image upload (thanks to Jimmy Coder for the code snippet for image rotation),
 - word tags (as opposed to tagging a user): tags that don't correspond with a username will be added to the tags of the photo (searchable),
 - Includes the following changes in Tidypics from official Tidypics repo at https://github.com/cash/Tidypics:
    * set tiny size for sites that may not have it set (e.g. possibly sites updated from Elgg 1.6) (by Cash Costello),
    * stripping non word characters from title when pulled from image filename (by Cash Costello),
    * added tagging to river with notification to user (by Cash Costello, Kevin Jardine).


Changes for release 1.8.1beta6 (by iionly):
 - Fix for Tidypics to work in Elgg 1.8.15 (creating new albums),
 - Updated uploadify flash uploader to version 3.2 (this might only be a preliminary solution as I might need to switch to another flash (html5) uploader as the Uploadify uploader has some limitations and also seems no longer fully supported),
 - Fixed some deprecated function calls (they were not in actively used code but some people might have wondered about it nonetheless as the Code Analyzer plugin gave some warnings about them),
 - Fixed html code in widgets' content.php for better theme compatibility (as suggested by ura soul).


Changes for release 1.8.1beta5 (by iionly):

- Fix in river entry creation (hopefully last fix necessary for now...).


Changes for release 1.8.1beta4 (by iionly):

- River entries code reworked (solution introduced in beta3 did not work as intended),
- Option to include preview images in river entries when comments were made on albums and images,
- Fix a few errors in language files (en and de),
- Permission handling of tidypics_batches: on permission change of an album the permissions of corresponding tidypics_batches are changed to same new permission.


Changes for release 1.8.1beta3 (by iionly):

- River entries fixed (note: commenting on existing "batch" river entries does not work. It will only work for river entries created after upgrading to 1.8.1beta3!)


Changes for release 1.8.1beta2 (by iionly):

- Fixed quota support,
- Fixed issue with image entries (without images available) getting created on failed image uploads,
- Fixed an issue introduced in beta1 that resulted in (harmless but many) log entries getting created,
- Fixed Highest vote counts page,
- Display of Elggx Fivestar rating widget defined via Elggx Fivestar default view (requires version 1.8.3 of Elggx Fivestar plugin).


Changes for release 1.8.1beta1 (by iionly):

- removal of option to set access level for images. Images always get the same access level as the album they are uploaded to. On changing the access level of an album all its images get assigned the same access level, too.
- new plugin navigation / pages: more centered on (recent) images than albums,
- support of Widget Manager index page and groups' pages widgets,
- Elggx Fivestar voting widget included on detailed image views,
- some code-cleanup.


Changes since 1.8.0 Release Candidate 1:

- Pull requests made on github included. These PR were made by
    * Cash Costello
    * Brett Profitt
    * Kevin Kardine
    * Sem (sembrestels)
    * Steve Clay
    * Luciano Lima
