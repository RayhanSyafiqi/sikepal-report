-- Tabel users dengan photo_public_id
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(500) DEFAULT 'default.jpg',
    photo_public_id VARCHAR(255) NULL,
    role ENUM('admin', 'sales') DEFAULT 'sales',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel sales_reports dengan photo_public_id
CREATE TABLE sales_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    location_name VARCHAR(200) NOT NULL,
    pic_name VARCHAR(100) NOT NULL,
    pic_phone VARCHAR(20) NOT NULL,
    tuna_mayo INT DEFAULT 0,
    tuna_pedas INT DEFAULT 0,
    ayam_mayo INT DEFAULT 0,
    ayam_pedas INT DEFAULT 0,
    total_sales INT DEFAULT 0,
    total_revenue DECIMAL(10,2) DEFAULT 0,
    photo VARCHAR(500) DEFAULT 'default.jpg',
    photo_public_id VARCHAR(255) NULL,
    notes TEXT,
    visit_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel visit_reports dengan photo_public_id
CREATE TABLE visit_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    location_name VARCHAR(200) NOT NULL,
    pic_name VARCHAR(100) NOT NULL,
    pic_phone VARCHAR(20) NOT NULL,
    google_maps_link VARCHAR(500),
    photo VARCHAR(500) DEFAULT 'default.jpg',
    photo_public_id VARCHAR(255) NULL,
    notes TEXT,
    visit_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert default admin user (password: password)
INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@onigiri.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert default sales user (password: password)
INSERT INTO users (name, email, password, role) 
VALUES ('Sales User', 'sales@onigiri.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sales');