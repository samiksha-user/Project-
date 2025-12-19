<?php
include "../config.php";

// Fetch recent activities
$sql = "SELECT * FROM items 
        ORDER BY created_at DESC 
        LIMIT 5";
$result = $conn->query($sql);
?>