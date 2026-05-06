<?php
require_once 'config/database.php';

class Order
{
    private $conn;
    private $orders_table = 'orders';
    private $items_table = 'order_items';

    public $id;
    public $user_id;
    public $status;
    public $total_amount;
    public $customer_name;
    public $phone;
    public $address;

    public function __construct($db = null)
    {
        if ($db) {
            $this->conn = $db;
            return;
        }

        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getStatusLabels()
    {
        return [
            'cart' => 'Giỏ hàng',
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn tất',
            'cancelled' => 'Đã huỷ'
        ];
    }

    private function getProductPrice($product_id)
    {
        $query = "SELECT price FROM products WHERE id = :product_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['price'] ?? 0);
    }

    private function getOrCreateCartOrder($user_id)
    {
        $query = "SELECT id FROM " . $this->orders_table . " WHERE user_id = :user_id AND status = 'cart' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return (int) $row['id'];
        }

        $insertQuery = "INSERT INTO " . $this->orders_table . "
                        SET user_id = :user_id,
                            status = 'cart',
                            total_amount = 0,
                            customer_name = '',
                            phone = '',
                            address = ''";
        $insertStmt = $this->conn->prepare($insertQuery);
        $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($insertStmt->execute()) {
            return (int) $this->conn->lastInsertId();
        }

        return null;
    }

    private function recalculateCartTotal($order_id)
    {
        $query = "SELECT COALESCE(SUM(oi.quantity * oi.unit_price), 0) AS total_amount
                  FROM " . $this->items_table . " oi
                  WHERE oi.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_amount = (int) ($row['total_amount'] ?? 0);

        $updateQuery = "UPDATE " . $this->orders_table . " SET total_amount = :total_amount WHERE id = :id";
        $updateStmt = $this->conn->prepare($updateQuery);
        $updateStmt->bindParam(':total_amount', $total_amount, PDO::PARAM_INT);
        $updateStmt->bindParam(':id', $order_id, PDO::PARAM_INT);
        $updateStmt->execute();

        return $total_amount;
    }

    public function getCart($user_id)
    {
        $order_id = $this->getOrCreateCartOrder($user_id);
        if (!$order_id) {
            return null;
        }

        $orderQuery = "SELECT * FROM " . $this->orders_table . " WHERE id = :id LIMIT 1";
        $orderStmt = $this->conn->prepare($orderQuery);
        $orderStmt->bindParam(':id', $order_id, PDO::PARAM_INT);
        $orderStmt->execute();
        $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

        $itemsQuery = "SELECT oi.id, oi.order_id, oi.product_id, oi.quantity, oi.unit_price,
                              p.name, p.slug, p.image, p.price, p.stock, c.name AS category_name
                       FROM " . $this->items_table . " oi
                       INNER JOIN products p ON oi.product_id = p.id
                       LEFT JOIN categories c ON p.category_id = c.id
                       WHERE oi.order_id = :order_id
                       ORDER BY oi.id DESC";
        $itemsStmt = $this->conn->prepare($itemsQuery);
        $itemsStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $itemsStmt->execute();
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

        $itemCount = 0;
        foreach ($items as $item) {
            $itemCount += (int) $item['quantity'];
        }

        return [
            'order' => $order,
            'items' => $items,
            'count' => $itemCount,
            'total_amount' => (int) ($order['total_amount'] ?? 0)
        ];
    }

    public function addToCart($user_id, $product_id, $quantity = 1)
    {
        $quantity = max(1, (int) $quantity);
        $price = $this->getProductPrice($product_id);

        if ($price <= 0) {
            return false;
        }

        $order_id = $this->getOrCreateCartOrder($user_id);
        if (!$order_id) {
            return false;
        }

        $query = "INSERT INTO " . $this->items_table . " (order_id, product_id, quantity, unit_price)
                  VALUES (:order_id, :product_id, :quantity, :unit_price)
                  ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':unit_price', $price, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->recalculateCartTotal($order_id);
            return true;
        }

