CREATE TABLE user(
username VARCHAR(20) NOT NULL PRIMARY KEY,
emailaddress VARCHAR(60) NOT NULL,
password VARCHAR(12) NOT NULL,
gender ENUM('Male', 'Female', 'Other') NOT NULL,
agerange ENUM('16-25', '26-35', '36-45', '46-55', '56-65', '66-75') NOT NULL);

CREATE TABLE gyms(
gymname VARCHAR(50) NOT NULL,
avg_rating FLOAT DEFAULT NULL,
longitude FLOAT NOT NULL,
latitude FLOAT NOT NULL,
description VARCHAR(500) NOT NULL,
imagekey VARCHAR(50) NOT NULL,
gym_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY);

CREATE TABLE reviews(
review VARCHAR(500) NOT NULL,
rating FLOAT NOT NULL,
reviewer VARCHAR(20) NOT NULL REFERENCES users(username),
gym INT UNSIGNED NOT NULL REFERENCES gyms(gym_id),
PRIMARY KEY (reviewer, gym));