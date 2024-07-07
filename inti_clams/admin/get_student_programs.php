<?php
                                    include_once '../database/db.php';

                                    if(isset($_GET['department_id'])){
                                        $department_id = $_GET['department_id'];
                                        
                                        // Fetch programs based on the selected department
                                        $query = "SELECT * FROM program WHERE department_id = $department_id";
                                        $result = mysqli_query($con, $query);

                                        // Check if there are any programs
                                        if (mysqli_num_rows($result) > 0) {
                                            // Loop through each program and create an option element
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='" . $row['program_id'] . "'>" . $row['program_name'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No programs found</option>";
                                        }
                                    }
?>