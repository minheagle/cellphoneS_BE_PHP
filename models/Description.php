<?php
class Description
{
    private $conn;
    private $name_tbl = "descriptions";

    public $id;
    public $productId;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getDescriptionByProduct()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE productId =:productId ";

        $stmt = $this->conn->prepare($query);

        $this->productId = htmlspecialchars(strip_tags($this->productId));

        $stmt->bindParam(":productId", $this->productId);

        $stmt->execute();

        // $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        // $this->description = $dataRow['description'];
        return $stmt;
    }

    public function createDescription()
    {
        $query = "INSERT INTO " . $this->name_tbl . " SET productId=:productId, description=:description ";

        $stmt = $this->conn->prepare($query);

        $this->productId = htmlspecialchars(strip_tags($this->productId));

        $stmt->bindParam(':productId', $this->productId);
        $stmt->bindParam(':description', $this->description);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateDescription()
    {
        $query = "UPDATE " . $this->name_tbl . " SET productId = '" . $this->productId . "' , description = '" . $this->description . "'  WHERE id = '" . $this->id . "'";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteDescription()
    {
        $query = "DELETE FROM " . $this->name_tbl . " WHERE productId =:productId ";
        $stmt = $this->conn->prepare($query);

        $this->productId = htmlspecialchars(strip_tags($this->productId));

        $stmt->bindParam(':productId', $this->productId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
