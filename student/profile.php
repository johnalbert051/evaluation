<?php
session_start();
include '../connection/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../index.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$student_id = $_SESSION['user_id'];
?>
<html>
<head>
<title>Student - Profile</title>
 <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Roboto', Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
    color: #333;
}
header {
    background-color: #2196f3;
    color: white;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
main {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
   
}
.profile-header {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
}
.profile-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 2rem;
    border: 4px solid #2196f3;
}
.profile-name {
    font-size: 2rem;
    margin: 0;
    color: #1976d2;
}
.profile-id {
    font-size: 1rem;
    color: #666;
    margin: 0.5rem 0;
}
.profile-section {
    margin-bottom: 2rem;
}
.profile-section h2 {
    color: #1976d2;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 0.5rem;
}
.profile-info {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 1rem;
}
.info-label {
    font-weight: bold;
    color: #555;
}
.edit-btn {
    background-color: #2196f3;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}
.edit-btn:hover {
    background-color: #1976d2;
}
footer {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 92px;
}

:root {
            --primary-color: #433878;
            --secondary-color: #f50057;
            --background-color: #f5f5f5;
            --card-color: #ffffff;
            --text-color: #333333;
            --nav-color: #160859;
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

        #user-menu {
            cursor: pointer;
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

/* ... rest of your existing styles ... */
</style>
</head>
<body>
    <nav1>
        <h3>Student Dashboard</h3>
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
    <main>
        <div class="profile-header">
            <img src="../img/pogi.jpg" alt="Student profile picture" class="profile-picture">
            <div>
                <h1 class="profile-name">Neil Dave</h1>
                <p class="profile-id">Student ID: 203201</p>
                <button class="edit-btn" id="edit-profile">Edit Profile</button>
            </div>
        </div>
        
        <section class="profile-section">
            <h2>Personal Information</h2>
            <div class="profile-info">
                <span class="info-label">Date of Birth:</span>
                <span>January 15, 2000</span>
                <span class="info-label">Gender:</span>
                <span>Male</span>
                <span class="info-label">Nationality:</span>
                <span>Filipino</span>
            </div>
        </section>
        
        <section class="profile-section">
            <h2>Contact Information</h2>
            <div class="profile-info">
                <span class="info-label">Email:</span>
                <span>dave@student.com</span>
                <span class="info-label">Phone:</span>
                <span>(+63) 9123-4567-434</span>
                <span class="info-label">Address:</span>
                <span> Richwell Colleges Inc.</span>
            </div>
        </section>
        
        <section class="profile-section">
            <h2>Academic Information</h2>
            <div class="profile-info">
                <span class="info-label">Major:</span>
                <span>Information System</span>
                <span class="info-label">Year:</span>
                <span>3rd</span>
                <span class="info-label">Advisor:</span>
                <span>Mr. Cenita</span>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Student Evaluation System. All rights reserved.</p>
    </footer>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const editButton = document.getElementById('edit-profile');
    const profileInfo = document.querySelectorAll('.profile-info');

    editButton.addEventListener('click', () => {
        if (editButton.textContent === 'Edit Profile') {
            editButton.textContent = 'Save Changes';
            profileInfo.forEach(section => {
                const spans = section.querySelectorAll('span:not(.info-label)');
                spans.forEach(span => {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = span.textContent;
                    input.style.width = '100%';
                    input.style.padding = '0.25rem';
                    input.style.border = '1px solid #ccc';
                    input.style.borderRadius = '4px';
                    span.parentNode.replaceChild(input, span);
                });
            });
        } else {
            editButton.textContent = 'Edit Profile';
            profileInfo.forEach(section => {
                const inputs = section.querySelectorAll('input');
                inputs.forEach(input => {
                    const span = document.createElement('span');
                    span.textContent = input.value;
                    input.parentNode.replaceChild(span, input);
                });
            });
            alert('Profile updated successfully!');
            // In a real application, this would send the updated data to the server
        }
    });

    // Simulating dynamic profile picture update
    const profilePicture = document.querySelector('.profile-picture');
    profilePicture.addEventListener('click', () => {
        const newPictureUrl = prompt('Enter the URL of your new profile picture:');
        if (newPictureUrl) {
            profilePicture.src = newPictureUrl;
            alert('Profile picture updated successfully!');
        }
    });
});

document.addEventListener('DOMContentLoaded', (event) => {
    const userMenu = document.getElementById('user-menu');
    const dropdownContent = document.getElementById('dropdown-content');

    userMenu.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', (e) => {
        if (!userMenu.contains(e.target) && !dropdownContent.contains(e.target)) {
            dropdownContent.style.display = 'none';
        }
    });
});
</script>
</body>
</html>