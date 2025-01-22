<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $name = ($_POST['name']);
    $email = ($_POST['email']);
    $password = ($_POST['password']);
    $confirm_password = ($_POST['confirm_password']);
    $phone = ($_POST['phone']);
    $address = ($_POST['address']);

    
    $errors = [];

   
    if (empty($name)) {
        $errors[] = "Full Name is required.";
    } elseif (preg_match('/\d/', $name)) {
        $errors[] = "Full Name should not contain numbers.";
    }


    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

  
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (!preg_match('/[@#$&]/', $password)) {
        $errors[] = "Password must contain at least one special character (@, #, $, or &).";
    }

    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!ctype_digit($phone)) {
        $errors[] = "Phone number must contain only digits.";
    } elseif (strlen($phone) > 11) {
        $errors[] = "Phone number must not exceed 11 digits.";
    }elseif(strlen($phone) < 11) {
        $errors[] = "Phone number must not less than 11 digits.";
    }

   
    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    } else {
        echo "Form submitted successfully!";
      
    }
}
?>
