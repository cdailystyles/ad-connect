document.addEventListener("DOMContentLoaded", function() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;
      console.log(`Latitude: ${lat}, Longitude: ${lon}`);
      // Implement logic to convert lat/lon to city and load listings
    }, function() {
      console.log('Unable to retrieve your location');
    });
  } else {
    console.log('Geolocation is not supported by this browser');
  }
});
