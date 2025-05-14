<?php
include 'includes/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Booking System</title>
    <link rel="stylesheet" href="assets/style.css"> <!-- Optional styling -->
</head>
<body>
    <h1>Upcoming Events</h1>

    <?php
    $sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 15px;">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><strong>Date:</strong> <?php echo $row['event_date']; ?> at <?php echo $row['event_time']; ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
            <p><strong>Seats Available:</strong> <?php echo $row['seats_available']; ?></p>
            <a href="event.php?id=<?php echo $row['id']; ?>">Book Now</a>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>No upcoming events.</p>";
    endif;

    $conn->close();
    ?>
</body>
</html>
