<?php
session_start();
include '../connection/conn.php';

// Fetch teachers who have completed evaluations
$query = "SELECT DISTINCT t.id, t.name, t.subject
          FROM teachers t
          INNER JOIN evaluations e ON t.id = e.teacher_id
          ORDER BY t.subject, t.name";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluated Teachers Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
         @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
         @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
        :root {
            --primary-color: #160859;
            --secondary-color: #433878;
            --background-color: #f4f4f4;
            --text-color: #333;
        }

        body {
            font-family: 'Poppins', sans-serif;
            
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .container {
            display: flex;
            height: 100vh; /* Add this line */
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            /* height: 100%; Change this from 100vh to 100% */
            overflow-y: auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .sidebar-divider {
            border: 0;
            height: 1px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0));
            margin: 15px 0;
        }

        .nav-menu {
            list-style-type: none;
            padding: 0;
            margin: 0; /* Add this line */
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-link:hover {
            background-color: var(--secondary-color);
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto; /* Add this line */
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: var(--primary-color);
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: var(--primary-color);
        }
        
        .report-header {
       
        background-color: var(--primary-color);
        color: white;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
     box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .report-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color:#fff;
    }

    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">Teachers Evaluation</div>
            <hr class="sidebar-divider">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="maindashboard.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="teachers.php" class="nav-link">
                        <i class="fas fa-user-tie"></i>
                        <span>Teachers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="student.php" class="nav-link">
                        <i class="fas fa-user-graduate"></i>
                        <span>Students</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="courses.php" class="nav-link">
                        <i class="fas fa-book"></i>
                        <span>Courses</span>
                    </a>
                </li>
                <hr class="sidebar-divider">
                <li class="nav-item">
                    <a href="semester.php" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Semester</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="evaluation_top_ratings.php" class="nav-link">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Evaluations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="report.php" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <hr class="sidebar-divider">
                <li class="nav-item">
                    <a href="settings.php" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
        <div class="report-header">
            <h1>Evaluated Teachers Report</h1>
        </div>
            <table>
                <thead>
                    <tr>
                        <th>Teacher ID</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                            echo "<td><a href='results.php?teacher_id=" . $row['id'] . "' class='btn'>View Evaluation</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No evaluated teachers found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>