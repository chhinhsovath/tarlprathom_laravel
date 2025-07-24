-- Primary seed users for TaRL Laravel system
-- Run this script after migrations are complete

-- Create a sample school first
INSERT INTO schools (name, province, district, cluster, school_name, school_code, created_at, updated_at) 
VALUES ('Phnom Penh Primary School', 'Phnom Penh', 'Chamkarmon', 'Central', 'Phnom Penh Primary School', 'PPPS001', NOW(), NOW());

-- Get the school ID (assuming it will be 1 if this is the first school)
SET @school_id = 1;

-- Admin user
INSERT INTO users (name, email, password, role, is_active, created_at, updated_at) 
VALUES ('Kairav Admin', 'kairav@prathaminternational.org', '$2y$12$rA/LVVzjO.NGgNM4KEbHYuEF/nTQNJrkYOOKjqBbAlzd0mqD380M6', 'admin', 1, NOW(), NOW());

-- Mentor user  
INSERT INTO users (name, email, password, role, is_active, created_at, updated_at) 
VALUES ('Mentor One', 'mentor1@prathaminternational.org', '$2y$12$rA/LVVzjO.NGgNM4KEbHYuEF/nTQNJrkYOOKjqBbAlzd0mqD380M6', 'mentor', 1, NOW(), NOW());

-- Teacher user
INSERT INTO users (name, email, password, role, school_id, is_active, created_at, updated_at) 
VALUES ('Teacher One', 'teacher1@prathaminternational.org', '$2y$12$rA/LVVzjO.NGgNM4KEbHYuEF/nTQNJrkYOOKjqBbAlzd0mqD380M6', 'teacher', @school_id, 1, NOW(), NOW());

-- Viewer user
INSERT INTO users (name, email, password, role, is_active, created_at, updated_at) 
VALUES ('Viewer User', 'viewer@prathaminternational.org', '$2y$12$rA/LVVzjO.NGgNM4KEbHYuEF/nTQNJrkYOOKjqBbAlzd0mqD380M6', 'viewer', 1, NOW(), NOW());

-- Note: The password hash above is for 'admin123' - all users will have password 'admin123'