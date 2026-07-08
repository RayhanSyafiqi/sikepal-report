<?php
class Controller {
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        $viewPath = '../app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View tidak ditemukan: " . $viewPath);
        }
    }

    public function redirect($url) {
        if (strpos($url, '?') !== false) {
            header('Location: ' . BASE_URL . 'public/index.php?' . $url);
        } else {
            header('Location: ' . BASE_URL . 'public/index.php?route=' . $url);
        }
        exit();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
            exit();
        }
    }

    public function requireRole($role) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            $this->redirect('dashboard');
            exit();
        }
    }

    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            $userModel = $this->model('User');
            return $userModel->findById($_SESSION['user_id']);
        }
        return null;
    }
}
?>