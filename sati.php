<?php
header('Content-Type: application/json');

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = sanitizeInput($_POST['fullName'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $dob = sanitizeInput($_POST['dob'] ?? '');
    $gender = sanitizeInput($_POST['gender'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');

    $errors = [];

    if (empty($fullName) || !preg_match("/^[a-zA-Z\s]+$/", $fullName)) {
        $errors[] = "Invalid full name";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address";
    }

    if (!preg_match("/^\d{10}$/", $phone)) {
        $errors[] = "Invalid phone number";
    }

    if (empty($dob) || !strtotime($dob)) {
        $errors[] = "Invalid date of birth";
    }

    if (!empty($errors)) {
        echo json_encode([
            'status' => 'error',
            'message' => implode(', ', $errors)
        ]);
        exit;
    }

    $response = [
        'status' => 'success',
        'message' => "Registration Successful!<br>" .
                     "Name: $fullName<br>" .
                     "Email: $email<br>" .
                     "Phone: $phone<br>" .
                     "Date of Birth: $dob<br>" .
                     "Gender: $gender<br>" .
                     "Address: $address"
    ];

    echo json_encode($response);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>