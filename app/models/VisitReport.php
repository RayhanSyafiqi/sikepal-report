<?php
class VisitReport {
    private $db;
    
    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }


    public function create($data) {
        $sql = "INSERT INTO visit_reports 
            (user_id, location_name, pic_name, pic_phone, google_maps_link, 
             photo, photo_public_id, notes, visit_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['user_id'],
            $data['location_name'],
            $data['pic_name'],
            $data['pic_phone'],
            $data['google_maps_link'] ?? '',
            $data['photo'] ?? 'default.jpg',
            $data['photo_public_id'] ?? null,
            $data['notes'] ?? '',
            date('Y-m-d')
        ];
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }


    public function update($id, $data) {
        $sql = "UPDATE visit_reports SET 
            location_name = ?,
            pic_name = ?,
            pic_phone = ?,
            google_maps_link = ?,
            photo = ?,
            photo_public_id = ?,
            notes = ?
            WHERE id = ?";
        
        $params = [
            $data['location_name'],
            $data['pic_name'],
            $data['pic_phone'],
            $data['google_maps_link'] ?? '',
            $data['photo'] ?? 'default.jpg',
            $data['photo_public_id'] ?? null,
            $data['notes'] ?? '',
            $id
        ];
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }


    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM visit_reports WHERE id = ?");
        return $stmt->execute([$id]);
    }


    public function findById($id) {
        $stmt = $this->db->prepare("SELECT vr.*, u.name as sales_name 
                                    FROM visit_reports vr 
                                    JOIN users u ON vr.user_id = u.id 
                                    WHERE vr.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }


    public function getTodayReports($userId = null) {
        $sql = "SELECT vr.*, u.name as sales_name 
                FROM visit_reports vr 
                JOIN users u ON vr.user_id = u.id 
                WHERE DATE(vr.visit_date) = CURDATE()";
        if ($userId) {
            $sql .= " AND vr.user_id = ?";
        }
        $sql .= " ORDER BY vr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

   
    public function getAllReports($userId = null) {
        $sql = "SELECT vr.*, u.name as sales_name 
                FROM visit_reports vr 
                JOIN users u ON vr.user_id = u.id";
        if ($userId) {
            $sql .= " WHERE vr.user_id = ?";
        }
        $sql .= " ORDER BY vr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }


    public function getByDate($date, $userId = null) {
        $sql = "SELECT vr.*, u.name as sales_name 
                FROM visit_reports vr 
                JOIN users u ON vr.user_id = u.id 
                WHERE DATE(vr.visit_date) = ?";
        if ($userId) {
            $sql .= " AND vr.user_id = ?";
        }
        $sql .= " ORDER BY vr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$date, $userId]);
        } else {
            $stmt->execute([$date]);
        }
        return $stmt->fetchAll();
    }


    public function getByDateRange($startDate, $endDate, $userId = null) {
        $sql = "SELECT vr.*, u.name as sales_name 
                FROM visit_reports vr 
                JOIN users u ON vr.user_id = u.id 
                WHERE DATE(vr.visit_date) BETWEEN ? AND ?";
        if ($userId) {
            $sql .= " AND vr.user_id = ?";
        }
        $sql .= " ORDER BY vr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$startDate, $endDate, $userId]);
        } else {
            $stmt->execute([$startDate, $endDate]);
        }
        return $stmt->fetchAll();
    }


    public function getUserTotalVisits($userId) {
        $sql = "SELECT COUNT(*) as total_visits 
                FROM visit_reports 
                WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total_visits'] ?? 0;
    }


    public function getTodayCount($userId = null) {
        $sql = "SELECT COUNT(*) as total_visits 
                FROM visit_reports 
                WHERE DATE(visit_date) = CURDATE()";
        if ($userId) {
            $sql .= " AND user_id = ?";
        }
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId]);
        } else {
            $stmt->execute();
        }
        $result = $stmt->fetch();
        return $result['total_visits'] ?? 0;
    }


    public function getSalesVisitStats() {
        $sql = "SELECT 
                    u.id as user_id,
                    u.name as sales_name,
                    COUNT(vr.id) as total_visits,
                    COUNT(DISTINCT DATE(vr.visit_date)) as active_days
                FROM users u
                LEFT JOIN visit_reports vr ON u.id = vr.user_id
                WHERE u.role = 'sales'
                GROUP BY u.id, u.name
                ORDER BY total_visits DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

 
    public function getLatestReports($limit = 10, $userId = null) {
        $sql = "SELECT vr.*, u.name as sales_name 
                FROM visit_reports vr 
                JOIN users u ON vr.user_id = u.id";
        if ($userId) {
            $sql .= " WHERE vr.user_id = ?";
        }
        $sql .= " ORDER BY vr.created_at DESC LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId, $limit]);
        } else {
            $stmt->execute([$limit]);
        }
        return $stmt->fetchAll();
    }

 
    public function search($keyword, $userId = null) {
        $sql = "SELECT vr.*, u.name as sales_name 
                FROM visit_reports vr 
                JOIN users u ON vr.user_id = u.id 
                WHERE vr.location_name LIKE ? 
                OR vr.pic_name LIKE ? 
                OR vr.pic_phone LIKE ?";
        if ($userId) {
            $sql .= " AND vr.user_id = ?";
        }
        $sql .= " ORDER BY vr.created_at DESC";
        
        $searchTerm = '%' . $keyword . '%';
        $params = [$searchTerm, $searchTerm, $searchTerm];
        if ($userId) {
            $params[] = $userId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }


    public function getLocationStats($userId = null) {
        $sql = "SELECT 
                    location_name,
                    COUNT(*) as total_visits,
                    COUNT(DISTINCT user_id) as total_sales
                FROM visit_reports";
        if ($userId) {
            $sql .= " WHERE user_id = ?";
        }
        $sql .= " GROUP BY location_name ORDER BY total_visits DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }
}
?>