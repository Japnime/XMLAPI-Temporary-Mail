<?php

// Call
    include("./config.php");
    require_once './PhpImap/__autoload.php';
    
// Initiation
    if(isset($_GET['hash']))
    {
        $_email = $_GET['hash'];
        $data_ = file_get_contents("./email_log/$_email");
        $result_ = explode(',', $data_);
        
        // Call PhpImap to Connect
        $php_imap = new PhpImap\Mailbox('{'.$data['server'].'/imap/ssl}INBOX', $result_[1], $result_[2]);
        try
        {
            $_request = $php_imap->searchMailbox('ALL');
        }
        catch(PhpImap\Exceptions\ConnectionException $ex)
        {
            die($ex);
        }
        
        if(!$_request) {
        	$mail_log = '
            <div class="alert alert-info">
              No emails found!
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
    	<meta http-equiv="refresh" content="25">
    	<title>Simple Function: XMLAPI Call</title>
    	<link rel="stylesheet" href="./bootstrap.min.css">
	</head>
    
    <body style="padding-top:10px;">
        
        <div class="container">
             <div class="row" style="padding-top: 10px;">
    
                <div class="col"></div>
                <div class="col-12">
                    <center><h1><?php if(isset($result_[1])){ echo $result_[1]; } else { echo "Temporary Email"; } ?></h1></center>
                    <hr>
                    <form method="post" action="init.php">
                        <div class="form-group">
                            <button type="submit" name="addQuery" class="btn btn-primary btn-lg btn-block">Create another random email</button>
                        </div>
                    </form>
                    
                    <?php
                            foreach ($_request as $mail_id):
                                $email = $php_imap->getMail($mail_id,false);
                    ?>
                    
                    <div class="card mb-3">
                      <h3 class="card-header"><?php echo $email->subject; ?></h3>
                      <div class="card-body">
                        <?php
                            if ($email->textHtml) {
                                echo $email->textHtml;
                            } else {
                                echo $email->textPlain;
                            }
                        ?>
                      </div>
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item text-muted"><?php echo  $email->fromAddress; ?></li>
                      </ul>
                    </div>
                    
                    <?php
                             endforeach;
                        
                        $php_imap->disconnect();
                    ?>
                   <?php         
                    if(isset($mail_log))
                    {
                        echo $mail_log;
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
