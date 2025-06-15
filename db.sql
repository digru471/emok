-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Password reset tokens
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Categories (e.g., Engineering, Medical)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Subjects (e.g., Mathematics, Physics)
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Tests/Quizzes
CREATE TABLE tests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    time_limit INT, -- in minutes
    total_questions INT,
    passing_score INT,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Questions
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    test_id INT NOT NULL,
    question_text TEXT NOT NULL,
    option_a TEXT NOT NULL,
    option_b TEXT NOT NULL,
    option_c TEXT NOT NULL,
    option_d TEXT NOT NULL,
    correct_answer CHAR(1) NOT NULL, -- 'a', 'b', 'c', or 'd'
    explanation TEXT,
    FOREIGN KEY (test_id) REFERENCES tests(id)
);

-- User test attempts
CREATE TABLE test_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    test_id INT NOT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    score INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (test_id) REFERENCES tests(id)
);

-- User answers
CREATE TABLE user_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT NOT NULL,
    question_id INT NOT NULL,
    selected_answer CHAR(1),
    is_correct BOOLEAN,
    FOREIGN KEY (attempt_id) REFERENCES test_attempts(id),
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

-- Teachers/Developers (for landing page)
CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL, -- 'teacher' or 'developer'
    bio TEXT,
    image_path VARCHAR(255),
    display_order INT
);