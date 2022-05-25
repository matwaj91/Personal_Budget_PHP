<?php

session_start(); // musimy umiescic zeby moc pezeslac zmienna sesyjna

if(!isset($_SESSION['logged_in'])){
    header('Location: Personal_Budget_LOGIN.php');
    exit();
}

$user_id = $_SESSION['user_id'];

require_once "DB_CONNECT.php";
mysqli_report(MYSQLI_REPORT_STRICT);

try{
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->connect_errno != 0){ // sprawdzamy czy jestesmy polaczeni z baza danych
        throw new Exception(mysqli_connect_errno()); // "rzucamy bledem"
    }else{

        $query_result = $connection->query("SELECT SUM(amount) AS total_incomes FROM incomes WHERE user_id='$user_id' AND MONTH(date_of_income)=MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(date_of_income)=YEAR(CURDATE() - INTERVAL 1 MONTH)");
        $query_result2 = $connection->query("SELECT SUM(amount) AS total_expenses FROM expenses WHERE user_id='$user_id' AND MONTH(date_of_expense)=MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(date_of_expense)=YEAR(CURDATE() - INTERVAL 1 MONTH)");
        $query_result3 = $connection->query("SELECT DISTINCT income_category_assigned_to_user_id AS incomes_categories_id FROM incomes WHERE user_id='$user_id' AND MONTH(date_of_income)=MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(date_of_income)=YEAR(CURDATE() - INTERVAL 1 MONTH)");
        $query_result4 = $connection->query("SELECT DISTINCT expense_category_assigned_to_user_id AS expenses_categories_id FROM expenses WHERE user_id='$user_id' AND MONTH(date_of_expense)=MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(date_of_expense)=YEAR(CURDATE() - INTERVAL 1 MONTH)");

        $row = $query_result->fetch_assoc();
        $total_incomes_amount = $row['total_incomes'];

        $row = $query_result2->fetch_assoc();
        $total_expenses_amount = $row['total_expenses'];

        while($row = $query_result3->fetch_assoc())
            $income_category_id[] = $row['incomes_categories_id'];

        while($row = $query_result4->fetch_assoc())
            $expense_category_id[] = $row['expenses_categories_id'];

        if(!empty($income_category_id)){
            foreach($income_category_id as $value){
                $query_result5 = $connection->query("SELECT name AS incomes_categories_names FROM incomes_category_assigned_to_users WHERE user_id='$user_id' AND id='$value'");
                $row = $query_result5->fetch_assoc();
                $income_category_name[] = $row['incomes_categories_names'];
                $query_result6 = $connection->query("SELECT SUM(amount) AS incomes_categories_total_amount FROM incomes WHERE user_id='$user_id' AND income_category_assigned_to_user_id='$value' AND MONTH(date_of_income)=MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(date_of_income)=YEAR(CURDATE() - INTERVAL 1 MONTH)");
                $row = $query_result6->fetch_assoc();
                $income_category_sum[] = $row['incomes_categories_total_amount'];}

            $income_combined_array = array_combine($income_category_name, $income_category_sum);
        }

        if(!empty($expense_category_id)){
            foreach($expense_category_id as $value){
                $query_result7 = $connection->query("SELECT name AS expenses_categories_names FROM expenses_category_assigned_to_users WHERE user_id='$user_id' AND id='$value'");
                $row = $query_result7->fetch_assoc();
                $expense_category_name[] = $row['expenses_categories_names'];
                $query_result8 = $connection->query("SELECT SUM(amount) AS expenses_categories_total_amount FROM expenses WHERE user_id='$user_id' AND expense_category_assigned_to_user_id='$value' AND MONTH(date_of_expense)=MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(date_of_expense)=YEAR(CURDATE() - INTERVAL 1 MONTH)");
                $row = $query_result8->fetch_assoc();
                $expense_category_sum[] = $row['expenses_categories_total_amount'];}

            $expense_combined_array = array_combine($expense_category_name, $expense_category_sum);
        }

        $connection->close();
    }
}catch(Exception $exception){
    $_SESSION['balance_error'] = "Server error occurred! We are sorry for the inconvenience!";
}

$total_balance = ($total_incomes_amount - $total_expenses_amount);

if($total_incomes_amount < $total_expenses_amount)
    $negative_balance = "Be careful! You spend more than you earn.";
else
    $positive_balance = "Great job! You manage your finances very well!";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal_Budget_CURRENTH_MONTH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Personal_Budget_PREVIOUS_MONTH.css">
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
                        <a class="nav-link" href="Personal_Budget_EXPENSE.php">Add Expense</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" data-toggle="dropdown" role="button"
                            aria-expanded="false" id="subMenu" aria-haspopup="true">Display
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
    <?php
    if(empty($income_category_id) && empty($expense_category_id)){
        echo "<div class='text-center mt-3 text-dark bg-white font-weight-bold h5'>";
                echo "There are neither incomes nor expenses at a given time!";
        echo "</div>";
    }
    elseif(isset($positive_balance)){
        echo "<div class='text-center mt-3 text-dark bg-white font-weight-bold h5'>";
                    echo "Balance: $total_balance PLN $positive_balance";
        echo "</div>";
    }else{
        echo "<div class='text-center mt-3 text-danger bg-white font-weight-bold h5'>";
                    echo "Balance: $total_balance PLN $negative_balance";
        echo "</div>";
    }
    ?>
    <div class=" text-center mt-4 text-danger bg-white font-weight-bold h5">
                <?php if(isset($_SESSION['balance_error'])){
                    echo $_SESSION['balance_error']; 
                    unset($_SESSION['balance_error']);}?>
    </div>
    <div class="container mt-2 p-2 h5 mx-auto">
        <div
            class="bg-white rounded-lg opacity-75 shadow-lg p-3 mx-auto col-10 col-sm-9 col-md-7 col-lg-6 col-xl-5 col-xxl-5">
            <div class="mb-3 mt-0">
                <h1 class="display-6 text-center p-0">Incomes</h1>
            </div>
            <table class="table text-center p-2">
                <thead>
                    <tr>
                        <th scope="col">Category</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    if(!empty($income_category_id)){
                        foreach($income_combined_array as $income_name => $income_sum){
                            echo "<tr>";
                                    echo  "<td>$income_name</td>";
                                    echo "<td>$income_sum</td>";
                            echo "</tr>";}}
                ?>
                    <tr>
                        <th scope="row">Total amount</th>
                        <td class="totalAmount font-weight-bold h5">
                            <?php if(!empty($income_category_id)) 
                                        echo $total_incomes_amount;
                                  else 
                                        echo "0.00"?></td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-3 mt-0">
                <h1 class="display-6 text-center p-0">Expenses</h1>
            </div>
            <table class="table text-center p-2">
                <thead>
                    <tr>
                        <th scope="col">Category</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    if(!empty($expense_category_id)){
                        foreach($expense_combined_array as $expense_name => $expense_sum){
                            echo "<tr>";
                                    echo  "<td>$expense_name</td>";
                                    echo "<td>$expense_sum</td>";
                            echo "</tr>";}}
                ?>
                    <tr>
                        <th scope="row">Total amount</th>
                        <td class="totalAmount font-weight-bold h5">
                        <?php if(!empty($expense_category_id)) 
                                        echo $total_expenses_amount;
                                  else 
                                        echo "0.00"?></td>
                    </tr>
                </tbody>
            </table>
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