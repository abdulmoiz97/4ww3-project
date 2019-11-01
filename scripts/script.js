// HTML5 Geo location API used to pull user's location from browser
function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(savePosition);
    } else { 
      alert("Gelocation is not supported by this browser");
    }
  }

// This function saves the location to variables and also prints them to the console to confirm functionality
function savePosition(location) {
    var latitude = location.coords.latitude;
    var longitude = location.coords.longitude;
    console.log("Latitude: " + latitude + ", Longitude: " + longitude);
}