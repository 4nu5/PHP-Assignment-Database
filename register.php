<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CommunityConnect</title>
     <style>
            #map { height: 300px; width: 100%; border-radius: 8px; margin-bottom: 15px; border: 1px solid #ccc; }
     </style>
    </head>
<body>

    <h2>Create Resident Account</h2>
    
    <form action="registerProccess.php" method="POST">
        
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="email">Email Address:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required minlength="6"><br><br>
        <select name="role" id="role">
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select><br><br>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"> 
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <div class="input-group">
                <label for="home_address">Home Address</label>
                <input type="text" name="address" id="home_address" required placeholder="Sunway Piramid">
                <small>Click on the map below to pinpoint your location!</small>
            </div>
            <div id="map"></div>
            <br>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
            <br>
               <button type="submit">Register Account</button>
    </form>
            <script>
                var map = L.map('map').setView([3.0731, 101.6077], 14);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{
                    maxZoom: 19,
                    attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var marker;
                map.on('click', function(e){
                    var lat = e.latlng.lat;
                    var lng = e.latlng.lng;

                    if(marker){
                        marker.setLatLng(e.latlng)
                    }else{
                        marker = L.marker(e.latlng).addTo(map);
                    }

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                });
            </script>
    <p>Already have an account? <a href="login.php">Log in here</a>.</p>

</body>
</html>

