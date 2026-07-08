<?php
require_once '../app/helpers/CloudinaryHelper.php';

class ProfileController extends Controller {
    public function index() {
        $this->requireLogin();
        
        $userModel = $this->model('User');
        $user = $userModel->findById($_SESSION['user_id']);
        $this->view('sales/profile', ['user' => $user]);
    }

    public function update() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photoUrl = $_SESSION['photo'];
            $photoPublicId = null;
            
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $cloudinary = new CloudinaryHelper();
                
                $userModel = $this->model('User');
                $user = $userModel->findById($_SESSION['user_id']);
                if ($user && $user['photo_public_id']) {
                    $cloudinary->deleteFile($user['photo_public_id']);
                }
                
                $publicId = $cloudinary->generatePublicId($_FILES['photo']['name'], 'profile');
                $result = $cloudinary->uploadFile($_FILES['photo']['tmp_name'], $publicId);
                
                if ($result['success']) {
                    $photoUrl = $result['url'];
                    $photoPublicId = $result['public_id'];
                }
            }
            
            $data = [
                'name' => $_POST['name'],
                'photo' => $photoUrl,
                'photo_public_id' => $photoPublicId
            ];
            
            $userModel = $this->model('User');
            if ($userModel->update($_SESSION['user_id'], $data)) {
                $_SESSION['name'] = $data['name'];
                $_SESSION['photo'] = $photoUrl;
                $_SESSION['success'] = 'Profile berhasil diupdate!';
            } else {
                $_SESSION['error'] = 'Gagal update profile!';
            }
            
            $this->redirect('profile');
        }
    }
}
?>