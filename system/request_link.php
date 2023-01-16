<?php
    include("system_functions.php");
    include("db_connect.php");
    require '../vendor/autoload.php';
    include("s3_connect.php");

    $sqlQuery = "SELECT FileName FROM files WHERE UserID = {$_SESSION["UserID"]} AND FileID = {$_POST["FileID"]}";
    $output = mysqli_query($db, $sqlQuery);

    if (mysqli_num_rows($output) > 0)
    {
        $outputData = mysqli_fetch_assoc($output);
        
        $fileName = $_SESSION["UserFiles"][$_POST["FileID"]][1];
        
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $fileName
        ]);
        
        $request = $s3Client->createPresignedRequest($cmd, '+10 minutes');
        
        $presignedUrl = (string) $request->getUri();

        redirect($presignedUrl);
    }
?>