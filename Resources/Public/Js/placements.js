/**
 * placements.js
 * @author Dirk Wenzel 
 */
$(document).ready(function() {
	if (!window.location.origin){
		basePath = window.location.protocol+"//"+window.location.host +"/";
	}
	else{
		// for webkit browsers
		basePath = window.location.origin + "/";
	}

	/**
	 * In switch view map is initial hidden. We
	 * initialize it when view is switched to map for the first time
	 */ 
	if(mapDisplayType == 'switchView') {
		$('#switch-view .btn').click(function(e){
			activeButton = $('#switch-view .btn.active');
			$('#' + activeButton.attr('value')).hide();
			activeButton.removeClass('active');
			$(this).addClass('active');
			$('#' + this.value).show();
			if(this.value == 'map-view' && (!map)) {
				initMap();
			} 
		});
	} else {
		initMap();
	}
	$('#placements-map-radius').change(function(e) {
		if($('#radius-search-submit').hasClass('active')) {
			filterPlaces('radius');
		}
	});
	// Radius Search submit
	$('#radius-search-submit').click(function(e) {
		if($(this).hasClass('active')) {
		    clearFilter();
		} else {
		    filterPlaces('radius');
		}
		$(this).toggleClass('active');
	});
	// clear all filter
	/*$('#radius-search-clear').click(function(e) {
		clearFilter();
		$('#radius-search-submit').toggleClass('active');
	});*/
});

function initMap() {
	gm = google.maps;
	mapContainer = document.getElementById('map_canvas');
	geocoder = new gm.Geocoder();
	infoWindow = new gm.InfoWindow();
	mapOptions = {
		center: new gm.LatLng(parseFloat(mapCenterLatitude),parseFloat(mapCenterLongitude)),
		zoom: 7,
		mapTypeId: gm.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(mapContainer, mapOptions);
	oms = new OverlappingMarkerSpiderfier(map);
	oms.addListener('click', function(marker, event) {
		infoWindow.setContent(marker.note);
		infoWindow.open(map, marker);
	});
	bounds = new gm.LatLngBounds();
	shadow = new gm.MarkerImage(
		'https://www.google.com/intl/en_ALL/mapfiles/shadow50.png',
		new gm.Size(37, 34), // size - for sprite clipping
		new gm.Point(0, 0), // origin - ditto
		new gm.Point(10, 34) // anchor - where to meet map location
	);
	oms.addListener('spiderfy', function(markers) {
		for(var i = 0; i < markers.length; i ++) {
			markers[i].setIcon(iconWithColor(spiderfiedColor));
			markers[i].setShadow(null);
		}
		infoWindow.close();
	});
	oms.addListener('unspiderfy', function(markers) {
		for(var i = 0; i < markers.length; i ++) {
			markers[i].setIcon(iconWithColor(usualColor));
			markers[i].setShadow(shadow);
		}
	});
	loadMapData();
}
function loadMapData(){
	$.ajax({
		async: 'true',
		url: 'index.php',      
		type: 'POST',
		data: {
			eID: "placementsAjax",  
			request: {
				pluginName:  'Placements',
				controller:  'Position',
				action:      'ajaxList',
				arguments: {
					'overwriteDemand': overwriteDemand,
				}
			}
		},
		dataType: "json",      
		success: function(result) {
			mapData = result;
			updatePlaces();
		},
		error: function(error) {
			//console.log(error);               
		},
		done: function() {
			updatePlaces();
		}
	});
}

function updatePlaces() {
	allMarkers = [];
	for (var uid=0; uid< mapData.length;uid++) {
		var place = mapData[uid],
		    address;
		if(place.latitude && place.longitude) {
			address = new google.maps.LatLng(parseFloat(place.latitude),parseFloat(place.longitude));
			addMarker(address, uid);
		}else if(place.city) {
			address = place.city;
			getLocationData(address, uid, function(locationData, uid) {
				addMarker(locationData, uid);
			});
		}
	}
}

function getLocationData(address, uid, callback) {
	geocoder.geocode({ 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			callback(results[0].geometry.location, uid);
     		}
	});
}
function addMarker(position, uid) {
	var place = mapData[uid],
	    title = place.title,
	    note = 
		'<div class="infoWindow">' +
			'<div class="title"><strong>' + title + '</strong></div>' +
			'<div class="position-type">' + JSON.parse(place.type).title + '</div>' +
			'<div class="location">' + place.zip + ' ' + place.city + '</div>' +
			//'<div class="summary">' + place.summary + '</div>' +
		'</div>';
	//	'<div class="description">' + place.description + '</div>';
	marker = new google.maps.Marker({
		position: position,
		map: map,
		note: note,
		title: title,
		icon: iconWithColor(usualColor),
		shadow: shadow
	});
	if(fitMapBounds) {
		bounds.extend(position);
		map.fitBounds(bounds);
	}
	allMarkers.push(marker);
	oms.addMarker(marker);
	return marker;
}

function filterPlaces(criterion) {
	switch (criterion) {
		case 'radius':
			filterByRadius();
	}
}

function filterByRadius() {
	var radius = $('#placements-map-radius').val(),

	address = $('#placements-map-location').val(),
	errors = {};

	//console.log('address: ', address, 'radius: ', radius);
	if (address == '') {
		errors.push('address_field_empty');
	}else {
		getLocationData(address, null, function(locationData) {
			map.panTo(locationData);
			setHomeMarker(locationData);
			var circleOptions = {
				strokeColor: "#2875BB",
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: "#2875BB",
				fillOpacity: 0.35,
				map: map,
				center: locationData,
				radius: parseInt(radius)
			};
			if(radiusCircle) {
				radiusCircle.setMap(null);
			}
			radiusCircle = new google.maps.Circle(circleOptions)
			var currMarkers = {};
			for(var i = 0; i<allMarkers.length;i++) {
				currMarker = allMarkers[i];
				var distance = google.maps.geometry.spherical.computeDistanceBetween(currMarker.position, locationData);
				if (distance > radius) {
					currMarker.setMap(null);
				} else {
					currMarker.setMap(map);
					currMarker.setAnimation(google.maps.Animation.DROP);
				}
			}
			map.fitBounds(radiusCircle.getBounds());
		});
	}
}

function clearFilter() {
	radiusCircle.setMap(null);
	homeMarker.setMap(null);
	for (var i = 0; i < allMarkers.length; i++) {
		allMarkers[i].setMap(map);
	}
}
function setHomeMarker(position) {
	if(homeMarker) {
		homeMarker.setPosition(position);
	} else {
		homeMarker = new google.maps.Marker({
			position: position,
			map: map,
			title: 'You',
			animation: google.maps.Animation.DROP
		});
	}
}

