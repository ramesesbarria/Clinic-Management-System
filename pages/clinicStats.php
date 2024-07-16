<?php
// Include your database connection or adjust the path accordingly
include '../models/db.php';

// Fetch data for Appointment Types
$sqlAppointmentTypes = "SELECT appointment_type, COUNT(*) as count FROM appointments GROUP BY appointment_type";
$resultAppointmentTypes = $conn->query($sqlAppointmentTypes);
$appointmentTypesData = [];
while ($row = $resultAppointmentTypes->fetch_assoc()) {
    $appointmentTypesData[] = $row;
}

// Fetch data for Completed/Not Completed Appointments
$sqlCompletedAppointments = "SELECT completed, COUNT(*) as count FROM appointments GROUP BY completed";
$resultCompletedAppointments = $conn->query($sqlCompletedAppointments);
$completedAppointmentsData = [];
while ($row = $resultCompletedAppointments->fetch_assoc()) {
    $completedAppointmentsData[] = $row;
}

// Fetch data for Daily Appointments
$sqlDailyAppointments = "SELECT DATE(date_preference) as date, COUNT(*) as count FROM appointments GROUP BY DATE(date_preference)";
$resultDailyAppointments = $conn->query($sqlDailyAppointments);
$dailyAppointmentsData = [];
while ($row = $resultDailyAppointments->fetch_assoc()) {
    $dailyAppointmentsData[] = $row;
}

// Fetch data for Income (Monthly and Daily)
$sqlIncomeMonthly = "SELECT DATE_FORMAT(paid_on, '%Y-%m') as month, SUM(paymentAmount) as income FROM payments GROUP BY month";
$resultIncomeMonthly = $conn->query($sqlIncomeMonthly);
$incomeMonthlyData = [];
while ($row = $resultIncomeMonthly->fetch_assoc()) {
    $incomeMonthlyData[] = $row;
}

