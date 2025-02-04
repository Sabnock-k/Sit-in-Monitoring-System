<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="w3-light-grey">

    <div class="w3-container w3-blue w3-padding">
        <h2><i class="fas fa-user-circle"></i> Welcome to Dashboard</h2>
    </div>

    <div class="w3-container w3-white w3-padding w3-margin">
        <h3>Hello, <?php echo htmlspecialchars($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?>!</h3>
        <p><b>ID No:</b> <?php echo htmlspecialchars($_SESSION['idno']); ?></p>
        <p><b>Course:</b> <?php echo htmlspecialchars($_SESSION['course']); ?></p>
        <p><b>Year Level:</b> <?php echo htmlspecialchars($_SESSION['year_level']); ?></p>

        <a href="logout.php" class="w3-button w3-red"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

</body>
</html>
