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
                <button type="button" id="findbtn" style="width: auto;height: 55px; background-color: #0056b3; margin: 0;">Find On Map</button>
            </div>

             <small id="map-status" style="display:block; margin-bottom: 20px; font-weight: bold;"></small>
            <label>Pinpoint Location on Map:</label>
            <small style="display:block; margin-bottom: 10px; color: #666;">Click the map exactly where the event is happening.</small>
            <div id="map"></div>
            <br>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
            <br>
               <button type="submit">Register Account</button>
    </form>
            <script>
                var map = L.map('map').setView([3.0731, 101.6077], 14);
                L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png',{
                    maxZoom: 19,
                    attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var marker;
                map.on('click', function(e){
                    if(marker){
                        marker.setLatLng(e.latlng)
                    }else{
                        marker = L.marker(e.latlng).addTo(map);
                    }

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    document.getElementById('map-status').innerText = "Pin Dropped Manually";
                    document.getElementById('map-status').style.color = "Green";
                });
                document.getElementById('home_address').addEventListener('keypress', function(event){
                    if(event.key === 'Enter'){
                        event.preventDefault();
                        document.getElementById('findbtn').click();
                    }
                });
                document.getElementById('findbtn').addEventListener('click', function(){
                    var address = document.getElementById('home_address').value;
                    var statusText = document.getElementById('map-status');

                    if(address.trim() === ''){
                        statusText.innerText = "Please Enter an Address";
                        statusText.style.color = "red";
                        return;
                    }
                    statusText.innerText = "Searching The Map";
                    statusText.style.color = "green";

                    if(address.trim() !== ''){
                        var url = "geolocator.php?address=" + encodeURIComponent(address);
                        fetch(url)
                        .then(Response => {
                            if(!Response.ok){
                                throw new Error("HTTP Status" + Response.status);
                            }
                            return Response.json();
                        })
                        .then(data => {
                            if(data.length > 0){
                                var lat = data[0].lat;
                                var lng = data[0].lon;

                                map.setView([lat,lng], 16);

                                if(marker){
                                    marker.setLatLng([lat,lng]);
                                }else{
                                    marker = L.marker([lat,lng]).addTo(map);
                                }
                                document.getElementById('latitude').value = lat;
                                document.getElementById('longitude').value = lng;

                                statusText.innerText = "Address Found";
                                statusText.style.color = "green";
                            }else{
                                statusText.innerText = "Address not Found";
                                statusText.style.color = "red";

                                document.getElementById('latitude').value = '';
                                document.getElementById('longitude').value = '';
                            }
                        })
                        .catch(error => {
                            statusText.innerText = "Network Error: " + error.message;
                            statusText.style.color = "red";
                        }); 
                    }
                });
            </script>
    <p>Already have an account? <a href="login.php">Log in here</a>.</p>

</body>
</html>

