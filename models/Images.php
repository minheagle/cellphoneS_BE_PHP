<?php
class Images
{
    private $conn;
    private $name_tbl = "images";

    public $id;
    public $productId;
    public $imageName;
    public $imageThumbUrl;
    public $imageType;
    public $imageUrl;
    public $createdAt;
    public $updatedAt;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getImageByProductId()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE productId = '" . $this->productId . "'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $result = array(
                "id" => $row['id'],
                "imageName" => $row['imageName'],
                "imageThumbUrl" => $row['imageThumbUrl'],
                "imageType" => $row['imageType'],
                "imageUrl" => $row['imageUrl']
            );
            array_push($results, $result);
        }


        return $results;
    }

    public function createImageProduct()
    {
        $query = "INSERT INTO " . $this->name_tbl . "
                 SET productId=:productId,
                     imageName=:imageName,
                     imageThumbUrl=:imageThumbUrl,
                     imageType=:imageType,
                     imageUrl=:imageUrl";
        $stmt = $this->conn->prepare($query);

        $this->productId = htmlspecialchars(strip_tags($this->productId));
        $this->imageName = htmlspecialchars(strip_tags($this->imageName));
        // $this->imageThumbUrl = htmlspecialchars(strip_tags($this->imageThumbUrl));
        $this->imageType = htmlspecialchars(strip_tags($this->imageType));
        // $this->imageUrl = htmlspecialchars(strip_tags($this->imageUrl));


        $stmt->bindParam(':productId', $this->productId);
        $stmt->bindParam(':imageName', $this->imageName);
        $stmt->bindParam(':imageThumbUrl', $this->imageThumbUrl);
        $stmt->bindParam(':imageType', $this->imageType);
        $stmt->bindParam(':imageUrl', $this->imageUrl, PDO::PARAM_LOB);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // public function updateImageProduct()
    // {
    //     $query = "UPDATE " . $this->name_tbl . " SET productId = '" . $this->productId . "' , imageProduct = '" . $this->imageProduct . "'  WHERE id = '" . $this->id . "'";
    //     $stmt = $this->conn->prepare($query);

    //     if ($stmt->execute()) {
    //         return true;
    //     }
    //     return false;
    // }

    public function deleteImageProduct()
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
