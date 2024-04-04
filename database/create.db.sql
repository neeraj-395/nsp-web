CREATE DATABASE IF NOT EXISTS nsp_db;

USE nsp_db;

CREATE TABLE IF NOT EXISTS user_data (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    name  VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    reg_date DATE DEFAULT (CURRENT_DATE) NOT NULL,
    contact VARCHAR(15),
    profession VARCHAR(50),
    INDEX login_inx (user_id, username, password) 
);

CREATE TABLE IF NOT EXISTS user_social_set (
    user_id INT PRIMARY KEY NOT NULL,
    github VARCHAR(50), 
    instagram VARCHAR(50), 
    twitter VARCHAR(50), 
    reddit VARCHAR(50), 
    FOREIGN KEY (user_id) REFERENCES user_data (user_id)
);

CREATE TABLE IF NOT EXISTS note_data (
    note_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(50) NOT NULL,
    description VARCHAR(255) NOT NULL,
    upload_date DATE DEFAULT (CURRENT_DATE) NOT NULL,
    upload_time TIME DEFAULT (CURRENT_TIME) NOT NULL,
    cover_path VARCHAR(100) NOT NULL,
    note_path VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user_data (user_id)
);