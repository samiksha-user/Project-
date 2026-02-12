<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

include '../config.php';
include_once 'create_notification.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lost_id = intval($_POST['lost_id'] ?? 0);
    $found_id = intval($_POST['found_id'] ?? 0);
    $lost_user_email = $_POST['lost_user_email'] ?? '';
    $found_user_email = $_POST['found_user_email'] ?? '';

    // Determine which user to notify
    $notify_lost_user = !empty($lost_user_email);
    $notify_found_user = !empty($found_user_email);

    if ($notify_lost_user) {
        // Get lost item and user info
        $sql = "SELECT i.id, COALESCE(i.title, i.title) AS item_name, u.id AS user_id, u.name AS user_name
                FROM items i
                JOIN users u ON i.user_id = u.id
                WHERE i.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $lost_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $message = "🎉 Great news! A potential match has been found for your lost item: '" . htmlspecialchars($row['item_name']) . "'. Please check the match details.";
            createNotification($conn, $row['user_id'], $message);
        }
        $stmt->close();
    }

    if ($notify_found_user) {
        // Get found item and user info
        $sql = "SELECT i.id, COALESCE(i.title, i.title) AS item_name, u.id AS user_id, u.name AS user_name
                FROM items i
                JOIN users u ON i.user_id = u.id
                WHERE i.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $found_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $message = "🎉 Great news! A potential match has been found for your found item: '" . htmlspecialchars($row['item_name']) . "'. Please check the match details.";
            createNotification($conn, $row['user_id'], $message);
        }
        $stmt->close();
    }

    // Redirect back to match page with success message
    header("Location: match.php?notified=1");
    exit;
} else {
    header("Location: match.php");
    exit;
}
?>

