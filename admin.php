<?php
include 'includes/db.php';

// Handle add form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['event_date'];
    $time = $_POST['event_time'];
    $location = $_POST['location'];
    $seats = (int)$_POST['seats_available'];

    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, event_time, location, seats_available)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $title, $desc, $date, $time, $location, $seats);
    $stmt->execute();
}

// Fetch all events
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Events</title>
</head>
<body>
    <h1>Admin Panel</h1>

    <h2>Add New Event</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required><br><br>
        <textarea name="description" placeholder="Description" required></textarea><br><br>
        <input type="date" name="event_date" required placeholder="yyyy-mm-dd"><br><br>
        <input type="time" name="event_time" required placeholder="HH:MM"><br><br>
        <input type="text" name="location" placeholder="Location" required><br><br>
        <input type="number" name="seats_available" placeholder="Total Seats" required><br><br>
        <button type="submit">Add Event</button>
    </form>

    <h2>All Events</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                Date: <?php echo $row['event_date']; ?> at <?php echo $row['event_time']; ?><br>
                Location: <?php echo htmlspecialchars($row['location']); ?><br>
                Seats: <?php echo $row['seats_available']; ?>
                <hr>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
