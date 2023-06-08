<?php
class Products
{
    private $conn;
    private $name_tbl = "products";

    public $id;
    public $categoryId;
    public $brandId;
    public $seriesId;
    public $productName;
    public $standardCost;
    public $isNew;
    public $quantity;
    public $createdAt;
    public $updatedAt;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getListAllProduct()
    {
        $query = "SELECT * FROM " . $this->name_tbl . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "categoryId" => $row['categoryId'],
                "brandId" => $row['brandId'],
                "seriesId" => $row['seriesId'],
                "productName" => $row['productName'],
                "standardCost" => $row['standardCost'],
                "isNew" => $row['isNew'],
                "quantity" => $row['quantity']
            );
            array_push($results, $result);
        }


        return $results;
    }

    public function getProductById()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE id =:id ";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->categoryId = $dataRow['categoryId'];
        $this->brandId = $dataRow['brandId'];
        $this->seriesId = $dataRow['seriesId'];
        $this->productName = $dataRow['productName'];
        $this->standardCost = $dataRow['standardCost'];
        $this->isNew = $dataRow['isNew'];
        $this->quantity = $dataRow['quantity'];

        return $dataRow;
    }

    public function getProductBySeries()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE seriesId=:seriesId";
        $stmt = $this->conn->prepare($query);

        $this->seriesId = htmlspecialchars(strip_tags($this->seriesId));

        $stmt->bindParam(":seriesId", $this->seriesId);

        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "categoryId" => $row['categoryId'],
                "brandId" => $row['brandId'],
                "seriesId" => $row['seriesId'],
                "productName" => $row['productName'],
                "standardCost" => $row['standardCost'],
                "isNew" => $row['isNew'],
                "quantity" => $row['quantity']
            );
            array_push($results, $result);
        }


        return $results;
    }

    public function getProductByBrand()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE brandId=:brandId";
        $stmt = $this->conn->prepare($query);

        $this->brandId = htmlspecialchars(strip_tags($this->brandId));

        $stmt->bindParam(":brandId", $this->brandId);

        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "categoryId" => $row['categoryId'],
                "brandId" => $row['brandId'],
                "seriesId" => $row['seriesId'],
                "productName" => $row['productName'],
                "standardCost" => $row['standardCost'],
                "isNew" => $row['isNew'],
                "quantity" => $row['quantity']
            );
            array_push($results, $result);
        }


        return $results;
    }

    public function getProductByCategory()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE categoryId=:categoryId";
        $stmt = $this->conn->prepare($query);

        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

        $stmt->bindParam(":categoryId", $this->categoryId);

        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "categoryId" => $row['categoryId'],
                "brandId" => $row['brandId'],
                "seriesId" => $row['seriesId'],
                "productName" => $row['productName'],
                "standardCost" => $row['standardCost'],
                "isNew" => $row['isNew'],
                "quantity" => $row['quantity']
            );
            array_push($results, $result);
        }


        return $results;
    }

    public function createProduct()
    {
        $query = "INSERT INTO " . $this->name_tbl . " SET categoryId=:categoryId, brandId=:brandId, seriesId=:seriesId, productName=:productName, standardCost=:standardCost, isNew=:isNew, quantity=:quantity";
        $stmt = $this->conn->prepare($query);


        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
        $this->brandId = htmlspecialchars(strip_tags($this->brandId));
        $this->seriesId = htmlspecialchars(strip_tags($this->seriesId));
        $this->productName = htmlspecialchars(strip_tags($this->productName));
        $this->standardCost = htmlspecialchars(strip_tags($this->standardCost));
        $this->isNew = htmlspecialchars(strip_tags($this->isNew));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));

        $stmt->bindParam(':categoryId', $this->categoryId);
        $stmt->bindParam(':brandId', $this->brandId);
        $stmt->bindParam(':seriesId', $this->seriesId);
        $stmt->bindParam(':productName', $this->productName);
        $stmt->bindParam(':standardCost', $this->standardCost);
        $stmt->bindParam(':isNew', $this->isNew);
        $stmt->bindParam(':quantity', $this->quantity);

        if ($stmt->execute() != null) {
            $lastId = $this->conn->lastInsertId();
            return $lastId;
        }
    }

    public function updateProduct()
    {
    }


    public function updateQuantityProduct($newQuantity)
    {
        $query = "UPDATE " . $this->name_tbl . " SET quantity=:quantity WHERE id=:id ";

        $stmt = $this->conn->prepare($query);

        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':quantity', $newQuantity);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteProduct()
    {
        $query = "DELETE FROM " . $this->name_tbl . " WHERE id =:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
