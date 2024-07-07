<?php
require_once 'db_functions.php'; // Use require_once to ensure it's included only once

session_start();

if (!isset($_SESSION['Staff_id'])) 
{
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    session_destroy();
    exit();
}

// Establish database connection
$conn = connectDB();

// Fetch pending and frozen students for display
$sql = "SELECT student_id, student_name, status_id FROM student WHERE status_id IN (1, 6)";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Accounts</title>
    <link rel="stylesheet" href="consultationsystem/css/styles.css"> <!-- Include your CSS file -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
        <!-- Images -->
        <div class="row justify-content-center">
            <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>
    
    <div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-end mb-2">
                <a href="adminmain.php" class="btn btn-secondary">Back</a>
            </div>

                <div class="card shadow">
                    <div class="card-title text-center border-bottom">
                        <h2 class="p-3">Manage Student Accounts</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="needs-validation">
                            <div class="mb-4">
                                <h4>Pending and Frozen Accounts</h4>
                                <!-- Checkbox to select all -->
                                
                                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" style="vertical-align: middle;">
                                            <div class="form-check text-center">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                <label class="form-check-label" for="selectAll">Select All</label>
                                            </div>
                                        </th>
                                        <th scope="col" style="vertical-align: middle;">Student Name</th>
                                        <th scope="col" style="vertical-align: middle;">Student ID</th>
                                        <th scope="col" style="vertical-align: middle;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                              if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $status = $row['status_id'] == 1 ? 'Pending' : 'Frozen';
                                    echo '<tr>';
                                    echo '<td><div class="form-check text-center"><input class="form-check-input studentCheckbox" type="checkbox" name="students[]" value="' . $row['student_id'] . '"></div></td>';
                                    echo '<td>' . htmlspecialchars($row['student_name']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['student_id']) . '</td>';
                                    echo '<td>' . htmlspecialchars($status) . '</td>';
                                    echo '</tr>';
                                }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center">No pending or frozen accounts found.</td></tr>';
                                }

                                ?>
                                </tbody>
                            </table>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success" name="approve">Approve</button>
                                <button type="submit" class="btn btn-danger" name="reject">Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function(){
            // Toggle select all checkboxes
            $('#selectAll').click(function(){
                $('.studentCheckbox').prop('checked', this.checked);
            });
            // Check if all student checkboxes are checked
            $('.studentCheckbox').click(function(){
                if($('.studentCheckbox:checked').length == $('.studentCheckbox').length){
                    $('#selectAll').prop('checked', true);
                }else{
                    $('#selectAll').prop('checked', false);
                }
            });
        });
    </script>
</body>
</html>

<?php
// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['students'])) {
    $student_ids = $_POST['students'];
    $frozen_accounts = []; // Array to store frozen account IDs
    $approve_accounts = [];
    $reject_accounts = [];

    // Approve or reject selected students
    foreach ($student_ids as $student_id) {
        // Fetch student status
        $sql = "SELECT status_id FROM student WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $status_id = $row['status_id'];

            // Update student status
            if (isset($_POST['approve'])) {
                if ($status_id == 1) {
                    // For pending accounts
                    $update_sql = "UPDATE student SET status_id = 2 WHERE student_id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("s", $student_id);
                    $approve_accounts[] = $student_id;
                } elseif ($status_id == 6) {
                    // For frozen accounts
                    $new_password = password_hash("123456", PASSWORD_DEFAULT);
                    $update_sql = "UPDATE student SET student_pass = ?, status_id = 2 WHERE student_id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ss", $new_password, $student_id);
                    
                    // Add frozen account to the array
                    $frozen_accounts[] = $student_id;
                }

                if ($update_stmt->execute()) {
                    echo '<div class="alert alert-success mt-3">Status updated successfully for student ID ' . $student_id . '.</div>';
                } else {
                    echo '<div class="alert alert-danger mt-3">Error updating status for student ID ' . $student_id . ': ' . $update_stmt->error . '</div>';
                }

            } elseif (isset($_POST['reject'])) {
                $status = 3; // Rejected status

                $reject_sql = "UPDATE student SET status_id = ? WHERE student_id = ?";
                $reject_stmt = $conn->prepare($reject_sql);
                $reject_stmt->bind_param("is", $status, $student_id);
                $reject_accounts[] = $student_id;
                if ($reject_stmt->execute()) {
                    echo '<div class="alert alert-success mt-3">Status updated successfully for student ID ' . $student_id . '.</div>';
                } else {
                    echo '<div class="alert alert-danger mt-3">Error updating status for student ID ' . $student_id . ': ' . $reject_stmt->error . '</div>';
                }
            }
        } else {
            echo '<div class="alert alert-danger mt-3">Error preparing statement for student ID ' . $student_id . '.</div>';
        }
    }

    // Include send_reset_link.php if there are frozen accounts
    if (!empty($frozen_accounts)) {
        // Serialize the array and encode it in base64
        $frozen_accounts_encoded = base64_encode(serialize($frozen_accounts));
        echo '<form id="sendResetForm" method="POST" action="send_reset_link.php">';
        echo '<input type="hidden" name="frozen_accounts" value="' . $frozen_accounts_encoded . '">';
        echo '</form>';
        echo '<script>document.getElementById("sendResetForm").submit();</script>';
    }
    if (!empty($approve_accounts)) {
        // Serialize the array and encode it in base64
        $approve_accounts_encoded = base64_encode(serialize($approve_accounts));
        echo '<form id="sendResetForm1" method="POST" action="send_email.php">';
        echo '<input type="hidden" name="approve_accounts" value="' . $approve_accounts_encoded . '">';
        echo '</form>';
        echo '<script>document.getElementById("sendResetForm1").submit();</script>';
    }
    if (!empty($reject_accounts)) {
        // Serialize the array and encode it in base64
        $reject_accounts_encoded = base64_encode(serialize($reject_accounts));
        echo '<form id="sendResetForm2" method="POST" action="send_email.php">';
        echo '<input type="hidden" name="reject_accounts" value="' . $reject_accounts_encoded . '">';
        echo '</form>';
        echo '<script>document.getElementById("sendResetForm2").submit();</script>';
    }
}

?>
