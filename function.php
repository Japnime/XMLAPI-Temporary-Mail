<?php

/**
 * Simplified Function for XMLAPI Mechanism
 * Modifier: @japnimedev
 * Description:
 * To understand the following functions below
 * and of course for you to modify it for the
 * better as I can't even make this better.
 **/

// Important
    include("./api.php");

// Folder for Cache
    if (!file_exists('email_log'))
    {
        mkdir('email_log', 0777, true);
    }
    
// Password Generator
    function random_str($length)
    {
        $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
    
        for($i=0; $i < $length; $i++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $key;
    }

// Call API to add the email
    function api_add_query($server, $port, $username, $password, $mailserver, $mailuser, $mailpassword, $mailqouta)
    {
        $apiquery = new xmlapi($server);
        $apiquery -> set_port($port);
        $apiquery -> password_auth($username, $password);
        
        $array_query = array(
            'domain' => $mailserver,
            'email' => $mailuser,
            'password' => $mailpassword,
            'quota' => $mailqouta
            );
            
        $result = $apiquery -> api2_query($username, 'Email', 'addpop', $array_query);
        
        if ($result -> data -> result == 1)
        {
            log_file($mailuser, $mailserver);
            return 1; // Success
        } else {
            return $result  -> data -> reason; // Failed
        }
        
    }
    
// Call API to delete the email
    function api_del_query($server, $port, $username, $password, $mailserver, $mailuser)
    {
        $apiquery = new xmlapi($server);
        $apiquery -> set_port($port);
        $apiquery -> password_auth($username, $password);
        
        $array_query = array(
            'domain' => $mailserver,
            'email' => $mailuser
            );
            
        $apiquery -> api2_query($username, 'Email', 'delpop', $array_query);
    }
    
// Internal logging for time stamp deletion
    function log_file($userlog, $mailserver)
    {
        $log_file = $userlog;
        $log_path = "email_log/$log_file";
        $implement = fopen($log_path, w);
        $information = time().'@@'.$userlog.'@'.$mailserver;
        fwrite($implement, $information);
        fclose($implement);
    }

?>
