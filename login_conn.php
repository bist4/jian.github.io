<?php
session_start(); // Start the session

// include('security.php');
require('config/db_con.php');

if (isset($_POST['username']) && isset($_POST['password'])) {

    // Initialize a counter for failed login attempts
    $failedAttempts = 0;

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    if (empty($username) && empty($password)) {
        header("Location: index.php?error=Username and Password are required");
        exit();
    } elseif (empty($username)) {
        header("Location: index.php?error=Username is required");
        exit();
    } elseif (empty($password)) {
        header("Location: index.php?error=Password is required");
        exit();
    } else {
        
        $sql = "SELECT * FROM users WHERE Username='$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if ($row['is_Lock'] == 1) {
                // Account is locked
                header("Location: lock_account.php");
                exit();
            }
            
            if (password_verify($password, $row['Password'])) {
                // Password is correct, proceed with the login logic.
                $failedAttempts = 0;

                // Super Admin
                if ($row['UserRoleID'] == 0) {
                    // Administrator
                    $_SESSION['Username'] = $username;
                    $_SESSION['UserID'] = $row['UserID'];                    

                    $userID = $_SESSION['UserID'];
                    $select_sql = "SELECT Fname FROM users WHERE UserID = ? ";
                    $stmt = mysqli_prepare($conn, $select_sql);
                    mysqli_stmt_bind_param($stmt, "i", $userID);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $fname);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);
                


                    // Log the login activity
                    $logActivity = "INSERT INTO admin_logs (DateTime, Activity, UserID, Active, Action) 
                        VALUES (NOW(), CONCAT('Login as Super Admin - ', ?), ?, 1, 'LOGIN')";
                    $stmt = mysqli_prepare($conn, $logActivity);
                    mysqli_stmt_bind_param($stmt, "si", $fname, $userID);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    // Update the login column in userinfo table
                    $updateLogin = "UPDATE users SET is_Login = 1 WHERE UserID = " . $row['UserID'];
                    mysqli_query($conn, $updateLogin);

                    header("Location: home.php");
                    exit();
                }
                // Admin
                else if($row['UserRoleID'] == 1) {
                    // Administrator
                    $_SESSION['Username'] = $username;
                    $_SESSION['UserID'] = $row['UserID'];                    

                    // Log the login activity
                    $logActivity = "INSERT INTO admin_logs (DateTime, Activity, UserID, Active) 
                        VALUES (NOW(), 'Login as Administrator', " . $row['UserID'] . ", 1)";
                    mysqli_query($conn, $logActivity);

                    // Update the login column in userinfo table
                    $updateLogin = "UPDATE users SET is_Login = 1 WHERE UserID = " . $row['UserID'];
                    mysqli_query($conn, $updateLogin);

                    header("Location: home.php");
                    exit();
                }
                else if($row['UserRoleID'] == 2) {
                    // User
                    $_SESSION['Username'] = $username;
                    $_SESSION['UserID'] = $row['UserID'];                    

                    // Log the login activity
                    $logActivity = "INSERT INTO admin_logs (DateTime, Activity, UserID, Active) 
                        VALUES (NOW(), 'Login as User', " . $row['UserID'] . ", 1)";
                    mysqli_query($conn, $logActivity);

                    // Update the login column in userinfo table
                    $updateLogin = "UPDATE users SET is_Login = 1 WHERE UserID = " . $row['UserID'];
                    mysqli_query($conn, $updateLogin);

                    header("Location: home.php");
                    exit();
                }

                else if($row['UserRoleID'] == 3) {
                    // Doctor
                    $_SESSION['Username'] = $username;
                    $_SESSION['UserID'] = $row['UserID'];                    

                    // Log the login activity
                    $logActivity = "INSERT INTO admin_logs (DateTime, Activity, UserID, Active) 
                        VALUES (NOW(), 'Login as Doctor', " . $row['UserID'] . ", 1)";
                    mysqli_query($conn, $logActivity);

                    // Update the login column in userinfo table
                    $updateLogin = "UPDATE users SET is_Login = 1 WHERE UserID = " . $row['UserID'];
                    mysqli_query($conn, $updateLogin);

                    header("Location: home.php");
                    exit();
                }
               
                   
            } else {
                // Incorrect password
                $failedAttempts++;

                // Check if the account should be locked
                header("Location: index.php?error=Incorrect User name or password");
                exit();
            }
        } else {
            // No user found with the given username
            header("Location: index.php?error=Incorrect User name or password");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>