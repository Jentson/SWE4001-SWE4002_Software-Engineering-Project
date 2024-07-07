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
    <title>Staff Dashboard</title>
    <style>
        .chart-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
  </head>
  <body class="p-3 m-0 border-0 bd-example m-0 border-0">
 
 <!-- Navbar -->
 <?php include '../validation/session.php'; ?>
 <?php include '../index/staff_navbar.php'; ?>

<h4 class ="text-center"> Leave and Consultation </h1>
<div class="chart-container">
    <canvas id="leaveCountChart"></canvas>
</div>
<div class="chart-container">
    <canvas id="timescheduleChart"></canvas>
</div>
<script>
    // Fetch the staff_id from PHP session and pass it to JavaScript
    const staff_id = <?php echo isset($_SESSION['Staff_id']) ? json_encode($_SESSION['Staff_id']) : 'null'; ?>;

    if (staff_id) {
        // Fetch leave count data
        fetch(`fetch_data_staff.php?Staff_id=${staff_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error fetching staff data:', data.error);
                    return;
                }

                // Filter out entries with total leave count <= 5
                const filteredData = data.filter(entry => (entry.approved_count + entry.pending_count + entry.rejected_count) > 5);

                const labels = filteredData.map(entry => entry.student_id);
                const approvedCounts = filteredData.map(entry => entry.approved_count);
                const pendingCounts = filteredData.map(entry => entry.pending_count);
                const rejectedCounts = filteredData.map(entry => entry.rejected_count);

                const ctx = document.getElementById('leaveCountChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Approved Leaves',
                                data: approvedCounts,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Cyan for approved
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Pending Leaves',
                                data: pendingCounts,
                                backgroundColor: 'rgba(255, 205, 86, 0.2)', // Yellow for pending
                                borderColor: 'rgba(255, 205, 86, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Rejected Leaves',
                                data: rejectedCounts,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)', // Red for rejected
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        indexAxis: 'y', // This makes the bar chart horizontal
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Leave Count'
                                },
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    callback: function(value) {
                                        if (Number.isInteger(value)) {
                                            return value;
                                        }
                                    }
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Student ID'
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
                                        return `${context.dataset.label}: ${context.raw}`;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching staff data:', error));

        // Fetch timeschedule data
        fetch(`fetch_timeschedule_data.php?Staff_id=${staff_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error fetching timeschedule data:', data.error);
                    return;
                }

                const timescheduleDataAvailable = data.filter(entry => entry.book_avail === 'available').map(entry => ({
                    x: new Date(entry.schedule_date + 'T' + entry.start_time),
                    y: 1,  // Line 1 for available
                    status: 'available',
                    end: new Date(entry.schedule_date + 'T' + entry.end_time)
                }));

                const timescheduleDataBooked = data.filter(entry => entry.book_avail === 'booked').map(entry => ({
                    x: new Date(entry.schedule_date + 'T' + entry.start_time),
                    y: 2,  // Line 2 for booked
                    status: 'booked',
                    end: new Date(entry.schedule_date + 'T' + entry.end_time)
                }));

                const timescheduleDataCancel = data.filter(entry => entry.book_avail === 'cancel').map(entry => ({
                    x: new Date(entry.schedule_date + 'T' + entry.start_time),
                    y: 3,  // Line 3 for cancel
                    status: 'cancel',
                    end: new Date(entry.schedule_date + 'T' + entry.end_time)
                }));

                const ctxTimeline = document.getElementById('timescheduleChart').getContext('2d');
                new Chart(ctxTimeline, {
                    type: 'scatter',
                    data: {
                        datasets: [
                            {
                                label: 'Available',
                                data: timescheduleDataAvailable,
                                backgroundColor: 'rgba(75, 192, 192, 1)', // Green for available
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            },
                            {
                                label: 'Booked',
                                data: timescheduleDataBooked,
                                backgroundColor: 'rgba(255, 99, 132, 1)', // Red for booked
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            },
                            {
                                label: 'Cancel',
                                data: timescheduleDataCancel,
                                backgroundColor: 'rgba(201, 203, 207, 1)', // Grey for cancel
                                borderColor: 'rgba(201, 203, 207, 1)',
                                borderWidth: 1,
                                pointRadius: 5,
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
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value === 1) return 'Available';
                                        if (value === 2) return 'Booked';
                                        if (value === 3) return 'Cancel';
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Status'
                                },
                                beginAtZero: true,
                                stepSize: 1,
                                max: 3
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return context[0].raw.status + ' from ' + context[0].raw.x.toLocaleTimeString() + ' to ' + context[0].raw.end.toLocaleTimeString();
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching timeschedule data:', error));
    } else {
        console.error('No valid staff ID is set.');
    }
</script>
</body>
</html>
