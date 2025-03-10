<!DOCTYPE html>
<html lang="en">

@include('admin.layouts.head')


<body class="bg-gray-900 text-white font-sans">
    <div id="app" class="flex">
        <!-- Sidebar -->
        @include('admin.layouts.aside')

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                <button id="toggleSidebarBtn" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md md:hidden">
                    ☰
                </button>
            </div>
            <div class="bg-gray-800 p-4 rounded-md">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold mb-2">User Graph</h2>
                    <canvas id="userGraph"></canvas>
                </div>
                <div class="mb-4">
                    <h2 class="text-lg font-semibold mb-2">Membership Graph</h2>
                    <canvas id="membershipGraph"></canvas>
                </div>
                <div class="mb-4">
                    <h2 class="text-lg font-semibold mb-2">Income Graph</h2>
                    <canvas id="incomeGraph"></canvas>
                </div>
            </div>
            
        </div>
    </div>

    @include('admin.layouts.script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sample data for the graphs
            const userData = [120, 200, 150, 180, 90, 170, 220]; // Example data for users
            const membershipData = [50, 100, 70, 90, 120, 150, 180]; // Example data for memberships
            const incomeData = [500, 800, 600, 700, 400, 750, 900]; // Example data for income
    
            // User Graph
            const userGraph = new Chart(document.getElementById('userGraph'), {
                type: 'line', // Type of the chart (line, bar, etc.)
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'], // Labels for each point (month in this case)
                    datasets: [{
                        label: 'Users',
                        data: userData, // Data for the graph
                        borderColor: 'rgba(75, 192, 192, 1)', // Line color
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Fill color
                        fill: true, // Whether to fill the area under the line
                        tension: 0.1 // Line smoothness
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
    
            // Membership Graph
            const membershipGraph = new Chart(document.getElementById('membershipGraph'), {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Memberships',
                        data: membershipData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
    
            // Income Graph
            const incomeGraph = new Chart(document.getElementById('incomeGraph'), {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Income',
                        data: incomeData,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
