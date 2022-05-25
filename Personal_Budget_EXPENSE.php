<?php

session_start(); // musimy umiescic zeby moc pezeslac zmienna sesyjna

if(!isset($_SESSION['logged_in'])){
    header('Location: Personal_Budget_LOGIN.php');
    exit();
}

if(isset($_POST['payment_method'])){ // sprawdzamy czy formularz zostal wyslany(tzn czy nacisniety zostal przycisk SUBMIT) 
    
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $category = $_POST['category'];
    $comment = $_POST['comment'];
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id'];


    require_once "DB_CONNECT.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try{
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if($connection->connect_errno != 0){ // sprawdzamy czy jestesmy polaczeni z baza danych
            throw new Exception(mysqli_connect_errno()); // "rzucamy bledem"
        }else{

            $query_result = $connection->query("SELECT id FROM expenses_category_assigned_to_users WHERE user_id='$user_id' AND name='$category'");
            $query_result2 = $connection->query("SELECT id FROM payment_methods_assigned_to_users WHERE user_id='$user_id' AND name='$payment_method'");

            $row = $query_result->fetch_assoc();
            $expense_category_id = $row['id'];

            $row = $query_result2->fetch_assoc();
            $payment_method_id = $row['id'];


            if($connection->query("INSERT INTO expenses VALUES(NULL,'$user_id','$expense_category_id', '$payment_method_id', '$amount', '$date', '$comment')"))
            {
                    $_SESSION['expense_successful'] = "Expense has been added!";
                    header('Location: Personal_Budget_MAINMENU.php');
            }
            else{
                    throw new Exception($connection->error);
            }
            $connection->close();
        }
    }catch(Exception $exception){ // "lapiemy blad"
        $_SESSION['expense_error'] = "Server error occurred! We are sorry for the inconvenience!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal_Budget_EXPENSE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Personal_Budget_EXPENSE.css">
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
                        <a class="nav-link" href="Personal_Budget_MAINMENU.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Personal_Budget_INCOME.php">Add Income</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="Personal_Budget_EXPENSE.php">Add Expense</a>
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
    <div class=" text-center mt-4 text-danger bg-white font-weight-bold h5">
                <?php if(isset($_SESSION['expense_error'])){
                    echo $_SESSION['expense_error']; 
                    unset($_SESSION['expense_error']);}?>
    </div>
    <div class="container mt-2 p-2 h5 mx-auto">
        <form method="post"
            class="bg-white rounded-lg opacity-75 shadow-lg p-3 mx-auto col-10 col-sm-9 col-md-7 col-lg-6 col-xl-5 col-xxl-4 ">
            <div class="mb-2">
                <h1 class="display-6 text-center p-0">Expense</h1>
            </div>
            <div class="form-group mx-auto">
                <label for="amount" class="font-weight-normal text-black">Amount</label>
                <input type="number" min="0" class="form-control font-weight-bold text-dark border-4" id="amount"
                    name="amount" aria-describedby="amount" required>
            </div>
            <div class="form-group mx-auto">
                <label for="date" class="font-weight-normal text-black">Date</label>
                <input type="date" class="form-control font-weight-bold text-dark border-4" id="date" name="date"
                    aria-describedby="date" required>
            </div>
            <div class="mx-auto mb-2">
                <label class=" font-weight-normal text-black">Payment method:</label>
                <div>
                    <label for="cash" class="font-weight-bold h6">Cash</label>
                    <input  type="radio" id="cash" name="payment_method" value="Cash" checked>
                </div>
                <div>
                    <label for="debitCard" class="font-weight-bold h6">Debit Card</label>
                    <input  type="radio" id="debitCard" name="payment_method" value="Debit Card" checked>
                </div>
                <div>
                    <label for="creditCard" class="font-weight-bold h6">Credit Card</label>
                    <input type="radio" id="creditCard" name="payment_method" value="Credit Card" checked>
                </div>
            </div>
            <div class="form-group mx-auto">
                <label for="date" class="font-weight-normal text-black">Category</label>
                <select class="form-control text-dark border-4" id="category" size="4" name="category"
                    aria-describedby="category" required>
                    <option value="transport" class="font-weight-bold">Transport</option>
                    <option value="books" class="font-weight-bold">Books</option>
                    <option value="food" class="font-weight-bold">Food</option>
                    <option value="apartments" class="font-weight-bold">Apartments</option>
                    <option value="telecommunication" class="font-weight-bold">Telecommunication</option>
                    <option value="health" class="font-weight-bold">Health</option>
                    <option value="clothes" class="font-weight-bold">Clothes</option>
                    <option value="hygiene" class="font-weight-bold">Hygiene</option>
                    <option value="kids" class="font-weight-bold">Kids</option>
                    <option value="recreation" class="font-weight-bold">Recreation</option>
                    <option value="trip" class="font-weight-bold">Trip</option>
                    <option value="savings" class="font-weight-bold">Savings</option>
                    <option value="retirement" class="font-weight-bold">Retirement</option>
                    <option value="repayment" class="font-weight-bold">Repayment</option>
                    <option value="gift" class="font-weight-bold">Gift</option>
                    <option value="another" class="font-weight-bold">Another</option>
                </select>
            </div>
            <div class="form-group mx-auto">
                <label for="comment" class="font-weight-normal text-black">Comment</label>
                <textarea id="comment" name="comment" placeholder="Type something here. . . " rows="2"
                    class="form-control font-weight-bold text-black border-4" ></textarea>
            </div>
            <button type="submit" class="addButton my-3 p-1 d-block text-white btn btn-block mx-auto">Add
            </button>
            <a href="Personal_Budget_MAINMENU.php"
                class="text-decoration-none cancelButton my-1 p-1 d-block text-white btn btn-block mx-auto">Cancel</a>
        </form>
    </div>
    <footer>
        <div class="footer">
            <p class="h5 text-center">All rights reserved&copy; 2022 Thank you for your visit!</p>
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