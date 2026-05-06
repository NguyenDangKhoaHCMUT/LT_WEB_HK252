<?php
require_once 'config/database.php';

class Product
{
    private $conn;
    private $table_name = 'products';

    public $id;
    public $category_id;
    public $name;
    public $slug;
    public $description;
    public $image;
    public $price;
    public $stock;

    public function __construct($db = null)
    {
        if ($db) {
            $this->conn = $db;
            return;
        }

        $database = new Database();
        $this->conn = $database->getConnection();
    }

    private function generateSlug($value)
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
        $value = trim($value, '-');

        return $value !== '' ? $value : 'product-' . time();
    }

    public function getAllProducts($keyword = '', $limit = null, $offset = null)
    {
        $query = "SELECT p.*, c.name AS category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE 1=1";

        if ($keyword !== '') {
            $query .= " AND (p.name LIKE :keyword OR p.slug LIKE :keyword OR p.description LIKE :keyword OR c.name LIKE :keyword)";
        }

        $query .= " ORDER BY p.created_at DESC";

        if ($limit !== null) {
            $query .= " LIMIT :limit";
            if ($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }

        $stmt = $this->conn->prepare($query);

        if ($keyword !== '') {
            $likeKeyword = '%' . $keyword . '%';
            $stmt->bindValue(':keyword', $likeKeyword, PDO::PARAM_STR);
        }

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countProducts($keyword = '')
    {
        $query = "SELECT COUNT(*) AS total
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE 1=1";

        if ($keyword !== '') {
            $query .= " AND (p.name LIKE :keyword OR p.slug LIKE :keyword OR p.description LIKE :keyword OR c.name LIKE :keyword)";
        }

        $stmt = $this->conn->prepare($query);

        if ($keyword !== '') {
            $likeKeyword = '%' . $keyword . '%';
            $stmt->bindValue(':keyword', $likeKeyword, PDO::PARAM_STR);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }

    public function getProductById($id)
    {
        $query = "SELECT p.*, c.name AS category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductBySlug($slug)
    {
        $query = "SELECT p.*, c.name AS category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.slug = :slug LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategories()
    {
        $query = "SELECT id, name FROM categories ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRelatedProducts($category_id, $exclude_id = null, $limit = 4)
    {
        $query = "SELECT p.*, c.name AS category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.category_id = :category_id";

        if ($exclude_id !== null) {
            $query .= " AND p.id != :exclude_id";
        }

        $query .= " ORDER BY p.created_at DESC LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

        if ($exclude_id !== null) {
            $stmt->bindParam(':exclude_id', $exclude_id, PDO::PARAM_INT);
        }

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
                  SET category_id = :category_id,
                      name = :name,
                      slug = :slug,
                      description = :description,
                      image = :image,
                      price = :price,
                      stock = :stock";

        $stmt = $this->conn->prepare($query);

        $this->slug = !empty($this->slug) ? $this->slug : $this->generateSlug($this->name);

        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':price', $this->price, PDO::PARAM_INT);
        $stmt->bindParam(':stock', $this->stock, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                  SET category_id = :category_id,
                      name = :name,
                      slug = :slug,
                      description = :description,
                      image = :image,
                      price = :price,
                      stock = :stock
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->slug = !empty($this->slug) ? $this->slug : $this->generateSlug($this->name);

        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':price', $this->price, PDO::PARAM_INT);
        $stmt->bindParam(':stock', $this->stock, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id = null)
    {
        $productId = $id ?? $this->id;

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>