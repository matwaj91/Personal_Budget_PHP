<?php

    session_start();

    if((isset($_SESSION['logged_in'])) && ($_SESSION['logged_in'] == true)){ // sprawdzamy czy user jest zalogowany(czy jest taka zmienna sesyjna i czy jest ustawiona na true)
        header('Location: Personal_Budget_MAINMENU.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal_Budget_LOGIN</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="Personal_Budget_LOGIN.css">
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
            <form action="Personal_Budget_CHECK_LOGIN.php" method="post"
                class="bg-white rounded-lg opacity-75 shadow-lg p-3 mx-auto col-10 col-sm-9 col-md-7 col-lg-6 col-xl-5 col-xxl-4">
                <div class="form-group mx-auto">
                    <label for="email" class="font-weight-normal text-black">Email address</label>
                    <input type="email" class="form-control font-weight-bold text-dark border-4 rounded-3" id="email"
                        name="email" aria-describedby="email" required>
                </div>
                <div class="form-group mx-auto">
                    <label for="password" class="font-weight-normal text-black">Password</label>
                    <input type="password" class="form-control font-weight-bold text-dark border-4 rounded-3"
                        id="password" name="password" required>
                </div>
                <button type="submit" class="logInButton my-3 p-1 d-block text-white btn btn-block mx-auto">Log
                    In</button>
                <a href="Personal_Budget_REGISTRATION.php"
                    class="text-decoration-none newAccountButton my-1 p-1 d-block text-white btn btn-block mx-auto">Create
                    New
                    Account</a>
            </form> 
        </div>
        <div class=" text-center text-danger bg-white font-weight-bold h5">
                <?php if(isset($_SESSION['login_error'])){
                    echo $_SESSION['login_error']; 
                    unset($_SESSION['login_error']);
                }?>
        </div>
        <div class=" text-center text-dark bg-white font-weight-bold h5">
                <?php if(isset($_SESSION['registration_successful'])){
                    echo $_SESSION['registration_successful']; 
                    unset($_SESSION['registration_successful']);}?>
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