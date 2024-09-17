<?php
    session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$mobile = dataFilter($_POST['mobile']);
	$user = dataFilter($_POST['uname']);
	$pass =	dataFilter($_POST['pass'] );
	$category = dataFilter($_POST['category']);
    $_SESSION['Password'] = $pass;
    $_SESSION['Username'] = $user;
    $_SESSION['Mobile'] = $mobile;
    $_SESSION['Category'] = $category;
}


require '../db.php';

$length = strlen($mobile);

if($length != 10)
{
	$_SESSION['message'] = "Invalid Mobile Number !!!";
	header("location: error.php");
	die();
}

if($category == 1)
{
    $sql = "SELECT * FROM farmer WHERE fmobile='$mobile'";

    $result = mysqli_query($conn, "SELECT * FROM farmer WHERE fmobile='$mobile'") or die($mysqli->error());

    if ($result->num_rows > 0 )
    {
        $_SESSION['message'] = "User already exists!";
        //echo $_SESSION['message'];
        header("location: error.php");
    }
    else
    {
    	$sql = "INSERT INTO farmer ( fusername, fpassword, fmobile)
    			VALUES ('$user','$pass','$mobile')";

    	if (mysqli_query($conn, $sql))
    	{
    	    $_SESSION['Active'] = 0;
            $_SESSION['logged_in'] = true;

            $_SESSION['picStatus'] = 0;
            $_SESSION['picExt'] = 0;

            $sql = "SELECT * FROM farmer WHERE fusername='$user'";
            $result = mysqli_query($conn, $sql);
            $User = $result->fetch_assoc();
            $_SESSION['id'] = $User['fid'];
            if($_SESSION['picStatus'] == 0)
            {
                $_SESSION['picId'] = 0;
                $_SESSION['picName'] = "profile0.png";
            }
            else
            {
                $_SESSION['picId'] = $_SESSION['id'];
                $_SESSION['picName'] = "profile".$_SESSION['picId'].".".$_SESSION['picExt'];
            }

            header("location: profile.php");
    	}
    	else
    	{
    	    $_SESSION['message'] = "Registration failed!";
            header("location: error.php");
    	}
    }
}

else
{
    $sql = "SELECT * FROM buyer WHERE bmobile='$mobile'";

    $result = mysqli_query($conn, "SELECT * FROM buyer WHERE bmobile='$mobile'") or die($mysqli->error());

    if ($result->num_rows > 0 )
    {
        $_SESSION['message'] = "User already exists!";
        //echo $_SESSION['message'];
        header("location: error.php");
    }
    else
    {
    	$sql = "INSERT INTO buyer (busername, bpassword, bmobile)
    			VALUES ('$user','$pass','$mobile')";

    	if (mysqli_query($conn, $sql))
    	{
    	    $_SESSION['Active'] = 0;
            $_SESSION['logged_in'] = true;

            $sql = "SELECT * FROM buyer WHERE busername='$user'";
            $result = mysqli_query($conn, $sql);
            $User = $result->fetch_assoc();
            $_SESSION['id'] = $User['bid'];
            header("location: profile.php");
    	}
    	else
    	{
    	    $_SESSION['message'] = "Registration not successfull!";
            header("location: error.php");
    	}
    }
}

function dataFilter($data)
{
	$data = trim($data);
 	$data = stripslashes($data);
	$data = htmlspecialchars($data);
  	return $data;
}

?>
