-- ============================================
-- BASE DE DONNÉES - Gestion des réservations
-- ============================================

CREATE DATABASE IF NOT EXISTS efrei_reservations;
USE efrei_reservations;

-- Table utilisateur
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('etudiant', 'enseignant') DEFAULT 'etudiant',
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table matière
CREATE TABLE matiere (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- Table créneau
CREATE TABLE creneau (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dateheure_debut DATETIME NOT NULL,
    dateheure_fin DATETIME NOT NULL,
    type_seance ENUM('individu', 'collective') DEFAULT 'collective',
    enseignant_id INT,
    matiere_id INT,
    capacite_max INT DEFAULT 20,
    FOREIGN KEY (enseignant_id) REFERENCES utilisateur(id) ON DELETE SET NULL,
    FOREIGN KEY (matiere_id) REFERENCES matiere(id) ON DELETE SET NULL,
    CHECK (dateheure_fin > dateheure_debut)
);

-- Table réservation
CREATE TABLE reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    creneau_id INT NOT NULL,
    etudiant_id INT NOT NULL,
    matiere_id INT NOT NULL,
    statut ENUM('validee', 'en_attente', 'a_declarer', 'annulee') DEFAULT 'en_attente',
    date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creneau_id) REFERENCES creneau(id) ON DELETE CASCADE,
    FOREIGN KEY (etudiant_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    FOREIGN KEY (matiere_id) REFERENCES matiere(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reservation (creneau_id, etudiant_id)
);

-- Index pour optimisation
CREATE INDEX idx_creneau_date ON creneau(dateheure_debut);
CREATE INDEX idx_reservation_etudiant ON reservation(etudiant_id);
CREATE INDEX idx_reservation_creneau ON reservation(creneau_id);

-- ============================================
-- DONNÉES DE TEST
-- ============================================

-- Insertion des matières
INSERT INTO matiere (nom, description) VALUES
('Intelligence Artificielle', 'Cours d\'IA et machine learning avec les enseignants du département'),
('Cybersécurité', 'Sécurité des systèmes d\'information et protection des données'),
('Data Science', 'Analyse et visualisation de données, Big Data'),
('Développement Web', 'Front-end, back-end et frameworks modernes'),
('Réseaux', 'Architecture et protocoles réseaux'),
('Programmation Java', 'Programmation orientée objet avec Java'),
('Bases de données', 'SQL, NoSQL et modélisation de données'),
('DevOps', 'Intégration continue, déploiement et cloud');

-- Insertion des utilisateurs (mots de passe: 'password123' haché)
-- Mot de passe haché pour 'password123' est: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role) VALUES
('Admin', 'Système', 'admin@efrei.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant'),
('Dupont', 'Jean', 'jean.dupont@efrei.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant'),
('Martin', 'Sophie', 'sophie.martin@efrei.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant'),
('Bernard', 'Jean', 'jean.bernard@efrei.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant'),
('Petit', 'Claire', 'claire.petit@efrei.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant'),
('Hamidi', 'Mohamed', 'mohamed.hamidi@efrei.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant');

-- Insertion des créneaux de test
INSERT INTO creneau (dateheure_debut, dateheure_fin, type_seance, enseignant_id, matiere_id, capacite_max) VALUES
('2026-04-01 14:00:00', '2026-04-01 16:00:00', 'individu', 4, 1, 5),
('2026-04-02 10:00:00', '2026-04-02 12:00:00', 'collective', 4, 2, 20),
('2026-04-03 09:00:00', '2026-04-03 11:00:00', 'collective', 5, 3, 15),
('2026-04-04 13:00:00', '2026-04-04 15:00:00', 'individu', 5, 4, 3),
('2026-04-05 15:00:00', '2026-04-05 17:00:00', 'collective', 6, 5, 25),
('2026-04-08 10:00:00', '2026-04-08 12:00:00', 'collective', 4, 6, 20),
('2026-04-09 14:00:00', '2026-04-09 16:00:00', 'individu', 5, 7, 4),
('2026-04-10 09:00:00', '2026-04-10 11:00:00', 'collective', 6, 8, 15);