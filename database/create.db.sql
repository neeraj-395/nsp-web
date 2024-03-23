CREATE DATABASE IF NOT EXISTS nsp_db;

USE nsp_db;

CREATE TABLE IF NOT EXISTS user_data (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    `name`  VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    email_id VARCHAR(255) NOT NULL,
    reg_date DATE DEFAULT (CURRENT_DATE) NOT NULL,
    contact VARCHAR(15), /* Optional Field */
    profession VARCHAR(50) /* Optional Field */
);

CREATE TABLE IF NOT EXISTS note_data (
    note_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    title VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    upload_date DATE DEFAULT (CURRENT_DATE) NOT NULL,
    upload_time TIME DEFAULT (CURRENT_TIME) NOT NULL,
    view_url TEXT,
    download_url TEXT
);

CREATE TABLE IF NOT EXISTS user_handles (
    user_id INT PRIMARY KEY,
    wesbite_url VARCHAR(50), /* Optional Field */
    github_handle VARCHAR(50), /* Optional Field */
    insta_handle VARCHAR(50), /* Optional Field */
    twitter_handle VARCHAR(50), /* Optional Field */
    reddit_handle VARCHAR(50), /* Optional Field */
    FOREIGN KEY (user_id) REFERENCES user_data (user_id)
);

/*CREATE VIEW IF NOT EXISTS user_profile_view AS
SELECT ud.user_id,
       ud.name,
       ud.contact,
       ud.profession
       uh.website_url,
       uh.github_handle,
       uh.insta_handle,
       uh.twitter_handle,
       uh.reddit_handle,
FROM user_data ud
LEFT JOIN user_handles uh ON ud.user_id = uh.user_id;*/

