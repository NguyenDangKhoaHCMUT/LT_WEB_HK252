<?php
require_once 'config/Database.php';

class Setting
{
    private $conn;
    private $table_name = "settings";

    public $setting_key;
    public $setting_value;
    public $updated_at;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Lấy tất cả cài đặt ra dưới dạng mảng kết hợp (key => value)
     */
    public function getAllSettings()
    {
        $query = "SELECT setting_key, setting_value FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        return $settings;
    }

    /**
     * Lấy giá trị của một cài đặt cụ thể
     */
    public function getSetting($key)
    {
        $query = "SELECT setting_value FROM " . $this->table_name . " WHERE setting_key = :key LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':key', $key);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['setting_value'];
        }
        return null;
    }

    /**
     * Cập nhật hoặc thêm mới một cài đặt (Upsert)
     */
    public function updateSetting($key, $value)
    {
        $query = "INSERT INTO " . $this->table_name . " (setting_key, setting_value) 
                  VALUES (:key, :value) 
                  ON DUPLICATE KEY UPDATE setting_value = :value2";

        $stmt = $this->conn->prepare($query);

        // Sanitize (tuỳ chọn, nhưng nên cẩn thận với XSS)
        // $value = htmlspecialchars(strip_tags($value)); // Không dùng htmlspecialchars nếu muốn lưu tag <b>, <i>

        $stmt->bindParam(":key", $key);
        $stmt->bindParam(":value", $value);
        $stmt->bindParam(":value2", $value);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
