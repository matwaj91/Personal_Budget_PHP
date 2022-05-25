<?php

    session_start();

    if(isset($_POST['email'])){ // sprawdzamy czy formularz zostal wyslany(tzn czy nacisniety zostal przycisk SUBMIT) 
        //udana walidacja
        $correct_validation = true; 

        //sprawdzamy poprawnosc email address
        $first_name = $_POST['firstName'];
        $email = $_POST['email'];
        $email_sanitization = filter_var($email, FILTER_SANITIZE_EMAIL);

        if((filter_var($email_sanitization, FILTER_VALIDATE_EMAIL) == false) || (($email_sanitization != $email))){
            $correct_validation = false; 
            $_SESSION['registration_error'] = "Please provide a valid email address!";
        }

        //sprawdzamy poprawnosc hasla
        $password = $_POST['password'];

        if(strlen($password) < 8){
            $correct_validation = false; 
            $_SESSION['registration_error'] = "Password must contain at least 8 characters!";
        }

        //hashowanie hasla
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        require_once "DB_CONNECT.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        try{
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            if($connection->connect_errno != 0){ // sprawdzamy czy jestesmy polaczeni z baza danych
                throw new Exception(mysqli_connect_errno()); // "rzucamy bledem"
            }else{
                //czy email juz istnieje
                $query_result = $connection->query("SELECT id FROM users WHERE email='$email'");
                $query_result2 = $connection->query("SELECT id FROM incomes_category_assigned_to_users");
                $query_result3 = $connection->query("SELECT id FROM expenses_category_assigned_to_users");
                $query_result4 = $connection->query("SELECT id FROM payment_methods_assigned_to_users");

                $incomes_category_numbers = $query_result2->num_rows;
                if($incomes_category_numbers == 0){
                    $connection->query("ALTER TABLE incomes_category_assigned_to_users AUTO_INCREMENT=1");
                }

                $expenses_category_numbers = $query_result3->num_rows;
                if($expenses_category_numbers == 0){
                    $connection->query("ALTER TABLE expenses_category_assigned_to_users AUTO_INCREMENT=1");
                }

                $payment_methods_numbers = $query_result4->num_rows;
                if($payment_methods_numbers == 0){
                    $connection->query("ALTER TABLE payment_methods_assigned_to_users AUTO_INCREMENT=1");
                }

                if(!$query_result) throw new Exception($connection->error);

                $email_numbers = $query_result->num_rows;
                if($email_numbers > 0){
                    $correct_validation = false; 
                    $_SESSION['registration_error'] = "This email address is already being used!";
                }

                if($correct_validation == true){
                    //dodajemy nowegi usera do bazy jesli przeszlismy walidacje pomyslnie
                    if($connection->query("INSERT INTO users VALUES(NULL,'$first_name','$password_hash','$email')"))
                    {
                        $connection->query("INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT users.id, incomes_category_default.name FROM users, incomes_category_default WHERE users.email = '$email'");
                        $connection->query("INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT users.id, expenses_category_default.name FROM users, expenses_category_default WHERE users.email = '$email'");
                        $connection->query("INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT users.id, payment_methods_default.name FROM users, payment_methods_default WHERE users.email = '$email'");
                        $_SESSION['registration_successful'] = "Account has been successfully created! You can log in to your account!";
                        header('Location: Personal_Budget_LOGIN.php');
                    }
                    else
                    {
                        throw new Exception($connection->error);
                    }
                }
                $connection->close();
            }
        }catch(Exception $exception){ // "lapiemy blad"
            $correct_validation = false; 
            $_SESSION['registration_error'] = "Server error occurred! We are sorry for the inconvenience!";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal_Budget_REGISTRATION</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="Personal_Budget_REGISTRATION.css">
</head>

<body>
    <section>
        <header>
            <div class="">
                <h1 class="display-1 text-center p-3">Personal Budget</h1>
            </div>
        </header>
    </section>
    <div class="">
        <blockquote class="blockquote text-center text-black p-2 ">
            <p>"Unless you control your money, making more won’t help. <br> You’ll just have bigger
                payments."
            </p>
            <footer class="blockquote-footer text-right font-italic ">Dave Ramsey</footer>
        </blockquote>
    </div>
    <main>
        <div class="container mt-1 p-3 h5 mx-auto">
            <form method="post"
                class="bg-white rounded-lg opacity-75 shadow-lg p-3 mx-auto col-10 col-sm-9 col-md-7 col-lg-6 col-xl-5 col-xxl-4">
                <div class="form-group mx-auto">
                    <label for="firstName" class="font-weight-normal text-black">First name</label>
                    <input type="text" class="form-control font-weight-bold text-dark border-4" id="firstName"
                        name="firstName" aria-describedby="firstName" required>
                </div>
                <div class="form-group mx-auto">
                    <label for="email" class="font-weight-normal text-black">Email address</label>
                    <input type="email" class="form-control font-weight-bold text-dark border-4" id="email" name="email"
                        aria-describedby="email" required>
                </div>
                <div class="form-group mx-auto">
                    <label for="password" class="font-weight-normal text-black">Password</label>
                    <input type="password" class="form-control font-weight-bold text-dark border-4" id="password" name="password"
                        required>
                </div>
                <button type="submit" class="logInButton my-3 p-1 d-block text-white btn btn-block mx-auto">Sign Up
                </button>
                <a href="Personal_Budget_LOGIN.php"
                    class="text-decoration-none newAccountButton my-1 p-1 d-block text-white btn btn-block mx-auto">Back
                    to Login Page</a>
            </form>
        </div>
        <div class=" text-center text-danger bg-white font-weight-bold h5">
                <?php if(isset($_SESSION['registration_error'])){
                    echo $_SESSION['registration_error']; 
                    unset($_SESSION['registration_error']);}?>
        </div>
    </main>
    <footer>
        <div class="footer">
            <p class="text-center h5">All rights reserved&copy; 2022 Thank you for your visit!</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
</body>

</html>