<?php
class AuthController extends Controller {
    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $this->view('auth/login', ['error' => 'Email dan password harus diisi!']);
                return;
            }
            
            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);
            
            if (!$user) {
                $this->view('auth/login', ['error' => 'Email tidak ditemukan!']);
                return;
            }
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role']; 
                $_SESSION['photo'] = $user['photo'] ?? 'default.jpg';
                
                error_log("Login successful: " . $user['email'] . " - Role: " . $user['role']);
                
                $this->redirect('dashboard');
                exit();
            } else {
                $this->view('auth/login', ['error' => 'Password salah!']);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        $this->redirect('login');
        exit();
    }
}
?>