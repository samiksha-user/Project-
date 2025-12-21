<?php
/**
 * Function to create a notification for a user
 * Call this when a match is found
 */
if (!function_exists('createNotification')) {
function createNotification($conn, $user_id, $message) {
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)");
    if ($stmt === false) {
        error_log("Notification Error: " . $conn->error);
        return false;
    }
    $stmt->bind_param("is", $user_id, $message);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
}

/**
 * Get unread notifications count for a user
 */
if (!function_exists('getUnreadNotificationCount')) {
function getUnreadNotificationCount($conn, $user_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM notifications WHERE user_id = ? AND is_read = 0");
    if ($stmt === false) {
        return 0;
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'] ?? 0;
}
}

/**
 * Get all notifications for a user
 */
if (!function_exists('getUserNotifications')) {
function getUserNotifications($conn, $user_id, $limit = 10) {
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
    if ($stmt === false) {
        return [];
    }
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
    $stmt->close();
    return $notifications;
}
}

/**
 * Mark notification as read
 */
if (!function_exists('markNotificationAsRead')) {
function markNotificationAsRead($conn, $notification_id, $user_id) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("ii", $notification_id, $user_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
}

/**
 * Mark all notifications as read for a user
 */
if (!function_exists('markAllNotificationsAsRead')) {
function markAllNotificationsAsRead($conn, $user_id) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("i", $user_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
}
?>

