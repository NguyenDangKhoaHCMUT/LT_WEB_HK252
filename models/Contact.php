<?php
require_once 'config/Database.php';

class Contact {
    private $conn;
    private $table_name = "contacts";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($name, $email, $message) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, email, message)
                  VALUES (:name, :email, :message)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":message", $message);

        return $stmt->execute();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE contacts SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function countContacts($status = null) {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table_name;
        if ($status !== null) {
            $query .= " WHERE status = :status";
        }
        $stmt = $this->conn->prepare($query);
        if ($status !== null) {
            $stmt->bindParam(':status', $status);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function getAllContacts($status = null, $limit = null, $offset = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        if ($status !== null) {
            $query .= " AND status = :status";
        }
        $query .= " ORDER BY created_at DESC";
        if ($limit !== null) {
            $query .= " LIMIT :limit";
            if ($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }
        $stmt = $this->conn->prepare($query);
        if ($status !== null) {
            $stmt->bindParam(':status', $status);
        }
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $query = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>