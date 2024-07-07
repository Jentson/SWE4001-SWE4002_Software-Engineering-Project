<?php
require_once "../database/db.php";

// Function to get department ID from department name
function getDepartmentId($department_name, $con) {
    $query = "SELECT department_id FROM Department WHERE department_name = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $department_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['department_id'];
    } else {
        return null;
    }
}

// Function to get program ID from program name
function getProgramId($program_name, $con) {
    $query = "SELECT program_id FROM Program WHERE program_name = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $program_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['program_id'];
    } else {
        return null;
    }
}

// Function to get status ID from status name
function getStatusId($status_name, $con) {
    $query = "SELECT status_id FROM Status WHERE status_name = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $status_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['status_id'];
    } else {
        return null;
    }
}

// Check if file is uploaded
if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $file = $_FILES["file"]["tmp_name"];
    $handle = fopen($file, "r");
    $failedRows = []; // Array to store failed rows
    $duplicateRows = []; // Array to store duplicate rows

    // Skip the first row (column headers)
    fgetcsv($handle, 1000, ",");

    // Read the CSV file line by line
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $student_id = $data[0];
        $student_name = $data[1];
        $student_email = $data[2];
        $student_pass = password_hash($student_id, PASSWORD_DEFAULT); // Use student_id as default password
        $student_phone_number = $data[3];
        $student_identify_number = $data[4];
        $student_address = $data[5];
        $state = $data[6];
        $department_name = $data[7];
        $program_name = $data[8];
        $gender = $data[9];
        $status_name = $data[10];

        // Get the department_id from the department_name
        $department_id = getDepartmentId($department_name, $con);
        if ($department_id === null) {
            $failedRows[] = $data; // Department name not found
            continue;
        }

        // Get the program_id from the program_name
        $program_id = getProgramId($program_name, $con);
        if ($program_id === null) {
            $failedRows[] = $data; // Program name not found
            continue;
        }

        // Get the status_id from the status_name
        $status_id = getStatusId($status_name, $con);
        if ($status_id === null) {
            $failedRows[] = $data; // Status name not found
            continue;
        }

        // Check if the record already exists
        $check_sql = "SELECT * FROM student WHERE student_id = '$student_id'";
        $result = $con->query($check_sql);

        if ($result->num_rows == 0) {
            // Insert data into database
            $sql = "INSERT INTO student (student_id, student_name, student_email, student_pass, student_phone_number, student_identify_number, student_address, state, department_id, program_id, gender, status_id) 
                    VALUES ('$student_id', '$student_name', '$student_email', '$student_pass', '$student_phone_number', '$student_identify_number', '$student_address', '$state', '$department_id', '$program_id', '$gender', '$status_id')";
            if ($con->query($sql) !== TRUE) {
                // Store failed row for later reporting
                $failedRows[] = $data;
            }
        } else {
            // Record already exists, store it as a duplicate row
            $duplicateRows[] = $data;
        }
    }

    fclose($handle);

    echo "CSV file uploaded.";
    if (!empty($duplicateRows)) {
        echo " The following rows are duplicates and were not inserted:";
        echo "<ul>";
        foreach ($duplicateRows as $row) {
            echo "<li>" . implode(', ', $row) . "</li>";
        }
        echo "</ul>";
    }
    if (!empty($failedRows)) {
        echo " The following rows failed to insert:";
        echo "<ul>";
        foreach ($failedRows as $row) {
            echo "<li>" . implode(', ', $row) . "</li>";
        }
        echo "</ul>";
    }

    // Add a button to redirect to another page
    echo '<button onclick="window.location.href=\'viewStudents.php\'">OK</button>';
} else {
    echo "Error uploading file.";
}

$con->close();
?>
