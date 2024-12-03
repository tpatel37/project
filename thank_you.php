<?php
if (isset($_GET['name'])) {
    $name = htmlspecialchars($_GET['name']); // Retrieve and sanitize the user's name
} else {
    $name = "Guest"; // Default if name is not passed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #fff;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .thank-you-card {
            background: #fff;
            color: #333;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }
        .thank-you-card img {
            width: 80px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: #6a11cb;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background: #2575fc;
        }
    </style>
</head>
<body>
    <div class="thank-you-card">
        <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="Thank You Icon">
        <h1>Thank You, <?php echo $name; ?>!</h1>
        <p>We appreciate your decision to stay with us.</p>
        <p>Please pay at reception. We look forward to hosting you!</p>
        <a href="index.php" class="btn btn-primary mt-4">Back to Home</a>
    </div>
</body>
</html>
