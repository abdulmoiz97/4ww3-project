// The following function is called after the dependancies are loaded via the script tag in the HTML
function initMap() {
    // New map is generated in the map div
    var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 14, center: {lat: 43.653064, lng: -79.372747}});
    
    // Marker drawn on map for individual result
    let marker = new google.maps.Marker({position: {lat: 43.653064, lng: -79.372747}, map: map, title: 'World Gym'});
    
    // Details of gym saved to variable to be presented
    var contentString = '<div><a href="../src/individual_sample.html">World Gym</a></div>' + 
                        '<div>(416) 792-8275 <br> 172 Main Street S <br> Upper Beach</div>';

    // Popup box on marker to show user details about gym on map
    var infoWindow = new google.maps.InfoWindow({content: contentString});
  
    // Click listeners added to markers on map to show pop up when clicked.
    marker.addListener('click', function() {
        infoWindow.open(map, marker);
    });
  }