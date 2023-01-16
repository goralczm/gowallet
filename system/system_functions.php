<?php
    session_start();

    function setPopupInfo($message, $type)
    {
        $_SESSION["popupMessage"] = $message;
        $_SESSION["popupType"] = $type;
    }

    
    function redirect($link)
    {
        header("Location: "."{$link}");
        exit();
    }


    function loginUser($userId, $userLogin)
    {
        $_SESSION["isLogged"] = '1';
        $_SESSION["UserID"] = $userId;
        $_SESSION["UserLogin"] = $userLogin;
    }


    function logoutUser()
    {
        unset($_SESSION['isLogged']);
        unset($_SESSION['UserID']);
        unset($_SESSION['UserLogin']);
        unset($_SESSION["UserNotes"]);
        unset($_SESSION["UserFiles"]);
        session_destroy();
    }


    function checkStrongPassword()
    {

    }


    function verifyPassword($userLogin, $userPassword, $db)
    {
        $sqlQuery = "SELECT UserID, UserLogin, UserPassword FROM users WHERE UserLogin = '{$userLogin}'";
        $output = mysqli_query($db, $sqlQuery);
        $outputData = mysqli_fetch_assoc($output);

        $hashedPassword = $outputData["UserPassword"];
        if (password_verify($userPassword, $hashedPassword))
            return $outputData;
        return null;
    }

    function encryptData($data, $key, $iv)
    {
        if ($iv == null)
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        else
            $iv = hex2bin($iv);

        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

        return array($encrypted, bin2hex($iv));
    }

    function decryptData($data, $key, $iv)
    {
        $decrypted = openssl_decrypt($data, 'aes-256-cbc', $key, 0, hex2bin($iv));

        return $decrypted;
    }

    function addNote($db, $title, $data, $password)
    {
        $encryptedData = encryptData($data, $password, null);
        $encryptedTitle = encryptData($title, $password, $encryptedData[1]);

        $sqlQuery = "INSERT INTO data VALUES (NULL, {$_SESSION["UserID"]}, '{$encryptedTitle[0]}', '{$encryptedData[0]}', '{$encryptedData[1]}')";
        $output = mysqli_query($db, $sqlQuery);
        
        $_SESSION["UserNotes"] = downloadNotes($db, $_POST["UserPassword"]);
    }

    function downloadNotes($db, $userPassword)
    {
        $sqlQuery = "SELECT * FROM data WHERE UserID = {$_SESSION["UserID"]}";
        $output = mysqli_query($db, $sqlQuery);

        $userNotes = array();

        if (mysqli_num_rows($output) > 0)
        {
            while ($outputData = mysqli_fetch_assoc($output))
            {
                $decryptedTitle = decryptData($outputData["EncryptedTitle"], $userPassword, $outputData["HexIV"]);
                $decryptedData = decryptData($outputData["EncryptedData"], $userPassword, $outputData["HexIV"]);
                $userNotes[$outputData["DataID"]] =  array($decryptedTitle, $decryptedData);
            }
        }

        return $userNotes;
    }

    function addFile($db, $fileInfo, $fileTitle, $userPassword)
    {
        $sqlQuery = "INSERT INTO files VALUES (NULL, {$_SESSION["UserID"]}, '{$fileInfo[0]}', '{$fileTitle[0]}', '{$fileTitle[1]}')";
        $output = mysqli_query($db, $sqlQuery);

        $_SESSION["UserFiles"] = downloadFiles($db, $userPassword);
    }

    function downloadFiles($db, $userPassword)
    {
        $sqlQuery = "SELECT * FROM files WHERE UserID = {$_SESSION["UserID"]}";
        $output = mysqli_query($db, $sqlQuery);

        $userFiles = array();

        if (mysqli_num_rows($output) > 0)
        {
            while ($outputData = mysqli_fetch_assoc($output))
            {
                $decryptedFileTitle = decryptData($outputData["FileTitle"], $userPassword, $outputData["IV"]);
                $decryptedFileName = decryptData($outputData["FileName"], $userPassword, $outputData["IV"]);
                $userFiles[$outputData["FileID"]] = array($decryptedFileTitle, $decryptedFileName);
            }
        }

        return $userFiles;
    }
?>