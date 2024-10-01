<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../index.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$student_id = $_SESSION['user_id'];
?>
<html><head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Evaluation List</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f5f9;
        margin: 0;
        padding: 20px;
        color: #333;
    }
    .container {
        max-width: 800px;
        margin: 0 auto;
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    h1 {
        background-color: #433878;
        padding: 20px;
        color: #fff;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 30px;
    }
    .teacher-list {
        list-style-type: none;
        padding: 0;
    }
    .teacher-item {
        background-color: #fff;
        margin-bottom: 15px;
        padding: 20px;
        border-radius: 5px;
        transition: all 0.3s ease;
        opacity: 0; /* Start invisible */
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .teacher-item:hover {
        transform: translateY(-15px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .teacher-name {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .teacher-subject {
        font-style: italic;
        color: #5a7d8c;
    }
    .rating {
        margin-top: 10px;
    }
    .star {
        color: #f1c40f;
        font-size: 1.2em;
    }
    .evaluate-btn {
        background-color: #433878;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 3px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .evaluate-btn:hover {
        background-color: #160859;
    }
    .evaluation-complete {
        background-color: #d4edda;
        color: #155724;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 20px;
    }
    .evaluation-complete h2 {
        margin-top: 0;
    }
    .evaluation-complete p {
        margin-bottom: 0;
    }
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 20px;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        color: #fff;
        font-weight: 500;
        padding: 8px 12px;
        background-color: #433878;
        border-radius: 4px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .back-button:hover {
        background-color: #160859;
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }

    .back-icon {
        font-size: 16px;
        margin-right: 6px;
    }

    .back-text {
        font-size: 14px;
    }

    

    @media (max-width: 600px) {
        .header {
            flex-direction: column;
            align-items: flex-start;
        }

        .back-button {
            margin-bottom: 15px;
        }
    }
</style>
</head>
<body>
<div class="container">
<a href="student_home.php" class="back-button" title="Back to Home">
    <span class="back-icon">&#8592;</span>
    <span class="back-text">Back</span>
</a>
<h1>Teacher Evaluation List</h1>
    
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include "../connection/conn.php";

    $student_id = $_SESSION['user_id'] ?? null;

    if (!$student_id) {
        die("You must be logged in to view this page.");
    }

    // Retrieve semester info from URL parameter
    $semester_key = $_GET['semester_key'] ?? null;


    if ($semester_key) {
        // Fetch semester info from the database
        $query = "SELECT id, semester, YEAR(start_date) as year FROM semester WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $semester_key);
        $stmt->execute();
        $result = $stmt->get_result();
        $semester_info = $result->fetch_assoc();


    } else {

        echo "Debug: No semester_key provided<br>";
    }

    if (!$semester_info) {
        die("Unable to find the selected semester. Please go back and try again. (semester_key: $semester_key)");
    }

    // If we reach this point, we have valid semester information
    $semester_id = $semester_info['id'];
    $semester_name = $semester_info['semester'];
    $semester_year = $semester_info['year'];

    // Function to render stars
    function renderStars($rating) {
        $fullStars = floor($rating);
        $halfStar = $rating - $fullStars >= 0.5 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;
        
        $stars = str_repeat('★', $fullStars) . 
                 ($halfStar ? '½' : '') . 
                 str_repeat('☆', $emptyStars);
        
        return $stars;
    }

    ?>

    <ul class="teacher-list" id="teacherList">
        <?php
        $sql = "SELECT t.id, t.name, t.subject, t.rating, 
                CASE WHEN e.teacher_id IS NOT NULL THEN 1 ELSE 0 END as is_evaluated
                FROM teachers t
                JOIN semester_teachers st ON t.id = st.teacher_id
                LEFT JOIN evaluations e ON t.id = e.teacher_id AND e.student_id = ?
                WHERE st.semester_id = ?
                ORDER BY is_evaluated ASC, t.name ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $student_id, $semester_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $delay = 0.1; // Starting delay
            while($row = $result->fetch_assoc()) {
                echo "<li class='teacher-item' style='animation: fadeIn 0.6s forwards; animation-delay: {$delay}s;'>";
                echo "<div class='teacher-name'>" . htmlspecialchars($row["name"]) . "</div>";
                echo "<div class='teacher-subject'>" . htmlspecialchars($row["subject"]) . "</div>";
                echo "<div class='rating' id='rating-" . $row["id"] . "'>Current Rating: <span class='star'>" . renderStars($row["rating"]) . "</span> (<span class='rating-value'>" . number_format($row["rating"], 1) . "</span>)</div>";
                if ($row["is_evaluated"] == 0) {
                    echo "<button class='evaluate-btn' onclick='evaluateTeacher(" . $row["id"] . ", \"" . htmlspecialchars($row["name"]) . "\", \"" . htmlspecialchars($row["subject"]) . "\")'>Evaluate</button>";
                } else {
                    echo "<button class='evaluate-btn' disabled>Evaluated</button>";
                }
                echo "</li>";
                $delay += 0.2; // Increment delay for next item
            }
        } else {
            echo "<li style='animation: fadeIn 0.6s forwards;'>No teachers found for this semester</li>";
        }

        $conn->close();
        ?>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function evaluateTeacher(teacherId, teacherName, teacherSubject) {
    sessionStorage.setItem('lastEvaluatedTeacher', teacherId);
    window.location.href = `questionnaire.php?id=${encodeURIComponent(teacherId)}&name=${encodeURIComponent(teacherName)}&subject=${encodeURIComponent(teacherSubject)}`;
}

function updateRatings(specificTeacherId = null) {
    $.ajax({
        url: 'get_ratings.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            data.forEach(function(teacher) {
                if (!specificTeacherId || specificTeacherId == teacher.id) {
                    var ratingDiv = $('#rating-' + teacher.id);
                    ratingDiv.find('.star').html(renderStars(teacher.rating));
                    ratingDiv.find('.rating-value').text(teacher.rating.toFixed(1));
                }
            });
        },
        complete: function() {
            if (!specificTeacherId) {
                // Schedule the next update in 10 seconds
                setTimeout(function() { updateRatings(); }, 10000);
            }
        }
    });
}

function renderStars(rating) {
    var fullStars = Math.floor(rating);
    var halfStar = rating - fullStars >= 0.5 ? 1 : 0;
    var emptyStars = 5 - fullStars - halfStar;
    
    return '★'.repeat(fullStars) + (halfStar ? '½' : '') + '☆'.repeat(emptyStars);
}

// Start the update cycle when the page loads
$(document).ready(function() {
    updateRatings();
    
    // Check if we just returned from an evaluation
    var lastEvaluatedTeacher = sessionStorage.getItem('lastEvaluatedTeacher');
    if (lastEvaluatedTeacher) {
        // Immediately update the rating for this teacher
        updateRatings(lastEvaluatedTeacher);
        sessionStorage.removeItem('lastEvaluatedTeacher');
    }
});
</script>
</body></html>