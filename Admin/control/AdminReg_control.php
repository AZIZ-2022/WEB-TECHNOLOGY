<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

  
    $name = ($_POST["name"]);
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Full name should contain only letters and spaces.";
    }

   
    $email = ($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format. It must contain '@'.";
    }

    
    $password = ($_POST["password"]);
    if (strlen($password) < 5) {
        $errors[] = "Password must be at least 5 characters long.";
    }


    $confirm_password = ($_POST["confirm_password"]);
    if ($password !== $confirm_password) {
        $errors[] = "Password and confirm password must match.";
    }

   
    $phone = ($_POST["phone"]);
    if (!preg_match("/^\d{11}$/", $phone)) {
        $errors[] = "Phone number must be exactly 11 digits and contain no letters.";
    }

   
    $nid = ($_POST["nid"]);
    if (!preg_match("/^\d{10}$/", $nid)) {
        $errors[] = "NID number must contain only digits and be exactly 10 digits.";
    }

    
     if (empty($_POST["education"])) {
    $errors[] = "Educational qualification must be selected.";
    }

   
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "$error";
        }
    } else {
        
        echo "Registration successful!";
        
    }
}
?>
