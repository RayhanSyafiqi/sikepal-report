<?php
require_once '../app/helpers/CloudinaryHelper.php';

class VisitReportController extends Controller {
    
    public function index() {
        $this->requireLogin();
        $visitModel = $this->model('VisitReport');
        
        if ($_SESSION['role'] === 'admin') {
            $reports = $visitModel->getTodayReports();
        } else {
            $reports = $visitModel->getTodayReports($_SESSION['user_id']);
        }
        
        $this->view('sales/visit_dashboard', ['reports' => $reports]);
    }

    public function create() {
        $this->requireLogin();
        $this->requireRole('sales');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreateVisit();
        } else {
            $this->view('sales/create_visit_report');
        }
    }

    private function processCreateVisit() {
        $photoUrl = 'default.jpg';
        $photoPublicId = null;
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $cloudinary = new CloudinaryHelper();
            
            $validation = $cloudinary->validateImage($_FILES['photo']);
            if (!$validation['valid']) {
                $_SESSION['error'] = $validation['error'];
                $this->redirect('visit_create');
                return;
            }
            
            $publicId = $cloudinary->generatePublicId($_FILES['photo']['name'], 'visit');
            
            $result = $cloudinary->uploadFile($_FILES['photo']['tmp_name'], $publicId);
            
            if ($result['success']) {
                $photoUrl = $result['url'];
                $photoPublicId = $result['public_id'];
            } else {
                $_SESSION['error'] = 'Gagal upload foto: ' . $result['error'];
                $this->redirect('visit_create');
                return;
            }
        }
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'location_name' => $_POST['location_name'],
            'pic_name' => $_POST['pic_name'],
            'pic_phone' => $_POST['pic_phone'],
            'google_maps_link' => $_POST['google_maps_link'] ?? '',
            'photo' => $photoUrl,
            'photo_public_id' => $photoPublicId,
            'notes' => $_POST['notes'] ?? ''
        ];
        
        $visitModel = $this->model('VisitReport');
        if ($visitModel->create($data)) {
            $_SESSION['success'] = '✅ Laporan visit berhasil dibuat!';
            $this->redirect('visit');
        } else {
            $_SESSION['error'] = '❌ Gagal membuat laporan visit!';
            $this->redirect('visit_create');
        }
    }

    public function viewAll() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $visitModel = $this->model('VisitReport');
        $reports = $visitModel->getAllReports();
        
        $this->view('admin/visit_reports', ['reports' => $reports]);
    }
}
?>