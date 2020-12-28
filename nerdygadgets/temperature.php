<?php
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


        //---------------(copy records to coldroomtemperatures archive and delete from coldroomtemperatures)---------------\\
        
        //warning: the following code is not the most beutifull. And I'm not proud og it. But it f***ing works. and I'm o so happy.
        //I spent waaaay to much time trying to do this really efficiently in only a few rows of code.


        //select all records
        $query = "
            SELECT * 
            FROM coldroomtemperatures
            WHERE RecordedWhen != ?";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($statement, "s", $date);
        mysqli_stmt_execute($statement);

        $ReturnableResult = mysqli_stmt_get_result($statement);
        
        $this_is_a_variable = 0;

        //insert those records into coldroomtemperaturesArchive
        foreach ($ReturnableResult as $key => $value) {
            $query = "
                INSERT INTO coldroomtemperatures_archive (ColdRoomTemperatureID,ColdRoomSensorNumber,Temperature, RecordedWhen) 
                VALUES (?,?,?,?)";
            $statement = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($statement, "iids",$value['ColdRoomTemperatureID'],$value['ColdRoomSensorNumber'], $value['Temperature'], $value['RecordedWhen']);
            mysqli_stmt_execute($statement);
           
            //oh and at the same check which has the latest added ID 
            if ($value['ColdRoomTemperatureID'] > $this_is_a_variable) {
                $this_is_a_variable = $value['ColdRoomTemperatureID'];
            }
        }


        //and now delete those records from coldroomtemperatures...
        $query = "
            DELETE
            FROM coldroomtemperatures
            WHERE ColdRoomTemperatureID != ?";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($statement, "i", $this_is_a_variable);
        mysqli_stmt_execute($statement);






    } else {
        // yes, I hate myself for typing this..
        print('b-b-baka! something whent wrongu heru uwu. pwease make surwe all wrequiwred data is inculwuded');
    }
    ?>