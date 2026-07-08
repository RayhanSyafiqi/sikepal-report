<?php
class User {
    private $db;
    
    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO users (name, email, password, photo, photo_public_id, role) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = [
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['photo'] ?? 'default.jpg',
            $data['photo_public_id'] ?? null,
            $data['role']
        ];
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function update($id, $data) {
        $sql = "UPDATE users SET ";
        $params = [];
        $updates = [];
        
        if (isset($data['name'])) {
            $updates[] = "name = ?";
            $params[] = $data['name'];
        }
        
        if (isset($data['role'])) {
            $updates[] = "role = ?";
            $params[] = $data['role'];
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $updates[] = "password = ?";
            $params[] = $data['password'];
        }
        
        if (isset($data['photo']) && !empty($data['photo'])) {
            $updates[] = "photo = ?";
            $params[] = $data['photo'];
        }
        
        if (isset($data['photo_public_id'])) {
            $updates[] = "photo_public_id = ?";
            $params[] = $data['photo_public_id'];
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql .= implode(", ", $updates);
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getSales() {
        $stmt = $this->db->query("SELECT * FROM users WHERE role = 'sales' ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function search($keyword) {
        $sql = "SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC";
        $searchTerm = '%' . $keyword . '%';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
}
?>