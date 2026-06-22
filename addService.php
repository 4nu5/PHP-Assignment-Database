<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service | CommunityConnect</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        input[type="text"], textarea { width: 100%; padding: 10px; margin: 8px 0 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        #map { height: 300px; width: 100%; border-radius: 8px; border: 1px solid #ccc; margin-bottom: 20px; }
        button { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background-color: #218838; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #0056b3; }
    </style>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"> 
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>

    <div class="container">
        <a href="dashboardtest.php" class="back-link">← Back to Dashboard</a>
        <h2>Host a Community Service</h2>
        <p>Fill out the details below to add a new service to the community board.</p>
        
        <form action="addServiceProcess.php" method="POST">
            
            <label for="title">Service Title:</label>
            <input type="text" id="title" name="title" required placeholder="E.g., SS15 Park Cleanup">
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required placeholder="What will volunteers be doing?"></textarea>

            <label for="sdgTarget">SDG Target:</label>
            <input type="text" name="sdgTarget" id="sdgTarget" required placeholder="E.g., SDG 1:">

            <label for="eventDate">Event Date:</label>
            <input type="date" name="eventDate" id="eventDate"><br><br>
            
            <label for="service_address">Location Address:</label>
            <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                 <input type="text" id="service_address" name="service_address" required placeholder="E.g., Jalan SS15/4">
                 <button type="button" id="findbtn" style="width: auto;height: 55px; background-color: #0056b3; margin: 0;">Find On Map</button>
            </div>
            <small id="map-status" style="display:block; margin-bottom: 20px; font-weight: bold;"></small>
            <label>Pinpoint Location on Map:</label>
            <small style="display:block; margin-bottom: 10px; color: #666;">Click the map exactly where the event is happening.</small>
            
            <div id="map"></div>
            
            <input type="hidden" id="latitude" name="latitude" required>
            <input type="hidden" id="longitude" name="longitude" required>
            
            <button type="submit">Publish Service</button>
        </form>
    </div>

    <script>
        
        var map = L.map('map').setView([3.0731, 101.6077], 14);
        
        L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png',{
            attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker;
        map.on('click', function(e){
            if(marker){
                marker.setLatLng(e.latlng);
            }else{
                marker = L.marker(e.latlng).addTo(map);
            }
            
            // Send coords to the hidden inputs
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
            document.getElementById('map-status').innerText="Pin Dropped Manually";
            document.getElementById('map-status').style.color="Green";
        });
            document.getElementById('service_address').addEventListener('keypress',function(event){
                if(event.key === 'Enter'){
                    event.preventDefault();
                    document.getElementById('findbtn').click();
                }
            });

            document.getElementById('findbtn').addEventListener('click', function(){
            var address = document.getElementById('service_address').value;
            var statusText= document.getElementById('map-status');

            if(address.trim() === ''){
                statusText.innerText= "Please Enter an Address";
                statusText.style.color = "red";
                return;
            }
            statusText.innerText = "🔍 Searching OpenStreetMap...";
              statusText.style.color = "#09b300";
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

                    map.setView([lat,lng],16);

                    if(marker){
                        marker.setLatLng([lat,lng]);
                    }else{
                        marker = L.marker([lat,lng]).addTo(map);
                    }

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    statusText.innerText = "Address found";
                    statusText.style.color = "green";
                }else{
                     statusText.innerText = "Address not found";
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
</body>
</html>