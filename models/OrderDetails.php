<?php
class OrderDetail
{
    private $conn;
    private $name_tbl = "order_detail";

    public $id;
    public $orderId;
    public $productName;
    public $quantity;
    public $unitPrice;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createOrderDetail()
    {
        $query = "INSERT INTO " . $this->name_tbl . "
                 SET orderId=:orderId,
                     productName=:productName,
                     quantity=:quantity,
                     unitPrice=:unitPrice";
        $stmt = $this->conn->prepare($query);

        $this->orderId = htmlspecialchars(strip_tags($this->orderId));
        $this->productName = htmlspecialchars(strip_tags($this->productName));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->unitPrice = htmlspecialchars(strip_tags($this->unitPrice));

        $stmt->bindParam(':orderId', $this->orderId);
        $stmt->bindParam(':productName', $this->productName);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':unitPrice', $this->unitPrice);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getOrderDetailByOrderId($orderId)
    {
        $query = "SELECT * FROM " . $this->name_tbl . " WHERE orderId=:orderId";
        $stmt = $this->conn->prepare($query);

        $orderId = htmlspecialchars(strip_tags($orderId));

        $stmt->bindParam(":orderId", $orderId);

        $stmt->execute();

        // $results = array();

        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        //     $result = array(
        //         "id" => $row['id'],
        //         "productName" => $row['productName'],
        //         "quantity" => $row['quantity'],
        //         "unitPrice" => $row['unitPrice'],


        //     );
        //     array_push($results, $result);
        // }

        return $stmt;
    }
}
