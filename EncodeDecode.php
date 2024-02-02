<?php
// error_reporting(0);
include("encryption.php");

// Initialize $re to an empty string
$re = '';

if(isset($_POST['encode'])){
    // if($_POST['string']){
        $string = $_POST['string'];
        $re = encrypt($string);
    // } 
}
if(isset($_POST['decode'])){
    // if($_POST['string']){
        $string = $_POST['string'];
        $re = decrypt($string);
    // } 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<form method="post" action="">
    <div class="row text-center mt-3 m-0">
        <div class="col-md-5">
            <textarea class="w-75" name="string" id="" cols="30" rows="10" placeholder="Enter String Here"></textarea>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary mb-3 w-100" name="encode">Encode</button>
            <button class="btn btn-primary w-100" name="decode">Decode</button>
        </div>
        <div class="col-md-5">
            <textarea class="w-75" name="result" id="" cols="30" rows="10" placeholder="Result"><?php echo isset($re) ? $re : ''; ?></textarea>
        </div>
    </div>
</form>
</body>
</html>
