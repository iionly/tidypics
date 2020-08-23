<?php
/**
 * Tidypics CSS
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */
?>

.elgg-module-tidypics-album,
.elgg-module-tidypics-image {
	width: 161px;
	text-align: left;
	margin: 5px 0;
}
.elgg-module-tidypics-image {
	margin: 5px auto;
}

.tidypics-gallery {
	align-items: flex-end;
}

.tidypics-gallery-widget > li {
	width: 69px;
}
.tidypics-photo-wrapper {
	position: relative;
}

.tidypics-heading {
	color: #0054A7;
}
.tidypics-heading:hover {
	color: #0054A7;
	text-decoration: none;
}

.tidypics-input-thin {
	width: 120px;
}

#tidypics-sort li {
	width:153px;
	height:153px;
	cursor: move;
}

.tidypics-river-list > li {
	display: inline-block;
}

.tidypics-photo-item {
	margin-left: 7px;
}

.tidypics-gallery > li {
	padding: 0 9px;
}

.tidypics-album-nav {
	margin: 3px 0;
	text-align: center;
	justify-content: center;
	color: #aaa;
}

.tidypics-album-nav > li {
	padding: 0 3px;
}

.tidypics-album-nav > li {
	vertical-align: top;
}

.tidypics-tagging-border1 {
	border: solid 2px white;
}

.tidypics-tagging-border1, .tidypics-tagging-border2,
.tidypics-tagging-border3, .tidypics-tagging-border4 {
	filter: alpha(opacity=50);
	opacity: 0.5;
}

.tidypics-tagging-handle {
	background-color: #fff;
	border: solid 1px #000;
	filter: alpha(opacity=50);
	opacity: 0.5;
}

.tidypics-tagging-outer {
	background-color: #000;
	filter: alpha(opacity=50);
	opacity: 0.5;
}

.tidypics-tagging-help {
	position: absolute;
	left: 35%;
	top: -40px;
	width: 450px;
	margin-left: -125px;
	text-align: left;
}

.tidypics-tagging-select {
	position: absolute;
	max-width: 200px;
	text-align: left;
}

.tidypics-tag-wrapper {
	display: none;
	position: absolute;
}

.tidypics-tag {
	border: 2px solid white;
	clear: both;
}

.tidypics-tag-label {
	float: left;
	margin-top: 5px;
	color: #666;
}

.tidypics-river-popup {
	width:100%;
	height:auto;
}

.tidypicsRiverPhotoPopup {
	width:100%;
	height:auto;
	min-height:60%;
	text-align: center;
	justify-content: center;
	background-color:#FFF;
}

.tidypics-slideshow-button {
	font-size: 24px;
	font-family: "Font Awesome 5 Free";
	font-weight: 400;
	cursor: pointer;
}

#tidypics-uploader {
	position:relative;
	width:540px;
	min-height:20px;
}

.tidypics-selectalbum {
	width: 400px;
}

#uploader {
	text-shadow: none;
}

<?php
/**
 * Galleria Classic Theme 2017-02-13
 * http://galleria.io
 *
 * Copyright (c) 2010 - 2017 worse is better UG
 * Licensed under the MIT license
 * https://raw.github.com/worseisbetter/galleria/master/LICENSE
 *
 * Modified for Tidypics plugin
 * (c) iionly 2018
 */
?>

#galleria-loader {
	height:1px!important;
}

#galleria-slideshow {
	height: 100%;
	padding: 0px !important;
}

