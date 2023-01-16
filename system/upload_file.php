<?php
    include("system_functions.php");
    include("db_connect.php");
    require '../vendor/autoload.php';
    include("s3_connect.php");

    if (isset($_POST['submit']))
    {
        if (verifyPassword($_SESSION["UserLogin"], $_POST["UserPassword"], $db))
        {
            $file = $_FILES['files'];

            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            $fileType = $file['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'txt', 'py', 'php', 'html', 'css', 'rar', 'zip');

            if ($fileError === 0)
            {
                $fileNameNew = uniqid('', true).".".$fileActualExt;

                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $fileNameNew,
                    'SourceFile' => $fileTmpName,
                    'ContentType' => $fileType,
                ]);

                $encryptedFileTitle = encryptData($_POST["FileTitle"], $_POST["UserPassword"], null);
                $encryptedNewFileName = encryptData($fileNameNew, $_POST["UserPassword"], $encryptedFileTitle[1]);

                addFile($db, $encryptedNewFileName, $encryptedFileTitle, $_POST["UserPassword"]);

                setPopupInfo("Successfully uploaded file!", "success");
                redirect("../dashboard.php");
            }
            else
            {
                setPopupInfo("There was an error uploading your file! Please try again", "danger");
                redirect("../dashboard.php");
            }
        }
        else
        {
            setPopupInfo("Wrong user password!", "danger");
            redirect("../dashboard.php");
        }
    }
?>