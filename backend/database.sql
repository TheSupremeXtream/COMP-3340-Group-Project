CREATE DATABASE IF NOT EXISTS store_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE store_db;

CREATE TABLE IF NOT EXISTS categories (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO categories (name, slug, description) VALUES
('Storage',      'storage',      'Hard drives, SSDs, and memory cards'),
('Audio',        'audio',        'Headphones, headsets, and microphones'),
('Input Devices','input-devices','Mice, cameras, and controllers'),
('Cables',       'cables',       'USB, Ethernet, and extension cables'),
('Power',        'power',        'Charging bricks and power banks'),
('Accessories',  'accessories',  'Hubs, adapters, and misc accessories');

CREATE TABLE IF NOT EXISTS products (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    category_id     INT NOT NULL,
    title           VARCHAR(200) NOT NULL,
    brand           VARCHAR(150) NOT NULL,
    description     TEXT,
    base_price      DECIMAL(8,2) NOT NULL,
    stock           INT NOT NULL DEFAULT 0,
    image_file      VARCHAR(255),
    is_featured     TINYINT(1) DEFAULT 0,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO products (category_id, title, brand, description, base_price, stock, image_file, is_featured) VALUES
(1, 'External HDD',             'Seagate',       'Portable external hard drive, USB 3.0, compact and reliable for backups and file transfers.',        59.99,  80, 'HDD.jpg',                             1),
(1, 'Raspberry Pi SSD',         'SanDisk',       'High-speed SSD designed for Raspberry Pi and single-board computers, fast boot times.',              29.99,  60, 'SSD.jpg',                             0),
(3, 'Computer Mouse',           'Logitech',      'Ergonomic computer mouse, smooth tracking on all surfaces, comfortable for long use sessions.',       24.99, 100, 'Mouse.jpg',                           1),
(2, 'Headphones',               'Sony',          'Over-ear stereo headphones with rich sound, padded earcups, and foldable design.',                    49.99,  55, 'Headphones.jpg',                      0),
(2, 'Gaming Headset',           'HyperX',        '7.1 surround sound gaming headset with noise-cancelling mic and memory foam earcups.',                69.99,  45, 'Gaming_Headset.jpg',                  1),
(2, 'Microphone',               'Blue',          'Studio-quality condenser microphone, ideal for streaming, podcasting, and video calls.',              89.99,  35, 'Microphone.jpg',                      1),
(3, 'Webcam',                   'Logitech',      '1080p HD webcam with auto-focus, built-in mic, and wide-angle lens for video calls and streaming.',   59.99,  50, 'Camera.jpg',                          0),
(4, 'Ethernet Cable',           'Cable Matters', 'Cat6 Ethernet cable, snagless RJ45 connectors, supports up to 1Gbps speeds.',                         8.99,  200, 'Ethernet_cable.jpg',                  0),
(1, 'USB Thumb Drive',          'Kingston',      'Compact USB 3.0 flash drive, fast read/write speeds, durable and pocket-sized.',                      9.99,  150, 'USB-thumb-drive.jpg',                 0),
(1, 'Micro SD Card + Adapter',  'Samsung',       'High-speed microSD card with full-size SD adapter, ideal for cameras, phones, and tablets.',         14.99, 120, 'MicroSD_card.jpg',                    0),
(4, 'USB-A Cable',              'Anker',         'Braided USB-A to USB-A cable, fast data transfer up to 480Mbps, tangle-resistant.',                   7.99,  180, 'usb-a-cable.jpg',                     0),
(4, 'USB-B Cable',              'Anker',         'USB-A to USB-B cable for printers, scanners, and audio interfaces, durable braided nylon.',           7.99,  140, 'USB_Mini-B_and_Standard-A_plugs.jpg', 0),
(4, 'USB-C Cable',              'Anker',         '100W USB-C to USB-C cable supporting fast charging and 10Gbps data transfer.',                       11.99, 200, 'USB_Type-C_Cable.jpg',                1),
(6, 'USB-C Hub',                'Anker',         'Multi-port USB-C hub with HDMI, USB-A ports, and SD card reader, plug-and-play.',                    39.99,  65, 'USB-C_Hub.jpg',                       1),
(5, 'USB-C Charging Brick',     'Apple',         'Compact GaN USB-C wall charger with fast charging support, ideal for laptops and phones.',           19.99,  90, 'USB-C_chargers.jpg',                    0),
(5, 'USB-A Charging Brick',     'Anker',         'Dual-port USB-A wall charger, compatible with all USB-A devices, foldable plug.',                    12.99, 100, 'Chargers.webp',                       0),
(5, 'USB-C Power Bank',         'Samsung',       '10000mAh portable power bank with USB-C in/out and USB-A port, slim and lightweight.',               29.99,  70, 'Blue_Charger.png',                      1),
(4, 'USB-C Extension Cable',    'Cable Matters', 'USB-C male to female extension cable, supports charging and data, flexible and durable.',             13.99,  85, 'usb-c-extension-cable.jpg',           0),
(3, 'Controller',               'PowerA',        'Wired game controller compatible with PC and consoles, ergonomic grip and responsive buttons.',       34.99,  60, 'Red_Controller.png',                  1),
(6, 'Laptop Stand',             'Arvuti',        'Designer aluminium laptop stand for better ergonomics, cooling, and desk organization.',             27.99,  75, 'laptop-stand.jpg',                       0);

CREATE TABLE IF NOT EXISTS product_options (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    product_id   INT NOT NULL,
    option_type  ENUM('color','storage','connectivity','length','wattage') NOT NULL,
    option_value VARCHAR(100) NOT NULL,
    option_image VARCHAR(255) NULL,
    price_delta  DECIMAL(6,2) DEFAULT 0.00,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO product_options (product_id, option_type, option_value, option_image, price_delta) VALUES
(1,  'storage',       '512GB', NULL,                          0.00),
(1,  'storage',       '1TB', NULL,                           20.00),
(2,  'storage',       '256GB', NULL,                          0.00),
(2,  'storage',       '512GB', NULL,                         15.00),
(3,  'connectivity',  'Wired', NULL,                          0.00),
(3,  'connectivity',  'Wireless', NULL,                      10.00),
(4,  'connectivity',  'Wired', NULL,                          0.00),
(4,  'connectivity',  'Wireless', NULL,                      15.00),
(5,  'connectivity',  'Wired', NULL,                          0.00),
(5,  'connectivity',  'Wireless', NULL,                      20.00),
(6,  'connectivity',  'USB', NULL,                            0.00),
(6,  'connectivity',  'XLR', NULL,                           20.00),
(7,  'connectivity',  'USB', NULL,                            0.00),
(7,  'connectivity',  'Bluetooth', NULL,                     10.00),
(8,  'length',        '3ft', NULL,                            0.00),
(8,  'length',        '6ft', NULL,                            3.00),
(9,  'storage',       '32GB', NULL,                           0.00),
(9,  'storage',       '64GB', NULL,                           5.00),
(10, 'storage',       '64GB', NULL,                           0.00),
(10, 'storage',       '128GB', NULL,                          8.00),
(11, 'length',        '3ft', NULL,                            0.00),
(11, 'length',        '6ft', NULL,                            3.00),
(12, 'length',        '3ft', NULL,                            0.00),
(12, 'length',        '6ft', NULL,                            3.00),
(13, 'length',        '3ft', NULL,                            0.00),
(13, 'length',        '6ft', NULL,                            3.00),
(14, 'connectivity',  'With Ethernet + USB-C', NULL,          0.00),
(14, 'connectivity',  'Without Ethernet', NULL,              -10.00),
(15, 'wattage',       '30W', NULL,                            0.00),
(15, 'wattage',       '12W', NULL,                           -8.00),
(16, 'wattage',       '12W', NULL,                            0.00),
(16, 'wattage',       '5W', NULL,                            -4.00),
(17, 'color',         'Blue', "Blue_Charger.png",             0.00),
(17, 'color',         'Red', "Red_Charger.png",               0.00),
(18, 'length',        '6ft', NULL,                            0.00),
(18, 'length',        '12ft', NULL,                           5.00),
(19, 'color',         'Red', "Red_Controller.png",            0.00),
(19, 'color',         'Blue', "Blue_Controller.png",          0.00),
(20, 'color',         'Steel', NULL,                          0.00),
(20, 'color',         'Black', NULL,                          2.00);

CREATE TABLE IF NOT EXISTS users (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    username     VARCHAR(60)  NOT NULL UNIQUE,
    email        VARCHAR(150) NOT NULL UNIQUE,
    password     VARCHAR(255) NOT NULL,
    full_name    VARCHAR(150),
    role         ENUM('customer','admin') DEFAULT 'customer',
    is_active    TINYINT(1)   DEFAULT 1,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@techvault.local',
 '$2y$12$QKkYHAEZQwnbDrJJKM/RgueJ6kxKq5lIoLOz4wHSHm4JRRGOwHmoi',
 'Site Administrator', 'admin');

CREATE TABLE IF NOT EXISTS orders (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT,
    total        DECIMAL(10,2) NOT NULL,
    status       ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    order_id         INT NOT NULL,
    product_id       INT NOT NULL,
    quantity         INT NOT NULL DEFAULT 1,
    unit_price       DECIMAL(8,2) NOT NULL,
    selected_options JSON,
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS site_settings (
    setting_key   VARCHAR(80) PRIMARY KEY,
    setting_value VARCHAR(255),
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO site_settings (setting_key, setting_value) VALUES
('active_theme',     'light'),
('site_name',        'The Computer Store'),
('maintenance_mode', '0');

CREATE TABLE IF NOT EXISTS service_log (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    status       ENUM('online','offline','degraded') NOT NULL,
    response_ms  INT,
    detail       TEXT,
    checked_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;