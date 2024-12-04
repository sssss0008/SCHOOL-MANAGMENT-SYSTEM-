<?php
// Database Configuration
$host = "localhost";
$user = "root";
$password = "";
$dbname = "student_management";

$conn = new mysqli($host, $user, $password, $dbname);

// Check Database Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to Validate Input
function validateInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Handle Add Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_student"])) {
    $name = validateInput($_POST["name"]);
    $age = validateInput($_POST["age"]);
    $class = validateInput($_POST["class"]);
    $contact = validateInput($_POST["contact"]);

    if (!empty($name) && !empty($age) && !empty($class) && !empty($contact)) {
        $stmt = $conn->prepare("INSERT INTO students (name, age, class, contact) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $name, $age, $class, $contact);

        if ($stmt->execute()) {
            echo "Student added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required!";
    }
}

// Handle Add Teacher
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_teacher"])) {
    $name = validateInput($_POST["name"]);
    $subject = validateInput($_POST["subject"]);

    if (!empty($name) && !empty($subject)) {
        $stmt = $conn->prepare("INSERT INTO teachers (name, subject) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $subject);

        if ($stmt->execute()) {
            echo "Teacher added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required!";
    }
}

// Handle Add Class
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_class"])) {
    $class_name = validateInput($_POST["class_name"]);
    $class_teacher = validateInput($_POST["class_teacher"]);

    if (!empty($class_name) && !empty($class_teacher)) {
        $stmt = $conn->prepare("INSERT INTO classes (class_name, class_teacher) VALUES (?, ?)");
        $stmt->bind_param("ss", $class_name, $class_teacher);

        if ($stmt->execute()) {
            echo "Class added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required!";
    }
}

// Handle Record Fee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["record_fee"])) {
    $student_name = validateInput($_POST["student_name"]);
    $amount = validateInput($_POST["amount"]);
    $date_paid = validateInput($_POST["date_paid"]);

    if (!empty($student_name) && !empty($amount) && !empty($date_paid)) {
        $stmt = $conn->prepare("INSERT INTO fees (student_name, amount, date_paid) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $student_name, $amount, $date_paid);

        if ($stmt->execute()) {
            echo "Fee recorded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required!";
    }
}

// Handle Add Grade
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_grade"])) {
    $student_name = validateInput($_POST["student_name"]);
    $subject = validateInput($_POST["subject"]);
    $grade = validateInput($_POST["grade"]);

    if (!empty($student_name) && !empty($subject) && !empty($grade)) {
        $stmt = $conn->prepare("INSERT INTO grades (student_name, subject, grade) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $student_name, $subject, $grade);

        if ($stmt->execute()) {
            echo "Grade added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required!";
    }
}

$conn->close();
?>
