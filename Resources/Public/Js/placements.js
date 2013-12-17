/**
 * placements.js
 * @author Dirk Wenzel 
 */
/*var mapContainer,
mapData = {},
mapWidth = 300,
mapHeight = 300,
zoom = 7,
map = '',
mapType = google.maps.MapTypeId.SATELLITE
;
*/
$(document).ready(function() {
	if (!window.location.origin){
		basePath = window.location.protocol+"//"+window.location.host +"/";
	}
	else{
		// for webkit browsers
		basePath = window.location.origin + "/";
	}
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
	// initialize map
});

function initMap() {
	mapContainer = document.getElementById('map_canvas');
	geocoder = new google.maps.Geocoder();
	mapOptions = {
		center: new google.maps.LatLng(52.520007,13.404954),
		zoom: 7,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(mapContainer, mapOptions);
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
					'dummy': 'nil'
				}
			}
		},
		dataType: "json",      
		success: function(result) {
			mapData = result;
			updatePlaces();
			//console.log(result);
		},
		error: function(error) {
			console.log(error);               
		},
		done: function() {
			updatePlaces();
		}
	});
}

function updatePlaces() {
	//console.log(mapData);
	for (var i=0; i< mapData.length;i++) {
		var position = mapData[i];
		//console.log(position.title);
		if(position.latitude && position.longitude) {
			currLatlng = new google.maps.LatLng(parseFloat(position.latitude),parseFloat(position.longitude));
			var marker = new google.maps.Marker({
					position: currLatlng,
					map: map,
					title: position.title,
					//icon: currIcon
				});
		}else if(position.city) {
			//console.log(position.city);
			//var address = position.zip + ',' + position.city;i
			var address = position.city;
			console.log(address);
			geocoder.geocode({ 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					var marker = new google.maps.Marker({
            			map: map,
            			position: results[0].geometry.location
					});
				}else {
        			console.log("Geocode failed: " + status);
     			}

			});

		}
	}
}
