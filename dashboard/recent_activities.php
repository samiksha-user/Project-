<?php
include "../config.php";

// Fetch recent activities - try status/title first (from schema), fallback to report_type/item_name
$sql = "SELECT id, title AS item_name, description, status AS report_type, created_at
        FROM items 
        ORDER BY created_at DESC 
        LIMIT 5";
$result = $conn->query($sql);

// If query fails, try with report_type/item_name columns
if ($result === false) {
    $sql = "SELECT id, item_name, description, report_type, created_at
            FROM items 
            ORDER BY created_at DESC 
            LIMIT 5";
    $result = $conn->query($sql);
}
?>