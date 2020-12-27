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


    
    if (isset($_GET['temp']) and isset($_GET['date']) and isset($_GET['sensor'])) {
        $connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
    
        //insert values into the database
        print('successful');

        $temp = $_GET['temp'];
        $date = $_GET['date'];
        $sensor = $_GET['sensor'];
       
        $connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
        $query = "
            INSERT INTO coldroomtemperatures (ColdRoomSensorNumber,Temperature, RecordedWhen) 
            VALUES (?,?, ?)";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($statement, "ids",$sensor, $temp, $date);
        mysqli_stmt_execute($statement);


        //select all records which arn't from just now
        $query = "SELECT * 
            FROM coldroomtemperatures
            WHERE RecordedWhen != ?";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($statement, "s",$date);
        mysqli_stmt_execute($statement);
        $ReturnableResult = mysqli_stmt_get_result($Statement);
        print_r($ReturnableResult);

        //insert those records into coldroomtemperaturesArchive

        //now delete those records...







    } else {
        // yes, I hate myself for typing this..
        print('b-b-baka! something whent wrongu heru uwu. pwease make surwe all wrequiwred data is inculwuded');
    }
    ?>