$(document).ready(function() {
  if ($("#js-parallax-window").length) {
    parallax();
  }
});

$(window).scroll(function(e) {
  if ($("#js-parallax-window").length) {
    parallax();
  }
});



$(window).load(function() {
  $('.flexslider').flexslider({
    animation: "slide"
  });
});





//Scroll on Page
(function (jQuery) {
  jQuery.mark = {
    jump: function (options) {
      var defaults = {
        selector: 'a.scroll-on-page-link'
      };
      if (typeof options == 'string') defaults.selector = options;
      var options = jQuery.extend(defaults, options);
      return jQuery(options.selector).click(function (e) {
        var jumpobj = jQuery(this);
        var target = jumpobj.attr('href');
        var thespeed = 1000;
        var offset = jQuery(target).offset().top;
        jQuery('html,body').animate({
          scrollTop: offset
        }, thespeed, 'swing')
        e.preventDefault();
      })
    }
  }
})(jQuery);


jQuery(function(){
  jQuery.mark.jump();
});





$(document).ready(function () {
  $('.accordion-tabs-minimal').each(function(index) {
    $(this).children('li').first().children('a').addClass('is-active').next().addClass('is-open').show();
  });

  $('.accordion-tabs-minimal').on('click', 'li > a', function(event) {
    if (!$(this).hasClass('is-active')) {
      event.preventDefault();
      var accordionTabs = $(this).closest('.accordion-tabs-minimal');
      accordionTabs.find('.is-open').removeClass('is-open').hide();

      $(this).next().toggleClass('is-open').toggle();
      accordionTabs.find('.is-active').removeClass('is-active');
      $(this).addClass('is-active');
    } else {
      event.preventDefault();
    }
  });
});





//map
var bittersMap = (function () {
var myLatlng = new google.maps.LatLng(-36.4218071, 148.6120031),
	mapCenter = new google.maps.LatLng(-36.4218071, 148.6120031),
	mapCanvas = document.getElementById('map_canvas'),
	mapOptions = {
	  center: mapCenter,
	  zoom: 15,
	  scrollwheel: true,
	  draggable: true,
	  disableDefaultUI: true,
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	},
	map = new google.maps.Map(mapCanvas, mapOptions),
	contentString =
	  '<div id="content">'+
	  '<div id="siteNotice">'+
	  '</div>'+
	  '<h1 id="firstHeading" class="firstHeading">Highview Estate</h1>'+
	  '<div id="bodyContent"'+
	  '<p>Twynam St Jindabyne</br> NSW, 2627</p>'+
	  '</div>'+
	  '</div>',
	infowindow = new google.maps.InfoWindow({
	  content: contentString,
	  maxWidth: 300
	}),
	marker = new google.maps.Marker({
	  position: myLatlng,
	  map: map,
	  title: 'Highview Estate, Jindabyne'
	});

return {
  init: function () {
	map.set('styles', [{
	  featureType: 'landscape',
	  elementType: 'geometry',
	  stylers: [
		{ hue: '#ffff00' },
		{ saturation: 50 },
		{ lightness: 10}
	  ]}
	]);

	google.maps.event.addListener(marker, 'click', function () {
	  infowindow.open(map,marker);
	});
  }
};
}());

bittersMap.init();
