<?php
include 'includes/db.php';

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

// Fetch event
$sql = "SELECT * FROM events WHERE id = $event_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Event not found.");
}

$event = $result->fetch_assoc();

// Handle booking
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($event['seats_available'] > 0) {
        $stmt = $conn->prepare("INSERT INTO bookings (event_id, user_name, user_email) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $event_id, $name, $email);
        $stmt->execute();

        // Reduce available seats
        $conn->query("UPDATE events SET seats_available = seats_available - 1 WHERE id = $event_id");

        $message = "🎉 Booking successful!";
        // Refresh event details
        $event = $conn->query("SELECT * FROM events WHERE id = $event_id")->fetch_assoc();
    } else {
        $message = "❌ Sorry, this event is fully booked.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Event - <?php echo htmlspecialchars($event['title']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($event['title']); ?></h1>
    <p><strong>Date:</strong> <?php echo $event['event_date']; ?> at <?php echo $event['event_time']; ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
    <p><strong>Seats Left:</strong> <?php echo $event['seats_available']; ?></p>

    <?php if ($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>

    <?php if ($event['seats_available'] > 0): ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Your Name" required><br><br>
            <input type="email" name="email" placeholder="Your Email" required><br><br>
            <button type="submit">Book Now</button>
        </form>
    <?php else: ?>
        <p><strong>No seats available.</strong></p>
    <?php endif; ?>
</body>
</html>
