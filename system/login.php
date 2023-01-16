<?php
    if (isset($_SESSION["isLogged"]))
    {
        if ($_SESSION["isLogged"] == '1')
        {
            redirect("../dashboard.php");
        }
    }

    include("system_functions.php");

    if (isset($_POST["username"]) && isset($_POST["password"]))
    {
        include("db_connect.php");
        
        $outputData = verifyPassword($_POST["username"], $_POST["password"], $db);

        if ($outputData != null)
        {
            loginUser($outputData["UserID"], $outputData["UserLogin"]);
            setPopupInfo("Logged successfully {$outputData["UserLogin"]}", "success");

            $_SESSION["UserNotes"] = downloadNotes($db, $_POST["password"]);
            $_SESSION["UserFiles"] = downloadFiles($db, $_POST["password"]);

            redirect("../dashboard.php");
        }
        else
        {
            setPopupInfo("Username and password match is invalid", "danger");
            redirect("../index.php");
        }
    }
    else
    {
        setPopupInfo("Please fill all input fields!", "danger");
        redirect("../index.php");
    }
?>