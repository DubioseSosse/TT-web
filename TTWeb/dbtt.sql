-- Datenbank erstellen mit UTF-8-Kodierung
DROP DATABASE IF EXISTS troja_toscana;
CREATE DATABASE troja_toscana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Datenbank auswählen
USE troja_toscana;

-- Tabelle für Gruppen (Meuten, Sippen)
DROP TABLE IF EXISTS groups;
CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('Meute', 'Sippe') NOT NULL,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    photo VARCHAR(255) NOT NULL DEFAULT 'default.jpg',
    meeting_times VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelle für Benutzer (Stammesführung & Gruppenleitungen)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('leitung', 'stammesfuehrung') default NULL,
    group_id INT NULL,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Tabelle für Kalender
DROP TABLE IF EXISTS calendar;
CREATE TABLE calendar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    date DATE NOT NULL,
    time TIME NOT NULL,
    pickup_date DATE NOT NULL,
    pickup_time TIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelle für Seiteninhalte (Info, Kontakt)
DROP TABLE IF EXISTS pages;
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title varchar(255) NOT NULL UNIQUE,
    content TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

Drop Table if exists images;
CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    description TEXT,
    group_id INT DEFAULT NULL, 
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	uploaded_by INT DEFAULT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

Drop Table if exists posts;
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image1 VARCHAR(255),
    image2 VARCHAR(255),
    image3 VARCHAR(255),
    image4 VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- Beispielgruppen (Meuten und Sippen)
INSERT INTO groups (id, name, type, description, photo, meeting_times) VALUES
(1, 'Die Bären', 'Meute', 'Eine mutige Meute, die Abenteuer liebt.', 'bear.jpg', 'Freitags, 17:00-19:00 Uhr'),
(2, 'Die Adler', 'Sippe', 'Unsere kreativen Köpfe in der Sippe.', 'eagle.jpg', 'Mittwochs, 18:00-20:00 Uhr'),
(3, 'Paris', 'Sippe', 'Adrett', 'parislogo.png', 'Dienstags, 19:00-20:00');

-- Testdaten für Benutzer
INSERT INTO users (username, password, role, group_id) VALUES
('backuproot', '$2y$10$fvww9TM/4AdKhk/PGNOeGuJ9c54V3gfM7igQIrLww0WPqURbLWSua', 'stammesfuehrung', NULL),		-- pwd rootlog
('1', '$2y$10$2Pqe3W82rUZCCTaAIqaHseHo0.yHHc82xiVRDQa5DlrE6lfbnCm.u', 'leitung', 1),		-- pwd 1
('1234', '$2y$10$c1jfjPTCRanAZ1RYOBKYNO7CoMEpayw.t/.pp94HIMnf8V9B/vyyy', '', 1);		-- pwd 1234



-- Beispielkalendertermine
INSERT INTO calendar (title, description, date, time, pickup_date, pickup_time) VALUES
('Wandertag', 'Gemeinsamer Wandertag in den Bergen.', '2025-02-10', '09:00', '2025-03-10', '15:00'),
('Sommerlager', 'Unser jährliches Sommerlager.', '2025-07-15', '08:00', '2025-07-17', '15:00');

-- Inhalte der Seiten (Info & Kontakt)
INSERT INTO pages (title, content) VALUES
('info', '<h2>Willkommen bei Troja Toscana</h2><p>Wir sind ein engagierter Verein für Kinder- und Jugendarbeit.</p>'),
('contact', '<h2>Kontakt</h2><p>Adresse: Vereinsstraße 123, 12345 Musterstadt<br>Email: info@troja-toscana.de<br>Telefon: 01234/56789</p>');




