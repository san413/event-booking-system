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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4 text-center">🎫 Admin Panel - Event Management</h1>

    <!-- Add New Event Form -->
    <div class="card mb-5 shadow">
        <div class="card-header bg-primary text-white">
            Add New Event
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" name="event_time" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Seats</label>
                    <input type="number" name="seats_available" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Add Event</button>
            </form>
        </div>
    </div>

    <!-- All Events -->
    <h2 class="mb-3">📋 All Events</h2>
    <?php if ($result->num_rows > 0): ?>
        <div class="list-group shadow">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="list-group-item">
                    <h5 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h5>
                    <p class="mb-1"><?php echo htmlspecialchars($row['description']); ?></p>
                    <small>
                        <strong>Date:</strong> <?php echo $row['event_date']; ?> at <?php echo $row['event_time']; ?><br>
                        <strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?><br>
                        <strong>Seats:</strong> <?php echo $row['seats_available']; ?>
                    </small>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No events found.</div>
    <?php endif; ?>
</div>

</body>
</html>
