#!/usr/bin/env python3
import re

def convert_postgres_to_mysql(input_file, output_file):
    with open(input_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Remove PostgreSQL specific comments about server type
    content = re.sub(r'Source Server Type.*?PostgreSQL.*?\n', 'Source Server Type    : MySQL\n', content)
    content = re.sub(r'Target Server Type.*?PostgreSQL.*?\n', 'Target Server Type    : MySQL\n', content)
    
    # Replace PostgreSQL table syntax with MySQL
    content = re.sub(r'DROP TABLE IF EXISTS "public"\."(\w+)";', r'DROP TABLE IF EXISTS `\1`;', content)
    content = re.sub(r'CREATE TABLE "public"\."(\w+)"', r'CREATE TABLE `\1`', content)
    
    # Replace PostgreSQL data types with MySQL equivalents
    content = re.sub(r'"(\w+)"\s+int4\s+NOT NULL DEFAULT nextval\([^)]+\)', r'`\1` int NOT NULL AUTO_INCREMENT PRIMARY KEY', content)
    content = re.sub(r'"(\w+)"\s+int4', r'`\1` int', content)
    content = re.sub(r'"(\w+)"\s+int2', r'`\1` smallint', content)
    content = re.sub(r'"(\w+)"\s+int8', r'`\1` bigint', content)
    content = re.sub(r'"(\w+)"\s+varchar\((\d+)\)[^,\n]*', r'`\1` varchar(\2)', content)
    content = re.sub(r'"(\w+)"\s+text[^,\n]*', r'`\1` text', content)
    content = re.sub(r'"(\w+)"\s+timestamp\(6\)[^,\n]*', r'`\1` timestamp', content)
    content = re.sub(r'"(\w+)"\s+timestamp[^,\n]*', r'`\1` timestamp', content)
    content = re.sub(r'"(\w+)"\s+bool[^,\n]*', r'`\1` boolean', content)
    content = re.sub(r'"(\w+)"\s+numeric[^,\n]*', r'`\1` decimal(10,2)', content)
    content = re.sub(r'"(\w+)"\s+float[48][^,\n]*', r'`\1` float', content)
    content = re.sub(r'"(\w+)"\s+jsonb?[^,\n]*', r'`\1` json', content)
    
    # Handle any remaining quoted column names
    content = re.sub(r'"(\w+)"', r'`\1`', content)
    
    # Remove COLLATE clauses
    content = re.sub(r'\s+COLLATE\s+"[^"]+"\."[^"]+"', '', content)
    
    # Remove DEFAULT NULL::character varying and similar
    content = re.sub(r'\s+DEFAULT\s+NULL::[^,\n]+', ' DEFAULT NULL', content)
    content = re.sub(r'\s+DEFAULT\s+\'[^\']*\'::[^,\n]+', r'', content)
    
    # Replace PostgreSQL sequence nextval with AUTO_INCREMENT (handled above)
    
    # Handle constraints
    content = re.sub(r'CONSTRAINT\s+"([^"]+)"', r'CONSTRAINT `\1`', content)
    
    # Handle indexes
    content = re.sub(r'CREATE\s+(?:UNIQUE\s+)?INDEX\s+"([^"]+)"\s+ON\s+"public"\."(\w+)"\s+USING\s+\w+\s*\(([^)]+)\);', 
                    r'CREATE INDEX `\1` ON `\2` (\3);', content)
    
    # Handle INSERT statements
    content = re.sub(r'INSERT\s+INTO\s+"public"\."(\w+)"', r'INSERT INTO `\1`', content)
    
    # Replace PostgreSQL true/false with MySQL equivalents
    content = re.sub(r'\btrue\b', '1', content)
    content = re.sub(r'\bfalse\b', '0', content)
    
    # Handle ALTER TABLE statements
    content = re.sub(r'ALTER\s+TABLE\s+"public"\."(\w+)"', r'ALTER TABLE `\1`', content)
    content = re.sub(r'ADD\s+CONSTRAINT\s+"([^"]+)"', r'ADD CONSTRAINT `\1`', content)
    content = re.sub(r'FOREIGN\s+KEY\s+\("(\w+)"\)', r'FOREIGN KEY (`\1`)', content)
    content = re.sub(r'REFERENCES\s+"public"\."(\w+)"\s+\("(\w+)"\)', r'REFERENCES `\1` (`\2`)', content)
    
    # Replace ONLY keyword (PostgreSQL specific)
    content = re.sub(r'\bONLY\s+"public"\."(\w+)"', r'`\1`', content)
    content = re.sub(r'\bONLY\s+', '', content)
    
    # Handle PRIMARY KEY
    content = re.sub(r'ALTER\s+TABLE.*?ADD\s+CONSTRAINT\s+`[^`]+`\s+PRIMARY\s+KEY\s*\(([^)]+)\);', '', content)
    
    # Add PRIMARY KEY to CREATE TABLE
    lines = content.split('\n')
    new_lines = []
    in_create_table = False
    table_name = None
    primary_key_col = None
    
    for i, line in enumerate(lines):
        if 'CREATE TABLE' in line:
            in_create_table = True
            table_name = re.search(r'CREATE TABLE `(\w+)`', line)
            if table_name:
                table_name = table_name.group(1)
            new_lines.append(line)
        elif in_create_table:
            if 'AUTO_INCREMENT' in line:
                match = re.search(r'`(\w+)`.*AUTO_INCREMENT', line)
                if match:
                    primary_key_col = match.group(1)
            
            if ');' in line:
                # Don't add PRIMARY KEY again if it's already defined inline
                new_lines.append(') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;')
                in_create_table = False
                primary_key_col = None
            else:
                new_lines.append(line)
        else:
            # Skip ALTER TABLE ... ADD PRIMARY KEY statements as we've added them inline
            if 'ALTER TABLE' in line and 'PRIMARY KEY' in line:
                continue
            new_lines.append(line)
    
    content = '\n'.join(new_lines)
    
    # Add MySQL specific settings at the beginning
    mysql_header = """SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

"""
    
    mysql_footer = """
SET FOREIGN_KEY_CHECKS = 1;
"""
    
    # Remove PostgreSQL specific sequences
    content = re.sub(r'CREATE SEQUENCE[^;]+;', '', content)
    content = re.sub(r'ALTER SEQUENCE[^;]+;', '', content)
    content = re.sub(r'SELECT pg_catalog\.setval[^;]+;', '', content)
    
    # Final cleanup
    content = re.sub(r'\n\n+', '\n\n', content)
    
    final_content = mysql_header + content + mysql_footer
    
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write(final_content)
    
    print(f"Conversion complete! MySQL file saved to {output_file}")

if __name__ == "__main__":
    input_file = "/Users/user/Desktop/apps/tarlprathom_laravel/docs/schools.sql"
    output_file = "/Users/user/Desktop/apps/tarlprathom_laravel/docs/schools_mysql.sql"
    convert_postgres_to_mysql(input_file, output_file)