$sqlIncomeDaily = "SELECT DATE(paid_on) as date, SUM(paymentAmount) as income FROM payments GROUP BY date";
$resultIncomeDaily = $conn->query($sqlIncomeDaily);
$incomeDailyData = [];
while ($row = $resultIncomeDaily->fetch_assoc()) {
    $incomeDailyData[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Stats Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            max-width: 300px;
            margin: auto;
        }
        .chart-title {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .chart-wrapper {
            padding: 1rem;
            border: none; /* Remove border */
            box-shadow: none; /* Remove box shadow */
        }
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f7fafc; /* Tailwind 'gray-100' */
        }
        .sidebar {
            width: 250px;
            background-color: #1a202c; /* Tailwind 'gray-800' */
            color: white;
            padding: 20px;
            flex-shrink: 0;
        }
        .sidebar a {
            display: block;
            padding: 10px 0;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #2d3748; /* Tailwind 'gray-700' */
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"> <img src="../img/logo.png" alt="Logo"> </div>
        <b> </b>
        <h2 class="text-xl font-bold mb-6">Secretary Dashboard</h2>
        <a href="secretaryDashboard.php">Main Dashboard</a>
        <a href="clinicStats.php">Clinic Stats</a>
        <a href="viewAppointments.php">Appointments Lists</a>
        <a href="viewPatients.php">Patients list</a>
        <a href="../models/handleLogout.php">Log Out</a>
    </div>
    <div class="content">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4 text-center">Clinic Stats Dashboard</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Left Side -->
                <div>
                    <!-- Doughnut Chart for Appointment Types -->
                    <div class="bg-white p-2 shadow rounded chart-wrapper">
                        <h2 class="chart-title text-center">Appointment Types</h2>
                        <div class="chart-container">
                            <canvas id="appointmentTypesChart"></canvas>
                        </div>
                    </div>
                    <!-- Doughnut Chart for Completed/Not Completed Appointments -->
                    <div class="bg-white p-2 shadow rounded chart-wrapper">
                        <h2 class="chart-title text-center">Completed Appointments</h2>
                        <div class="chart-container">
                            <canvas id="completedAppointmentsChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Right Side -->
                <div>
                    <!-- Line Chart for Daily Appointments -->
                    <div class="bg-white p-2 shadow rounded chart-wrapper">
                        <h2 class="chart-title text-center">Daily Appointments</h2>
                        <div class="chart-container">
                            <canvas id="dailyAppointmentsChart"></canvas>
                        </div>
                    </div>
                    <!-- Line Chart for Daily Income -->
                    <div class="bg-white p-2 shadow rounded chart-wrapper">
                        <h2 class="chart-title text-center">Daily Income</h2>
                        <div class="chart-container">
                            <canvas id="dailyIncomeChart"></canvas>
                        </div>
                    </div>
                    <!-- Bar Chart for Monthly Income -->
                    <div class="bg-white p-2 shadow rounded chart-wrapper">
                        <h2 class="chart-title text-center">Monthly Income</h2>
                        <div class="chart-container">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize charts with PHP data
            var appointmentTypesData = <?php echo json_encode($appointmentTypesData); ?>;
            var completedAppointmentsData = <?php echo json_encode($completedAppointmentsData); ?>;
            var dailyAppointmentsData = <?php echo json_encode($dailyAppointmentsData); ?>;
            var incomeMonthlyData = <?php echo json_encode(array_reverse($incomeMonthlyData)); ?>;
            var incomeDailyData = <?php echo json_encode($incomeDailyData); ?>;

            var appointmentTypesChart = new Chart(document.getElementById('appointmentTypesChart'), {
                type: 'doughnut',
                data: {
                    labels: appointmentTypesData.map(function(data) { return data.appointment_type; }),
                    datasets: [{
                        label: 'Appointment Types',
                        data: appointmentTypesData.map(function(data) { return data.count; }),
                        backgroundColor: [
                            '#36A2EB',
                            '#FFCE56',
                            '#FF6384',
                            '#4BC0C0',
                            '#9966FF'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });

//             var completedAppointmentsChart = new Chart(document.getElementById('completedAppointmentsChart'), {
//                 type: 'doughnut',
//                 data: {
//                     labels: ['Completed', 'Not Completed'],
//                     datasets: [{
//                         label: 'Completed Appointments',
//                         data: [completedAppointmentsData[0].count, completedAppointmentsData[1].count], // Assuming completed and not completed are two types
//                         backgroundColor: [
//                             '#4CAF50',
//                             '#F44336'
//                         ]
//                     }]
//                 },
//                 options: {
//                     responsive: true
//                 }
//             });
var completedAppointmentsChart = new Chart(document.getElementById('completedAppointmentsChart'), {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Not Completed'],
        datasets: [{
            label: 'Completed Appointments',
            data: [
                <?php echo $completedAppointmentsData[0]['count']; ?>, // Completed count
                <?php echo $completedAppointmentsData[1]['count']; ?>  // Not Completed count
            ],
            backgroundColor: [
                '#4CAF50', // Completed
                '#F44336'  // Not Completed
            ]
        }]
    },
    options: {
        responsive: true,
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var currentValue = dataset.data[tooltipItem.index];
                    var label = data.labels[tooltipItem.index];
                    return label + ': ' + currentValue;
                }
            }
        }
    }
});



            var dailyAppointmentsDataSorted = dailyAppointmentsData.sort(function(a, b) {
                // Assuming 'date' is a string in 'YYYY-MM-DD' format, you can sort by date
                return new Date(a.date) - new Date(b.date);
            });

            var dailyAppointmentsChart = new Chart(document.getElementById('dailyAppointmentsChart'), {
                type: 'line',
                data: {
                    labels: dailyAppointmentsDataSorted.map(function(data) { return data.date; }),
                    datasets: [{
                        label: 'Daily Appointments',
                        data: dailyAppointmentsDataSorted.map(function(data) { return data.count; }),
                        backgroundColor: '#36A2EB',
                        borderColor: '#36A2EB',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }]
                    }
                }
            });

            var dailyIncomeChart = new Chart(document.getElementById('dailyIncomeChart'), {
                type: 'line',
                data: {
                    labels: incomeDailyData.map(function(data) { return data.date; }),
                    datasets: [{
                        label: 'Daily Income',
                        data: incomeDailyData.map(function(data) { return data.income; }),
                        backgroundColor: '#4BC0C0',
                        borderColor: '#4BC0C0',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }]
                    }
                }
            });

            var incomeChart = new Chart(document.getElementById('incomeChart'), {
                type: 'bar',
                data: {
                    labels: incomeMonthlyData.map(function(data) { return data.month; }),
                    datasets: [{
                        label: 'Monthly Income',
                        data: incomeMonthlyData.map(function(data) { return data.income; }),
                        backgroundColor: '#FFCE56',
                        borderColor: '#FFCE56',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        xAxes: [{
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }]
                    }
                }
            });
        });
    </script>
</body>
</html>