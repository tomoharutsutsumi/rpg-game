-- Create the `users` table for user authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the `characters` table for user characters
CREATE TABLE IF NOT EXISTS characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    char_id VARCHAR(50) NOT NULL UNIQUE,
    starter_item VARCHAR(50) NOT NULL,
    level INT DEFAULT 1,
    experience INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create the `quests` table
CREATE TABLE IF NOT EXISTS quests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    target_count INT NOT NULL,
    reward INT NOT NULL,
    status ENUM('available', 'active', 'completed') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the `battle_logs` table for tracking battles
CREATE TABLE IF NOT EXISTS battle_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    monster_id INT NOT NULL,
    result ENUM('win', 'lose') NOT NULL,
    battle_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (character_id) REFERENCES characters(id)
);

-- Create the `monsters` table for battle mechanics
CREATE TABLE IF NOT EXISTS monsters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    level INT DEFAULT 1,
    health INT NOT NULL,
    attack INT NOT NULL,
    reward_gold INT DEFAULT 0
);

-- Insert sample data into the `quests` table
INSERT INTO quests (name, description, target_count, reward, status) VALUES
('Training Day', 'Defeat 5 monsters to improve your skills.', 5, 50, 'available'),
('Monster Extermination', 'Defeat 20 monsters to protect the town.', 20, 200, 'available'),
('Hero\'s Trial', 'Defeat 50 monsters to prove your worth.', 50, 500, 'available');

