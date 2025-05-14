<?php
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">🎟️ Event Booking</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="admin.php" class="nav-link">Admin</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Container -->
<div class="container">
    <h1 class="text-center mb-4">📅 Upcoming Events</h1>

    <div class="row">
        <?php
        $sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="card-text"><strong>Date:</strong> <?php echo $row['event_date']; ?> at <?php echo $row['event_time']; ?></p>
                        <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                        <p class="card-text"><strong>Seats Available:</strong> <?php echo $row['seats_available']; ?></p>
                        <a href="event.php?id=<?php echo $row['id']; ?>" class="btn btn-success w-100">Book Now</a>
                    </div>
                </div>
            </div>
        <?php
            endwhile;
        else:
        ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No upcoming events.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