.galleria-theme-tidypics {
	position: relative;
	overflow: hidden;
}
.galleria-theme-tidypics img {
	-moz-user-select: none;
	-webkit-user-select: none;
	-o-user-select: none;
}
.galleria-theme-tidypics .galleria-stage {
	position: absolute;
	top: 10px;
	bottom: 60px;
	left: 10px;
	right: 10px;
	overflow:hidden;
}
.galleria-theme-tidypics .galleria-thumbnails-container {
	height: 50px;
	bottom: 0px;
	position: absolute;
	left: 10px;
	right: 10px;
	z-index: 2;
}
.galleria-theme-tidypics .galleria-carousel .galleria-thumbnails-list {
	margin-left: 30px;
	margin-right: 30px;
}
.galleria-theme-tidypics .galleria-thumbnails .galleria-image {
	height: 40px;
	width: 60px;
	margin: 0 5px 0 0;
	border: 1px solid #000;
	float: left;
	cursor: pointer;
}
.galleria-theme-tidypics .galleria-counter {
	position: absolute;
	bottom: 10px;
	left: 10px;
	text-align: right;
	color: #444;
	font: normal 11px/1 arial,sans-serif;
	z-index: 1;
}
.galleria-theme-tidypics .galleria-loader {
	width: 20px;
	height: 20px;
	position: absolute;
	top: 10px;
	right: 10px;
	z-index: 2;
	display: none;
	background: url(<?= elgg_get_simplecache_url('tidypics/loader.gif'); ?>) no-repeat 2px 2px;
}
.galleria-theme-tidypics .galleria-info {
	width: 50%;
	top: 15px;
	left: 15px;
	z-index: 2;
	position: absolute;
}
.galleria-theme-tidypics .galleria-info-text {
	padding: 12px;
	display: none;
	background-color: #FFF;
	border: 1px solid #DEDEDE;
	border-radius: 0 0 3px 3px;
	box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
	/* IE7 */ zoom:1;
}
.galleria-theme-tidypics .galleria-info-title {
	font: bold 12px/1.1 arial,sans-serif;
	margin: 0;
	color: #444;
	margin-bottom: 7px;
}
.galleria-theme-tidypics .galleria-info-description {
	font: italic 12px/1.4 georgia,serif;
	margin: 0;
	color: #444;
}
.galleria-theme-tidypics .galleria-info-close {
	position: absolute;
	top: 5px;
	right: 5px;
	opacity: .7;
	filter: alpha(opacity=70);
	display: none;
}
.galleria-theme-tidypics .notouch .galleria-info-close:hover {
	opacity:1;
	filter: alpha(opacity=100);
}
.galleria-theme-tidypics .touch .galleria-info-close:active {
	opacity:1;
	filter: alpha(opacity=100);
}
.galleria-theme-tidypics .galleria-info-close:before {
	font-size: 20px;
	font-family: "Font Awesome 5 Free";
	font-weight: 900;
	cursor: pointer;
	content: "\f410";
}
.galleria-theme-tidypics .galleria-info-link {
	opacity: .7;
	filter: alpha(opacity=70);
	position: absolute;
}
.galleria-theme-tidypics.notouch .galleria-info-link:hover {
	opacity: 1;
	filter: alpha(opacity=100);
}
.galleria-theme-tidypics.touch .galleria-info-link:active {
	opacity: 1;
	filter: alpha(opacity=100);
}
.galleria-theme-tidypics .galleria-info-link:before {
	font-size: 20px;
	font-family: "Font Awesome 5 Free";
	font-weight: 900;
	cursor: pointer;
	content: "\f05a";
}
.galleria-theme-tidypics .galleria-image-nav {
	position: absolute;
	top: 50%;
	margin-top: -62px;
	width: 100%;
	height: 62px;
	left: 0;
}
.galleria-theme-tidypics .galleria-image-nav-left,
.galleria-theme-tidypics .galleria-image-nav-right {
	opacity: .3;
	filter: alpha(opacity=30);
	width: 62px;
	height: 124px;
	position: absolute;
	left: 10px;
	z-index: 2;
}
.galleria-theme-tidypics .galleria-image-nav-right {
	left: auto;
	right: 10px;
	z-index: 2;
}
.galleria-theme-tidypics.notouch .galleria-image-nav-left:hover,
.galleria-theme-tidypics.notouch .galleria-image-nav-right:hover {
	opacity: 1;
	filter: alpha(opacity=100);
}
.galleria-theme-tidypics.touch .galleria-image-nav-left:active,
.galleria-theme-tidypics.touch .galleria-image-nav-right:active {
	opacity: 1;
	filter: alpha(opacity=100);
}
.galleria-theme-tidypics .galleria-image-nav-left:before {
	font-size: 62px;
	font-family: "Font Awesome 5 Free";
	font-weight: 900;
	cursor: pointer;
	content: "\f104";
	background: #fff;
	padding:5px;
	border-radius: 50%;
}
.galleria-theme-tidypics .galleria-image-nav-right:before {
	font-size: 62px;
	font-family: "Font Awesome 5 Free";
	font-weight: 900;
	cursor: pointer;
	content: "\f105";
	background: #fff;
	padding:5px;
	border-radius: 50%;
}
.galleria-theme-tidypics .galleria-thumb-nav-left,
.galleria-theme-tidypics .galleria-thumb-nav-right {
	display: none;
	position: absolute;
	left: 0;
	top: 10px;
	height: 40px;
	width: 23px;
	z-index: 3;
	opacity: .5;
	filter: alpha(opacity=50);
}
.galleria-theme-tidypics .galleria-thumb-nav-right {
	border-right: none;
	right: 0;
	left: auto;
}
.galleria-theme-tidypics .galleria-thumbnails-container .disabled {
	opacity: .2;
	filter: alpha(opacity=20);
	cursor: default;
}
.galleria-theme-tidypics.notouch .galleria-thumb-nav-left:hover,
.galleria-theme-tidypics.notouch .galleria-thumb-nav-right:hover {
	opacity: 1;
	filter: alpha(opacity=100);
}
.galleria-theme-tidypics.touch .galleria-thumb-nav-left:active,
.galleria-theme-tidypics.touch .galleria-thumb-nav-right:active {
	opacity: .5;
	filter: alpha(opacity=50);
}
.galleria-theme-tidypics .galleria-thumb-nav-left:before {
	font-size: 40px;
	font-family: "Font Awesome 5 Free";
	font-weight: 900;
	cursor: pointer;
	content: "\f0d9";
}
.galleria-theme-tidypics .galleria-thumb-nav-right:before {
	font-size: 40px;
	font-family: "Font Awesome 5 Free";
	font-weight: 900;
	cursor: pointer;
	content: "\f0da";
}
.galleria-theme-tidypics.notouch .galleria-thumbnails-container .disabled:hover {
	opacity: .2;
	filter: alpha(opacity=20);
	background-color: transparent;
}
.galleria-theme-tidypics .galleria-carousel .galleria-thumb-nav-left,
.galleria-theme-tidypics .galleria-carousel .galleria-thumb-nav-right {
	display: block;
}
.galleria-theme-tidypics.galleria-container.videoplay .galleria-info,
.galleria-theme-tidypics.galleria-container.videoplay .galleria-counter {
	display:none!important;
}
