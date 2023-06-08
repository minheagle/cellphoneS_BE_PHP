<?php
class Categories
{
    private $conn;
    private $name_tbl = "categories";

    public $id;
    public $categoryName;
    public $path;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getListCategory()
    {
        $query = "SELECT * FROM " . $this->name_tbl . "";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getCategoryById($id)
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE id =:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->categoryName = $dataRow['categoryName'];
        $this->path = $dataRow['path'];

        return $dataRow;
    }

    public function getCategoryByPath($path)
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE path =:path";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":path", $path);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $dataRow['id'];
        $this->categoryName = $dataRow['categoryName'];
        $this->path = $dataRow['path'];

        return $dataRow;
    }

    public function checkCategoryExist()
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE categoryName =:categoryname";
        $stmt = $this->conn->prepare($query);

        $this->categoryName = htmlspecialchars(strip_tags($this->categoryName));

        $stmt->bindParam(":categoryName", $this->categoryName);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function createCategory()
    {
        $query = "INSERT INTO " . $this->name_tbl . " SET categoryName=:categoryName";
        $stmt = $this->conn->prepare($query);

        $this->categoryName = htmlspecialchars(strip_tags($this->categoryName));

        $stmt->bindParam(':categoryName', $this->categoryName);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateCategory()
    {
        $query = "UPDATE " . $this->name_tbl . " SET categoryName =: categoryName, path =: path  WHERE id =: id";
        $stmt = $this->conn->prepare($query);

        $this->categoryName = htmlspecialchars(strip_tags($this->categoryName));
        $this->path = htmlspecialchars(strip_tags($this->path));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':categoryName', $this->categoryName);
        $stmt->bindParam(':path', $this->path);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteCategory()
    {
        $query = "DELETE FROM " . $this->name_tbl . " WHERE id =: id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
