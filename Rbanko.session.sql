CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(32) UNIQUE NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(8) NOT NULL,
    role ENUM('Controleur' , 'Adminasteur' , 'Client') NOT NULL
);

