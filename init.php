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
        if(is_file($email_log_files) && $timeDiff > 1) // 24 hours validity
        {
            $query_string = str_replace(array("email_log/"), array(""), $email_log_files);
            api_del_query($data['server'], $data['port'], $data['username'], $data['password'], $data['email_server'], $query_string);
            unlink($email_log_files);
        }
    }

// Initiaton
    if(isset($_POST['addQuery']))
    {
        $password_str = random_str(10);
        $api_call = api_add_query($data['server'], $data['port'], $data['username'], $data['password'], $data['email_server'], $_POST['userMail'], $password_str, $data['megabytequota']);
        
        if ($api_call == 1)
        {
            $message = '
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Information</h4>
                    <h6 class="card-subtitle mb-2 text-muted">We do not save anything here so you need to save this one.</h6>
                    <p class="card-text">
                        Your email: <strong>'.$_POST['userMail'].'@'.$data['email_server'].'</strong>
                        </br>
                        Your password: <strong>'.$password_str.'</strong>
                        </br>
                        Validity: <strong>1 day</strong>
                    </p>
                    <a href="https://'.$data['email_server'].':2096/login" class="card-link">Login to the server</a>
                  </div>
                </div>
            ';
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
                <div class="col-5">
                    <center><h1>Temporary Mail</h1></center>
                    <hr>
                    <form method="post">
                        <div class="form-group">
                          <div class="input-group mb-3">
                            <input type="text" name="userMail" class="form-control" placeholder="Your username">
                            <div class="input-group-append">
                              <span class="input-group-text">@<?php echo $data['email_server']; ?></span>
                            </div>
                          </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="addQuery" class="btn btn-primary btn-lg btn-block">Submit</button>
                        </div>
                    </form>
                   <?php         
                    if(isset($message)) {
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
