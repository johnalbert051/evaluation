<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List</title>
    <!-- ... (keep the existing styles) ... -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
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
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 20px;
            padding: 5px 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            border: none;
            outline: none;
            padding: 5px;
            font-size: 14px;
        }

        .search-bar i {
            color: var(--text-color);
        }

        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-edit {
            color: #2ecc71;
            cursor: pointer;
        }

        .btn-delete {
            color: #e74c3c;
            cursor: pointer;
        }

        .add-teacher-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
            display: inline-block;
            text-decoration: none;
        }

        .add-teacher-button:hover {
            background-color: #2980b9;
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
            max-width: 600px;
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <div class="logo">Teachers Evaluation</div>
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
                    <i class="fas fa-user"></i>
                    <span>Students</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="courses.php" class="nav-link">
                    <i class="fas fa-book"></i>
                    <span>Courses</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="semester.php" class="nav-link">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Suestionnaire</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="evaluations.php" class="nav-link">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Evaluations</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="reports.php" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="settings.php" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>
            <div class="user-profile">
                <img src="https://i.pravatar.cc/100?img=12" alt="Admin profile picture">
                <span>Admin</span>
            </div>
        </div>

        <h2>Admin List</h2>
        <button class="add-teacher-button" id="openModal">Add New Admin</button>
        <table id="adminTable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Database connection
            include "../connection/conn.php";

            // Fetch admin data
            $sql = "SELECT * FROM admin_table";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["admin_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                    echo "<td class='action-buttons'>
                            <span class='btn-edit' data-id='" . $row["id"] . "'><i class='fas fa-edit'></i></span>
                            <span class='btn-delete' data-id='" . $row["id"] . "'><i class='fas fa-trash-alt'></i></span>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No admins found</td></tr>";
            }

            $conn->close();
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="adminModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Admin</h2>
        <form id="addAdminForm" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <div class="form-group">
                <input type="hidden" id="role" name="role" value="admin" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Add Admin</button>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $role = $_POST['role'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    include "../connection/conn.php";

    // Insert to admin_table
    $sql = "INSERT INTO admin_table (admin_name, username, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $username, $hashed_password, $role);
    
    if ($stmt->execute()) {
        echo "New Admin added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<script>
    // Get modal element
    var modal = document.getElementById("adminModal");
    // Get open modal button
    var openModal = document.getElementById("openModal");
    // Get close button
    var closeBtn = document.getElementsByClassName("close")[0];

    // Listen for open click
    openModal.addEventListener("click", function() {
        modal.style.display = "block";
    });

    // Listen for close click
    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Listen for outside click
    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });

    // Handle form submission
    document.getElementById("addAdminForm").addEventListener("submit", function(event) {
        event.preventDefault();
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm-password").value;

        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        // If passwords match, submit the form using AJAX
        var formData = new FormData(this);

        fetch('add_admin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new row to the table
                var table = document.getElementById("adminTable").getElementsByTagName('tbody')[0];
                var newRow = table.insertRow();
                newRow.innerHTML = `
                    <td>${data.admin.name}</td>
                    <td>${data.admin.username}</td>
                    <td class='action-buttons'>
                        <span class='btn-edit' data-id='${data.admin.id}'><i class='fas fa-edit'></i></span>
                        <span class='btn-delete' data-id='${data.admin.id}'><i class='fas fa-trash-alt'></i></span>
                    </td>
                `;
                
                // Clear form fields
                this.reset();
                
                // Close modal
                modal.style.display = "none";
                
                alert("Admin added successfully!");
            } else {
                alert("Error adding admin: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while adding the admin.");
        });
    });
</script>
</body>
</html>