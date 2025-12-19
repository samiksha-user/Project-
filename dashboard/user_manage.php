<?php
include "../config.php";

// Handle user deletion
if(isset($_GET['delete_id'])){
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE user_id = $delete_id");
    header("Location: admin_users.php");
    exit;
}

// Fetch users with report count
$sql = "

SELECT 
  u.user_id,
  u.fullname AS name,
  u.email,
  COUNT(i.item_id) AS reports_filed
FROM users u
LEFT JOIN items i ON u.user_id = i.user_id
WHERE u.role != 'admin'   -- exclude admin users
GROUP BY u.user_id
";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>User Management</title>
<style>
body{
    font-family:Arial, sans-serif;
    background:#f5f6fa;
    margin:0;
}
.dashboard {
  display: flex;
  min-height: 100vh;
  overflow-x:hidden;
}
.container{
    margin-left:400px;
    padding:20px 30px;
}
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}
th,td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
}
th{
    background:#f0f2f5;
}
tr:hover{
    background:#f1f1f1;
}
.btn{
    padding:6px 12px;
    background:#3498db;
    color:#fff;
    border:none;
    border-radius:4px;
    cursor:pointer;
    transition:0.3s;
    margin-right:5px;
}
.btn:hover{
    background:#2980b9;
}
.btn.delete{
    background:#e74c3c;
}
.btn.delete:hover{
    background:#c0392b;
}
.sidebar{
    width:200px;
    height:100vh;
    background:#1e272e;
    color:#fff;
    position:fixed;
    top:0;
    left:0;
    z-index:1000;
}
.sidebar h3{
    padding:12px;
}
.sidebar a{
    display:block;
    color:#fff;
    padding:12px;
    text-decoration:none;
}
.sidebar a.active{
    background:#3498db;
}
</style>
<script>
function confirmDelete(userId){
    if(confirm("Are you sure you want to delete this user?")){
        window.location.href = "admin_users.php?delete_id=" + userId;
    }
}
function notifyUser(email){
    alert("Notification sent to: " + email);
}
</script>
</head>
<body>
<div class="dashboard">
<?php require_once "sidebar.php"; ?>

<div class="container">
<h2>User Management</h2>
<table>
<tr>
  <th>User ID</th>
  <th>Name</th>
  <th>Email</th>
  <th>Reports Filed</th>
  <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
  <td>user-<?php echo $row['user_id']; ?></td>
  <td><?php echo htmlspecialchars($row['name']); ?></td>
  <td><?php echo htmlspecialchars($row['email']); ?></td>
  <td><?php echo $row['reports_filed']; ?></td>
  <td>
    <button class="btn" onclick="notifyUser('<?php echo $row['email']; ?>')">Notify</button>
    <button class="btn delete" onclick="confirmDelete(<?php echo $row['user_id']; ?>)">Delete</button>
  </td>
</tr>
<?php endwhile; ?>

</table>
</div>
</div>
</body>
</html>
