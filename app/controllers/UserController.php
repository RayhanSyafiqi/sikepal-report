<?php
require_once '../app/helpers/CloudinaryHelper.php';

class UserController extends Controller {
    
    public function index() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $userModel = $this->model('User');
        $users = $userModel->getAll();
        $this->view('admin/users', ['users' => $users]);
    }

    public function create() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreateUser();
        } else {
            $this->redirect('users');
        }
    }

    private function processCreateUser() {
        $errors = $this->validateUserInput($_POST, false);
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $this->redirect('users');
            return;
        }
        
        $userModel = $this->model('User');
        
        $existingUser = $userModel->findByEmail($_POST['email']);
        if ($existingUser) {
            $_SESSION['error'] = 'Email sudah terdaftar!';
            $this->redirect('users');
            return;
        }
        
        $photoUrl = 'default.jpg';
        $photoPublicId = null;
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $uploadResult = $this->uploadPhotoToCloudinary($_FILES['photo'], 'profile');
            if ($uploadResult['success']) {
                $photoUrl = $uploadResult['url'];
                $photoPublicId = $uploadResult['public_id'];
            } else {
                $_SESSION['error'] = 'Gagal upload foto: ' . $uploadResult['error'];
                $this->redirect('users');
                return;
            }
        }
        
        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'photo' => $photoUrl,
            'photo_public_id' => $photoPublicId,
            'role' => $_POST['role']
        ];
        
        if ($userModel->create($data)) {
            $_SESSION['success'] = '✅ User berhasil ditambahkan!';
        } else {
            $_SESSION['error'] = '❌ Gagal menambahkan user!';
        }
        
        $this->redirect('users');
    }

    public function edit($id) {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = 'User tidak ditemukan!';
            $this->redirect('users');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEditUser($id, $user);
        } else {
            $this->view('admin/edit_user', ['user' => $user]);
        }
    }

    private function processEditUser($id, $oldUser) {
        $errors = $this->validateUserInput($_POST, true);
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $this->redirect('users_edit&id=' . $id);
            return;
        }
        
        $data = [
            'name' => trim($_POST['name']),
            'role' => $_POST['role']
        ];
        
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            if (!empty($oldUser['photo_public_id']) && $oldUser['photo_public_id'] !== 'default.jpg') {
                $this->deletePhotoFromCloudinary($oldUser['photo_public_id']);
            }
            
            $uploadResult = $this->uploadPhotoToCloudinary($_FILES['photo'], 'profile');
            if ($uploadResult['success']) {
                $data['photo'] = $uploadResult['url'];
                $data['photo_public_id'] = $uploadResult['public_id'];
            } else {
                $_SESSION['error'] = 'Gagal upload foto: ' . $uploadResult['error'];
                $this->redirect('users_edit&id=' . $id);
                return;
            }
        }
        
        $userModel = $this->model('User');
        if ($userModel->update($id, $data)) {
            if ($id == $_SESSION['user_id'] && isset($data['photo'])) {
                $_SESSION['photo'] = $data['photo'];
            }
            $_SESSION['success'] = '✅ User berhasil diupdate!';
        } else {
            $_SESSION['error'] = '❌ Gagal mengupdate user!';
        }
        
        $this->redirect('users');
    }

    public function delete($id) {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = '❌ Tidak dapat menghapus akun sendiri!';
            $this->redirect('users');
            return;
        }
        
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = 'User tidak ditemukan!';
            $this->redirect('users');
            return;
        }
        
        if (!empty($user['photo_public_id']) && $user['photo_public_id'] !== 'default.jpg') {
            $this->deletePhotoFromCloudinary($user['photo_public_id']);
        }
        
        if ($userModel->delete($id)) {
            $_SESSION['success'] = '✅ User berhasil dihapus!';
        } else {
            $_SESSION['error'] = '❌ Gagal menghapus user!';
        }
        
        $this->redirect('users');
    }

    private function uploadPhotoToCloudinary($file, $prefix = 'profile') {
        $cloudinary = new CloudinaryHelper();
        
        $validation = $cloudinary->validateImage($file);
        if (!$validation['valid']) {
            return ['success' => false, 'error' => $validation['error']];
        }
        
        $publicId = $cloudinary->generatePublicId($file['name'], $prefix);
        return $cloudinary->uploadFile($file['tmp_name'], $publicId);
    }

    private function deletePhotoFromCloudinary($publicId) {
        if (empty($publicId)) {
            return ['success' => false, 'error' => 'Public ID kosong'];
        }
        $cloudinary = new CloudinaryHelper();
        return $cloudinary->deleteFile($publicId);
    }

    private function validateUserInput($data, $isEdit = false) {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors[] = 'Nama lengkap harus diisi!';
        } elseif (strlen($data['name']) < 3) {
            $errors[] = 'Nama lengkap minimal 3 karakter!';
        }
        
        if (!$isEdit) {
            if (empty($data['email'])) {
                $errors[] = 'Email harus diisi!';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid!';
            }
        }
        
        if (!$isEdit) {
            if (empty($data['password'])) {
                $errors[] = 'Password harus diisi!';
            } elseif (strlen($data['password']) < 6) {
                $errors[] = 'Password minimal 6 karakter!';
            }
        } else {
            if (!empty($data['password']) && strlen($data['password']) < 6) {
                $errors[] = 'Password minimal 6 karakter!';
            }
        }
        
        if (empty($data['role'])) {
            $errors[] = 'Role harus dipilih!';
        } elseif (!in_array($data['role'], ['admin', 'sales'])) {
            $errors[] = 'Role tidak valid!';
        }
        
        return $errors;
    }

    public function search() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $keyword = $_GET['q'] ?? '';
        $userModel = $this->model('User');
        $users = $userModel->search($keyword);
        
        $this->view('admin/users', ['users' => $users, 'search' => $keyword]);
    }

    public function export() {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $userModel = $this->model('User');
        $users = $userModel->getAll();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=users_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nama', 'Email', 'Role', 'Tanggal Bergabung']);
        
        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['name'],
                $user['email'],
                $user['role'],
                $user['created_at']
            ]);
        }
        
        fclose($output);
        exit();
    }
}
?>