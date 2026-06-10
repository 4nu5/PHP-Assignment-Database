
create database AssignmentWebDev;

CREATE TABLE Community_services (
    Service_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NOT NULL UNIQUE,
    sdgTarget VARCHAR(30) NOT NULL,
    eventDate date not null,
    createdBy INT,
    FOREIGN KEY (createdBy) REFERENCES users(user_id)
    ON UPDATE CASCADE ON DELETE SET NULL
    );

CREATE TABLE users(
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    passwordHash VARCHAR(255) NOT NULL,
    role ENUM('user' , 'admin') DEFAULT 'user'
    );
    
CREATE TABLE participation_Requests(
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'Pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Community_services(Service_id)
    ON UPDATE CASCADE ON DELETE CASCADE
    ); 
    latitude
    longitude
    home_address

alter table users
add column latitude DECIMAL(10,8) NULL;
add column longitude DEC    IMAL(11,8) NULL;
add column home_address VARCHAR(90) NULL;

alter table Community_services
add column latitude DECIMAL(10,8) NULL;
add column longitude DECIMAL(11,8) NULL;
add column home_address VARCHAR(90) NULL;


INSERT INTO community_services (title, description, sdgTarget, eventDate, createdby, service_address, latitude, longitude) VALUES
('Sunway Regional Food Bank', 'Distributing surplus food to local urban poor', 'Zero Hunger', '2026-07-15', '1', 'Jalan Universiti, Bandar Sunway', 3.067600, 101.603400),
('SS15 Street Cleanup', 'Clearing blocked drains and litter around commercial zones', 'Sustainable Cities', '2026-07-20', '1', 'Jalan SS15/4, Subang Jaya', 3.076300, 101.585700),
('Lakeside Youth Coding Camp', 'Free weekend programming workshops for high schoolers', 'Quality Education', '2026-08-05', '1', 'No. 1 Jalan Taylors, Subang Jaya', 3.062600, 101.616800),
('Lagoon Selatan Tree Planting', 'Greening the pedestrian walkways and local parks', 'Climate Action', '2026-08-10', '1', 'Jalan Lagoon Selatan, Bandar Sunway', 3.064500, 101.600600),
('USJ 1 Soup Kitchen', 'Serving hot meals to vulnerable communities', 'Zero Hunger', '2026-08-15', '1', 'Persiaran Subang Permai, USJ 1', 3.053800, 101.593200);





UPDATE users 
SET latitude = 3.075000, 
 longitude = 101.593000, 
 home_address = 'SS15 Residential Area, Subang Jaya' 
WHERE username = 'collinllaxman';