-- Add sample students and assessments for testing

-- Add sample students
INSERT INTO students (name, sex, age, class, school_id, gender, created_at, updated_at) VALUES
('Sophea Vanna', 'female', 8, 'Grade 2A', 1, 'female', NOW(), NOW()),
('Dara Meng', 'male', 9, 'Grade 2A', 1, 'male', NOW(), NOW()),
('Sreypov Chan', 'female', 8, 'Grade 2B', 1, 'female', NOW(), NOW()),
('Sokha Lim', 'male', 10, 'Grade 3A', 1, 'male', NOW(), NOW()),
('Vicheka Neth', 'female', 9, 'Grade 3A', 1, 'female', NOW(), NOW()),
('Ratanak Hem', 'male', 8, 'Grade 2B', 1, 'male', NOW(), NOW()),
('Srey Mom Chea', 'female', 10, 'Grade 3B', 1, 'female', NOW(), NOW()),
('Pisach Keo', 'male', 9, 'Grade 3B', 1, 'male', NOW(), NOW()),
('Channary Pich', 'female', 8, 'Grade 2A', 1, 'female', NOW(), NOW()),
('Sovann Tep', 'male', 10, 'Grade 3A', 1, 'male', NOW(), NOW());

-- Add sample baseline assessments for Khmer
INSERT INTO assessments (student_id, cycle, subject, level, score, assessed_at, created_at, updated_at) VALUES
(1, 'baseline', 'khmer', 'Beginner', 0, '2025-01-15', NOW(), NOW()),
(2, 'baseline', 'khmer', 'Letter', 25, '2025-01-15', NOW(), NOW()),
(3, 'baseline', 'khmer', 'Word', 50, '2025-01-15', NOW(), NOW()),
(4, 'baseline', 'khmer', 'Paragraph', 75, '2025-01-15', NOW(), NOW()),
(5, 'baseline', 'khmer', 'Story', 85, '2025-01-15', NOW(), NOW()),
(6, 'baseline', 'khmer', 'Beginner', 0, '2025-01-15', NOW(), NOW()),
(7, 'baseline', 'khmer', 'Word', 45, '2025-01-15', NOW(), NOW()),
(8, 'baseline', 'khmer', 'Paragraph', 70, '2025-01-15', NOW(), NOW()),
(9, 'baseline', 'khmer', 'Letter', 30, '2025-01-15', NOW(), NOW()),
(10, 'baseline', 'khmer', 'Story', 90, '2025-01-15', NOW(), NOW());

-- Add sample baseline assessments for Math
INSERT INTO assessments (student_id, cycle, subject, level, score, assessed_at, created_at, updated_at) VALUES
(1, 'baseline', 'math', 'Beginner', 0, '2025-01-16', NOW(), NOW()),
(2, 'baseline', 'math', '1-Digit', 20, '2025-01-16', NOW(), NOW()),
(3, 'baseline', 'math', '2-Digit', 55, '2025-01-16', NOW(), NOW()),
(4, 'baseline', 'math', 'Subtraction', 75, '2025-01-16', NOW(), NOW()),
(5, 'baseline', 'math', 'Division', 85, '2025-01-16', NOW(), NOW()),
(6, 'baseline', 'math', 'Beginner', 0, '2025-01-16', NOW(), NOW()),
(7, 'baseline', 'math', '2-Digit', 50, '2025-01-16', NOW(), NOW()),
(8, 'baseline', 'math', 'Subtraction', 70, '2025-01-16', NOW(), NOW()),
(9, 'baseline', 'math', '1-Digit', 25, '2025-01-16', NOW(), NOW()),
(10, 'baseline', 'math', 'Division', 90, '2025-01-16', NOW(), NOW());

-- Add some midline assessments
INSERT INTO assessments (student_id, cycle, subject, level, score, assessed_at, created_at, updated_at) VALUES
(2, 'midline', 'khmer', 'Word', 55, '2025-03-15', NOW(), NOW()),
(3, 'midline', 'khmer', 'Paragraph', 65, '2025-03-15', NOW(), NOW()),
(4, 'midline', 'khmer', 'Story', 85, '2025-03-15', NOW(), NOW()),
(7, 'midline', 'khmer', 'Paragraph', 60, '2025-03-15', NOW(), NOW()),
(8, 'midline', 'khmer', 'Story', 80, '2025-03-15', NOW(), NOW()),
(2, 'midline', 'math', '2-Digit', 45, '2025-03-16', NOW(), NOW()),
(3, 'midline', 'math', 'Subtraction', 70, '2025-03-16', NOW(), NOW()),
(4, 'midline', 'math', 'Division', 85, '2025-03-16', NOW(), NOW()),
(7, 'midline', 'math', 'Subtraction', 65, '2025-03-16', NOW(), NOW()),
(8, 'midline', 'math', 'Division', 80, '2025-03-16', NOW(), NOW());