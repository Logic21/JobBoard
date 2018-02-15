var map;
function initMap(lat, lng) {
	var myLatLng = {lat: lat, lng: lng};	
		
	map = new google.maps.Map(document.getElementById('map'), {
		center: myLatLng,
		zoom: 16,
		panControl:true,
		zoomControl:true,
	});
	
	var marker = new google.maps.Marker({
		position: myLatLng,
		map: map,
		title: 'My Business'
	});
}
$(document).ready( function () {

  
	initMap(-34.397,150.644);

  
});
