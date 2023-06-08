<?php
class TechnicalSpecs
{
    private $conn;
    private $name_tbl = "technical_specs";

    public $id;
    public $productId;
    public $nameSpecs;
    public $valueSpecs;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getTechnicalSpecsByProductId()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE productId = '" . $this->productId . "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "nameSpecs" => $row['nameSpecs'],
                "valueSpecs" => $row['valueSpecs'],
            );
            array_push($results, $result);
        }


        return $results;
    }

    public function createTechnicalSpecs()
    {
        $query = "INSERT INTO " . $this->name_tbl . " SET productId=:productId, nameSpecs=:nameSpecs, valueSpecs=:valueSpecs ";

        $stmt = $this->conn->prepare($query);

        $this->productId = htmlspecialchars(strip_tags($this->productId));
        $this->nameSpecs = htmlspecialchars(strip_tags($this->nameSpecs));
        $this->valueSpecs = htmlspecialchars(strip_tags($this->valueSpecs));

        $stmt->bindParam(':productId', $this->productId);
        $stmt->bindParam(':nameSpecs', $this->nameSpecs);
        $stmt->bindParam(':valueSpecs', $this->valueSpecs);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateTechnicalSpecs()
    {
        $query = "UPDATE " . $this->name_tbl . " SET productId = '" . $this->productId . "' , nameSpecs = '" . $this->nameSpecs . "' , valueSpecs = '" . $this->valueSpecs . "'  WHERE id = '" . $this->id . "'";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteTechnicalSpecs()
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
