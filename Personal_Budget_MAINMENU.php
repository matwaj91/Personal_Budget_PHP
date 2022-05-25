<?php

session_start(); // musimy umiescic zeby moc pezeslac zmienna sesyjna

if(!isset($_SESSION['logged_in'])){
    header('Location: Personal_Budget_LOGIN.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal_Budget_MAINMENU</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Personal_Budget_MAINMENU.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-light bg-white navbar-expand-md">
            <a class="navbar-brand " href="#"></a>
            <button class="navbar-toggler order-first " type="button" data-toggle="collapse" data-target="#mainMenu"
                aria-controls="mainMenu" aria-expanded="false" aria-label="navigation">
                <span class="navbar-toggler-icon opacity-75"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-left " id="mainMenu">
                <ul class=" navbar-nav h5 p-0 mr-2">
                    <li class="nav-item ">
                        <a class="nav-link active" href="Personal_Budget_MAINMENU.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Personal_Budget_INCOME.php">Add Income</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Personal_Budget_EXPENSE.php">Add Expense</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"
                            id="subMenu" aria-haspopup="true">Display
                            Balance</a>
                        <ul class="dropdown-menu" aria-labelledby="subMenu">
                            <li><a class="dropdown-item h5" href="Personal_Budget_CURRENT_MONTH.php">Current Month</a></li>
                            <li><a class="dropdown-item h5" href="Personal_Budget_PREVIOUS_MONTH.php">Previous Month</a></li>
                            <li><a class="dropdown-item h5" href="Personal_Budget_CURRENT_YEAR.php">Current Year</a></li>
                            <li><button type="button" class="dropdown-item h5" data-toggle="modal"
                                    data-target="#exampleModal">Nonstandard</button></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Personal_Budget_LOG_OUT.php">Log Out</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="display: none;">
            <form action="Personal_Budget_NONSTANDARD.php" method="post">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Please provide a date range:
                            </h5>
                            <button type="button" class="btn-close " data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body h5">
                            <div class="form-group mx-auto">
                                <label for="dateFrom" class="font-weight-normal text-black">Date from:</label>
                                <input type="date" class="form-control text-dark border-4" id="dateFrom"
                                    aria-describedby="dateFrom" name="dateFrom" required>
                            </div>
                            <div class="form-group mx-auto">
                                <label for="dateTo" class="font-weight-normal text-black">Date to:</label>
                                <input type="date" class="form-control text-dark border-4" id="dateTo"
                                    aria-describedby="dateTo" name="dateTo" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cancelButton text-white btn" data-dismiss="modal">Close</button>
                            <button type="submit" class="modalButton btn text-white">Check the Balance</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </header>
    <div class=" text-center mt-4 text-dark bg-white font-weight-bold h5">
                <?php if(isset($_SESSION['income_successful'])){
                    echo $_SESSION['income_successful']; 
                    unset($_SESSION['income_successful']);}?>
    </div>
    <div class=" text-center mt-4 text-dark bg-white font-weight-bold h5">
                <?php if(isset($_SESSION['expense_successful'])){
                    echo $_SESSION['expense_successful']; 
                    unset($_SESSION['expense_successful']);}?>
    </div>
    <div class="container-fluid mt-4 p-2 h4 mx-auto">
        <div
            class="bg-white rounded-lg opacity-75 shadow-lg p-3 mx-auto col-10 col-sm-9 col-md-7 col-lg-6 col-xl-5 col-xxl-5">
            <div class="mb-4">
                <h1 class="display-4 text-center p-0">Personal Budget</h1>
            </div>
            <div>
                <p class="text-justify fst-italic lh-lg mb-4">Create monthly, yearly or nonstandard budgets arranged by
                    category
                    and keep
                    track of your expenses.
                    Know when you go over budget and anticipate how much money you can spare to meet your targets.
                </p>
                <div class="text-center fw-bolder fst-italic m">
                    Take control of your Budget!
                </div>
            </div>
        </div>
    </div>
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