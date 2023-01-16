<?php
    include("db_connect.php");
    include("system_functions.php");

    if (isset($_POST["DataID"]))
    {
        $sqlQuery = "SELECT * FROM data WHERE DataID = '{$_POST["DataID"]}' AND UserID = {$_SESSION["UserID"]}";
        $output = mysqli_query($db, $sqlQuery);

        if (mysqli_num_rows($output) > 0)
        {
            $sqlQuery = "DELETE FROM data WHERE DataID = '{$_POST["DataID"]}'";
            $output = mysqli_query($db, $sqlQuery);
            unset($_SESSION["UserNotes"][$_POST["DataID"]]);
        }
    }
    redirect("../dashboard.php");
?>