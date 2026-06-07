
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

alter table users
add column latitude DECIMAL(10,9) NULL;
add column longitude DECIMAL(10,9) NULL;
add column home_address VARCHAR(90) NULL;

alter table Community_services
add column latitude DECIMAL(10,9) NULL;
add column longitude DECIMAL(10,9) NULL;
add column home_address VARCHAR(90) NULL;


INSERT INTO community_services (service_name, service_address, latitude, longitude) VALUES
('Sunway Regional Food Bank', 'Jalan Universiti, Bandar Sunway', 3.067600, 101.603400),
('Subang Jaya Community Clinic', 'Jalan SS15/4, Subang Jaya', 3.078800, 101.589000),
('Pyramid Eco-Recycling Center', 'Jalan PJS 11/15, Bandar Sunway', 3.073100, 101.607700),
('Taylors Care Volunteer Hub', 'Jalan Taylors, Subang Jaya', 3.062800, 101.616900);

UPDATE users 
SET latitude = 3.075000, 
    longitude = 101.593000, 
    home_address = 'SS15 Residential Area, Subang Jaya' 
WHERE username = 'collinllaxman';