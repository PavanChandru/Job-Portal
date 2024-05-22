CREATE DATABASE job_portal;

USE job_portal;

DROP TABLE IF EXISTS Reviews;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS applicants;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS UserSkills;
DROP TABLE IF EXISTS Skills;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS users;


CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    f_name VARCHAR(100),
    l_name VARCHAR(100),
    date_of_birth DATE,
    phone VARCHAR(20),
    gender ENUM('M', 'F', 'Other'),
    address TEXT,
    resume LONGBLOB
);

CREATE TABLE companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    email VARCHAR(100),
    company_name VARCHAR(100),
    industry VARCHAR(100),
    size ENUM('Small', 'Medium', 'Large'),
    description TEXT,
    address TEXT
);

CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    email VARCHAR(100),
    f_name VARCHAR(100),
    l_name VARCHAR(100)
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE skills (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

CREATE TABLE userSkills (
    user_skill_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    skill_id INT,
    proficiency_level ENUM('Beginner', 'Intermediate', 'Expert'),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (skill_id) REFERENCES Skills(skill_id)
);

CREATE TABLE jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    salary DECIMAL(10, 2),
    FOREIGN KEY (company_id) REFERENCES companies(company_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);


CREATE TABLE applicants (
    applicant_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    application_status ENUM('pending', 'reviewed', 'rejected') DEFAULT 'pending',
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin (username, password, email, f_name, l_name)
VALUES ('admin', 'admin', 'admin@itforhire.com', 'Admin', 'User');

INSERT INTO categories (name, description) VALUES
('Software', 'Software development jobs'),
('Marketing', 'All marketing related jobs'),
('Technology', 'Jobs related to technology and software development'),
('Data Analysis', 'Jobs related to data analysis and business intelligence'),
('Network Administration', 'Jobs related to network administration and infrastructure management'),
('Cybersecurity', 'Jobs related to cybersecurity and information security'),
('Web Development', 'Jobs related to web development and design'),
('Database Administration', 'Jobs related to database administration and management'),
('IT Support', 'Jobs related to IT support and helpdesk services'),
('Cloud Computing', 'Jobs related to cloud computing and cloud infrastructure management');


INSERT INTO companies (username, password, email, company_name, industry, size, description, address)
VALUES 
('company1', 'hashed_password_1', 'company1@example.com', 'Company 1', 'Technology', 'Medium', 'Description of Company 1', '123 Main St, City, State Zip'),
('company2', 'hashed_password_2', 'company2@example.com', 'Company 2', 'Software', 'Large', 'Description of Company 2', '456 Elm St, City, State Zip'),
('company3', 'hashed_password_3', 'company3@example.com', 'Company 3', 'IT Services', 'Small', 'Description of Company 3', '789 Oak St, City, State Zip'),
('company4', 'hashed_password_4', 'company4@example.com', 'Company 4', 'Telecommunications', 'Medium', 'Description of Company 4', '1011 Pine St, City, State Zip'),
('company5', 'hashed_password_5', 'company5@example.com', 'Company 5', 'E-commerce', 'Large', 'Description of Company 5', '1213 Maple St, City, State Zip');


INSERT INTO jobs (company_id, category_id, title, description, salary) VALUES
(1, 1, 'Software Developer', 'Develop and maintain software applications.', 80000.00),
(2, 2, 'Marketing Manager', 'Manage marketing campaigns and staff.', 70000.00),
(3, 3, 'Data Analyst', 'Analyze data and generate insights for decision-making.', 75000.00),
(4, 1, 'Web Developer', 'Design and develop websites and web applications.', 85000.00),
(5, 4, 'Human Resources Specialist', 'Manage employee recruitment, onboarding, and HR activities.', 65000.00),
(2, 2, 'Social Media Manager', 'Create and execute social media strategies for brand promotion.', 70000.00);

INSERT INTO skills (name) VALUES
('Programming Languages'),
('Web Development'),
('Mobile App Development'),
('Database Management'),
('Data Analysis'),
('Cybersecurity'),
('Cloud Computing'),
('Artificial Intelligence'),
('Machine Learning'),
('Network Administration'),
('UI/UX Design'),
('DevOps'),
('Project Management'),
('Quality Assurance'),
('System Administration'),
('Digital Marketing'),
('Content Management Systems (CMS)'),
('E-commerce'),
('Blockchain'),
('Internet of Things (IoT)');

ALTER TABLE applicants DROP FOREIGN KEY applicants_ibfk_1;
ALTER TABLE applicants ADD CONSTRAINT applicants_ibfk_1
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE CASCADE;

ALTER TABLE applicants DROP FOREIGN KEY applicants_ibfk_2;
ALTER TABLE applicants ADD CONSTRAINT fk_user_id
	FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE;
    
ALTER TABLE userSkills DROP FOREIGN KEY userskills_ibfk_1;
ALTER TABLE userSkills ADD CONSTRAINT fk_userSkills_user_id
	FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE;

ALTER TABLE jobs DROP FOREIGN KEY jobs_ibfk_1;
ALTER TABLE jobs ADD CONSTRAINT fk_jobs_company_id
	FOREIGN KEY (company_id) REFERENCES companies(company_id) ON DELETE CASCADE;

select * from applicants;
select * from users;
select * from admin;
select * from categories;
select * from jobs;
select * from companies;
select * from contact_messages;
select * from userSkills;
select * from skills;