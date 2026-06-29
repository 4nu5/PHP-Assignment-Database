
-- -- create database AssignmentWebDev;

-- CREATE TABLE Community_services (
--     Service_id INT AUTO_INCREMENT PRIMARY KEY,
--     title VARCHAR(50) NOT NULL UNIQUE,
--     description TEXT NOT NULL UNIQUE,
--     sdgTarget VARCHAR(30) NOT NULL,
--     eventDate date not null,
--     createdBy INT,
--     FOREIGN KEY (createdBy) REFERENCES users(user_id)
--     ON UPDATE CASCADE ON DELETE SET NULL
-- );

-- CREATE TABLE users(
--     user_id INT AUTO_INCREMENT PRIMARY KEY,
--     username VARCHAR(50) NOT NULL UNIQUE,
--     email VARCHAR(100) NOT NULL UNIQUE,
--     passwordHash VARCHAR(255) NOT NULL,
--     role ENUM('user' , 'admin') DEFAULT 'user'
--     );
    
    
-- CREATE TABLE participation_Requests(
--     request_id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     service_id INT NOT NULL,
--     status ENUM('pending', 'approved', 'rejected') DEFAULT 'Pending',
--     request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(user_id)
--     ON UPDATE CASCADE ON DELETE CASCADE,
--     FOREIGN KEY (service_id) REFERENCES Community_services(Service_id)
--     ON UPDATE CASCADE ON DELETE CASCADE
-- --     ); 
--     latitude
--     longitude
--     home_address

-- alter table users
-- add column latitude DECIMAL(10,8) NULL;
-- add column longitude DECIMAL(11,8) NULL;
-- add column home_address VARCHAR(90) NULL;

-- alter table Community_services
-- add column latitude DECIMAL(10,8) NULL;
-- add column longitude DECIMAL(11,8) NULL;
-- add column service_address VARCHAR(90) NULL;




-- CREATE TABLE password_resets (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     email VARCHAR(255) NOT NULL,
--     token VARCHAR(255) NOT NULL,
--     expires_at DATETIME NOT NULL,
--     created_at DATETIME DEFAULT CURRENT_TIMESTAMP
-- );


-- ALTER TABLE users 
-- ADD COLUMN is_active TINYINT(1) DEFAULT 1;


-- CREATE TABLE notifications (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     message TEXT NOT NULL,
--     is_read TINYINT(1) DEFAULT 0,
--     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
-- );