        return false;
    }

    public function updateCartItem($user_id, $product_id, $quantity)
    {
        $quantity = max(1, (int) $quantity);
        $price = $this->getProductPrice($product_id);
        if ($price <= 0) {
            return false;
        }

        $cart = $this->getCart($user_id);
        if (!$cart || empty($cart['order'])) {
            return false;
        }

        $query = "UPDATE " . $this->items_table . "
                  SET quantity = :quantity,
                      unit_price = :unit_price
                  WHERE order_id = :order_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':unit_price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $cart['order']['id'], PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->recalculateCartTotal($cart['order']['id']);
            return true;
        }

        return false;
    }

    public function removeCartItem($user_id, $product_id)
    {
        $cart = $this->getCart($user_id);
        if (!$cart || empty($cart['order'])) {
            return false;
        }

        $query = "DELETE FROM " . $this->items_table . "
                  WHERE order_id = :order_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $cart['order']['id'], PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->recalculateCartTotal($cart['order']['id']);
            return true;
        }

        return false;
    }

    public function clearCart($user_id)
    {
        $cart = $this->getCart($user_id);
        if (!$cart || empty($cart['order'])) {
            return false;
        }

        $query = "DELETE FROM " . $this->items_table . " WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $cart['order']['id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->recalculateCartTotal($cart['order']['id']);
            return true;
        }

        return false;
    }

    public function placeOrder($user_id, $data = [])
    {
        $cart = $this->getCart($user_id);
        if (!$cart || empty($cart['items'])) {
            return false;
        }

        $customer_name = trim($data['customer_name'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $address = trim($data['address'] ?? '');

        if ($customer_name === '' || $phone === '' || $address === '') {
            return false;
        }

        $query = "UPDATE " . $this->orders_table . "
                  SET status = 'pending',
                      customer_name = :customer_name,
                      phone = :phone,
                      address = :address
                  WHERE id = :id AND user_id = :user_id AND status = 'cart'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':id', $cart['order']['id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getOrderHistory($user_id)
    {
        $query = "SELECT * FROM " . $this->orders_table . "
                  WHERE user_id = :user_id AND status <> 'cart'
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllOrders($keyword = '', $status = '', $limit = null, $offset = null)
    {
        $query = "SELECT o.*, u.fullname AS user_fullname, u.email AS user_email
                  FROM " . $this->orders_table . " o
                  INNER JOIN users u ON o.user_id = u.id
                  WHERE 1=1";

        if ($keyword !== '') {
            $query .= " AND (o.customer_name LIKE :keyword OR o.phone LIKE :keyword OR o.address LIKE :keyword OR u.fullname LIKE :keyword OR u.email LIKE :keyword OR o.id LIKE :keyword)";
        }

        if ($status !== '') {
            $query .= " AND o.status = :status";
        }

        $query .= " ORDER BY o.created_at DESC";

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

        if ($status !== '') {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
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

    public function countOrders($keyword = '', $status = '')
    {
        $query = "SELECT COUNT(*) AS total
                  FROM " . $this->orders_table . " o
                  INNER JOIN users u ON o.user_id = u.id
                  WHERE 1=1";

        if ($keyword !== '') {
            $query .= " AND (o.customer_name LIKE :keyword OR o.phone LIKE :keyword OR o.address LIKE :keyword OR u.fullname LIKE :keyword OR u.email LIKE :keyword OR o.id LIKE :keyword)";
        }

        if ($status !== '') {
            $query .= " AND o.status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if ($keyword !== '') {
            $likeKeyword = '%' . $keyword . '%';
            $stmt->bindValue(':keyword', $likeKeyword, PDO::PARAM_STR);
        }

        if ($status !== '') {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }

    public function getOrderById($id)
    {
        $query = "SELECT o.*, u.fullname AS user_fullname, u.email AS user_email
                  FROM " . $this->orders_table . " o
                  INNER JOIN users u ON o.user_id = u.id
                  WHERE o.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            return null;
        }

        $itemsQuery = "SELECT oi.*, p.name, p.slug, p.image, p.price, p.stock, c.name AS category_name
                       FROM " . $this->items_table . " oi
                       INNER JOIN products p ON oi.product_id = p.id
                       LEFT JOIN categories c ON p.category_id = c.id
                       WHERE oi.order_id = :order_id
                       ORDER BY oi.id ASC";
        $itemsStmt = $this->conn->prepare($itemsQuery);
        $itemsStmt->bindParam(':order_id', $id, PDO::PARAM_INT);
        $itemsStmt->execute();

        $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        return $order;
    }

    public function updateStatus($id, $status)
    {
        $allowed = array_keys($this->getStatusLabels());
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $query = "UPDATE " . $this->orders_table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>