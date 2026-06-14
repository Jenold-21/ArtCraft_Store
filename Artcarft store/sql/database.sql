-- CREATE DATABASE IF NOT EXISTS artcraft_store;
-- USE artcraft_store;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'default.jpg',
    category_id INT,
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, email, password) VALUES
('admin', 'admin@artcraft.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO categories (name) VALUES
('Painting'),
('Sculpture'),
('Handicraft'),
('Jewelry'),
('Pottery');

INSERT INTO products (name, description, price, image, category_id, featured) VALUES
('Abstract Painting', 'Beautiful abstract painting with vibrant colors', 150.00, 'abstract.jpg', 1, 1),
('Landscape Oil Painting', 'Scenic landscape painted with oil colors', 200.00, 'landscape.jpg', 1, 1),
('Marble Sculpture', 'Elegant marble sculpture of a figure', 350.00, 'marble.jpg', 2, 1),
('Wooden Handicraft', 'Handcrafted wooden decorative piece', 85.00, 'wooden.jpg', 3, 1),
('Silver Necklace', 'Handmade silver necklace with gemstone', 120.00, 'necklace.jpg', 4, 1),
('Clay Pottery Set', 'Set of 3 handcrafted clay pots', 65.00, 'pottery.jpg', 5, 0),
('Modern Art Painting', 'Contemporary modern art piece', 180.00, 'modern.jpg', 1, 0),
('Bronze Sculpture', 'Bronze sculpture of a horse', 450.00, 'bronze.jpg', 2, 0),
('Bamboo Handicraft', 'Eco-friendly bamboo handicraft', 55.00, 'bamboo.jpg', 3, 0),
('Gold Earrings', 'Traditional gold plated earrings', 90.00, 'earrings.jpg', 4, 0),
('Ceramic Vase', 'Hand-painted ceramic vase', 75.00, 'vase.jpg', 5, 0),
('Portrait Painting', 'Custom portrait style painting', 250.00, 'portrait.jpg', 1, 0);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    location TEXT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(200) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);
