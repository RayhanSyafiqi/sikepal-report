<?php
class Report {
    private $db;
    
    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO reports (user_id, location_name, pic_name, pic_phone, google_maps_link, photo, notes, visit_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['location_name'],
            $data['pic_name'],
            $data['pic_phone'],
            $data['google_maps_link'],
            $data['photo'],
            $data['notes'],
            date('Y-m-d')
        ]);
    }

    public function getTodayReports($userId = null) {
        $sql = "SELECT r.*, u.name as sales_name FROM reports r JOIN users u ON r.user_id = u.id WHERE DATE(r.visit_date) = CURDATE()";
        if ($userId) {
            $sql .= " AND r.user_id = ?";
        }
        $sql .= " ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function getAllReports($userId = null) {
        $sql = "SELECT r.*, u.name as sales_name FROM reports r JOIN users u ON r.user_id = u.id";
        if ($userId) {
            $sql .= " WHERE r.user_id = ?";
        }
        $sql .= " ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT r.*, u.name as sales_name FROM reports r JOIN users u ON r.user_id = u.id WHERE r.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getTodayCount($userId = null) {
        $sql = "SELECT COUNT(*) FROM reports WHERE DATE(visit_date) = CURDATE()";
        if ($userId) {
            $sql .= " AND user_id = ?";
        }
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchColumn();
    }
}
?>