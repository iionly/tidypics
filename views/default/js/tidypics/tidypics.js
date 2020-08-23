define(function(require) {
	var elgg = require("elgg");
	var $ = require("jquery");
	require('jquery.colorbox');

	function boxstyle(identifier) {
		var opts = {};
		var defaults = elgg.data.lightbox;
		if (!defaults.reposition) {
			// don't move colorbox on small viewports https://github.com/Elgg/Elgg/issues/5312
			defaults.reposition = $(window).height() > 600;
		}
		var settings = $.extend({}, defaults, opts);
		var values = elgg.trigger_hook('getOptions', 'ui.lightbox', null, settings);
		$(identifier).colorbox(values);
	}

	function init() {
		if ($(".tidypics-lightbox").length) {
			$(".tidypics-lightbox").colorbox({
				photo:true,
				maxWidth:'95%',
				maxHeight:'95%',
				onOpen: boxstyle(".tidypics-lightbox")
			});
			$("#cboxOverlay").css("z-index", "10100");
			$("#colorbox").css("z-index", "10101");
		}

		if ($(".tidypics-river-lightbox").length) {
			$(".tidypics-river-lightbox").colorbox({
				width:'640px',
				maxWidth:'95%',
				maxHeight:'95%',
				onOpen: boxstyle(".tidypics-river-lightbox"),
				onComplete: function() {
					$(this).colorbox.resize();
				}
			});
			$("#cboxOverlay").css("z-index", "10100");
			$("#colorbox").css("z-index", "10101");
		}

		if ($(".tidypics-selectalbum-lightbox").length) {
			$(".tidypics-selectalbum-lightbox").colorbox({
				onOpen: boxstyle(".tidypics-selectalbum-lightbox")
			});
			$("#cboxOverlay").css("z-index", "10100");
			$("#colorbox").css("z-index", "10101");
		}

		if ($(".tidypics-slideshow-lightbox").length) {
			$(".tidypics-slideshow-lightbox").colorbox({
				width: '95%',
				height: '95%',
				maxWidth: '95%',
				maxHeight: '95%',
				title: false,
				arrowKey: false,
				onOpen: boxstyle(".tidypics-slideshow-lightbox")
			});
			$("#cboxOverlay").css("z-index", "10100");
			$("#colorbox").css("z-index", "10101");
		}

		$("#tidypics-sort").sortable({
			opacity: 0.7,
			revert: true,
			scroll: true
		});

		$('.elgg-form-photos-album-sort').submit(function() {
			var tidypics_guids = [];
			$("#tidypics-sort li").each(function(index) {
				tidypics_guids.push($(this).attr('id'));
			});
			$('input[name="guids"]').val(tidypics_guids.toString());
		});
	}

	init();
});
