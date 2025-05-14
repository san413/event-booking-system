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
$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC");

// Fetch bookings grouped by event
$bookings = $conn->query("
    SELECT e.id AS event_id, e.title, b.user_name, b.user_email, b.booking_time
    FROM bookings b
    JOIN events e ON b.event_id = e.id
    ORDER BY e.event_date ASC, b.booking_time ASC
");

// Group bookings by event_id
$groupedBookings = [];
while ($row = $bookings->fetch_assoc()) {
    $groupedBookings[$row['event_id']][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Event Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4 text-center text-primary">Admin Panel - Event Management</h1>

        <!-- Add New Event -->
        <div class="card mb-5">
            <div class="card-header bg-success text-white">
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
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Date</label>
                            <input type="date" name="event_date" class="form-control" required>
                        </div>
                        <div class="col">
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
        <div class="card mb-5">
            <div class="card-header bg-info text-white">
                All Events
            </div>
            <div class="card-body">
                <?php if ($events->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($e = $events->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <h5><?php echo htmlspecialchars($e['title']); ?></h5>
                                <p>
                                    <strong>Date:</strong> <?php echo $e['event_date']; ?> at <?php echo $e['event_time']; ?><br>
                                    <strong>Location:</strong> <?php echo htmlspecialchars($e['location']); ?><br>
                                    <strong>Seats:</strong> <?php echo $e['seats_available']; ?>
                                </p>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No events found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bookings per Event -->
        <div class="card mb-5">
            <div class="card-header bg-warning text-dark">
                Bookings per Event
            </div>
            <div class="card-body">
                <?php if (!empty($groupedBookings)): ?>
                    <?php foreach ($groupedBookings as $eventId => $bookings): ?>
                        <h5 class="mt-4 text-primary"><?php echo htmlspecialchars($bookings[0]['title']); ?></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Booking Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $b): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($b['user_name']); ?></td>
                                            <td><?php echo htmlspecialchars($b['user_email']); ?></td>
                                            <td><?php echo date("d-m-Y H:i", strtotime($b['booking_time'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No bookings found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
