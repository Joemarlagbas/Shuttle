<!DOCTYPE html>
<html>
<body>
<h3>Shuttle GPS Sender</h3>
<script>
const API_URL = "http://yourserver.com/update_location.php"; // Replace with your server
const API_KEY = "MY_SECRET_KEY";
const SHUTTLE_ID = 1; // Change per shuttle

function sendLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            const data = new FormData();
            data.append('api_key', API_KEY);
            data.append('shuttle_id', SHUTTLE_ID);
            data.append('lat', pos.coords.latitude);
            data.append('lng', pos.coords.longitude);

            fetch(API_URL, {method:'POST', body: data})
                .then(r => r.json())
                .then(res => console.log(res))
                .catch(e => console.error(e));
        });
    } else {
        alert("GPS not supported");
    }
}

// Send every 10 seconds
setInterval(sendLocation, 10000);
</script>
</body>
</html>