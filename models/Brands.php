<?php
class Brands
{
    private $conn;
    private $name_tbl = "brands";

    public $id;
    public $brandName;
    public $categoryId;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getListBrand()
    {
        $query = "SELECT * FROM " . $this->name_tbl . "";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getBrandById()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE id =: id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->brandName = $dataRow['brandName'];
        $this->categoryId = $dataRow['categoryId'];

        return $dataRow;
    }

    public function getBrandByCategory($categoryId)
    {
        $query = "SELECT id, brandName, categoryId FROM " . $this->name_tbl . " WHERE categoryId = " . $categoryId . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function checkBrandExist()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE brandName =:brandName AND categoryId =:categoryId";
        $stmt = $this->conn->prepare($query);

        $this->brandName = htmlspecialchars(strip_tags($this->brandName));
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

        $stmt->bindParam(":brandName", $this->brandName);
        $stmt->bindParam(":categoryId", $this->categoryId);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function createBrand()
    {
        $query = "INSERT INTO " . $this->name_tbl . " SET brandName=:brandName, categoryId=:categoryId";
        $stmt = $this->conn->prepare($query);

        $this->brandName = htmlspecialchars(strip_tags($this->brandName));
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

        $stmt->bindParam(':brandName', $this->brandName);
        $stmt->bindParam(':categoryId', $this->categoryId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateBrand()
    {
        $query = "UPDATE " . $this->name_tbl . " SET brandName = " . $this->brandName . " , categoryId = " . $this->categoryId . "  WHERE id = " . $this->id . "";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteBrand()
    {
        $query = "DELETE FROM " . $this->name_tbl . " WHERE id = " . $this->id . "";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
