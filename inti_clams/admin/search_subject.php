<?php
require_once "../database/dbconnect.php";

// Check if the search term is provided
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];

    // Fetch data based on the search term
    $query = "SELECT subject.*, department.department_name, staff.staff_id, staff.staff_name
              FROM subject
              INNER JOIN department ON subject.department_id = department.department_id
              INNER JOIN staff ON subject.staff_id = staff.staff_id
              WHERE subject.subject_name LIKE '%$searchTerm%'
              OR department.department_name LIKE '%$searchTerm%'
              OR staff.staff_name LIKE '%$searchTerm%'";

    $result = mysqli_query($con, $query);

    if ($result) {
        // Check if any rows are returned
        if (mysqli_num_rows($result) > 0) {
            // Fetch all rows as an associative array
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            // Output the rows as HTML table rows
            foreach ($data as $row) {
                echo "<tr>";
                echo "<td>{$row['subject_id']}</td>";
                echo "<td>{$row['subject_name']}</td>";
                echo "<td>{$row['department_name']}</td>";
                echo "<td>{$row['staff_id']}</td>";
                echo "<td>{$row['staff_name']}</td>";
                echo "<td>";
                echo "<form method='post' action='updatesubject.php'>";
                echo "<input type='hidden' name='subject_id' value='{$row['subject_id']}'>";
                echo "<input type='submit' name='update' value='Update'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No subjects found.</td></tr>";
        }
    } else {
        // Error in executing the query
        echo "<tr><td colspan='6'>Error: " . mysqli_error($con) . "</td></tr>";
    }
} else {
    // No search term provided
    echo "<tr><td colspan='6'>Please provide a search term.</td></tr>";
}
?>
