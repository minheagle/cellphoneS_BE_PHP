<?php
class Guest
{
    private $conn;
    private $name_tbl = "guests";

    public $id;
    public $fullName;
    public $phone;
    public $address;
    public $email;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getGuestById()
    {
        $query = "SELECT * FROM " . $this->name_tbl . "
                  WHERE id = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->phone = $dataRow['phone'];
        $this->email = $dataRow['email'];
        $this->fullName = $dataRow['fullName'];
        $this->address = $dataRow['address'];

        return $dataRow;
    }

    public function createGuest()
    {
        $query = "INSERT INTO " . $this->name_tbl . " SET
                    fullName=:fullName,
                    phone=:phone,
                    address=:address,
                    email=:email";

        $stmt = $this->conn->prepare($query);

        $this->fullName = htmlspecialchars(strip_tags($this->fullName));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':fullName', $this->fullName);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':email', $this->email);

        if ($stmt->execute() != null) {
            $lastId = $this->conn->lastInsertId();
            return $lastId;
        }
    }

    public function checkExistGuest()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE fullName = " . $this->fullName . " AND phone = " . $this->phone . "";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
