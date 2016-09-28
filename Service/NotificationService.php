<?php

namespace Service;
use Utils\DBConnection;

class NotificationService
{



//create index on notifications
public function getNotifications($group_id)
{
	$conn = DBConnection::getInstance()->getConnection();
    $sql = "SELECT group_id,message,notification_date FROM notification WHERE group_id = :group_id ORDER BY notification_date DESC";

   try 
   {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("group_id", $group_id);
        $stmt->execute();
        $notifications = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $conn = null;
        return $notifications;
    } 
    catch(PDOException $e)
     {
         throw new Exception($e);
     }

}


public function createNotification($message,$receipent)
{

     $conn = DBConnection::getInstance()->getConnection();
     $sql = "insert into notification(message,group_id,notification_date) values(:message,:group_id,:notification_time)";
     $notification_time = date("Y-m-d  h:i:s",time());
     try
    {
      $stmt = $conn->prepare($sql);
      $stmt->bindParam("group_id",$receipent);
      $stmt->bindParam("message",$message);
      $stmt->bindParam("notification_time",$notification_time);
      $stmt->execute();
      return $stmt->rowCount(); // 1
    } 
    catch(PDOException $e)
     {
        throw new Exception($e);

     }

}


}



?>