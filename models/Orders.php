<?php
class Order
{
    private $conn;
    private $name_tbl = "orders";

    public $id;
    public $userId;
    public $guestId;
    public $createdAt;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getListOrder()
    {
        $query = "SELECT * FROM " . $this->name_tbl . "";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "userId" => $row['userId'],
                "guestId" => $row['guestId'],
                "createdAt" => $row['createdAt']
            );
            array_push($results, $result);
        }

        return $results;
    }

    public function createOrder()
    {
        $query = "INSERT INTO " . $this->name_tbl . " 
                 SET userId=:userId,
                     guestId=:guestId";
        $stmt = $this->conn->prepare($query);


        $this->userId = htmlspecialchars(strip_tags($this->userId));
        $this->guestId = htmlspecialchars(strip_tags($this->guestId));

        $stmt->bindParam(':userId', $this->userId);
        $stmt->bindParam(':guestId', $this->guestId);

        if ($stmt->execute() != null) {
            $lastId = $this->conn->lastInsertId();
            return $lastId;
        }
    }

    public function getOrderByUserId($userId)
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE userId=:userId";
        $stmt = $this->conn->prepare($query);

        $userId = htmlspecialchars(strip_tags($userId));

        $stmt->bindParam(":userId", $userId);

        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "createdAt" => $row['createdAt']
            );
            array_push($results, $result);
        }

        return $results;
    }
}
