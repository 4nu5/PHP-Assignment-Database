/*CREATE TABLE Community_services (
    Service_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NOT NULL UNIQUE,
    sdgTarget VARCHAR(30) NOT NULL,
    eventDate date not null,
    createdBy INT,
    FOREIGN KEY (createdBy) REFERENCES users(user_id)
    ON UPDATE CASCADE ON DELETE SET NULL
);*/

/*CREATE TABLE users(
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    passwordHash VARCHAR(255) NOT NULL,
    role ENUM('user' , 'admin') DEFAULT 'user'
    );
    */
    
/*CREATE TABLE participation_Requests(
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'Pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Community_services(Service_id)
    ON UPDATE CASCADE ON DELETE CASCADE
    ); */