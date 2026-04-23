<?php
$server = "localhost"; 
$username = "root";
$password = "";  //macbook gebruikers vullen bij wachtwoord "root" in.
$db = "escaperoom"; //pas dit aan indien de naam van jullie database anders is

try {
  $db_connection = new PDO("mysql:host=$server; dbname=$db", $username, $password);
  $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db_connection->exec(
    "CREATE TABLE IF NOT EXISTS riddles (
      id INT AUTO_INCREMENT PRIMARY KEY,
      riddle VARCHAR(255) NOT NULL,
      answer VARCHAR(100) NOT NULL,
      hint VARCHAR(255),
      roomId INT NOT NULL
    )"
  );
  $db_connection->exec(
    "CREATE TABLE IF NOT EXISTS team_members (
      id INT AUTO_INCREMENT PRIMARY KEY,
      team_name VARCHAR(100) NOT NULL,
      player_name VARCHAR(100) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
  );
  $db_connection->exec(
    "CREATE TABLE IF NOT EXISTS user_answers (
      id INT AUTO_INCREMENT PRIMARY KEY,
      team_name VARCHAR(100) NOT NULL,
      riddle_id INT NOT NULL,
      user_answer VARCHAR(255) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
  );
} catch (PDOException $e) {
  echo "Verbinding mislukt" . $e->getMessage();
}