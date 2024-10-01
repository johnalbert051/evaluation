<?php
session_start(); // Start the session
include "../connection/conn.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../index.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$student_id = $_SESSION['user_id'];
// At the top of your PHP file, before the HTML starts
$id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'Not specified';
$teacherName = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Not specified';
$subject = isset($_GET['subject']) ? htmlspecialchars($_GET['subject']) : 'Not specified';
$evaluationDate = isset($_GET['evaluationDate']) ? htmlspecialchars($_GET['evaluationDate']) : date('Y-m-d');
$evaluator = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Not specified'; // Use session username
$studentId = isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : 'Not specified'; // Use session user ID

// Check if this specific student has already evaluated this specific teacher
$checkEvaluationSql = "SELECT * FROM evaluations WHERE teacher_id = ? AND student_id = ?";
$stmt = $conn->prepare($checkEvaluationSql);
$stmt->bind_param("ss", $id, $studentId);
$stmt->execute();
$result = $stmt->get_result();

$alreadyEvaluated = $result->num_rows > 0;
$stmt->close();

// Fetch custom questions from the database
$stmt = $conn->prepare("SELECT * FROM custom_questions WHERE is_deleted = 0 ORDER BY id");
$stmt->execute();
$result = $stmt->get_result();
$custom_questions = $result->fetch_all(MYSQLI_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$alreadyEvaluated) {
    $teacherName = htmlspecialchars($_POST['name']);
    $subject = htmlspecialchars($_POST['subject']);
    $evaluationDate = htmlspecialchars($_POST['evaluationDate']);
    $evaluator = htmlspecialchars($_POST['evaluator']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert into evaluation_list table
        $sql = "INSERT INTO evaluation_list (teacher_id, student_id, name, subject, evaluationDate, evaluator) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $id, $studentId, $teacherName, $subject, $evaluationDate, $evaluator);
        $stmt->execute();
        $evaluationId = $conn->insert_id;

        // Insert responses for each question
        $insertResponseSql = "INSERT INTO evaluation_responses (evaluation_id, question_id, rating, comments) 
                              VALUES (?, ?, ?, ?)";
        $stmtResponse = $conn->prepare($insertResponseSql);

        foreach ($custom_questions as $question) {
            $questionId = $question['id'];
            $rating = isset($_POST["custom_{$questionId}"]) ? htmlspecialchars($_POST["custom_{$questionId}"]) : null;
            $comments = isset($_POST["custom_{$questionId}_comments"]) ? htmlspecialchars($_POST["custom_{$questionId}_comments"]) : null;

            $stmtResponse->bind_param("iiss", $evaluationId, $questionId, $rating, $comments);
            $stmtResponse->execute();
        }

        // Insert a record into the evaluations table
        $insertEvaluationSql = "INSERT INTO evaluations (student_id, teacher_id, evaluation_date) VALUES (?, ?, ?)";
        $stmtEvaluation = $conn->prepare($insertEvaluationSql);
        $stmtEvaluation->bind_param("sss", $studentId, $id, $evaluationDate);
        $stmtEvaluation->execute();

        // Update the teacher's rating
        $updateRatingSql = "UPDATE teachers t
                            SET rating = (
                                SELECT AVG(er.rating)
                                FROM evaluation_responses er
                                JOIN evaluation_list el ON er.evaluation_id = el.id
                                WHERE el.teacher_id = ?
                            )
                            WHERE t.id = ?";
        $updateStmt = $conn->prepare($updateRatingSql);
        $updateStmt->bind_param("ii", $id, $id);
        $updateStmt->execute();
        $updateStmt->close();

        // Commit the transaction
        $conn->commit();

        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = document.getElementById('successModal');
                    var span = document.getElementsByClassName('close')[0];

                    modal.style.display = 'block';

                    span.onclick = function() {
                        modal.style.display = 'none';
                        window.location.href = 'student_home.php'; // Redirect after closing modal
                    }

                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = 'none';
                            window.location.href = 'student_home.php'; // Redirect after closing modal
                        }
                    }
                });
              </script>";
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>View Evaluation | Complete Evaluation System</title>
    <style>
         :root {
            --primary-color: #433878;
            --secondary-color: #f50057;
            --background-color: #f5f5f5;
            --card-color: #ffffff;
            --text-color: #333333;
            --nav-color: #160859;
        }
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f7f9;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #1e88e5;
            color: white;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
