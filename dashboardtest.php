<?php
// 1. Start the session and connect to the database
session_start();
require 'connect.php';

// 2. Security Check: Kick them back to login if they aren't actually logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 3. Graceful Fallback Logic for the Map Coordinates
$canShowNearby = false;
if (!empty($_SESSION['latitude']) && !empty($_SESSION['longitude'])) {
    $userLatitude = $_SESSION['latitude'];
    $userLongitude = $_SESSION['longitude'];
    $canShowNearby = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | CommunityConnect</title>
    <style>
        /* Clean, professional styling for the dashboard */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7f6; color: #333; }
        .navbar { background-color: #0056b3; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .navbar h2 { margin: 0; }
        .btn-logout { background-color: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-logout:hover { background-color: #c82333; }
        .btn-addservice { background-color: #35dc6f; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-addservice:hover { background-color: #2389c8; }
        .main-container { max-width: 900px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        .warning-box { background-color: #fff3cd; color: #856404; padding: 20px; border-radius: 8px; border-left: 5px solid #ffeeba; margin-top: 20px; }
        .warning-box a { color: #856404; font-weight: bold; text-decoration: underline; }
        
        .services-list { margin-top: 20px; }    
        .service-card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 15px; transition: 0.3s; }
        .service-card:hover { border-color: #0056b3; box-shadow: 0 2px 8px rgba(0,86,179,0.1); }
        .service-title { margin: 0 0 10px 0; font-size: 1.2em; color: #0056b3; }
        .service-distance { background: #e9ecef; padding: 3px 8px; border-radius: 12px; font-size: 0.8em; color: #495057; float: right; }
        .service-address { color: #6c757d; font-size: 0.9em; margin-top: 10px; }
        
        .status-badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 0.85em; float: right; }
        .status-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-approved { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .my-requests-section { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px dashed #ccc; }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>CommunityConnect</h2>
        <a href="addService.php" class="btn-addservice">Add Service</a>
        <a href="login.php" class="btn-logout">Logout</a>
        <a href="participation_request.php" class="btn-addservice" style="background-color: #17a2b8;">Join an Event</a>
    </div>

    <div class="main-container">
        <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>This is your central hub for community resources.</p>

        <div class ="my-request-section">
            <h3>My Participation Status</h3>
            <?php
            $currentUserID = $_SESSION['user_id'] ?? 0;

            $reqSQL = "SELECT r.status, r.request_date, s.title, s.eventDate
                        FROM participation_Requests r
                        JOIN Community_services s ON r.service_id = s.Service_id
                        WHERE r.user_id = :user_id
                        ORDER BY r.request_date DESC";
                        
                        try{
                            $reqStmt = $cool -> prepare($reqSQL);
                            $reqStmt -> execute(['user_id' => $currentUserID]);
                            $myRequest = $reqStmt -> fetchAll(PDO::FETCH_ASSOC);

                            if($myRequest){
                                foreach($myRequest as $req){
                                    $badgeClass = ($req['status']) === 'pending' ? 'status-pending' : 'status-approved';
                                   
                                    echo "<div class='service-card'>";
                                    echo "<span class='status-badge {$badgeClass}'>" . htmlspecialchars($req['status']) . "</span>";
                                    echo "<h4 class='service-title'>" . htmlspecialchars($req['title']) . "</h4>";
                                    echo "<p style='margin: 5px 0 0 0; font-size: 0.9em; color: #555;'><strong>Event Date:</strong> " . htmlspecialchars($req['eventDate']) . "</p>";
                                    echo "</div>";
                                }
                            }else{
                                echo "<p style='color: #666; font-style: italic;'>You haven't requested to join any events yet.</p>";
                            }
                        }catch(PDOException $e){
                            echo "<p style='color: red;'>Could not load requests.</p>";
                        }
            ?>
        </div>
        <?php if (!$canShowNearby): ?>
            <div class="warning-box">
                <strong>Location Required:</strong> We couldn't find your coordinates in the system. Please update your profile with your home address so we can show you the community services closest to you!
                <br><br>
                <a href="#">Update Profile Location</a>
            </div>
            
        <?php else: ?>
            <div class="services-list">
                <h3>📍 Community Services Within 5KM</h3>
                
                <?php
                $earthsize = 6371;
                // Executing the math to find nearby services
                $sql = "SELECT title, description, service_address, 
                        ($earthsize * acos(cos(radians(:userLat)) * cos(radians(latitude)) * cos(radians(longitude) - radians(:userLng)) + sin(radians(:userLat)) * sin(radians(latitude)))) AS distance
                        FROM community_services
                        HAVING distance < 5
                        ORDER BY distance ASC";

                try {
                    $stmt = $cool->prepare($sql);
                    $stmt->execute([
                        'userLat' => $userLatitude,
                        'userLng' => $userLongitude
                    ]);
                    
                    $fetchnearbyServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($fetchnearbyServices) {
                        // Loop through each row and print a styled card
                        foreach ($fetchnearbyServices as $service) {
                            $formattedDistance = number_format($service['distance'], 2);
                            
                            echo "<div class='service-card'>";
                            echo "<span class='service-distance'>" . $formattedDistance . " KM away</span>";
                            echo "<h4 class='service-title'>" . htmlspecialchars($service['title']) . "</h4>";
                            echo "<p>" . htmlspecialchars($service['description']) . "</p>";
                            echo "<div class='service-address'>🏢 " . htmlspecialchars($service['service_address']) . "</div>";
                            echo "</div>";
                        } 
                    } else {
                        echo "<p>Looks quiet around here! There are no registered community services within a 5KM radius of your address yet.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p style='color: red;'><strong>System Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                ?>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>