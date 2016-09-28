<?php
namespace Service;
use Utils\DBConnection;

class ContactService
{

    public function getContacts($user_id)
    {
        // global $conn;
         $conn = DBConnection::getInstance()->getConnection();
        $sql = "select user_name from user where user_id in (select contact_1 from contacts where contact_2 = :user_id )";
         try 
        {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam("user_id", $user_id);
        $stmt->execute();
        $contacts = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $conn = null;
        return $contacts;
        } 
        catch(PDOException $e)
        {
        return null;   
        }

    }

    public function addContact($user_id1,$user_id2)
    {
        $conn = DBConnection::getInstance()->getConnection();
        $sql1 = "insert into contacts(contact_1,contact_2) values(?,?)";
        $sql2 = "insert into contacts(contact_2,contact_1) values(?,?)";
        
        try
         {
            $stmt1 = $conn->prepare($sql1);  
            $stmt2 = $conn->prepare($sql2);  
            $stmt1->bindParam("contact_1", $user_id1);
            $stmt1->bindParam("contact_2", $user_id2);
            $stmt1->bindParam("contact_2", $user_id1);
            $stmt1->bindParam("contact_1", $user_id2);
            $stmt1->execute();
            $stmt2->execute();
            $conn = null;
            return $stmt1->rowCount() * $stmt2->rowCount();
        } 
        catch(PDOException $e) 
        {
            return 0;
        }

    }



}






?>