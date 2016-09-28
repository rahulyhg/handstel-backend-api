<?php

class DBConnection
{
    private static $conn;
	
	private function __construct(){
    //make your db connnection
 	 }

   public static function getConnection()
    {
 	   if(empty(self::$conn)) 
 	   {
		try 
     	{
        	self::$conn = new PDO('mysql:host=127.0.0.1:3330;dbname=handstel', "root", "");
        	self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    	 catch(PDOException $e) 
     	{
        	echo 'ERROR: ' . $e->getMessage();
 	  	 }
    	}
 		 return self::$conn;
	}

	public static function closeConnection()
	{
		self::$conn = null;
	}
   
 }



function recommendUsers($user_id,$school_id)
{
    $conn = DBConnection::getConnection();
    $sql = "select user_name,phone_number from user where user_id in (select user_id from child where school_id=:school_id and user_id!=:user_id)";
     try 
        {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("school_id", $school_id);
        $stmt->bindParam("user_id", $user_id);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        DBConnection::closeConnection();
         $stmt = $conn->prepare($sql);
        // echo json_encode($users);
        return $users;
        } 
    catch(PDOException $e)
         {
        throw new Exception($e);
         }


}

$users = recommendUsers(1,2);
echo json_encode($users);


?>