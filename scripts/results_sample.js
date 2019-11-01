// The following function is called after the dependancies are loaded via the script tag in the HTML
function initMap() {
    // New map is generated in the map div
    var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 13, center: {lat: 43.664993, lng: -79.387535}});
    
    // List of markers to be drawn on map saved to an array to be iterated over
    let markerArray = [
                      new google.maps.Marker({position: {lat: 43.653064, lng: -79.372747}, map: map, title: 'World Gym', icon: 'https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=1|FE6256|000000'}),
                      new google.maps.Marker({position: {lat: 43.663347, lng: -79.377282}, map: map, title: 'Fitness Etc.', icon: 'https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=2|FE6256|000000'}),
                      new google.maps.Marker({position: {lat: 43.673311, lng: -79.407293}, map: map, title: 'Good Life Fitness', icon: 'https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=3|FE6256|000000'})
                      ];
    
    // Descriptions for the gym's markers stored in an array
    var contentString = ['<div><a href="../src/individual_sample.html">World Gym</a></div>' + 
                        '<div>(416) 792-8275 <br> 172 Main Street S <br> Upper Beach</div>',
                        '<div><a href="../src/individual_sample.html">Fitness Etc.</a></div>' + 
                        '<div>(647) 350-5288 <br> 844 Bloor St W <br> Christie Pits</div>',
                        '<div><a href="../src/individual_sample.html">Goodlife Fitness</a></div>' + 
                        '<div>(416) 977-0999 <br> 398 Church St <br> Downtown Core </div>'
                        ];

    // Info pop ups also saved to an array
    var infoWindows = [
                      new google.maps.InfoWindow({ content: contentString[0]}),
                      new google.maps.InfoWindow({ content: contentString[1]}),
                      new google.maps.InfoWindow({ content: contentString[2]})
                    ];

    // A simple loop goes through each marker and adds a listener to it for its information window pop up. The pop up is activated
    // via a click listener
    for (let i = 0; i < markerArray.length; i++) {
      markerArray[i].addListener('click', function() {
        infoWindows[i].open(map, markerArray[i]);
      });
    }
  }
