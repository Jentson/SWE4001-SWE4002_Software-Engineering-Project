<?php
require_once "../database/db.php";

// Check if file is uploaded
if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
    $file = $_FILES["file"]["tmp_name"];
    $handle = fopen($file, "r");
    $failedRows = []; // Array to store failed rows
    $duplicateRows = []; // Array to store duplicate rows

    // Read the CSV file line by line
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $subject_id = $data[0];
        $subject_name = $data[1];
        $department_name = $data[2]; // Read Department Name
        $staff_id = $data[3];

        // Fetch department_id based on department_name
        $dept_sql = "SELECT department_id FROM department WHERE department_name = '$department_name'";
        $dept_result = $con->query($dept_sql);

        if ($dept_result->num_rows > 0) {
            $dept_row = $dept_result->fetch_assoc();
            $department_id = $dept_row['department_id'];

            // Check if the record already exists
            $check_sql = "SELECT * FROM subject WHERE subject_id = '$subject_id'";
            $result = $con->query($check_sql);

            if ($result->num_rows == 0) {
                // Insert data into database
                $sql = "INSERT INTO subject (subject_id, subject_name, department_id, staff_id) 
                        VALUES ('$subject_id', '$subject_name', '$department_id', '$staff_id')";
                if ($con->query($sql) !== TRUE) {
                    // Store failed row for later reporting
                    $failedRows[] = $data;
                }
            } else {
                // Record already exists, store it as a duplicate row
                $duplicateRows[] = $data;
            }
        } else {
            // Department not found, store failed row
            $failedRows[] = $data;
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
    echo '<button onclick="window.location.href=\'viewSubject.php\'">OK</button>';
} else {
    echo "Error uploading file.";
}

$con->close();
?>
