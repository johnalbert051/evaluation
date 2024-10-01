<?php
session_start();
include '../connection/conn.php';

// Add this at the top of your PHP section
require_once '../vendor/autoload.php'; // Make sure this path is correct for your project structure
use Spipu\Html2Pdf\Html2Pdf;

// Fetch top 5 teachers based on rating
$query = "SELECT id, name, subject, rating FROM teachers ORDER BY rating DESC";
$result = $conn->query($query);

$top_teachers = [];
while ($row = $result->fetch_assoc()) {
    $top_teachers[] = $row;
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

// Function to get medal emoji
function getMedalEmoji($rank) {
    if ($rank === 1) return '<span class="medal gold">🥇</span>';
    if ($rank === 2) return '<span class="medal silver">🥈</span>';
    if ($rank === 3) return '<span class="medal bronze">🥉</span>';
    return '';
}

// Add this function for PDF generation
function generatePDF($content) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'Top Performing Teachers');
    $pdf->Ln(20);
    $pdf->SetFont('Arial','',12);
    
    // Add table headers
    $pdf->Cell(20,10,'Rank',1);
    $pdf->Cell(60,10,'Name',1);
    $pdf->Cell(60,10,'Subject',1);
    $pdf->Cell(30,10,'Rating',1);
    $pdf->Ln();
    
    // Add table content
    foreach ($content as $row) {
        $pdf->Cell(20,10,$row['rank'],1);
        $pdf->Cell(60,10,$row['name'],1);
        $pdf->Cell(60,10,$row['subject'],1);
        $pdf->Cell(30,10,$row['rating'],1);
        $pdf->Ln();
    }
    
    return $pdf->Output('S');
}

// Handle PDF download
if (isset($_POST['download_pdf'])) {
    $content = [];
    foreach ($top_teachers as $index => $teacher) {
        $content[] = [
            'rank' => $index + 1,
            'name' => $teacher['name'],
            'subject' => $teacher['subject'],
            'rating' => number_format($teacher['rating'], 1)
        ];
    }

    $pdf_content = generatePDF($content);
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="top_performers.pdf"');
    echo $pdf_content;
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Performers - Teacher Evaluation Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        :root {
            --primary-color: #160859;
            --secondary-color: #433878;
            --background-color: #f4f4f4;
            --text-color: #333;
            --accent-color: #e74c3c;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
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

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        header {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1rem;
            border-radius: 5px 5px 0 0;
        }

        h1 {
            margin-bottom: 0;
        }

        .subtitle {
            font-style: italic;
            margin-top: 0;
        }

        .evaluation-card {
            background-color: white;
            padding: 20px;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .rating {
            font-weight: bold;
            color: var(--primary-color);
        }

        .medal {
            font-size: 1.2em;
            margin-right: 5px;
        }

        @keyframes shine {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        .gold { color: gold; animation: shine 2s infinite; }
        .silver { color: silver; }
        .bronze { color: #cd7f32; }

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
            border: none;
            cursor: pointer;
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
            <header>
                <h1>Top Performers</h1>
                <p class="subtitle">Teacher Evaluation Dashboard</p>
            </header>

            <div class="evaluation-card">
                <table id="teacherTable">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Rating</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($top_teachers as $index => $teacher) {
                            $rank = $index + 1;
                            echo "<tr>";
                            echo "<td>" . getMedalEmoji($rank) . $rank . "</td>";
                            echo "<td>" . htmlspecialchars($teacher['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($teacher['subject']) . "</td>";
                            echo "<td class='rating'>" . number_format($teacher['rating'], 1) . " " . renderStars($teacher['rating']) . "</td>";
                            echo "<td>" . htmlspecialchars($teacher['subject']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="action-buttons">
                    <button onclick="window.print()" class="btn">Print</button>
                    <form method="post" style="display: inline;">
                        <button type="submit" name="download_pdf" class="btn">Download PDF</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>