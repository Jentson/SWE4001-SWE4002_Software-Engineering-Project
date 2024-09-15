<?php
session_start();
include_once '../database/dbconnect.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['Staff_id'])) {
    header("Location: ../staff/LoginForStaff.php");
    exit;
}

// Fetch user details from the database
$usersession = $_SESSION['Staff_id'];
$res = mysqli_query($con, "SELECT * FROM staff WHERE staff_id=" . $usersession);
$userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);

// Query the database to fetch unread bookings for the current staff
$query = "SELECT * FROM student_bookings WHERE staff_id = '$usersession' AND isRead = 0";
$result = mysqli_query($con, $query);

// Check if there are new unread bookings
if (mysqli_num_rows($result) > 0) {
    // Fetch all unread bookings
    $bookingDetails = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bookingDetails[] = $row;
    }
    $iconColor = 'red'; // Change the icon color to red
    mysqli_query($con, "UPDATE student_bookings SET isRead = 1 WHERE staff_id = '$usersession' AND isRead = 0");
} else {
    // No new unread bookings
    $bookingDetails = [];
    $iconColor = '#ccc'; // Set the default icon color
}

// Retrieve staff's appointment history
$historyQuery = "SELECT * FROM appointment_history WHERE staff_id = '$usersession'";
$historyResult = mysqli_query($con, $historyQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <!-- Include Bootstrap CSS and any other CSS files -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include your custom CSS file if any -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        #notification-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background-color: <?php echo $iconColor; ?>; /* Default color for the icon */
            border-radius: 50%;
            cursor: pointer;
        }
        .dropdown-menu {
            width: 300px; /* Adjust the width as needed */
        }

        .dropdown-menu .notification-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>

    <a href="../staff/logout_staff.php">Logout</a>
</head>

<body>
    <h1>Welcome <?php echo $userRow['staff_name']; ?></h1>
    <p>This is your dashboard.</p>
    <p>Here, you can manage your schedule.</p>

     <!-- Notification dropdown -->
    <div class="dropdown">
        <form id="notification-form" method="post">
            <div id="notification-icon" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
            <div class="dropdown-menu" aria-labelledby="notification-icon">
                <?php if (!empty($bookingDetails)) : ?>
                    <?php $count = 0; ?>
                    <?php foreach ($bookingDetails as $booking) : ?>
                        <?php if ($count < 5) : ?>
                        <a class="dropdown-item" href="../staff/history_appointment.php">
                            The <?php echo $booking['schedule_date']; ?> slot has been booked 
                            <!-- Time: <?php echo $booking['start_time'];?>  -->
                            <!-- - <?php echo $booking['end_time']; ?> -->
                        </a>
                        <?php endif; ?>
                    <?php $count++; ?>
                    <?php endforeach; ?>
                    <?php if (count($bookingDetails) > 5) : ?>
                    <a class="btn btn-link" href="../staff/history_appointment.php">Show More</a>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="dropdown-item">No new notifications</div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Navigation buttons -->
    <button onclick="window.location.href='../staff/addtime.php'" class="btn btn-primary">Add Time</button>
    <button onclick="window.location.href='../staff/autotime.php'" class="btn btn-secondary">Auto Time(Ref)</button>
    <button onclick="window.location.href='../staff/history_appointment.php'" class="btn btn-info">Appointment History</button>

    <!-- Display staff's appointment history -->
    <h3>Appointment History</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Schedule Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Modal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_array($historyResult)) {
                echo "<tr>";
                echo "<td>" . $row['student_id'] . "</td>";
                echo "<td>" . $row['student_name'] . "</td>";
                echo "<td>" . $row['schedule_date'] . "</td>";
                echo "<td>" . $row['start_time'] . "</td>";
                echo "<td>" . $row['end_time'] . "</td>";
                echo "<td>" . $row['modal'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#notification-icon').click(function() {
                $('#notification-icon').css('background-color', '#ccc');
                //$('#notification-form').submit();
            });
        });
    </script>
</body>

</html>
