var moment = require('moment');

var Map = (function () {

	var dataUrl, rowsCount, markers, map;
	var markersList = [];
	var currentRowNo = 1;
	var header = null;
	var importJobId = null;

	function bindEvents() {
		initializeMap();
		initializeLinks();
		initializeAbout();
	}

	function initializeMap() {
		map = L.map('map').setView([29.813142, -95.309789], 6);

        L.tileLayer('https://map.geocod.io/osm/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
        }).addTo(map);

        markers = L.markerClusterGroup({
        	chunkedLoading: true,
        	disableClusteringAtZoom: 16
        });

        fetchLocations();
	}

	function initializeLinks() {
		$('.marker-link').click(function () {
			var latLng = $(this).attr('href').replace('#', '');

			map.setView(latLng.split(','), 13);

			return false;
		});
	}

	function initializeAbout() {
		var key = 'dismiss-about';
		var state = localStorage.getItem(key);

		if (!state) {
			$('#about').removeClass('hide');
		}

		$('#dismiss-link').click(function () {
			localStorage.setItem(key, 'yes');
			$('#about').addClass('hide');

			return false;
		});
	}

	function fetchLocations() {
		var popupMinWidth = $(window).width() < 600 ? 50 : 600;
		console.log(popupMinWidth);

		$('#loading-indicator').show();
		$.get('/emergencies', function (results) {
			for (var index in results) {
				var result = results[index];
				var lat = result.lat;
				var lng = result.lng;

				var marker = new L.CircleMarker([lat, lng], {
					radius: 8,
					color: 'blue',
					fillColor: '#bbf',
					fillOpacity: 1.0
				});

				var popupHTML = '';

				var link = 'https://twitter.com/' + result.message.user_handle + '/status/' + result.message.twitter_id;
				var date = moment(result.message.message_created);

				popupHTML += '<a href="' + link + '" target="_blank">@' + result.message.user_handle + '</a>';
				popupHTML += '<blockquote>' + result.message.message_text + '</blockquote>';
				popupHTML += date.fromNow() + ', ' + date.format('MMMM Do YYYY, h:mm:ss a');


				marker.bindPopup(popupHTML, {
					minWidth: popupMinWidth
				});
				markers.addLayer(marker);
				markersList.push(marker);
			}

			map.addLayer(markers);
			$('#loading-indicator').hide();
		});
	}

	return {
		init: function() {
			bindEvents();
		}
	};

})();

$(Map.init());
