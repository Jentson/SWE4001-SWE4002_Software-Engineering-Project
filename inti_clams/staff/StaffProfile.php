<?php
require_once "../database/db.php";
include '../validation/session.php'; 

$staffId = $_SESSION['Staff_id'];

$staffInfo = mysqli_query($con,
"SELECT *,
department.department_name AS dept_name,
position.position_name AS position_name
FROM staff
JOIN department ON department.department_id = staff.department_id
JOIN position ON position.position_id = staff.position_id
WHERE staff.staff_id = '$staffId'
");

$staffProfile = mysqli_fetch_assoc($staffInfo);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
 
 <!-- Navbar -->
 <?php  include '../index/staff_navbar.php'; ?>

   <!-- Page Content -->
   <div id="page-content-wrapper">
            <div class="container-fluid px-4">
                <div class="row g-3 my-2">
                    <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-center text-uppercase fs-3 fw-bolder profile-heading">Staff Information</div>
                    <!-- Edit Button -->
                    <button class="btn btn-primary" id="edit-button" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                    </div>
                        <ul class="list-group profile-details">
                            <li class="list-group-item">Name: <?php echo htmlspecialchars($staffProfile['staff_name']); ?></li>
                            <li class="list-group-item">Stafft ID: <?php echo htmlspecialchars($staffProfile['staff_id']); ?></li>
                            <li class="list-group-item">Address: <?php echo htmlspecialchars($staffProfile['staff_address']); ?></li>
                            <li class="list-group-item">Phone: <?php echo htmlspecialchars($staffProfile['phone_number']); ?></li>
                            <li class="list-group-item">IC/Passport: <?php echo htmlspecialchars($staffProfile['staff_identity_number']); ?></li>
                            <li class="list-group-item">Faculty: <?php echo htmlspecialchars($staffProfile['department_name']); ?></li>
                            <li class="list-group-item">Position: <?php echo htmlspecialchars($staffProfile['position_name']); ?></li>
                        </ul>

                <!-- Edit Information -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Staff Info</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form id="edit-form" action="update_staff_info.php" method="POST">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($staffProfile['staff_address']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input class="form-control" type="number" id="phone" name="phone" value="<?php echo htmlspecialchars($staffProfile['phone_number']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password (required to change password)</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                    </div>
                    </div>
                </div>
                </div>
</body>
</html>
