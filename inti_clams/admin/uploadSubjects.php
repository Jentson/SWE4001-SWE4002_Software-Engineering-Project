<?php
require_once "../database/db.php";

session_start();

if (!isset($_SESSION['Staff_id'])) 
{
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../intizh/login.php";</script>';
    session_destroy();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Staff</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="bg-light">
     <!-- Images -->
     <div class="row justify-content-center">
        <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>
<div class="container mt-5">
    
    <div class="row justify-content-center">
        <div class="col-md-6">
        <div class="d-flex justify-content-end mb-2">
        <a href="adminmain.php" class="btn btn-secondary">Back</a>
    </div>
            <div class="card">
                <div class="card-header text-center">
                    <h2>Upload CSV File For Subject</h2>
                </div>
                <div class="card-body">
                    <form action="uploadSubject.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="file" class="form-label">Select CSV file to upload:</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                            <div class="invalid-feedback">
                                Please select a file.
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="submit" class="btn btn-primary">Upload CSV</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
