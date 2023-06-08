<?php
class Series
{
    private $conn;
    private $name_tbl = "series";

    public $id;
    public $seriesName;
    public $brandId;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getListSeries()
    {
        $query = "SELECT * FROM " . $this->name_tbl . "";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getSeriesByBrandId($brandId)
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE brandId =:brandId ";
        $stmt  = $this->conn->prepare($query);

        $brandId = htmlspecialchars(strip_tags($brandId));

        $stmt->bindParam(":brandId", $brandId);

        $stmt->execute();

        return $stmt;
    }

    public function getSeriesById()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE id =:id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->seriesName = $dataRow['seriesName'];
        $this->brandId = $dataRow['brandId'];

        return $dataRow;
    }

    public function getSeriesByBrand($brandId)
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE brandId = " . $brandId . "";
        $stmt = $this->conn->prepare($query);

        $brandId = htmlspecialchars(strip_tags($brandId));

        $stmt->bindParam(":brandId", $brandId);

        $stmt->execute();

        return $stmt;
    }

    public function checkSeriesExist()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE seriesName =:seriesName ";
        $stmt = $this->conn->prepare($query);

        $this->seriesName = htmlspecialchars(strip_tags($this->seriesName));

        $stmt->bindParam(":seriesName", $this->seriesName);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function createSeries()
    {
        $query = "INSERT INTO " . $this->name_tbl . " SET seriesName=:seriesName, brandId=:brandId";
        $stmt = $this->conn->prepare($query);

        $this->seriesName = htmlspecialchars(strip_tags($this->seriesName));
        $this->brandId = htmlspecialchars(strip_tags($this->brandId));

        $stmt->bindParam(':seriesName', $this->seriesName);
        $stmt->bindParam(':brandId', $this->brandId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateSeries()
    {
        $query = "UPDATE " . $this->name_tbl . " SET seriesName =:seriesName, brandId =:brandId  WHERE id =:id ";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->seriesName = htmlspecialchars(strip_tags($this->seriesName));
        $this->brandId = htmlspecialchars(strip_tags($this->brandId));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':seriesName', $this->seriesName);
        $stmt->bindParam(':brandId', $this->brandId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteSeries()
    {
        $query = "DELETE FROM " . $this->name_tbl . " WHERE id =:id ";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
