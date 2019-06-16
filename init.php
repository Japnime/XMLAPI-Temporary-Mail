<?php

// Call
    include("./function.php");
    include("./config.php");

// Automatic Delete Function
    $delete_path = glob('email_log/*');
    foreach($delete_path as $email_log_files)
    {
        $lastModifiedTime = filemtime($email_log_files);
        $currentTime = time();
        $timeDiff = abs($currentTime - $lastModifiedTime)/(60*60);
        if(is_file($email_log_files) && $timeDiff > 1)
        {
            $query_string = str_replace(array("email_log/"), array(""), $email_log_files);
            api_del_query($data['server'], $data['port'], $data['username'], $data['password'], $data['email_server'], _data($query_string, "decrypt"));
            unlink($email_log_files);
        }
    }

// Initiaton
    if(isset($_POST['addQuery']))
    {
        $password_str = random_str(10);
        $user_str = _randomizeUser();
        $api_call = api_add_query($data['server'], $data['port'], $data['username'], $data['password'], $data['email_server'], $user_str, $password_str, $data['megabytequota']);
        $hash_user = _data($user_str, "encrypt");
        
        if ($api_call == 1)
        {
            header('Location: mail.php?hash='.$hash_user);
        } else {
            $message = '
                <div class="alert alert-danger">
                  <strong>Woopsie!</strong> '.$api_call.'
                </div>
            ';
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<title>Simple Function: XMLAPI Call</title>
    	<link rel="stylesheet" href="./bootstrap.min.css">
	</head>
    
    <body style="padding-top:10px;">
        
        <div class="container">
             <div class="row" style="padding-top: 10px;">
    
                <div class="col"></div>
                <div class="col-12">
                    <center><h1>Temporary Mail</h1></center>
                    <hr>
                    <form method="post">
                        <div class="form-group">
                            <button type="submit" name="addQuery" class="btn btn-primary btn-lg btn-block">Create a random email</button>
                        </div>
                    </form>
                   <?php         
                    if(isset($message))
                    {
                        echo $message;
                    }
                    ?>
                </div>
                <div class="col"></div>
    
             </div>  
        </div>
        
        <!-- Java -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    </body>
</html>
