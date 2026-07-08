<?php
/**
 * Cloudinary Helper - Versi Final dengan Upload Preset
 */

class CloudinaryHelper {
    private $cloudName;
    private $folder;
    private $uploadPreset;
    private $apiKey;
    private $apiSecret;

    public function __construct() {
        $this->cloudName = CLOUDINARY_CLOUD_NAME;
        $this->folder = CLOUDINARY_FOLDER;
        $this->uploadPreset = CLOUDINARY_UPLOAD_PRESET;
        $this->apiKey = CLOUDINARY_API_KEY;
        $this->apiSecret = CLOUDINARY_API_SECRET;
    }

    /**
     * Upload file ke Cloudinary menggunakan Upload Preset
     */
    public function uploadFile($filePath, $publicId = null) {
        try {
            if (!file_exists($filePath)) {
                return [
                    'success' => false,
                    'error' => 'File tidak ditemukan: ' . $filePath
                ];
            }

            $uploadUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";
            
            $params = [
                'upload_preset' => $this->uploadPreset,
                'folder' => $this->folder,
                'public_id' => $publicId,
                'quality' => 'auto',
                'fetch_format' => 'auto'
            ];

            $postFields = $params;
            $postFields['file'] = new CURLFile($filePath);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $uploadUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $result = json_decode($response, true);
                $publicIdFull = $result['public_id'] ?? $publicId;
                $url = "https://res.cloudinary.com/{$this->cloudName}/image/upload/{$publicIdFull}";
                
                return [
                    'success' => true,
                    'public_id' => $publicIdFull,
                    'url' => $url,
                    'width' => $result['width'] ?? 0,
                    'height' => $result['height'] ?? 0,
                    'size' => $result['bytes'] ?? 0,
                    'format' => $result['format'] ?? 'jpg'
                ];
            } else {
                $errorData = json_decode($response, true);
                return [
                    'success' => false,
                    'error' => $errorData['error']['message'] ?? 'Upload gagal',
                    'http_code' => $httpCode
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Hapus gambar dari Cloudinary
     */
    public function deleteFile($publicId) {
        try {
            if (!$publicId) {
                return ['success' => false, 'error' => 'Public ID tidak boleh kosong'];
            }

            if (empty($this->apiSecret)) {
                return ['success' => false, 'error' => 'API Secret tidak ditemukan!'];
            }

            $deleteUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy";
            
            $timestamp = time();
            $paramsToSign = [
                'public_id' => $publicId,
                'timestamp' => $timestamp
            ];
            
            ksort($paramsToSign);
            $stringToSign = '';
            foreach ($paramsToSign as $key => $value) {
                $stringToSign .= $key . '=' . $value . '&';
            }
            $stringToSign = rtrim($stringToSign, '&');
            $stringToSign .= $this->apiSecret;
            $signature = sha1($stringToSign);
            
            $params = [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
                'api_key' => $this->apiKey,
                'signature' => $signature
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $deleteUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $result = json_decode($response, true);
                if (isset($result['result']) && $result['result'] === 'ok') {
                    return ['success' => true, 'result' => $result];
                }
                return ['success' => false, 'error' => 'Gagal menghapus: ' . ($result['result'] ?? 'unknown')];
            } else {
                return ['success' => false, 'error' => 'HTTP Error: ' . $httpCode];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate public ID dari nama file
     */
    public function generatePublicId($filename, $prefix = '') {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        $timestamp = time();
        $random = substr(md5(uniqid()), 0, 8);
        $prefix = $prefix ? $prefix . '_' : '';
        return $prefix . $name . '_' . $timestamp . '_' . $random;
    }

    /**
     * Validasi file gambar
     */
    public function validateImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024;
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return ['valid' => false, 'error' => 'Tipe file tidak didukung. Gunakan: JPG, PNG, GIF, WEBP'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['valid' => false, 'error' => 'Ukuran file maksimal 5MB'];
        }
        
        return ['valid' => true];
    }

    /**
     * Get URL gambar
     */
    public function getImageUrl($publicId) {
        return "https://res.cloudinary.com/{$this->cloudName}/image/upload/{$publicId}";
    }
}
?>