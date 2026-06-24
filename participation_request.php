<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

require 'connect.php';

$usrLat = $_SESSION['latitude'] ?? 0;
$usrLng = $_SESSION['longitude'] ?? 0;

$sort = isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'DESC' : 'ASC';

$sql = "SELECT id, title, description, eventDate, service_address, latitude, longitude,
        ( 6371 * acos( 
            cos( radians(:userLat1) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(:userLng) ) + 
            sin( radians(:userLat2) ) * sin( radians( latitude ) ) 
        ) ) AS distance 
        FROM Community_services 
        ORDER BY distance $sort";

$stmt = $cool -> prepare($sql);
$stmt -> execute([
    'userLat1' => $usrLat,
    'userLat2' => $usrLat,
    'userLng' => $usrLng
]);

$services = $stmt -> fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Opportunities | CommunityConnect</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f7f6; padding: 20px; }
        .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; max-width: 1000px; margin: 0 auto 20px auto; }
        .sort-select { padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; }
        .events-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; max-width: 1000px; margin: 0 auto; }
        .event-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .event-card h3 { margin-top: 0; color: #333; }
        .distance-badge { background: #e0f2f1; color: #00796b; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.9em; display: inline-block; margin-bottom: 10px; }
        .join-btn { background: #0056b3; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; margin-top: 15px; transition: background 0.3s; }
        .join-btn:hover { background: #004494; }
        .join-btn:disabled { background: #ccc; cursor: not-allowed; }
        .alert { padding: 10px; border-radius: 5px; margin-top: 10px; display: none; text-align: center; font-size: 14px; }
        .back-link { display: inline-block; text-decoration: none; color: #0056b3; }
    </style>
</head>
<body>

    <div class="header-controls">
        <div>
            <a href="dashboardtest.php" class="back-link">← Back to Dashboard</a>
            <h2>Local Service Projects</h2>
        </div>
        
        <form id="sortForm" method="GET">
            <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit();">
                <option value="asc" <?= $sort === 'ASC' ? 'selected' : '' ?>>Nearest to Furthest</option>
                <option value="desc" <?= $sort === 'DESC' ? 'selected' : '' ?>>Furthest to Nearest</option>
            </select>
        </form>
    </div>

    <div class="events-grid">
       <?php foreach ($services as $service): ?>
            <div class="event-card">
                <h3><?= htmlspecialchars($service['title']) ?></h3>
                <span class="distance-badge">📍 <?= number_format($service['distance'], 2) ?> km away</span>
                <p><strong>Date:</strong> <?= htmlspecialchars($service['eventDate']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($service['service_address']) ?></p>
                <p><?= htmlspecialchars($service['description']) ?></p>
                
                <form action="process_participation.php" method="POST">
                    <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                    <button type="submit" class="join-btn">Request to Join</button>
                </form>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($services)): ?>
            <p style="text-align: center; grid-column: 1 / -1;">No community events found at this time.</p>
        <?php endif; ?>
    </div>

    <script>
        document.querySelectorAll('.join-btn').forEach(button =>{
            button.addEventListener('click', function(){
                const serviceID = this.getAttribute('data-service-id');
                const btn = this;
                const alertBox = document.getElementById('alert-' + serviceID);


                btn.disabled = true;
                
                fetch('participation_requestProcess',{
                    method: 'POST',
                    headers:{
                        'content-Type' : 'application/json',
                    },
                    body: JSON.stringify({ serviceID: serviceID})
                })
                .then(Response => Response.json())
                .then(data => {
                    alertBox.style.display = 'block';
                if(data.status === 'success'){
                    alertBox.style.backgroundColor = 'Green';
                    alertBox.style.color = '#155724';
                    alertBox.innerText = data.message;
                    btn.style.display = 'none';
                }else{
                    alertBox.style.backgroundColor = '#f8d7da';
                        alertBox.style.color = '#721c24';
                        alertBox.innerText = data.message;
                        btn.disabled = false;
                        btn.innerText = "Request to Join";
                }
                })
                .catch(error => {
                    console.error('Error' , error);
                    alertBox.style.display = 'block';
                    alertBox.style.backgroundColor = '#f8d7da';
                    alertBox.style.color = '#721c24';
                    alertBox.innerText = "Network Error. Please try again.";
                    btn.disabled = false;
                    btn.innerText = "Request to Join";
                });
            });
        });
    </script>
</body>
</html>