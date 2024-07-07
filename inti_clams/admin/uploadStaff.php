<?php
require_once "../database/db.php";

// Function to get position ID from position name
function getPositionId($position_name, $con) {
    $query = "SELECT position_id FROM Position WHERE position_name = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $position_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['position_id'];
    } else {
        return null;
    }
}

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

// Function to log errors to a file
function logError($message) {
    file_put_contents("upload_errors.log", date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}

// Function to detect the delimiter
function detectDelimiter($file) {
    $handle = fopen($file, 'r');
    $line = fgets($handle);
    fclose($handle);

    $delimiters = [",", "\t", ";", "|"];
    $delimiterCounts = [];

    foreach ($delimiters as $delimiter) {
        $delimiterCounts[$delimiter] = count(str_getcsv($line, $delimiter));
    }

    return array_search(max($delimiterCounts), $delimiterCounts);
}

// Check if file is uploaded
if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $file = $_FILES["file"]["tmp_name"];
    $delimiter = detectDelimiter($file); // Detect delimiter
    $handle = fopen($file, "r");
    $failedRows = []; // Array to store failed rows
    $duplicateRows = []; // Array to store duplicate rows

    // Skip the first row (column headers)
    fgetcsv($handle, 1000, $delimiter);

    // Read the CSV file line by line
    while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
        // Check if row has the expected number of columns
        if (count($data) < 9) {
            $failedRows[] = $data; // Not enough columns
            logError("Row skipped due to insufficient columns: " . implode(", ", $data));
            continue;
        }

        $staff_id = $data[0];
        $staff_name = $data[1];
        $staff_email = $data[2];
        $staff_pass = password_hash($staff_id, PASSWORD_DEFAULT); // Use staff_id as default password
        $staff_identity_number = $data[3];
        $staff_address = $data[4];
        $phone_number = (string) floatval($data[5]); // Convert scientific notation to string
        $position_name = $data[6];
        $department_name = $data[7];
        $gender = $data[8];

        // Get the position_id from the position_name
        $position_id = getPositionId($position_name, $con);
        if ($position_id === null) {
            $failedRows[] = $data; // Position name not found
            logError("Position name not found: " . $position_name);
            continue;
        }

        // Get the department_id from the department_name
        $department_id = getDepartmentId($department_name, $con);
        if ($department_id === null) {
            $failedRows[] = $data; // Department name not found
            logError("Department name not found: " . $department_name);
            continue;
        }

        // Check if the record already exists
        $check_sql = "SELECT * FROM staff WHERE staff_id = '$staff_id'";
        $result = $con->query($check_sql);

        if ($result->num_rows == 0) {
            // Insert data into database
            $sql = "INSERT INTO staff (staff_id, staff_name, staff_email, staff_pass, staff_identity_number, staff_address, phone_number, position_id, department_id, gender) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($con->error));
            }
            $stmt->bind_param("ssssisssss", $staff_id, $staff_name, $staff_email, $staff_pass, $staff_identity_number, $staff_address, $phone_number, $position_id, $department_id, $gender);
            
            if (!$stmt->execute()) {
                // Store failed row for later reporting
                $failedRows[] = $data;
                logError("Insert failed for row: " . implode(", ", $data) . " - Error: " . htmlspecialchars($stmt->error));
            }
            $stmt->close();
        } else {
            // Record already exists, store it as a duplicate row
            $duplicateRows[] = $data;
            logError("Duplicate row found: " . implode(", ", $data));
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
    echo '<button onclick="window.location.href=\'viewStaff.php\'">OK</button>';
} else {
    echo "Error uploading file.";
}

$con->close();
?>
