<?php
// Database connection file
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS employee_db";
if ($conn->query($sql) === TRUE) {
    // Database created or already exists
}

// Select the database
$conn->select_db($dbname);

// Create employees table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    position VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    hire_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$conn->query($sql);

// Insert sample data if table is empty
$result = $conn->query("SELECT COUNT(*) as count FROM employees");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $sample_data = [
        "INSERT INTO employees (name, email, position, department, salary, phone, hire_date) VALUES ('John Doe', 'john@example.com', 'Developer', 'IT', 60000, '1234567890', '2020-01-15')",
        "INSERT INTO employees (name, email, position, department, salary, phone, hire_date) VALUES ('Jane Smith', 'jane@example.com', 'Manager', 'HR', 75000, '0987654321', '2019-03-20')",
        "INSERT INTO employees (name, email, position, department, salary, phone, hire_date) VALUES ('Mike Johnson', 'mike@example.com', 'Designer', 'Design', 55000, '5555666666', '2021-06-10')",
        "INSERT INTO employees (name, email, position, department, salary, phone, hire_date) VALUES ('Sarah Williams', 'sarah@example.com', 'Analyst', 'Finance', 65000, '4444333333', '2020-11-05')"
    ];
    
    foreach ($sample_data as $insert) {
        $conn->query($insert);
    }
}
?>
