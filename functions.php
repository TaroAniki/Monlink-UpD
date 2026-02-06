<?php

function check_login($con) {
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $query = "SELECT * FROM account_details WHERE user_id = '$id' LIMIT 1";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['first_name'] = $user_data['first_name'];
            $_SESSION['last_name'] = $user_data['last_name'];
            return $user_data;
        }
    }
    header("Location: login.php");
    die;
}

function get_unique_id($dbConnection) {
    $limit = 0; // Safety valve to prevent infinite loops

    do {
        // 1. Generate the 10-digit number
        $unique_id = random_num(10);

        // 2. Check the database (Example: checking an 'orders' table)
        // Adjust 'table_name' and 'column_name' to match your database
        $stmt = $dbConnection->prepare("SELECT COUNT(*) FROM account_details WHERE userid_id = ?");
        $stmt->execute([$unique_id]);
        $exists = $stmt->fetchColumn();

        $limit++;
        if ($limit > 100) {
            throw new Exception("Unable to generate unique ID after 100 tries.");
        }

    } while ($exists > 0); // Keep looping while the ID exists

    return $unique_id;
}

// The helper function from before
function random_num($length) {
    $text = "";
    for ($i = 0; $i < $length; $i++) {
        $text .= mt_rand(0, 9);
    }
    return $text;
}

function random_pass($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}