nav1 {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        nav1 h3 {
            margin: 0;
            font-weight: 500;
        }

        .dropdown {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
            
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

       

        nav {
            background-color: var(--nav-color);
            padding: 0.5rem;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 1rem;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 4px;
        }

        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        h1, h2, h3 {
            color: #fff;
        }
        .evaluation-details, .evaluation-section {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .rating-scale {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .rating-scale label {
            flex: 1;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 5px;
            margin: 0 5px;
        }

        .rating-scale input[type="radio"] {
            display: none;
        }

        .rating-scale input[type="radio"]:checked + label {
            background-color: var(--nav-color);
            color: #fff;
            border-color: #3498db;
        }

        .rating-scale label:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }
        .btn {
            display: inline-block;
            background-color: #433878;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #160859;
            transform: scale(1.02);
            transition: background-color 0.3s, transform 0.3s;
        }
        .signature-pad {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .alert {
            padding: 20px;
            background-color: #f9edbe; /* Yellow background */
            color: #856404; /* Dark yellow text */
            border: 1px solid #ffeeba; /* Light yellow border */
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.2em;
        }

        .alert strong {
            font-weight: bold;
        }

        .no-questions-container {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .no-questions-icon {
            font-size: 64px;
            color: #6c757d;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .no-questions-icon .overlay-icon {
            position: absolute;
            font-size: 32px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #ffffff;
            background-color: #6c757d;
            border-radius: 50%;
            padding: 5px;
        }

        .no-questions-title {
            font-size: 24px;
            color: #343a40;
            margin-bottom: 16px;
        }

        .no-questions-message {
            font-size: 16px;
            color: #6c757d;
            line-height: 1.5;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php
    // Make sure to include any necessary PHP code here, such as session start and user authentication
    // Retrieve the username from the session or database
    $username = $_SESSION['username'] ?? 'Student'; // Replace with your actual method of getting the username
    ?>

    <nav1>
        <h3>Student Evaluation</h3>
        <div class="dropdown">
            <h3 id="user-menu"><?php echo htmlspecialchars($username); ?> ▼</h3>
            <div id="dropdown-content" class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav1>
    <nav>
        <ul>
            <li><a href="student_home.php">Dashboard</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="#">Resources</a></li>
            <li><a href="profile.php">Profile</a></li>
        </ul>
    </nav>
    <div class="container">
        <?php if (!$alreadyEvaluated): ?>
        <form method="post" action="">
            <div class="evaluation-details">
           
                <h2>Employee Evaluation</h2>
                <p><strong>Teacher:</strong> <?php echo $teacherName; ?></p>
                <input type="text" name="name" value="<?php echo $teacherName; ?>" hidden required>
                <p><strong>Department:</strong> <?php echo $subject; ?></p>
                <input type="text" name="subject" value="<?php echo $subject; ?>" hidden required>
                <p><strong>Evaluation Date:</strong> <?php echo $evaluationDate; ?></p>
                <input type="date" name="evaluationDate" value="<?php echo $evaluationDate; ?>" hidden required>
                <p><strong>Evaluator:</strong> <?php echo $evaluator; ?></p>
                <input type="text" name="evaluator" value="<?php echo $evaluator; ?>" hidden required>
            </div>

            <div class="evaluation-section">
                <h3>Performance Criteria</h3>
                
                <!-- Custom Questions -->
                <?php
                $questionNumber = 1; // Initialize the question number counter
                if (empty($custom_questions)):
                ?>
                    <div class="no-questions-container">
                        <div class="no-questions-icon">
                            <i class="fas fa-clipboard"></i>
                            <i class="fas fa-question overlay-icon"></i>
                        </div>
                        <h3 class="no-questions-title">No Questions Available</h3>
                        <p class="no-questions-message">There are currently no evaluation questions available. Please check back later or contact the administrator for more information.</p>
                    </div>
                <?php
                else:
                    foreach ($custom_questions as $question):
                ?>
                    <div class="criteria">
                        <h4><?php echo $questionNumber . '. ' . htmlspecialchars($question['question_text']); ?></h4>
                        <?php if ($question['question_type'] == 'rating'): ?>
                            <div class="rating-scale">
                                <input type="radio" id="custom_<?php echo $question['id']; ?>_1" name="custom_<?php echo $question['id']; ?>" value="1" required>
                                <label for="custom_<?php echo $question['id']; ?>_1">1 - Poor</label>
                                <input type="radio" id="custom_<?php echo $question['id']; ?>_2" name="custom_<?php echo $question['id']; ?>" value="2" required>
                                <label for="custom_<?php echo $question['id']; ?>_2">2 - Fair</label>
                                <input type="radio" id="custom_<?php echo $question['id']; ?>_3" name="custom_<?php echo $question['id']; ?>" value="3" required>
                                <label for="custom_<?php echo $question['id']; ?>_3">3 - Satisfactory</label>
                                <input type="radio" id="custom_<?php echo $question['id']; ?>_4" name="custom_<?php echo $question['id']; ?>" value="4" required>
                                <label for="custom_<?php echo $question['id']; ?>_4">4 - Good</label>
                                <input type="radio" id="custom_<?php echo $question['id']; ?>_5" name="custom_<?php echo $question['id']; ?>" value="5" required>
                                <label for="custom_<?php echo $question['id']; ?>_5">5 - Excellent</label>
                            </div>
                            <!-- Add comment textarea only for rating questions -->
                            <textarea name="custom_<?php echo $question['id']; ?>_comments" placeholder="Additional comments on this rating" rows="3" required></textarea>
                        <?php else: ?>
                            <textarea name="custom_<?php echo $question['id']; ?>" placeholder="Enter your response" rows="3" required></textarea>
                        <?php endif; ?>
                    </div>
                <?php
                        $questionNumber++; // Increment the question number
                    endforeach;
                endif;
                ?>
            </div>

            <!--<div class="evaluation-section">
                <h3>Teacher Comments</h3>
                <textarea name="teacher_comments" placeholder="Teacher's comments on the evaluation" rows="5" required></textarea>
            </div> -->

            <button type="submit" id="submitEvaluation" class="btn">Submit Evaluation</button>
        </form>
        <?php else: ?>
        <div class="alert alert-warning">
            <strong>Notice:</strong> You have already evaluated this teacher.
            <br><br>
            <a href="list_of_teachers.php" class="btn">Go Back to List of Teachers</a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal HTML -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close"><a style="list-style: none; text-decoration: none; color: #000;" href="student_home.php">&times;</a></span>
            <p>Evaluation submitted successfully!</p>
        </div>
    </div>

    <script>
        // Initialize signature pads
        var evaluatorSignaturePad = new SignaturePad(document.getElementById('evaluatorSignature'));
        var employeeSignaturePad = new SignaturePad(document.getElementById('employeeSignature'));

        // Handle rating selection
        document.querySelectorAll('.rating-option').forEach(option => {
            option.addEventListener('click', function() {
                this.parentNode.querySelectorAll('.rating-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Handle form submission
        document.getElementById('submitEvaluation').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Collect all the form data
            var formData = {
                teacherId: <?php echo $teacherId; ?>,
                teacherName: "<?php echo $teacherName; ?>",
                department: "<?php echo $department; ?>",
                evaluationDate: "<?php echo $evaluationDate; ?>",
                evaluator: "<?php echo $evaluator; ?>",
                jobKnowledge: document.querySelector('input[name="job_knowledge"]:checked')?.value,
                qualityOfWork: document.querySelector('input[name="quality_of_work"]:checked')?.value,
                communicationSkills: document.querySelector('input[name="communication_skills"]:checked')?.value,
                overallAssessment: document.querySelector('textarea[placeholder="Provide an overall assessment of the employee\'s performance"]').value,
                goals: document.querySelector('textarea[placeholder="List specific goals for the employee to focus on in the coming period"]').value,
                employeeComments: document.querySelector('textarea[placeholder="Employee\'s comments on the evaluation"]').value,
                evaluatorSignature: evaluatorSignaturePad.toDataURL(),
                employeeSignature: employeeSignaturePad.toDataURL()
            };

            // Send the form data via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Show the modal
                    var modal = document.getElementById("successModal");
                    var span = document.getElementsByClassName("close")[0];

                    modal.style.display = "block";

                    // Close the modal when the user clicks on <span> (x)
                    span.onclick = function() {
                        modal.style.display = "none";
                        window.location.href = 'student_home.php'; // Redirect after closing modal
                    }

                    // Close the modal when the user clicks anywhere outside of the modal
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                            window.location.href = 'student_home.php'; // Redirect after closing modal
                        }
                    }
                }
            };
            xhr.send(JSON.stringify(formData));
        });
    </script>
    <script>
        // JavaScript to handle dropdown functionality
        document.getElementById('user-menu').onclick = function() {
            var dropdownContent = document.getElementById('dropdown-content');
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        };

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('#user-menu')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === 'block') {
                        openDropdown.style.display = 'none';
                    }
                }
            }
        };
    </script>
</body>
</html>
