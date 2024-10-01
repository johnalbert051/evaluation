<?php
// ... existing code ...
include '../connection/conn.php';

// Fetch custom questions from the database
$stmt = $conn->prepare("SELECT * FROM custom_questions ORDER BY id");
$stmt->execute();
$result = $stmt->get_result();
$custom_questions = $result->fetch_all(MYSQLI_ASSOC);

// Add this code where you want to insert a new question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    if (isset($_POST['question_text']) && isset($_POST['question_type'])) {
        $question_text = $_POST['question_text'];
        $question_type = $_POST['question_type'];
        
        if (!empty($question_text) && !empty($question_type)) {
            $stmt = $conn->prepare("INSERT INTO custom_questions (question_text, question_type) VALUES (?, ?)");
            $stmt->bind_param("ss", $question_text, $question_type);
            
            if ($stmt->execute()) {
                $message = "New question added successfully.";
                // Refresh the questions list
                $stmt = $conn->prepare("SELECT * FROM custom_questions ORDER BY id");
                $stmt->execute();
                $result = $stmt->get_result();
                $custom_questions = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Error: Question text and type cannot be empty.";
        }
    } else {
        $error = "Error: Missing question text or type.";
    }
}

// Add this code to handle question deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_question'])) {
    if (isset($_POST['question_id'])) {
        $question_id = $_POST['question_id'];
        
        $stmt = $conn->prepare("DELETE FROM custom_questions WHERE id = ?");
        $stmt->bind_param("i", $question_id);
        
        if ($stmt->execute()) {
            $delete_message = "Question deleted successfully.";
        } else {
            $delete_error = "Error: " . $stmt->error;
        }
        $stmt->close();
        
        // Refresh the questions list
        $stmt = $conn->prepare("SELECT * FROM custom_questions ORDER BY id");
        $stmt->execute();
        $result = $stmt->get_result();
        $custom_questions = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $delete_error = "Error: Missing question ID.";
    }
}

// ... rest of the existing code ...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... existing head content ... -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

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
            background-color: var(--secondary-color);
            color: white;
            padding: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        .nav-menu {
            list-style-type: none;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 5px;
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
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link i {
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        h2, h3 {
            color: var(--primary-color);
            margin-top: 20px;
            margin-bottom: 15px;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        input[type="text"], select, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"], 
        .back-button
         {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover, 
        .back-button:hover, 
        .delete-button:hover {
            background-color: var(--secondary-color);
        }

        .back-button i {
            margin-right: 5px;
        }

        .delete-button {
            background-color: var(--accent-color);
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .question-list {
            margin-top: 20px;
        }

        .question-item {
            background-color: white;
            border: 1px solid #e0e0e0;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .question-item strong {
            font-size: 14px;
        }

        .question-item small {
            font-size: 12px;
            color: #777;
        }

        .delete-button {
            background-color: var(--accent-color) !important;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.3s;
        }

        .delete-button:hover {
            background-color: #e80000 !important;
        }

        .evaluation-section {
            margin-top: 20px;
        }

        .criteria {
            background-color: white;
            border: 1px solid #e0e0e0;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .criteria h4 {
            margin-top: 0;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .rating-scale {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .rating-scale label {
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .rating-scale input[type="radio"] {
            margin-right: 5px;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }

        .delete-form {
            display: inline;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-header h3 {
            margin: 0;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: var(--secondary-color);
        }

        .back-button i {
            margin-right: 5px;
        }

        /* ... rest of the styles ... */
    </style>
</head>
<body>
    <div class="container">

        <div class="main-content">
            <div class="header">
            <h2>Custom Questions</h2>
            </div>
            
            <form method="POST">
                <div class="form-header">
                    <h3>Add New Custom Question</h3>
                    <a href="semester.php" class="back-button"><i class="fas fa-arrow-left"></i> Back to Semesters</a>
                </div>
                <label for="question_text">Question Text:</label>
                <input type="text" id="question_text" name="question_text" required>
                
                <label for="question_type">Question Type:</label>
                <select id="question_type" name="question_type" required>
                    <option value="rating">Rating</option>
                    <option value="text">Text</option>
                </select>
                
                <button type="submit" name="add_question">Add Question</button>
            </form>

            <?php if (isset($message)): ?>
                <p class="message success"><?php echo $message; ?></p>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <p class="message error"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="evaluation-section">
                <h3>Custom Evaluation Criteria</h3>
                <?php foreach ($custom_questions as $question): ?>
                    <div class="criteria">
                        <h4><?php echo htmlspecialchars($question['question_text']); ?></h4>
                        <?php if ($question['question_type'] == 'rating'): ?>
                            <div class="rating-scale">
                                <label><input type="radio" name="custom_<?php echo $question['id']; ?>" value="1"> 1 - Poor</label>
                                <label><input type="radio" name="custom_<?php echo $question['id']; ?>" value="2"> 2 - Fair</label>
                                <label><input type="radio" name="custom_<?php echo $question['id']; ?>" value="3"> 3 - Satisfactory</label>
                                <label><input type="radio" name="custom_<?php echo $question['id']; ?>" value="4"> 4 - Good</label>
                                <label><input type="radio" name="custom_<?php echo $question['id']; ?>" value="5"> 5 - Excellent</label>
                            </div>
                        <?php else: ?>
                            <textarea name="custom_<?php echo $question['id']; ?>" placeholder="Enter your response" rows="3"></textarea>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="question-list">
                <h3>Existing Custom Questions</h3>
                <?php foreach ($custom_questions as $question): ?>
                    <div class="question-item">
                        <div>
                            <strong><?php echo htmlspecialchars($question['question_text']); ?></strong>
                            <br>
                            <small>Type: <?php echo ucfirst($question['question_type']); ?></small>
                        </div>
                        <form method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this question?');">
                            <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                            <button type="submit" name="delete_question" class="delete-button">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (isset($delete_message)): ?>
                <p class="message success"><?php echo $delete_message; ?></p>
            <?php endif; ?>

            <?php if (isset($delete_error)): ?>
                <p class="message error"><?php echo $delete_error; ?></p>
            <?php endif; ?>

            <!-- ... rest of the form ... -->
        </div>
    </div>
</body>
</html>