define(function(require) {
	var elgg = require("elgg");
	var $ = require("jquery");
	var Ajax = require('elgg/Ajax');
	var ajax = new Ajax();
	require("tidypics/galleria");

	var slideshowurl; // Defines which category of images are displayed in slideshow (e.g. all site images, image of an album etc.)
	var limit; // How many images in one batch?
	var offset; // Where are we within the whole list of available images?
	var galleryLast = false; // Last available image loaded?
	var galleryFirst = true; // First available image loaded? Init with true to prevent loading of previous images already on init
	var galleryInitCount = 1; // Due to the dummy image used to init the slideshow with we need to prevent the image loaded event action to get executed on loading of real first image
	var initialImage = 0; // Unfortunately, it seems impossible to load another image but the first on initialization of the gallery, so we need to workaround that if offset > 0

	function init() {

		$('#slideshow').on('click', function() {

			slideshowurl = $(this).data('slideshowurl');
			limit = $(this).data('limit');
			orig_offset = offset = $(this).data('offset');

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
			Galleria.addTheme({
				name: 'tidypics',
				version: 1.6,
				author: 'Galleria',
				defaults: {
					transition: 'slide',
					thumbCrop:  'height',
					swipe: false,
					fullscreenDoubleTap: false,

					// set this to false if you want to show the caption all the time:
					_toggleInfo: true
				},
				init: function(options) {

					Galleria.requires(1.4, 'This version of Classic theme requires Galleria 1.4 or later');

					// add some elements
					this.addElement('info-link','info-close');
					this.append({
						'info' : ['info-link','info-close']
					});

					// cache some stuff
					var info = this.$('info-link,info-close,info-text'),
						touch = Galleria.TOUCH;

					// show loader & counter with opacity
					this.$('loader,counter').show().css('opacity', 0.8);

					// some stuff for non-touch browsers
					if (! touch ) {
						this.addIdleState( this.get('image-nav-left'), { left:-50 });
						this.addIdleState( this.get('image-nav-right'), { right:-70 });
						this.addIdleState( this.get('counter'), { opacity:0 });
					}

					// toggle info
					if ( options._toggleInfo === true ) {
						info.bind( 'click:fast', function() {
							info.toggle();
						});
					} else {
						info.show();
						this.$('info-link, info-close').hide();
					}

					// bind some stuff
					this.bind('thumbnail', function(e) {

						if (! touch ) {
							// fade thumbnails
							$(e.thumbTarget).css('opacity', 0.5).parent().hover(function() {
								$(this).not('.active').children().stop().fadeTo(100, 1);
							}, function() {
								$(this).not('.active').children().stop().fadeTo(400, 0.5);
							});

							if ( e.index === this.getIndex() ) {
								$(e.thumbTarget).css('opacity',1);
							}
						} else {
							$(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.5).bind('click:fast', function() {
								$(this).css( 'opacity', 1 ).parent().siblings().children().css('opacity', 0.5);
							});
						}
					});

					var activate = function(e) {
						$(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.5);
					};

					this.bind('loadstart', function(e) {
						if (!e.cached) {
							this.$('loader').show().fadeTo(200, 0.4);
						}
						window.setTimeout(function() {
							activate(e);
						}, touch ? 300 : 0);
						this.$('info').toggle( this.hasInfo() );
					});

					this.bind('loadfinish', function(e) {
						this.$('loader').fadeOut(200);
					});

					this.attachKeyboard({
						left: this.prev,
						right: this.next
					});
				}
			});

			Galleria.run('#galleria-slideshow', {
				dataSource: '#galleria-slideshow-dummy',
				maxScaleRatio: 1,
				preload: 0,
				extend: function(options) {
					var galleria = this;
					// With offset > 0 we load the images from (offset - limit) to (offset + limit)
					// First image to show is the image with starting offset value in the middle of the slideshow
					// We also need to make sure that we don't get into a negative offset range
					if (offset > 0) {
						// Need to use temp variables to not get into a negative offset range
						var offsetTemp = offset - limit;
						var limitTemp = limit;
						if (offsetTemp < 0) {
							limitTemp = limit + offsetTemp;
							offsetTemp = 0;
						}
						ajax.path(slideshowurl, {
							data: {
								view: "json",
								limit: (2*limit),
								offset: offsetTemp
							}
						}).done(function(output, statusText, jqXHR) {
							if (output.length) {
								// We need to initialize the slideshow with at least one dummy image
								// Ajax loader gif is used as dummy and can now be removed
								galleria.splice(0, 1);
								// Now we can add the real images
								galleria.push(output);
								// Set the first image to be loaded but do not load yet (would fail on init)
								initialImage = limitTemp;
								// If we get less images than limit the end is reached already
								if (output.length < (2 * limit)) {
									galleryLast = true;
								}
							}
						});
					} else { // With offset = 0 we start with 2*limit images from the beginning
						ajax.path(slideshowurl, {
							data: {
								view: "json",
								limit: (2*limit),
								offset: offset
							}
						}).done(function(output, statusText, jqXHR) {
							if (output.length) {
								// We need to initialize the slideshow with at least one dummy image
								// Ajax loader gif is used as dummy and can now be removed
								galleria.splice(0, 1);
								// Now we can add the real images
								galleria.push(output);
								// And now display the first real image
								galleria.show(0);
								// If we get less images than limit the end is reached already
								if (output.length < (2 * limit)) {
									galleryLast = true;
								}
							}
						});
					}

					// Using the image event of the Galleria script we check for first/last image in slideshow
					// and then try to load a new batch of images (adding either previous or next batch of image list)
					this.bind('image', function(e) {
						// Last image in slideshow?
						if (this._active == this._data.length - 1) {
							if (!galleryLast) { // Not yet reached last image on last loading
								// Try to load the next batch of images
								// Number of images to load is equal limit value with offset value set to end of
								// current images in slideshow
								var galleria = this;
								ajax.path(slideshowurl, {
									data: {
										view: "json",
										limit: limit,
										offset: (offset + this._data.length)
									}
								}).done(function(output, statusText, jqXHR) {
									// Do we have more images?
									if (output.length) {
										// Add to slideshow
										galleria.push(output);
										// Do we have to drop images from slideshow (max total number is 2*limit)?
										if ((galleria._data.length + output.length) > (2 * limit)) {
											// Drop images from the beginning equal to number exceeding 2*limit
											var drop = galleria._data.length + output.length - 2 * limit;
											galleria.splice(0, drop);
											// Recount index (total number of images in slideshow)
											galleria.setIndex(galleria._active - drop);
										} else {
											// Recount index (total number of images in slideshow)
											galleria.setIndex(galleria._active - output.length);
										}
										// Set counter to active image
										galleria.setCounter();
										// Set offset to new first image
										offset += output.length;
										// If offset larger 0 we are no longer at beginning
										if (offset > 0) {
											galleryFirst = false;
											$('.galleria-image-nav-left').show();
										}
										// If lesser than value of limit new images loaded we are at end
										if (output.length < limit) {
											galleryLast = true;
										}
									} else {
										// No more images so hide next button
										$('.galleria-image-nav-right').hide();
										galleryLast = true; // No more attempts to add more images at end
									}
								});
							} else {
								if (this._data.length == 1) {
									// Special case: slideshow with only 1 image
									$('.galleria-image-nav-left').hide();
								} else if (this._active != 0) {
									// Jumped directly to last with no more images available to be loaded but
									// with more than one image in slideshow we need to enable the prev button
									$('.galleria-image-nav-left').show();
								}
								// No more images so hide next button
								$('.galleria-image-nav-right').hide();
							}
						} else if (this._active == 0) { // First image in slideshow?
							// Are we already at the first images available?
							if (!galleryFirst) {
								var galleria = this;
								// Need to use temp variables to not get into a negative offset range
								var offsetTemp = offset - limit;
								var limitTemp = limit;
								if (offsetTemp < 0) {
									limitTemp = limit + offsetTemp;
									offsetTemp = 0;
								}
								ajax.path(slideshowurl, {
									data: {
										view: "json",
										limit: limitTemp,
										offset: offsetTemp
									}
								}).done(function(output, statusText, jqXHR) {
									if (output.length) {
										// Add new images at beginning of slideshow
										galleria.splice(0, 0, ...output);
										// Do we have to drop images from slideshow (max total number is 2*limit)?
										if ((galleria._data.length + output.length) > (2 * limit)) {
											// Drop images from the end equal to number exceeding 2*limit
											var drop = galleria._data.length + output.length - 2 * limit;
											galleria.splice(-drop, drop);
											// Recount index (total number of images in slideshow)
											galleria.setIndex(galleria._active + drop);
											// If we were at end of slideshow before we are no longer now due to removing images from end
											if (galleryLast) {
												galleryLast = false;
											}
										} else {
											// Recount index (total number of images in slideshow)
											galleria.setIndex(galleria._active + output.length);
										}
										// Set counter to active image
										galleria.setCounter();
										// Set offset to new first image
										offset -= output.length;
										// If offset smaller 1 we are at beginning
										if (offset < 1) {
											galleryFirst = true;
										}
									} else {
										// No new previous images so assuming we are are beginning of slideshow and therefore hide nav arrow
										$('.galleria-image-nav-left').hide();
										galleryFirst = true; // No more attempts to add more images at beginning
									}
								});
							} else {
								// On init we need to treat the image event firing on loading of the first image, i.e. replacing the dummy
								// image specifically if the offset is >0 as Galleria fails to show any other image but the first on init
								if (galleryInitCount > 0) {
									if (offset > 0) {
										// show the image we want to start with
										this.show(initialImage);
										// only now set offset to the right value
										offset -= initialImage;
									}
									// Now check if we are at offset=0 and show/hide the nav arrow accordingly
									if (offset > 0) {
										galleryFirst = false;
										$('.galleria-image-nav-left').show();
									} else {
										galleryFirst = true;
										$('.galleria-image-nav-left').hide();
									}
									// done with dealing with the init hassle
									galleryInitCount--;
								} else {
									// Jumped directly to first with no more images available to be loaded but
									// with more than one image in slideshow we need to enable the next button
									if (this._active != this._data.length - 1) {
										$('.galleria-image-nav-right').show();
									}
									// No more previous images so hide prev button
									$('.galleria-image-nav-left').hide();
								}
							}
						} else {
							// Neither at beginning or end
							$('.galleria-image-nav-right').show();
							$('.galleria-image-nav-left').show();
						}
					});
				}
			});
		});
	}

	init();
});
