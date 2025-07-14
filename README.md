Add the files to your htdocs

Make sure you've properly setup php and xammp on your device

turn on mysql and apache

go to localhost/phpmyadmin

create a database called mypokemon_catalogue

sql 

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(255)
);



CREATE TABLE cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    card_name VARCHAR(100),
    type ENUM('Fire', 'Water', 'Grass', 'Electric', 'Psychic'),
    rarity ENUM('Common', 'Full Art', 'Reverse Hollow'),
    image VARCHAR(255)
);
