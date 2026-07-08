<?php
class ReportController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        $salesModel = $this->model('SalesReport');
        
        if ($_SESSION['role'] === 'admin') {
            $reports = $salesModel->getTodayReports();
            $stats = $salesModel->getTodayStats();
            $view = 'admin/dashboard';
        } else {
            $reports = $salesModel->getTodayReports($_SESSION['user_id']);
            $stats = $salesModel->getTodayStats($_SESSION['user_id']);
            $view = 'sales/dashboard';
        }
        
        $this->view($view, [
            'reports' => $reports,
            'stats' => $stats,
            'price_per_unit' => $salesModel->getPricePerUnit()
        ]);
    }

    public function viewAll() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $salesModel = $this->model('SalesReport');
        $reports = $salesModel->getAllReports();
        $stats = $salesModel->getTodayStats();
        
        $this->view('admin/sales_reports', [
            'reports' => $reports,
            'stats' => $stats,
            'price_per_unit' => $salesModel->getPricePerUnit()
        ]);
    }

    public function export() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $salesModel = $this->model('SalesReport');
        $reports = $salesModel->getAllReports();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=laporan_penjualan_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Sales', 'Lokasi', 'PIC', 'Telepon', 'Tuna Mayo', 'Tuna Pedas', 'Ayam Mayo', 'Ayam Pedas', 'Total', 'Pendapatan', 'Tanggal']);
        
        foreach ($reports as $report) {
            fputcsv($output, [
                $report['id'],
                $report['sales_name'],
                $report['location_name'],
                $report['pic_name'],
                $report['pic_phone'],
                $report['tuna_mayo'],
                $report['tuna_pedas'],
                $report['ayam_mayo'],
                $report['ayam_pedas'],
                $report['total_sales'],
                $report['total_revenue'],
                $report['visit_date']
            ]);
        }
        
        fclose($output);
        exit();
    }
}
?>