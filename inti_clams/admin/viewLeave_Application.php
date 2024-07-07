<?php
require_once "../database/dbconnect.php";
require_once "../validation/session.php";

// Prepare the query with placeholders
$query = "SELECT leave_application.leave_id, 
                 leave_application.student_id, 
                 leave_application.student_name, 
                 leave_application.state, 
                 subject.subject_id, 
                 leave_application.start_date, 
                 leave_application.end_date, 
                 leave_application.reason, 
                 leave_application.documents, 
                 leave_application.lecturer_approval, 
                 leave_application.lecturer_remarks, 
                 leave_application.ioav_approval, 
                 leave_application.ioav_remarks, 
                 leave_application.hop_approval, 
                 leave_application.hop_remarks
          FROM leave_application
          JOIN subject ON leave_application.subject_id = subject.subject_id
          WHERE leave_application.is_deleted = 0";


function getApprovalBgColor($status) {
    switch ($status) {
        case 'Rejected':
            return 'bg-danger'; // Set background color to red
        case 'Approved':
            return 'bg-success'; // Set background color to green
        case 'Pending':
        default:
            return 'bg-warning'; // Set background color to yellow
    }
}

$result = $con->query($query);
$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "Error fetching data: " . $con->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            padding-top: 20px;
        }
        .approval-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
        }
        .approval-status.Approved {
            background-color: #28a745; 
        }
        .approval-status.Pending {
            background-color: #ffc107; 
        }
        .approval-status.Rejected {
            background-color: #dc3545; 
        }
        .approval-status.not-required {
            background-color: #6c757d;
        } 
        .remarks-box {
            background-color: #f2f2f2;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .no-remarks {
            color: #6c757d; 
        }
    </style>
    <script>
        // // function fetchFilteredData(searchTerm = "") {
        // //     var xhttp = new XMLHttpRequest();
        // //     xhttp.onreadystatechange = function() {
        // //         if (this.readyState == 4 && this.status == 200) {
        // //             var data = JSON.parse(this.responseText);
        // //             var tbody = document.getElementById('tableBody');
        // //             tbody.innerHTML = '';
        // //             data.forEach(function(row) {
        // //                 var tr = document.createElement('tr');
        // //                 tr.innerHTML = `
        // //                             <td>${row.leave_id}</td>
        // //                             <td>${row.student_id}</td>
        // //                             <td>${row.student_name}</td>
        // //                             <td>${row.state}</td>
        // //                             <td>${row.subject_id}</td>
        // //                             <td>${row.start_date}</td>
        // //                             <td>${row.end_date}</td>
        // //                             <td>${row.reason}</td>
        // //                             <td>${row.documents ? `<a href="../file/${row.documents}" target="_blank">Supporting Documents</a>` : '-'}</td>
        // //                             <td>${row.lecturer_approval}</td>
        // //                             <td>${row.lecturer_remarks}</td>
        // //                             <td>${row.ioav_approval}</td>
        // //                             <td>${row.ioav_remarks}</td>
        // //                             <td>${row.hop_approval}</td>
        // //                             <td>${row.hop_remarks}</td>
        // //                         `;

        // //                 tbody.appendChild(tr);
        // //             });
        // //         }
        // //     };
            
        //     // Append the search term to the URL
        //     var url = "search_leave_application.php?searchTerm=" + encodeURIComponent(searchTerm);
        //     xhttp.open("GET", url, true);
        //     xhttp.send();
        // }

        document.addEventListener('DOMContentLoaded', function() {
            fetchFilteredData(); // Load all data on page load
            document.getElementById('searchTerm').addEventListener('input', function() {
                fetchFilteredData(this.value); // Update data dynamically on input
            });
        });

    </script>
</head>
<body>
        <!-- Images -->
        <div class="row justify-content-center">
        <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>
         
        <div class="row justify-content-end align-items-center mb-3">
        <div class="col-auto">
            <form method="post" action="export_leave_application_xlsx.php" class="d-inline">
                <button type="submit" name="export_csv" class="btn btn-primary">Export to CSV</button>
            </form>
        </div>
        <div class="col-auto">
            <a href="adminmain.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
    <input type="text" id="searchTerm" class="form-control mb-3" placeholder="Search by subject name, department, or staff" oninput="searchSubjects()">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">Leave ID</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">State</th>
                    <th scope="col">Subject Code</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Reason</th>
                    <th scope="col">Documents</th>
                    <th scope="col">Lecturer Approval</th>
                    <th scope="col">Lecturer Remarks</th>
                    <th scope="col">IOAV Approval</th>
                    <th scope="col">IOAV Remarks</th>
                    <th scope="col">HOP Approval</th>
                    <th scope="col">HOP Remarks</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php foreach ($data as $row): 
                    // Background colors for approval statuses
                        $bgColors = array(
                            'lecturer' => getApprovalBgColor($row['lecturer_approval']),
                            'ioav' => getApprovalBgColor($row['ioav_approval']),
                            'hop' => getApprovalBgColor($row['hop_approval'])
                        );
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['leave_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['state']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>

                        <td>
                            <?php
                            if (empty($row['documents'])) {
                                echo '-';
                            } else {
                                echo '<a href="../leave/file/' . htmlspecialchars($row['documents']) . '" target="_blank">Supporting Documents</a>';
                            }
                            ?>
                        </td>
                        <!-- Lecturer  -->
                        <td>
                                    <div class="approval-status <?php echo $bgColors['lecturer']; ?>"><?php echo $row['lecturer_approval']; ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($row['lecturer_remarks'])): ?>
                                        <div class="remarks-box"><?php echo $row['lecturer_approval']; ?></div>
                                    <?php else: ?>
                                        <div class="no-remarks">No Remarks</div>
                                    <?php endif; ?>
                                </td>
                                

                        <!-- IOAV  -->           
                        <td>
                                <?php if ($row['ioav_approval'] === 'Not Required'): ?>
                                        <div class="approval-status not-required"><?php echo $row['ioav_approval']; ?></div>
                                    <?php elseif (!empty($row['ioav_approval'])): ?>
                                        <div class="approval-status <?php echo $bgColors['ioav']; ?>"><?php echo $row['ioav_approval']; ?></div>
                                    <?php else: ?>
                                        <div class="no-remarks">No Remarks</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['ioav_remarks'] === 'Not Required'): ?>
                                        <div class="remarks-box"><?php echo $row['ioav_remarks']; ?></div>
                                    <?php elseif (!empty($row['ioav_remarks'])): ?>
                                        <div class="remarks-box"><?php echo $row['ioav_remarks']; ?></div>
                                    <?php else: ?>
                                        <div class="no-remarks">No Remarks</div>
                                    <?php endif; ?>
                                </td>
                         <!-- HOP -->
                         <td>
                                <?php if ($row['hop_approval'] === 'Not Required'): ?>
                                    <div class="approval-status not-required"><?php echo $row['hop_approval']; ?></div>
                                <?php elseif (!empty($row['hop_approval'])): ?>
                                    <div class="approval-status <?php echo $bgColors['hop']; ?>"><?php echo $row['hop_approval']; ?></div>
                                <?php else: ?>
                                    <div class="no-remarks">No Remarks</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['hop_remarks'] === 'Not Required'): ?>
                                    <div class="remarks-box"><?php echo $row['hop_remarks']; ?></div>
                                <?php elseif (!empty($row['hop_remarks'])): ?>
                                    <div class="remarks-box"><?php echo $row['hop_remarks']; ?></div>
                                <?php else: ?>
                                    <div class="no-remarks">No Remarks</div>
                                <?php endif; ?>
                            </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
