    <?php
    session_start();
    session_destroy();
    header("Location: ../index.php"); // Redirect to login page or home page
    exit();
    ?>