<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || trim(strtolower($_SESSION['role'])) !== 'admin') {
    die("<h2 style='color:red; text-align:center;'>Access Denied. You do not have Administrator privileges.</h2><div style='text-align:center;'><p>Current Role: '" . htmlspecialchars($_SESSION['role'] ?? 'None') . "'</p><a href='dashboardtest.php'>Go Back</a></div>");
}

$reqSql = "SELECT pr.request_id, pr.status, pr.request_date, pr.user_id, u.username, cs.title
                FROM participation_Requests pr
                JOIN users u ON pr.user_id = u.user_id
                JOIN Community_services cs ON pr.service_id = cs.Service_id
                ORDER BY pr.request_date DESC";

$reqStmt = $cool -> query($reqSql);
$requests = $reqStmt -> fetchAll(PDO::FETCH_ASSOC);

$serviceSql = "SELECT Service_id, title, eventDate FROM Community_services
                ORDER BY eventDate DESC";
$serviceStmt = $cool -> prepare($serviceSql);
$serviceStmt -> execute();
$services = $serviceStmt -> fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | CommunityConnect</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #0056b3; color: white; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; color: white; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-approve { background-color: #28a745; }
        .btn-reject { background-color: #ffc107; color: #333; }
        .btn-delete { background-color: #dc3545; }
        .badge { padding: 4px 8px; border-radius: 12px; font-weight: bold; font-size: 0.85em; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboardtest.php" style="color: #0056b3; text-decoration: none;">← Back to Main Dashboard</a>
        <h1 style="border-bottom: 2px solid #eee; padding-bottom: 10px;">Admin Control Center</h1>

        <h2>1. Manage Participation Requests</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Event</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($requests as $req): ?>
            <tr>
                <td><?= htmlspecialchars($req['request_date']) ?></td>
                <td><strong><?= htmlspecialchars($req['username']) ?></strong></td>
                <td><?= htmlspecialchars($req['title']) ?></td>
                <td>
                    <span class="badge status-<?= strtolower($req['status']) ?>">
                        <?= htmlspecialchars(ucfirst($req['status'])) ?>
                    </span>
                </td>
                <td>
                    <?php if (strtolower($req['status']) === 'pending'): ?>
                        <a href="processRequest.php?id=<?= $req['request_id'] ?>&action=approved&uid=<?= $req['user_id'] ?>&title=<?= urlencode($req['title']) ?>" class="btn btn-approve">Approve</a>
                        <a href="processRequest.php?id=<?= $req['request_id'] ?>&action=rejected&uid=<?= $req['user_id'] ?>&title=<?= urlencode($req['title']) ?>" class="btn btn-reject">Reject</a>
                    <?php else: ?>
                        <em>Processed</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h2>2. Manage Community Services</h2>
        <table>
            <tr>
                <th>Event ID</th>
                <th>Title</th>
                <th>Event Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($services as $srv): ?>
            <tr>
                <td>#<?= $srv['Service_id'] ?></td>
                <td><?= htmlspecialchars($srv['title']) ?></td>
                <td><?= htmlspecialchars($srv['eventDate']) ?></td>
                <td>
                    <a href="deleteService.php?id=<?= $srv['Service_id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to permanently delete this event?');">Delete Event</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>