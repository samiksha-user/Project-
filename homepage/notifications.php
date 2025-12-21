<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

include '../config.php';
include_once '../dashboard/create_notification.php';

// Handle mark as read
if (isset($_GET['mark_read'])) {
    $notification_id = intval($_GET['mark_read']);
    markNotificationAsRead($conn, $notification_id, $_SESSION['user_id']);
    header("Location: notifications.php");
    exit;
}

// Handle mark all as read
if (isset($_GET['mark_all_read'])) {
    markAllNotificationsAsRead($conn, $_SESSION['user_id']);
    header("Location: notifications.php");
    exit;
}

// Get all notifications
$notifications = getUserNotifications($conn, $_SESSION['user_id'], 50);
$unread_count = getUnreadNotificationCount($conn, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Notifications | FindIt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="homepage.css" />
  <style>
    .notification-card {
      border-left: 4px solid #0d6efd;
      transition: all 0.3s;
    }
    .notification-card.unread {
      background-color: #f0f8ff;
      border-left-color: #0d6efd;
      font-weight: 500;
    }
    .notification-card.read {
      background-color: #ffffff;
      border-left-color: #6c757d;
      opacity: 0.8;
    }
    .notification-card:hover {
      transform: translateX(5px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .notification-time {
      font-size: 0.85rem;
      color: #6c757d;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <?php require_once "navbar.php"; ?>

  <div class="container mt-5 pt-5">
    <div class="row">
      <div class="col-md-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fw-bold">
            <i class="bi bi-bell-fill me-2"></i>My Notifications
            <?php if ($unread_count > 0): ?>
              <span class="badge bg-danger ms-2"><?= $unread_count ?> unread</span>
            <?php endif; ?>
          </h2>
          <?php if ($unread_count > 0): ?>
            <a href="?mark_all_read=1" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-check-all me-1"></i>Mark All as Read
            </a>
          <?php endif; ?>
        </div>

        <?php if (count($notifications) > 0): ?>
          <div class="list-group">
            <?php foreach ($notifications as $notification): ?>
              <div class="list-group-item notification-card <?= $notification['is_read'] ? 'read' : 'unread' ?> mb-2 rounded">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="flex-grow-1">
                    <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                    <small class="notification-time">
                      <i class="bi bi-clock me-1"></i><?= date('F j, Y g:i A', strtotime($notification['created_at'])) ?>
                    </small>
                  </div>
                  <?php if (!$notification['is_read']): ?>
                    <a href="?mark_read=<?= $notification['id'] ?>" class="btn btn-sm btn-outline-primary ms-3" title="Mark as read">
                      <i class="bi bi-check"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="alert alert-info text-center">
            <i class="bi bi-inbox display-4 d-block mb-3"></i>
            <h5>No notifications yet</h5>
            <p class="mb-0">You'll receive notifications here when matches are found for your reported items.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

