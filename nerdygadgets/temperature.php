<?php
    /*
    $temp = $_GET['temp'];
    $date = '0000-00-00';


    $connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
    $query = "
        INSERT INTO temperature (temperature, update_date) 
        VALUES (?, ?)";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "is", $temp, $date);
    mysqli_stmt_execute($statement);
    */

    $temp = $_GET['temp'];
    $date = '0000-00-00 00:00:00';


    $connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
    $query = "
        INSERT INTO coldroomtemperatures (Temperature, RecordedWhen) 
        VALUES (?, ?)";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "is", $temp, $date);
    mysqli_stmt_execute($statement);
?>