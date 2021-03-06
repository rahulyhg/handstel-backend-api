<?php
namespace Service;
use Utils\DBConnection;
class UserService
{

function getUserbyEmail($user_email)
{

    $conn = DBConnection::getInstance()->getConnection();
    $sql = "SELECT * FROM user WHERE user_email = :user_email";

   try 
   {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("user_email", $user_email);
        $stmt->execute();
        $user = $stmt->fetchAll();
        $conn=null;
        if(!empty($user))
            return $user[0];

    } 
    catch(PDOException $e)
     {
      throw new Exception($e);
     }

    return null;

}

function getUserbyUsername($user_name)
{

$conn = DBConnection::getInstance()->getConnection();
$sql = "SELECT * FROM user WHERE user_name = :user_name";

   try 
   {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("user_name", $user_name);
        $stmt->execute();
        $user = $stmt->fetchAll();
        $conn = null;
        if(!empty($user))
            return $user[0];
        
    } 
    catch(PDOException $e)
     {
         throw new Exception($e);
     }
     return null;

}

function getUserbyUserID($user_id)
{

$conn = DBConnection::getInstance()->getConnection();
$sql = "SELECT * FROM user WHERE user_id = :user_id";

   try 
   {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("user_id", $user_id);
        $stmt->execute();
        $user = $stmt->fetchAll();
        $conn = null;
         if(!empty($user))
            return $user[0];
    } 
    catch(PDOException $e)
     {
        return null;
     }

    return null;
}


function getUserbyAuthToken($auth_token)
{

$conn = DBConnection::getInstance()->getConnection();
$sql = "SELECT * FROM user WHERE auth_token = :auth_token";

   try 
   {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("auth_token", $auth_token);
        $stmt->execute();
        $user = $stmt->fetchAll();
        $conn = null;
         if(!empty($user))
            return $user[0];
        
    } 
    catch(PDOException $e)
     {
         throw new Exception($e);
     }

return null;
}

function getUserbyGoogleID($google_id)
{
$conn = DBConnection::getInstance()->getConnection();
$sql = "SELECT * FROM user WHERE google_id = :google_id";

   try 
   {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("google_id", $google_id);
        $stmt->execute();
        $user = $stmt->fetchAll();
        $conn = null;
        if(!empty($user))
            return $user[0];
        
    } 
    catch(PDOException $e)
     {
        throw new Exception($e);
     }

     return null;

}


function getUserbyFacebookID($fb_id)
{

$conn = DBConnection::getInstance()->getConnection();
$sql = "SELECT * FROM user WHERE fb_id = :fb_id";

   try 
   {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("fb_id", $fb_id);
        $stmt->execute();
        $user = $stmt->fetchAll();
        $conn = null;
         if(!empty($user))
            return $user[0];
        
    } 
    catch(PDOException $e)
     {
         throw new Exception($e);
     }

     return null;

}

function getUserID($user_name)
{
    $conn = DBConnection::getInstance()->getConnection();
    $sql = "select user_id from user where user_name=:user_name";
    try 
     {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("user_name",$user_name);
        $stmt->execute();
        $users = $stmt->fetchAll();
        $conn = null;
        return $users[0]['user_id'];
        
      } 
    catch(PDOException $e)
     {
         throw new Exception($e);
     }

}

function updateGCMKey($gcm_key,$user_id)
{

    $conn = DBConnection::getInstance()->getConnection();
    $sql = "UPDATE user set gcm_key=:gcm_key where user_id=:user_id";
    try 
    {
      $stmt = $conn->prepare($sql);
      $stmt->bindParam("user_id",$user_id);
      $stmt->bindParam("gcm_key",$gcm_key);
      $stmt->execute();
      return $stmt->rowCount(); // 1
    
     }
      catch(PDOException $e) 
      {
        throw new Exception($e);
      }


}

function addChild($child_name,$school_id,$user_id)
{
    $conn = DBConnection::getInstance()->getConnection();
    $sql = "INSERT INTO child(`child_name`,`school_id`,`user_id`) VALUES (:child_name,:school_id,:user_id)";
    try
     {
        $stmt = $conn->prepare($sql);  
        $stmt->bindParam("child_name", $child_name);
        $stmt->bindParam("user_id", $user_id);
        $stmt->bindParam("school_id",$school_id);
        $stmt->execute();
        return $conn->lastInsertId();

    } 
 catch(PDOException $e) 
    {
        throw new Exception($e);
    }
 


}

function updateProfile($user_id,$user_name,$profile_pic)
{

    $conn = DBConnection::getInstance()->getConnection();

    $sql = "UPDATE user set user_name=:user_name,profile_pic=:profile_pic where user_id=:user_id";
    try 
    {
      $stmt = $conn->prepare($sql);
      $stmt->bindParam("user_id",$user_id);
      $stmt->bindParam("user_name",$user_name);
      $stmt->bindParam("profile_pic",$profile_pic);
      $stmt->execute();
      return $stmt->rowCount(); // 1
    
     }
      catch(PDOException $e) 
      {
        throw new Exception($e);
      }
}


function recommendUsers($user_id,$school_id)
{
    $conn = DBConnection::getInstance()->getConnection();
    $sql = "select user_name,phone_number from user where user_id in (select user_id from child where school_id=:school_id and user_id!=:user_id)";
     try 
        {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("school_id", $school_id);
        $stmt->bindParam("user_id", $user_id);
        $stmt->execute();
        $users = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $conn = null;
        return $users;
        } 
    catch(PDOException $e)
         {
        throw new Exception($e);
         }


}


function getUserSchool($user_id)
{

    $conn = DBConnection::getInstance()->getConnection();
    $sql = "select school_id,school_name from school where school_id in (select school_id from child where user_id=:user_id)";
    try 
     {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("user_id",$user_id);
        $stmt->execute();
        $school = $stmt->fetchAll();
        $conn = null;
         if(!empty($school))
            return $school[0];
        
    } 
    catch(PDOException $e)
     {
         throw new Exception($e);
     }

    return null;
}

function getUserChild($user_id)
{
    $conn = DBConnection::getInstance()->getConnection();
    $sql = "select child_id,child_name from child where user_id=:user_id";
    try 
     {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("user_id",$user_id);
        $stmt->execute();
        $children = $stmt->fetchAll();
        $conn = null;
        if(!empty($children))
            return $children[0];
      } 
    catch(PDOException $e)
     {
        throw new Exception($e);
     }

     return null;
}

function addUser($user_name,$phone_number,$user_email,$gcm_key,$apns_key,$fb_id,$google_id,$token,$expiry_time,$profile_pic)
{

$conn = DBConnection::getInstance()->getConnection();
$auth_token = new AuthToken;
$sql = "INSERT INTO user(`user_name`,`user_email`,`phone_number`,`gcm_key`,`apns_key`,`fb_id`,`google_id`,`auth_token`,`expiry_time`,`profile_pic`) VALUES (:user_name,:user_email,:phone_number,:gcm_key,:apns_key,:fb_id,:google_id,:auth_token,:expiry_time,:profile_pic)";
 try
  {
        $stmt = $conn->prepare($sql);  
        $stmt->bindParam("user_name", $user_name);
        $stmt->bindParam("user_email", $user_email);
        $stmt->bindParam("phone_number",$phone_number);
        $stmt->bindParam("gcm_key",$gcm_key);
        $stmt->bindParam("apns_key",$apns_key);
        $stmt->bindParam("fb_id",$fb_id);
        $stmt->bindParam("google_id",$google_id);
        $stmt->bindParam("auth_token",$token);
        $stmt->bindParam("expiry_time",$expiry_time);
        $stmt->bindParam("profile_pic",$profile_pic);
        $stmt->execute();
        return $conn->lastInsertId();
        
} 
    catch(PDOException $e) 
    {
       throw new Exception($e);
    }
    finally
    {
        $conn = null;
    }
}


function getChannelsSubscribed($user_id)
{
    $conn = DBConnection::getInstance()->getConnection();
    $sql = "select channel_name from `group` where group_id in (select group_id from `group_users` where user_id=:user_id)";
     try 
        {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("user_id", $user_id);
        $stmt->execute();
        $channels = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $conn = null;
        return $channels;
        } 
    catch(PDOException $e)
         {
        throw new Exception($e);
         }

}




}



?>
