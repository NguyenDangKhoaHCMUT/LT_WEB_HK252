<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $email;
    public $fullname;
    public $avatar;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        if ($this->isEmailExists()) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  SET password=:password, email=:email, role='member'";
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":email", $this->email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($email, $password) {
        $query = "SELECT id, username, password, role, fullname FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->role = $row['role'];
                $this->fullname = $row['fullname'];
                return true;
            }
        }
        return false;
    }



    public function isEmailExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email AND id != :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $id_check = isset($this->id) ? $this->id : 0;
        $stmt->bindParam(":id", $id_check);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function getUserById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile() {
        if ($this->isEmailExists()) return false;
        
        $query = "UPDATE " . $this->table_name . " 
                  SET fullname = :fullname, email = :email";
        
        if (!empty($this->avatar)) {
            $query .= ", avatar = :avatar";
        }
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);
        if (!empty($this->avatar)) {
            $stmt->bindParam(':avatar', $this->avatar);
        }
        
        return $stmt->execute();
    }
    
    public function changePassword($new_password) {
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function getAllUsers() {
        $query = "SELECT id, username, email, fullname, role, created_at FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resetPassword($id) {
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $password_hash = password_hash('123456', PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND role != 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
