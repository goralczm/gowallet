<?php 
    include("system/system_functions.php");
    if (!isset($_SESSION["isLogged"]))
    {
        setPopupInfo("Please login to access dashboard!", "warning");
        redirect("index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>gowallet.club dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="styles/default.css">
    </head>
    <body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <?php
            include("system/db_connect.php");

            include("system/navbar.php");

            include("system/popup.php");
        ?>

        <div class="container">
            <div class='files'>
                <div class="custom-form fixed-inputs">
                    <form action="system/upload_file.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="files">
                        <label for="DataTitle">File Title</label>
                        <input type="text" name="FileTitle" autocomplete="off" require>
                        <label for="UserPassword">Confirm Password</label>
                        <input type="password" name="UserPassword" require>
                        <input type="submit" value="Upload File" name="submit">
                    </form>
                </div>
                <?php
                    if (isset($_SESSION["UserFiles"]))
                    {
                        print("<div class='files-card-group'>");
                        foreach ($_SESSION["UserFiles"] as $id => $fileName)
                        {
                            print("<div class='files-card'>");
                            print("<div class='files-card-header'>{$fileName[0]}</div>");
                            print("<div class='files-card-body'><form action='system/request_link.php' method='post'><input type='text' value='{$id}' name='FileID' hidden><input type='submit' value='Link'></form></div>");
                            print("</div>");
                        }
                        print("</div>");
                    }
                ?>
            </div>
            <div class='notes'>
                <div class="custom-form fixed-inputs">
                    <form action="system/add_card.php" method="post">
                        <label for="DataTitle">Note Title</label>
                        <input type="text" name="DataTitle" autocomplete="off" require>
                        <label for="Data">Note Description</label>
                        <input type="text" name="Data" autocomplete="off" require>
                        <label for="UserPassword">Confirm Password</label>
                        <input type="password" name="UserPassword" require>
                        <input type="submit" value="Add Note">
                    </form>
                </div>
                <?php
                if (isset($_SESSION["UserNotes"]))
                {
                    print("<div class='note-cards-group'>");
                    foreach ($_SESSION["UserNotes"] as $id => $data)
                    {
                        print("<div class='note-card'>");
                        print("<div class='header'>{$data[0]}</div>");
                        print("<div class='body'>{$data[1]}</div>");
                        print("<form action='system/delete_card.php', method='post'><input type='text' name='DataID' value='{$id}' hidden><input type='submit' value=''></form>");
                        print("</div>");
                    }
                    print("</div>");
                }
                print("</div>");
                ?>
            </div>
        </div>
    </body>
</html>