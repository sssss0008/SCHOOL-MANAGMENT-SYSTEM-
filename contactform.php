<?php
// Initialize variables
$name = $email = $message = "";
$nameErr = $emailErr = $messageErr = "";
$successMessage = "";

// Form submission processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        // Check if name contains only letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // Check if email format is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Validate message
    if (empty($_POST["message"])) {
        $messageErr = "Message is required";
    } else {
        $message = test_input($_POST["message"]);
    }

    // If no errors, save data
    if (empty($nameErr) && empty($emailErr) && empty($messageErr)) {
        // Save data to a file (can be changed to save in database)
        $file = fopen("contact_data.txt", "a");
        $data = "Name: $name\nEmail: $email\nMessage: $message\n\n";
        fwrite($file, $data);
        fclose($file);

        $successMessage = "Thank you for contacting us, $name! We will get back to you soon.";
    }
}

// Function to clean data (sanitize input)
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Thank You</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f1f1f1;
            text-align: center;
        }

        .message {
            margin-top: 50px;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>

    <div class="message">
        <?php 
        if (!empty($successMessage)) {
            echo "<p class='success'>$successMessage</p>";
        } else {
            if (!empty($nameErr)) echo "<p class='error'>$nameErr</p>";
            if (!empty($emailErr)) echo "<p class='error'>$emailErr</p>";
            if (!empty($messageErr)) echo "<p class='error'>$messageErr</p>";
        }
        ?>
        <p><a href="index.html">Go back to the form</a></p>
    </div>

</body>
</html>
