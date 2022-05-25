<?php

session_start();

if((!isset($_POST['email'])) || (!isset($_POST['password']))){
    header('Location: Personal_Budget_LOGIN.php');
    exit();
}

require_once "DB_CONNECT.php";

$connection = @new mysqli($host, $db_user, $db_password, $db_name); //laczymy sie z baza danych, @ oznacza wyciszenie bledu

if($connection->connect_errno != 0){ // sprawdzamy czy jestesmy polaczeni z baza danych
    echo "Error: ".$connection->connect_errno;
}else{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $email = htmlentities($email, ENT_QUOTES, "UTF-8");

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'"; // zapytanie zamykamy w cudzyslowach,  a zmienne PHP bedace lancuhcmai w apostrofach

    if($query_result = @$connection->query(sprintf("SELECT * FROM users WHERE email='%s'", mysqli_real_escape_string($connection, $email)))){ //pobieramy dane z bazy danych 
        $user_numbers = $query_result->num_rows; // sprawdzamy ile mamy rekordow
        if($user_numbers > 0){  // jesli wiecej niz jeden to automatyczni wiemy ze w naszej bazie jest taki uzytkownik

            $row = $query_result->fetch_assoc(); // tworzymy tablice asocjacyjna

            // sprawdzamy hashowane haslo
            if(password_verify($password, $row['password'])){

                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $row['id']; // przypisujemy wynik do zmiennej sesyjnej

                unset($_SESSION['login_error']);
                $query_result->close(); // zwalniamy miejsce 
                header('Location: Personal_Budget_MAINMENU.php');
            }
            else{
                $_SESSION['login_error'] = 'Login failed! Incorrect email or password. ';
                header('Location:Personal_Budget_LOGIN.php');
            }
        }else{
            $_SESSION['login_error'] = 'Login failed! Incorrect email or password. ';
            header('Location:Personal_Budget_LOGIN.php');
        }
    }

    $connection->close();
}

?>