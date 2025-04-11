CREATE DATABASE gab_db;

CREATE TABLE utilisateurs (
    utilisateur_id INT AUTO_INCREMENT PRIMARY KEY,
    numero_carte VARCHAR(20) NOT NULL,
    code_pin VARCHAR(255) NOT NULL,
    solde DECIMAL(10, 2) DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_maj DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    type ENUM('dépôt', 'retrait') NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_maj DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(utilisateur_id)
);