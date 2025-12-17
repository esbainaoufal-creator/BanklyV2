CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(32) UNIQUE NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(8) NOT NULL,
    role ENUM('Controleur' , 'Adminasteur' , 'Client') NOT NULL
);

--@block
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(32) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    phone_number VARCHAR(10) UNIQUE NOT NULL,
    creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    adresse VARCHAR(100) NOT NULL,
    cin VARCHAR(10) UNIQUE NOT NULL,
    gendre ENUM('Homme' , 'Femme') NOT NULL,
    birthday DATE NOT NULL,
    utilisateur_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

--@block
CREATE TABLE IF NOT EXISTS comptes (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    account_number VARCHAR(14) UNIQUE NOT NULL,
    account_type ENUM('Courant' , 'Epargne' , 'Professionnel' , 'Jeune') NOT NULL,
    solde DECIMAL(12,2) NOT NULL,
    account_statue ENUM('Actif' , 'Inactif' , 'Blocked') NOT NULL,
    creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    utilisateur_id INT NOT NULL,
    client_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);