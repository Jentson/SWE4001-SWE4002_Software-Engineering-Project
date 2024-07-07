<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <title>Student Dashboard</title>
    <style>
        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .chart-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 10px;
        }

        .chart-item canvas {
            margin-bottom: 10px;
        }

        .timeline-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }
    </style>
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">

<?php 
include '../validation/session.php'; 
include '../index/student_navbar.php';
?>

<h3 class="text-center"> Leave Status </h3>
<div class="chart-container" id="chartContainer"></div>
<div class="timeline-container">
    <canvas id="timelineChart" width="800" height="200"></canvas>
</div>
<script>
    // Fetch the student_id from PHP session and pass it to JavaScript
    const student_id = <?php echo isset($_SESSION['student_id']) ? json_encode($_SESSION['student_id']) : 'null'; ?>;
    
    if (student_id) {
        fetch(`fetch_data_student.php?student_id=${student_id}`)
            .then(response => response.json())
            .then(data => {
                console.log('Fetched Data:', data); // Log data to verify its structure

                const chartContainer = document.getElementById('chartContainer');
                const subjects = data.subjects;
                const leavesGroupedBySubject = {};

                data.leaves.forEach(leave => {
                    const subjectId = leave.subject_id;
                    if (!leavesGroupedBySubject[subjectId]) {
                        leavesGroupedBySubject[subjectId] = [];
                    }
                    leavesGroupedBySubject[subjectId].push(leave);
                });

                // Create subject doughnut charts
                subjects.forEach((subjectData, index) => {
                    const subjectId = subjectData.subject_id;
                    const label = `Subject ${subjectId}`;
                    const approvedLeaves = parseInt(subjectData.approved_leaves, 10);
                    const pendingLeaves = parseInt(subjectData.pending_leaves, 10);
                    const rejectedLeaves = parseInt(subjectData.rejected_leaves, 10);

                    // Create container for each chart and subject name
                    const chartItem = document.createElement('div');
                    chartItem.classList.add('chart-item');

                    // Create canvas element for each chart
                    const canvas = document.createElement('canvas');
                    canvas.id = `myChart${index}`;
                    canvas.width = 400;
                    canvas.height = 200;
                    chartItem.appendChild(canvas);

                    // Create label element for the subject name
                    const subjectLabel = document.createElement('span');
                    subjectLabel.innerText = label;
                    chartItem.appendChild(subjectLabel);

                    // Append the chartItem to the chartContainer
                    chartContainer.appendChild(chartItem);

                    // Create chart for current subject
                    new Chart(canvas.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Approved Leaves', 'Pending Leaves', 'Rejected Leaves'],
                            datasets: [{
                                label: label,
                                data: [approvedLeaves, pendingLeaves, rejectedLeaves],
                                backgroundColor: [
                                    'rgba(75, 192, 192, 0.2)', // Approved - cyan
                                    'rgba(255, 205, 86, 0.2)', // Pending - yellow
                                    'rgba(255, 99, 132, 0.2)'  // Rejected - red
                                ],
                                borderColor: [
                                    'rgba(75, 192, 192, 1)', // Approved - cyan
                                    'rgba(255, 205, 86, 1)', // Pending - yellow
                                    'rgba(255, 99, 132, 1)'  // Rejected - red
                                ],
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: false,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += context.raw;
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });

                // Flatten leaves grouped by subject_id
                const allLeaves = [];
                for (const subjectId in leavesGroupedBySubject) {
                    if (leavesGroupedBySubject.hasOwnProperty(subjectId)) {
                        const leavesArray = Array.isArray(leavesGroupedBySubject[subjectId]) ? leavesGroupedBySubject[subjectId] : [leavesGroupedBySubject[subjectId]];
                        allLeaves.push(...leavesArray);
                    }
                }

                // Separate leaves into three categories: approved, pending, and rejected
                const approvedLeaves = allLeaves.filter(leave => leave.lecturer_approval.toLowerCase() === 'approved' && leave.hop_approval.toLowerCase() === 'approved');
                const pendingLeaves = allLeaves.filter(leave => (leave.lecturer_approval.toLowerCase() === 'pending' || leave.hop_approval.toLowerCase() === 'pending') && leave.lecturer_approval.toLowerCase() !== 'rejected' && leave.hop_approval.toLowerCase() !== 'rejected');
                const rejectedLeaves = allLeaves.filter(leave => leave.lecturer_approval.toLowerCase() === 'rejected' || leave.hop_approval.toLowerCase() === 'rejected');

                console.log('Approved Leaves:', approvedLeaves);
                console.log('Pending Leaves:', pendingLeaves);
                console.log('Rejected Leaves:', rejectedLeaves);

                const leaveDataApproved = approvedLeaves.map(leave => ({
                    x: new Date(leave.start_date),
                    y: 1,  // Line 1
                    status: 'approved',
                    end: new Date(leave.end_date),
                    subject_id: leave.subject_id
                }));
                const leaveDataPending = pendingLeaves.map(leave => ({
                    x: new Date(leave.start_date),
                    y: 2,  // Line 2
                    status: 'pending',
                    end: new Date(leave.end_date),
                    subject_id: leave.subject_id
                }));
                const leaveDataRejected = rejectedLeaves.map(leave => ({
                    x: new Date(leave.start_date),
                    y: 3,  // Line 3
                    status: 'rejected',
                    end: new Date(leave.end_date),
                    subject_id: leave.subject_id
                }));

                console.log('leaveDataApproved:', leaveDataApproved);
                console.log('leaveDataPending:', leaveDataPending);
                console.log('leaveDataRejected:', leaveDataRejected);

                const ctxTimeline = document.getElementById('timelineChart').getContext('2d');
                new Chart(ctxTimeline, {
                    type: 'scatter',
                    data: {
                        datasets: [
                            {
                                label: 'Approved Leaves',
                                data: leaveDataApproved,
                                backgroundColor: 'rgba(75, 192, 192, 1)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                pointRadius: 8,
                                pointHoverRadius: 7
                            },
                            {
                                label: 'Pending Leaves',
                                data: leaveDataPending,
                                backgroundColor: 'rgba(255, 205, 86, 1)',
                                borderColor: 'rgba(255, 205, 86, 1)',
                                borderWidth: 1,
                                pointRadius: 8,
                                pointHoverRadius: 7
                            },
                            {
                                label: 'Rejected Leaves',
                                data: leaveDataRejected,
                                backgroundColor: 'rgba(255, 99, 132, 1)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                pointRadius: 8,
                                pointHoverRadius: 7
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'day'
                                },
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                type: 'linear',
                                ticks: {
                                    stepSize: 1,
                                    callback: function(value) {
                                        if (value === 1) return 'Approved';
                                        if (value === 2) return 'Pending';
                                        if (value === 3) return 'Rejected';
                                        return '';
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Leave Status'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const startDate = new Date(context.raw.x).toLocaleDateString();
                                        const endDate = new Date(context.raw.end).toLocaleDateString();
                                        const status = context.raw.status;
                                        const subjectId = context.raw.subject_id;
                                        return `Subject ${subjectId}: ${startDate} - ${endDate}: ${status}`;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching student data:', error));
    } else {
        console.error('No valid ID is set.');
    }
</script>
</body>
</html>
