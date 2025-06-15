-- Check if table exists and update structure
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    experience TEXT,
    photo VARCHAR(255),
    display_order INT DEFAULT 0
);

-- If table already exists, add/modify columns
ALTER TABLE team_members 
MODIFY role VARCHAR(100) NOT NULL,
CHANGE bio experience TEXT,
CHANGE image_path photo VARCHAR(255),
MODIFY display_order INT DEFAULT 0;

-- Add initial sample data if needed (optional)
INSERT INTO team_members (name, role, experience, photo, display_order)
VALUES 
('John Doe', 'Developer', 'Senior full-stack developer with 5+ years of experience in PHP, JavaScript, and MySQL.', '', 1),
('Jane Smith', 'Teacher', 'Experienced computer science instructor specializing in web development and database design.', '', 2); 