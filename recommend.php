<?php
$host = "localhost";
$user = "root";
$pass = "13072005";
$db = "college_recommendation";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
$course   = $_POST['course'] ?? '';
$location = $_POST['location'] ?? '';
$fees     = $_POST['fees'] ?? '';
$cutoff   = $_POST['cutoff'] ?? '';
$search = $_POST['search'] ?? '';

$sql = "SELECT * FROM colleges WHERE 1=1";  // start with true condition

if (!empty($course)) {
    $sql .= " AND course = '" . $conn->real_escape_string($course) . "'";
}

if (!empty($fees)) {
    $sql .= " AND fees <= " . intval($fees);
}

if (!empty($cutoff)) {
    $sql .= " AND cutoff <= " . intval($cutoff);
}

if (!empty($location)) {
    $sql .= " AND location LIKE '%" . $conn->real_escape_string($location) . "%'";
}

$sql .= " ORDER BY rating ASC;";
// 🧠 Basic search across multiple fields
$sql = "SELECT * FROM colleges WHERE 
            name LIKE '%" . $conn->real_escape_string($search) . "%' 
         OR course LIKE '%" . $conn->real_escape_string($search) . "%' 
         OR location LIKE '%" . $conn->real_escape_string($search) . "%'
         ORDER BY rating DESC";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
  <title>Recommended Colleges</title>
  <style>
    body { font-family: Arial; margin: 40px; }
    table { width: 80%; margin: auto; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
    th { background: #2c3e50; color: white; }
  </style>
</head>
<body>
  <h2>Recommended Colleges</h2>
  <table>
    <tr>
      <th>Name</th>
      <th>Location</th>
      <th>Course</th>
      <th>Fees</th>
      <th>Rating</th>
      <th>Cutoff</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['location']}</td>
                    <td>{$row['course']}</td>
                    <td>{$row['fees']}</td>
                    <td>{$row['rating']}</td>
                    <td>{$row['cutoff']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No colleges match your criteria.</td></tr>";
    }
    $conn->close();
    ?>
  </table>
</body>
</html>
