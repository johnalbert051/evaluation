<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #160859;
            --secondary-color: #433878;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content {
            padding: 20px;
            background-color: white;
            flex: 1;
        }
        .footer {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 10px;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .btn {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: var(--primary-color);
        }
        .nav-menu {
            list-style-type: none;
            padding: 0;
        }
        .nav-item {
            margin-bottom: 10px;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .nav-link:hover {
            background-color: var(--secondary-color);
        }
        .nav-link i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">Admin Dashboard</div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-tie"></i>
                        <span>Teachers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-graduate"></i>
                        <span>Students</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-book"></i>
                        <span>Courses</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Admin Dashboard</h1>
                <div class="user-dropdown">
                    Admin Name ▼
                </div>
            </div>
            <div class="content">
                <div class="card">
                    <h2>Overview</h2>
                    <!-- Dashboard overview content -->
                </div>
                <div class="card">
                    <h2>Recent Activity</h2>
                    <!-- Recent activity content -->
                </div>
            </div>
            <div class="footer">
                © 2024 Student Evaluation System. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>