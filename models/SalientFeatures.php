<?php
class SalientFeatures
{
    private $conn;
    private $name_tbl = "salient_features";

    public $id;
    public $productId;
    public $featureName;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getSalientFeatureByProductId()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE productId = '" . $this->productId . "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "featureName" => $row['featureName'],
            );
            array_push($results, $result);
        }


        return $results;
    }

    public function createSalientFeature()
    {
        $query = "INSERT INTO " . $this->name_tbl . " SET productId=:productId, featureName=:featureName ";

        $stmt = $this->conn->prepare($query);

        $this->productId = htmlspecialchars(strip_tags($this->productId));
        $this->featureName = htmlspecialchars(strip_tags($this->featureName));

        $stmt->bindParam(':productId', $this->productId);
        $stmt->bindParam(':featureName', $this->featureName);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateSalientFeature()
    {
        $query = "UPDATE " . $this->name_tbl . " SET productId = '" . $this->productId . "' , featureName = '" . $this->featureName . "'  WHERE id = '" . $this->id . "'";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteSalientFeature()
    {
        $query = "DELETE FROM " . $this->name_tbl . " WHERE productId =:productId";
        $stmt = $this->conn->prepare($query);

        $this->productId = htmlspecialchars(strip_tags($this->productId));

        $stmt->bindParam(':productId', $this->productId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
