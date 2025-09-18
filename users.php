<?php
// Connect to database (use same credentials as in mail.php)
$conn = new mysqli("localhost", "root", "0000", "dbpro");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users in ascending order by username
$sql = "SELECT username, email FROM users ORDER BY username ASC";
$result = $conn->query($sql);

// Display numbered list
if ($result && $result->num_rows > 0) {
    $i = 1;
    echo "<h2>Registered Users</h2>";
    echo "<ol>"; // ordered (numbered) list
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['username']) . " (" . htmlspecialchars($row['email']) . ")</li>";
        $i++;
    }
    echo "</ol>";
} else {
    echo "<p>No users found.</p>";
}

$conn->close();
?>
