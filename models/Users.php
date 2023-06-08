<?php
class Users
{
    private $conn;
    private $users_tbl = "users";

    public $id;
    public $phone;
    public $email;
    public $fullName;
    public $userPassword;
    public $userAddress;
    public $avatar;
    public $userRole;
    public $refreshToken;
    public $created_at;



    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getListUser()
    {
        $query = "SELECT id, phone, email, fullName, userPassword, userAddress, avatar, userRole, accessToken,  refreshToken, created_at FROM " . $this->users_tbl . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getUserById()
    {
        $query = "SELECT id,
                         phone,
                         email,
                         fullName,
                         userPassword,
                         userAddress,
                         avatar,
                         userRole,
                         refreshToken,
                         created_at
                  FROM " . $this->users_tbl . "
                  WHERE id = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->phone = $dataRow['phone'];
        $this->email = $dataRow['email'];
        $this->fullName = $dataRow['fullName'];
        $this->userPassword = $dataRow['userPassword'];
        $this->userAddress = $dataRow['userAddress'];
        $this->avatar = $dataRow['avatar'];
        $this->userRole = $dataRow['userRole'];
        $this->refreshToken = $dataRow['refreshToken'];
        $this->created_at = $dataRow['created_at'];

        return $dataRow;
    }

    public function createUser()
    {
        $query = "INSERT INTO " . $this->users_tbl . " SET
                    phone=:phone,
                    email=:email,
                    fullName=:fullName,
                    userPassword=:userPassword";

        $stmt = $this->conn->prepare($query);

        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullName = htmlspecialchars(strip_tags($this->fullName));
        $this->userPassword = htmlspecialchars(strip_tags($this->userPassword));

        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fullName', $this->fullName);
        $stmt->bindParam(':userPassword', $this->userPassword);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function checkEmail()
    {
        $query = "SELECT * FROM " . $this->users_tbl . " WHERE email =:email";

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":email", $this->email);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function login()
    {
        $query = "SELECT * FROM " . $this->users_tbl . " WHERE email =:email ";

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":email", $this->email);

        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($dataRow)) {
            return $dataRow;
        } else {
            return false;
        }
    }


    public function changePassword($newPassword)
    {
        $query = "UPDATE " . $this->users_tbl . " SET userPassword=:userPassword WHERE id=:id ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":userPassword", $newPassword);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function generateToken()
    {
        $query = "UPDATE " . $this->users_tbl . "
                 SET 
                    refreshToken =:refreshToken
                 WHERE email =:email";

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":refreshToken", $this->refreshToken);
        $stmt->bindParam(":email", $this->email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteJWT()
    {
        $query = "UPDATE " . $this->users_tbl . "
                 SET 
                    refreshToken =:refreshToken 
                 WHERE id =:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":refreshToken", $this->refreshToken);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function checkToken()
    {
        $query = "SELECT id,
                         phone,
                         email,
                         fullName,
                         userPassword,
                         userAddress,
                         avatar,
                         userRole,
                         accessToken,
                         created_at
                  FROM " . $this->users_tbl . "
                  WHERE refreshToken = " . $this->refreshToken . "";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($dataRow)) {
            return $dataRow;
        } else {
            return false;
        }
    }

    public function deleteUser()
    {
        $query = "DELETE FROM " . $this->users_tbl . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateUser()
    {
        $query = "UPDATE " . $this->users_tbl . " SET phone=:phone, email=:email, fullName=:fullName, userAddress=:userAddress WHERE id=:id ";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullName = htmlspecialchars(strip_tags($this->fullName));
        $this->userAddress = htmlspecialchars(strip_tags($this->userAddress));


        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":fullName", $this->fullName);
        $stmt->bindParam(":userAddress", $this->userAddress);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    public function updateUserForAdmin()
    {
        $query = "UPDATE " . $this->users_tbl . " SET phone=:phone, email=:email, fullName=:fullName, userAddress=:userAddress WHERE id=:id ";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullName = htmlspecialchars(strip_tags($this->fullName));
        $this->userAddress = htmlspecialchars(strip_tags($this->userAddress));


        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":fullName", $this->fullName);
        $stmt->bindParam(":userAddress", $this->userAddress);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateInfoShipping()
    {
        $query = "UPDATE " . $this->users_tbl . " 
                  SET 
                     phone = :phone,
                     email = :email,
                     fullName = :fullName,
                     userAddress = :userAddress      
                  WHERE id = " . $this->id . "";

        $stmt = $this->conn->prepare($query);

        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullName = htmlspecialchars(strip_tags($this->fullName));
        $this->userAddress = htmlspecialchars(strip_tags($this->userAddress));

        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":fullName", $this->fullName);
        $stmt->bindParam(":userAddress", $this->userAddress);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
