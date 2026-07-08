<?php
require_once 'app/config/database.php';

echo "<h1>🔍 Debug Foto User</h1>";

$stmt = $pdo->query("SELECT id, name, photo, photo_public_id FROM users");
$users = $stmt->fetchAll();

echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr style='background: #f1f5f9;'>";
echo "<th>ID</th>";
echo "<th>Name</th>";
echo "<th>Photo URL</th>";
echo "<th>Public ID</th>";
echo "<th>Valid URL</th>";
echo "</tr>";

foreach ($users as $user) {
    $photo = $user['photo'] ?? 'default.jpg';
    $isValid = filter_var($photo, FILTER_VALIDATE_URL);
    $status = $isValid ? '✅' : '❌';
    
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . htmlspecialchars($user['name']) . "</td>";
    echo "<td style='word-break: break-all;'>" . htmlspecialchars($photo) . "</td>";
    echo "<td>" . ($user['photo_public_id'] ?? '-') . "</td>";
    echo "<td>" . $status . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>Test URLs</h2>";
foreach ($users as $user) {
    $photo = $user['photo'] ?? 'default.jpg';
    if (!empty($photo) && $photo !== 'default.jpg') {
        echo "<p><strong>" . htmlspecialchars($user['name']) . ":</strong> ";
        echo "<a href='" . htmlspecialchars($photo) . "' target='_blank'>" . htmlspecialchars($photo) . "</a>";
        echo " <span style='color: " . (filter_var($photo, FILTER_VALIDATE_URL) ? 'green' : 'red') . ";'>" . (filter_var($photo, FILTER_VALIDATE_URL) ? '✅' : '❌') . "</span>";
        echo "</p>";
    }
}

echo "<h2>BASE_URL</h2>";
echo "BASE_URL: " . BASE_URL . "<br>";
echo "Full path: " . BASE_URL . "uploads/photos/default.jpg<br>";

echo "<h2>Folder Uploads</h2>";
$uploadDir = __DIR__ . '/uploads/photos/';
echo "Path: " . $uploadDir . "<br>";
echo "Exists: " . (is_dir($uploadDir) ? '✅' : '❌') . "<br>";

if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    echo "Files: " . implode(', ', $files) . "<br>";
}
?>