<?php

namespace Service;
use Utils\DBConnection;

class AuthToken
{

 public function __construct()
 {

 }

function refreshToken($user,$token_expiry)
{
    $conn = DBConnection::getInstance()->getConnection();
    $user_mail = $user['user_email'];
    $token = $this->getAuthToken($user_mail);
    $expiry_time = $user['expiry_time'];
    $timestamp = strtotime($expiry_time);
    $expiry_time = strtotime("+" .$token_expiry."day", $timestamp);
    $expiry_time = date("Y-m-d h:i:s",$expiry_time);
    $sql = "UPDATE user SET auth_token = :auth_token,expiry_time = :expiry_time WHERE user_email = :user_mail";
    try
    {
        $stmt = $conn->prepare($sql);  
        $stmt->bindParam("auth_token", $token);
        $stmt->bindParam("expiry_time", $expiry_time);
        $stmt->bindParam("user_mail", $user_mail);
        $stmt->execute();
        $conn = null;
        return $token;
    }
    catch(PDOException $e)
    {
       throw new Exception($e); 
    }
    
}



function getAuthToken($user_email)
{
 $rand_string = $this->generateRandomString(20);
 $hex_string = $this->strtohex($user_email);
 $token = $hex_string.$rand_string;
 return sha1($token);
}


function validateToken($token,$user_id)
{    
     $user_service = new UserService;
     $user =  $user_service->getUserbyUserID($user_id);
     if(!empty($user))
     {

        $auth_token = $user['auth_token'];
        $expiry_time = $user['expiry_time'];
        $timestamp = strtotime($expiry_time);
        if(($auth_token == $token) && !$this->hasTokenExpired($expiry_time))
             return $user;
        
     }

    return null;
}

function hasTokenExpired($expiry_time)
{
    $timestamp = strtotime($expiry_time);
    $current = time();
    $is_expired = ($current > $timestamp) ? 1 : 0;
    return $is_expired;
}

private function generateRandomString($length)
 {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) 
    {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
            
    return $randomString;
  }

 private function strtohex($x) {
        $s = '';
        foreach (str_split($x) as $c)
            $s.=sprintf("%02X", ord($c));
        
        return $s;
    }

}


?>