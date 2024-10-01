<?php
session_start();
include '../connection/conn.php';

if (!isset($_GET['teacher_id'])) {
    die("No teacher ID provided");
}

$teacher_id = mysqli_real_escape_string($conn, $_GET['teacher_id']);

// Fetch teacher details including the rating
$query = "SELECT name, subject, rating FROM teachers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

if (!$teacher) {
    die("No teacher found with this ID");
}

// Function to render stars
function renderStars($rating) {
    $fullStars = floor($rating);
    $halfStar = $rating - $fullStars >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

    $stars = str_repeat("★", $fullStars);
    $stars .= $halfStar ? "½" : "";
    $stars .= str_repeat("☆", $emptyStars);

    return $stars;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Evaluation | Evaluation Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        :root {
            --primary-color: #160859;
            --secondary-color: #433878;
            --background-color: #ecf0f1;
            --text-color: #34495e;
            --accent-color: #e74c3c;
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
            min-height: 100vh;
            
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            /* height: 100vh; */
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
            padding: 30px;
        }

        .evaluation-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .evaluation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .employee-info {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            flex: 1;
        }

        .info-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .evaluation-section {
            margin-bottom: 30px;
        }

        .evaluation-section h3 {
            color: var(--primary-color);
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 5px;
        }

        .rating-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .rating-stars {
            color: gold;
            margin-left: 10px;
        }

        .response-count {
            font-size: 0.8em;
            color: #666;
            margin-left: 10px;
        }

        .comments {
            background-color: #f8f9fa;
            border-left: 4px solid var(--secondary-color);
            padding: 10px;
            margin-top: 10px;
        }

        .btn {
            display: inline-block;
            background-color: var(--secondary-color);
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: var(--primary-color);
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        @media print {
            .sidebar, .action-buttons {
                display: none;
            }
            .main-content {
                padding: 0;
            }
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
                    <a href="results.php" class="nav-link">
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
            <h1>View Evaluation</h1>
            <div class="evaluation-card">
                <!-- Your existing evaluation content here -->
                <div class="evaluation-header">
                    <h2>Teacher Evaluation - <?php echo date('Y'); ?></h2>
                </div>
                <div class="employee-info">
                    <div class="info-item">
                        <div class="info-label">Teacher:</div>
                        <div><?php echo htmlspecialchars($teacher['name']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Subject:</div>
                        <div><?php echo htmlspecialchars($teacher['subject']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Current Rating:</div>
                        <div class="rating">
                            <span class="star"><?php echo renderStars($teacher['rating']); ?></span>
                            (<span class="rating-value"><?php echo number_format($teacher['rating'], 1); ?></span>)
                        </div>
                    </div>
                </div>
                <div class="evaluation-section">
                    <h3>Performance Ratings</h3>
                    <?php
                    // Fetch average ratings for each question
                    $query = "SELECT er.question_id, 
                                     AVG(er.rating) as avg_rating,
                                     COUNT(er.id) as response_count
                              FROM evaluations e
                              JOIN evaluation_responses er ON e.id = er.evaluation_id
                              WHERE e.teacher_id = ?
                              GROUP BY er.question_id
                              ORDER BY er.question_id";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $teacher_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $avg_rating = round($row['avg_rating'], 1);
                            echo "<div class='rating-item'>";
                            echo "<span>Question " . htmlspecialchars($row['question_id']) . "</span>";
                            echo "<span class='rating-stars'>";
                            echo renderStars($avg_rating);
                            echo " (" . $avg_rating . "/5)";
                            echo "</span>";
                            echo "<span class='response-count'>Responses: " . $row['response_count'] . "</span>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No evaluation responses found for this teacher.</p>";
                    }
                    ?>
                </div>
                <!-- <div class="evaluation-section">
                    <h3>Goals Achievement</h3>
                    <div class="rating-item">
                        <span>Increase sales by 15%</span>
                        <span>Achieved (18% increase)</span>
                    </div>
                    <div class="rating-item">
                        <span>Acquire 5 new key accounts</span>
                        <span>Achieved (6 new accounts)</span>
                    </div>
                    <div class="rating-item">
                        <span>Improve customer satisfaction score</span>
                        <span>Partially Achieved (4.2 to 4.6, target was 4.8)</span>
                    </div>
                </div> -->
                <div class="evaluation-section">
                    <h3>Comments</h3>
                    <div class="comments">
                        <p>John has demonstrated exceptional performance this year, particularly in sales growth and acquiring new accounts. His communication skills and ability to build strong client relationships have been instrumental in his success. There's room for improvement in consistently meeting all aspects of customer satisfaction targets, but overall, John's performance has been outstanding.</p>
                    </div>
                </div>
                <div class="evaluation-section">
                    <h3>Areas for Improvement</h3>
                    <ul>
                        <li>Focus on strategies to further enhance customer satisfaction scores</li>
                        <li>Develop mentoring skills to support junior team members</li>
                        <li>Participate in advanced negotiation techniques training</li>
                    </ul>
                </div>
                <!-- <div class="evaluation-section">
                    <h3>Development Plan</h3>
                    <ul>
                        <li>Enroll in "Advanced Customer Experience Management" course by Q3</li>
                        <li>Shadow senior management in client meetings once a month</li>
                        <li>Lead a cross-functional project to improve internal processes by year-end</li>
                    </ul>
                </div> -->
                <div class="action-buttons">
                    <a href="#edit-evaluation" class="btn">Cancel</a>
                    <a href="#print-evaluation" class="btn">Print Evaluation</a>
                    <a href="#dashboard" class="btn">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const action = this.getAttribute('href').substring(1);
            switch(action) {
                case 'edit-evaluation':
                    window.location.href = 'report.php';
                    break;
                case 'print-evaluation':
                    window.print();
                    break;
                case 'dashboard':
                    window.location.href = 'maindashboard.php';
                    break;
            }
        });
    });
    </script>
</body>
</html>