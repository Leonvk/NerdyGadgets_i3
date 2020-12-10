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
    } else {
        print('b-b-baka! something whent wrongu heru uwu. pwease make surwe all wrequiwred data is inculwuded');
    }
    ?>