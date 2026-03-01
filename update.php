<?php
header('Content-Type: application/json');

include 'db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $position = isset($_POST['position']) ? trim($_POST['position']) : '';
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';
    $salary = isset($_POST['salary']) ? floatval($_POST['salary']) : 0;
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $hire_date = isset($_POST['hire_date']) ? trim($_POST['hire_date']) : '';

    // Validate input
    $errors = [];

    if (empty($id) || $id <= 0) {
        $errors[] = 'Invalid employee ID';
    }

    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }

    if (empty($position)) {
        $errors[] = 'Position is required';
    }

    if (empty($department)) {
        $errors[] = 'Department is required';
    }

    if ($salary <= 0) {
        $errors[] = 'Valid salary is required';
    }

    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    }

    if (empty($hire_date)) {
        $errors[] = 'Hire date is required';
    }

    // If there are validation errors
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
        exit;
    }

    try {
        // Check if employee exists
        $checkSql = "SELECT id FROM employees WHERE id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param('i', $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Employee not found'
            ]);
            $checkStmt->close();
            $conn->close();
            exit;
        }
        $checkStmt->close();

        // Check if email already exists for another employee
        $emailCheckSql = "SELECT id FROM employees WHERE email = ? AND id != ?";
        $emailStmt = $conn->prepare($emailCheckSql);
        $emailStmt->bind_param('si', $email, $id);
        $emailStmt->execute();
        $emailResult = $emailStmt->get_result();

        if ($emailResult->num_rows > 0) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'Email already exists for another employee'
            ]);
            $emailStmt->close();
            $conn->close();
            exit;
        }
        $emailStmt->close();

        // Update employee
        $updateSql = "UPDATE employees SET name = ?, email = ?, position = ?, department = ?, salary = ?, phone = ?, hire_date = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param('ssssdssi', $name, $email, $position, $department, $salary, $phone, $hire_date, $id);

        if ($updateStmt->execute()) {
            if ($updateStmt->affected_rows > 0) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Employee updated successfully!'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'No changes were made'
                ]);
            }
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . $conn->error
            ]);
        }
        $updateStmt->close();

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    } finally {
        $conn->close();
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}
?>
