<?php
require_once '../app/helpers/CloudinaryHelper.php';

class SalesReportController extends Controller {
    
    public function create() {
        $this->requireLogin();
        $this->requireRole('sales');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreateReport();
        } else {
            $salesModel = $this->model('SalesReport');
            $this->view('sales/create_sales_report', [
                'price_per_unit' => $salesModel->getPricePerUnit()
            ]);
        }
    }

    private function processCreateReport() {
        $photoUrl = 'default.jpg';
        $photoPublicId = null;
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $cloudinary = new CloudinaryHelper();
            
            $validation = $cloudinary->validateImage($_FILES['photo']);
            if (!$validation['valid']) {
                $_SESSION['error'] = $validation['error'];
                $this->redirect('sales_create');
                return;
            }
            
            $publicId = $cloudinary->generatePublicId($_FILES['photo']['name'], 'sales');
            
            $result = $cloudinary->uploadFile($_FILES['photo']['tmp_name'], $publicId);
            
            if ($result['success']) {
                $photoUrl = $result['url'];
                $photoPublicId = $result['public_id'];
            } else {
                $_SESSION['error'] = 'Gagal upload foto: ' . $result['error'];
                $this->redirect('sales_create');
                return;
            }
        }
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'location_name' => $_POST['location_name'],
            'pic_name' => $_POST['pic_name'],
            'pic_phone' => $_POST['pic_phone'],
            'tuna_mayo' => (int)$_POST['tuna_mayo'],
            'tuna_pedas' => (int)$_POST['tuna_pedas'],
            'ayam_mayo' => (int)$_POST['ayam_mayo'],
            'ayam_pedas' => (int)$_POST['ayam_pedas'],
            'photo' => $photoUrl,
            'photo_public_id' => $photoPublicId,
            'notes' => $_POST['notes'] ?? ''
        ];
        
        $salesModel = $this->model('SalesReport');
        if ($salesModel->create($data)) {
            $_SESSION['success'] = '✅ Laporan penjualan berhasil dibuat!';
            $this->redirect('dashboard');
        } else {
            $_SESSION['error'] = '❌ Gagal membuat laporan penjualan!';
            $this->redirect('sales_create');
        }
    }
}
?>