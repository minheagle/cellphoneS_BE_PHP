<?php
class ShortDescription
{
    private $conn;
    private $name_tbl = "short_desciptions";

    public $id;
    public $productId;
    public $content;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getShortDescriptionByProductId()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE productId = '" . $this->productId . "'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "content" => $row['content'],
            );
            array_push($results, $result);
        }


        return $results;
    }

    public function createShortDescription()
    {
        $query = "INSERT INTO " . $this->name_tbl . " SET productId=:productId, content=:content ";

        $stmt = $this->conn->prepare($query);

        $this->productId = htmlspecialchars(strip_tags($this->productId));
        $this->content = htmlspecialchars(strip_tags($this->content));

        $stmt->bindParam(':productId', $this->productId);
        $stmt->bindParam(':content', $this->content);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateShortDescription()
    {
        $query = "UPDATE " . $this->name_tbl . " SET productId = '" . $this->productId . "' , content = '" . $this->content . "'  WHERE id = '" . $this->id . "'";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteShortDescription()
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
