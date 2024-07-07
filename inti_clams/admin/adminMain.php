<?php
require_once "../database/db.php";
require_once "../validation/session.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="SWE20004" />
    <meta name="keywords" content="HTML,CSS,Javascript" />
    <meta name="author" content="Eazy 4 Leave" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="styles/style.css" rel="stylesheet">
</head>
<body>

    <header>
    <div class ="container">
        <!-- Images -->
        <div class="row justify-content-center">
        <a href="../admin/adminMain.php">
            <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
        </a>
        </div>

        <div class="d-flex justify-content-between">
            <button class="btn btn-danger ms-auto" onclick="handleLogout()">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>

        <script>
            // Function to handle logout
            function handleLogout() {
                // Perform logout actions here
                // For example, redirect to logout.php to destroy session
                window.location.href = "logout.php";
            }

            function handleCreateStudentAccount() {
                window.location.href = "Register_student.php";
            }
            function handleUploadStudentAccount(){
                window.location.href = "uploadStudents.php";
            }
            function handleViewStudentTable(){
                window.location.href = "viewStudents.php";
            }
            function handleResetStudent() {
                window.location.href = "resetPassword(Student).php";
            }
            function handleStudentAccountApproval(){
                window.location.href = "manageStudents.php";
            }
            function handleRegisterStaff(){
                window.location.href = "Register_staff.php";
            }
            function handleUploadStaff(){
                window.location.href = "uploadStaffs.php";
            }
            function handleViewStaff(){
                window.location.href = "viewStaff.php";
            }
            function handleResetStaff(){
                window.location.href = "resetPassword(Staff).php";
            }
            function handleAddSubject(){
                window.location.href = "addSubject.php";
            }
            function handleUploadStudentSubject(){
                window.location.href = "uploadStudent_Subjects.php";
            }
            function handleUploadSubject(){
                window.location.href = "uploadSubjects.php";
            }
            function handleViewStudentSubject(){
                window.location.href = "viewStudent_Subject.php";
            }
            function handleViewSubject(){
                window.location.href = "viewSubject.php";
            }
            function handleViewLeaveHistory(){
                window.location.href = "viewLeave_Application.php";
            }
            function handleViewConsultation(){
                window.location.href = "viewConsultation_history.php";
            }
            function handleAddDepartment(){
                window.location.href = "addDepartment.php";
            }
            function handleAddProgram(){
                window.location.href = "addProgram.php";
            }
            function handleAddServiceDepartment(){
                window.location.href = "addServiceDepartment.php";
            }
            function handleAddStudentSubject(){
                window.location.href = "addStudentSubject.php";
            }
        </script>
        <style>
            .d-grid{
                padding: 5px;
            }
            body{
                /*background-image: url("../admin/images/background.jpg");*/
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }
        </style>
    </header>

    <div class="col-md-12 bg-light text-left">
        <!-- Additional content can go here -->
        
    </div>
    <div class="container mt-5" id="bg">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        Student
                    </div>
                    <div class="card-body">
                        <!-- Content for Container 1 -->
                            <div class="d-grid">
                                <button class="btn btn-primary" onclick="handleCreateStudentAccount()">Register Students</button>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary" onclick="handleUploadStudentAccount()">Add Multiple Students</button>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary" onclick="handleViewStudentTable()">View Students</button>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary" onclick="handleStudentAccountApproval()">Student Account Approval</button>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary" onclick="handleResetStudent()">Reset Password (Student)</button>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        Staff
                    </div>
                    <div class="card-body">
                        <!-- Content for Container 2 -->
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleRegisterStaff()">Register Staff</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleUploadStaff()">Add Multiple Staff</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleViewStaff()">View Staffs</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleResetStaff()">Reset Password (Staff)</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        Others
                    </div>
                    <div class="card-body">
                        <!-- Content for Container 3 -->
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleAddDepartment()">Add Faculty</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleAddServiceDepartment()">Add Service Department</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleAddProgram()">Add Program</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleAddSubject()">Add Subjects</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleUploadSubject()">Add Multiple Subjects</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleAddStudentSubject()">Add Student Subject</button>
                        </div>  
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleUploadStudentSubject()">Add Multiple Students Subjects</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleViewSubject()">View Subjects</button>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleViewStudentSubject()">View Students taking Subjects</button>
                        </div>                    
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleViewLeaveHistory()">View Leave Application Histrory</button>
                        </div>  
                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="handleViewConsultation()">View Consultation History</button>
                        </div>


    
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
</body>
</html>
