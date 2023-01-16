<?php
    include("system_functions.php");
    include("db_connect.php");

    if (verifyPassword($_SESSION["UserLogin"], $_POST["UserPassword"], $db))
    {
        addNote($db, $_POST["DataTitle"], $_POST["Data"], $_POST["UserPassword"]);
        setPopupInfo("Successfully added a note!", "success");
        redirect("../dashboard.php");
    }
    else
    {
        setPopupInfo("Wrong user password!", "danger");
        redirect("../dashboard.php");
    }
?>