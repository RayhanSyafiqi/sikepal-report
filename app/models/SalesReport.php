<?php
class SalesReport {
    private $db;
    private $price_per_unit = 15000;
    
    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function create($data) {
        $total = $data['tuna_mayo'] + $data['tuna_pedas'] + $data['ayam_mayo'] + $data['ayam_pedas'];
        $revenue = $total * $this->price_per_unit;
        
        $sql = "INSERT INTO sales_reports 
            (user_id, location_name, pic_name, pic_phone, 
             tuna_mayo, tuna_pedas, ayam_mayo, ayam_pedas, 
             total_sales, total_revenue, photo, photo_public_id, notes, visit_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['user_id'],
            $data['location_name'],
            $data['pic_name'],
            $data['pic_phone'],
            $data['tuna_mayo'],
            $data['tuna_pedas'],
            $data['ayam_mayo'],
            $data['ayam_pedas'],
            $total,
            $revenue,
            $data['photo'] ?? 'default.jpg',
            $data['photo_public_id'] ?? null,
            $data['notes'] ?? '',
            date('Y-m-d')
        ];
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }


    public function update($id, $data) {
        $total = $data['tuna_mayo'] + $data['tuna_pedas'] + $data['ayam_mayo'] + $data['ayam_pedas'];
        $revenue = $total * $this->price_per_unit;
        
        $sql = "UPDATE sales_reports SET 
            location_name = ?,
            pic_name = ?,
            pic_phone = ?,
            tuna_mayo = ?,
            tuna_pedas = ?,
            ayam_mayo = ?,
            ayam_pedas = ?,
            total_sales = ?,
            total_revenue = ?,
            photo = ?,
            photo_public_id = ?,
            notes = ?
            WHERE id = ?";
        
        $params = [
            $data['location_name'],
            $data['pic_name'],
            $data['pic_phone'],
            $data['tuna_mayo'],
            $data['tuna_pedas'],
            $data['ayam_mayo'],
            $data['ayam_pedas'],
            $total,
            $revenue,
            $data['photo'] ?? 'default.jpg',
            $data['photo_public_id'] ?? null,
            $data['notes'] ?? '',
            $id
        ];
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }


    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM sales_reports WHERE id = ?");
        return $stmt->execute([$id]);
    }


    public function findById($id) {
        $stmt = $this->db->prepare("SELECT sr.*, u.name as sales_name 
                                    FROM sales_reports sr 
                                    JOIN users u ON sr.user_id = u.id 
                                    WHERE sr.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getTodayReports($userId = null) {
        $sql = "SELECT sr.*, u.name as sales_name 
                FROM sales_reports sr 
                JOIN users u ON sr.user_id = u.id 
                WHERE DATE(sr.visit_date) = CURDATE()";
        if ($userId) {
            $sql .= " AND sr.user_id = ?";
        }
        $sql .= " ORDER BY sr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }


    public function getAllReports($userId = null) {
        $sql = "SELECT sr.*, u.name as sales_name 
                FROM sales_reports sr 
                JOIN users u ON sr.user_id = u.id";
        if ($userId) {
            $sql .= " WHERE sr.user_id = ?";
        }
        $sql .= " ORDER BY sr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$userId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function getTodayStats($userId = null) {
        $sql = "SELECT 
                    COALESCE(SUM(tuna_mayo), 0) as total_tuna_mayo,
                    COALESCE(SUM(tuna_pedas), 0) as total_tuna_pedas,
                    COALESCE(SUM(ayam_mayo), 0) as total_ayam_mayo,
                    COALESCE(SUM(ayam_pedas), 0) as total_ayam_pedas,
                    COALESCE(SUM(total_sales), 0) as total_sales,
                    COALESCE(SUM(total_revenue), 0) as total_revenue,
                    COUNT(*) as total_transactions
                FROM sales_reports 
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
        return $stmt->fetch();
    }

 
    public function getByDate($date) {
        $sql = "SELECT sr.*, u.name as sales_name 
                FROM sales_reports sr 
                JOIN users u ON sr.user_id = u.id 
                WHERE DATE(sr.visit_date) = ?
                ORDER BY sr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

  
    public function getStatsByDate($date) {
        $sql = "SELECT 
                    COALESCE(SUM(tuna_mayo), 0) as total_tuna_mayo,
                    COALESCE(SUM(tuna_pedas), 0) as total_tuna_pedas,
                    COALESCE(SUM(ayam_mayo), 0) as total_ayam_mayo,
                    COALESCE(SUM(ayam_pedas), 0) as total_ayam_pedas,
                    COALESCE(SUM(total_sales), 0) as total_sales,
                    COALESCE(SUM(total_revenue), 0) as total_revenue,
                    COUNT(*) as total_transactions
                FROM sales_reports 
                WHERE DATE(visit_date) = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetch();
    }

 
    public function getSalesStats() {
        $sql = "SELECT 
                    u.id as user_id,
                    u.name as sales_name,
                    COUNT(sr.id) as total_transactions,
                    COALESCE(SUM(sr.total_sales), 0) as total_sales,
                    COALESCE(SUM(sr.total_revenue), 0) as total_revenue
                FROM users u
                LEFT JOIN sales_reports sr ON u.id = sr.user_id
                WHERE u.role = 'sales'
                GROUP BY u.id, u.name
                ORDER BY total_revenue DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getByDateRange($startDate, $endDate, $userId = null) {
        $sql = "SELECT sr.*, u.name as sales_name 
                FROM sales_reports sr 
                JOIN users u ON sr.user_id = u.id 
                WHERE DATE(sr.visit_date) BETWEEN ? AND ?";
        if ($userId) {
            $sql .= " AND sr.user_id = ?";
        }
        $sql .= " ORDER BY sr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$startDate, $endDate, $userId]);
        } else {
            $stmt->execute([$startDate, $endDate]);
        }
        return $stmt->fetchAll();
    }


    public function getStatsByDateRange($startDate, $endDate, $userId = null) {
        $sql = "SELECT 
                    COALESCE(SUM(tuna_mayo), 0) as total_tuna_mayo,
                    COALESCE(SUM(tuna_pedas), 0) as total_tuna_pedas,
                    COALESCE(SUM(ayam_mayo), 0) as total_ayam_mayo,
                    COALESCE(SUM(ayam_pedas), 0) as total_ayam_pedas,
                    COALESCE(SUM(total_sales), 0) as total_sales,
                    COALESCE(SUM(total_revenue), 0) as total_revenue,
                    COUNT(*) as total_transactions
                FROM sales_reports 
                WHERE DATE(visit_date) BETWEEN ? AND ?";
        if ($userId) {
            $sql .= " AND user_id = ?";
        }
        
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$startDate, $endDate, $userId]);
        } else {
            $stmt->execute([$startDate, $endDate]);
        }
        return $stmt->fetch();
    }


    public function getPricePerUnit() {
        return $this->price_per_unit;
    }

    public function setPricePerUnit($price) {
        $this->price_per_unit = $price;
    }

 
    public function getUserTotalRevenue($userId) {
        $sql = "SELECT COALESCE(SUM(total_revenue), 0) as total_revenue 
                FROM sales_reports 
                WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total_revenue'] ?? 0;
    }

  
    public function getUserTotalSales($userId) {
        $sql = "SELECT COALESCE(SUM(total_sales), 0) as total_sales 
                FROM sales_reports 
                WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total_sales'] ?? 0;
    }
}
?>