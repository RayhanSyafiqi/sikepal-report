<?php

session_start();

echo "<h1>🔍 Debug Session</h1>";

echo "<h2>Session Data</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Role Check</h2>";
echo "Role: " . ($_SESSION['role'] ?? 'Tidak ada') . "<br>";

if ($_SESSION['role'] === 'admin') {
    echo "✅ Admin menu should appear<br>";
} elseif ($_SESSION['role'] === 'sales') {
    echo "✅ Sales menu should appear<br>";
} else {
    echo "❌ Role tidak dikenali!<br>";
}

echo "<h2>User Info</h2>";
echo "User ID: " . ($_SESSION['user_id'] ?? 'Tidak ada') . "<br>";
echo "Name: " . ($_SESSION['name'] ?? 'Tidak ada') . "<br>";
echo "Photo: " . ($_SESSION['photo'] ?? 'Tidak ada') . "<br>";

echo "<h2>Database Users</h2>";
require_once 'app/config/database.php';

$stmt = $pdo->query("SELECT id, name, email, role FROM users");
$users = $stmt->fetchAll();

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . $user['name'] . "</td>";
    echo "<td>" . $user['email'] . "</td>";
    echo "<td>" . $user['role'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>