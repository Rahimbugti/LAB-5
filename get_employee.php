<?php
header('Content-Type: application/json');

include 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid employee ID'
    ]);
    $conn->close();
    exit;
}

try {
    $sql = "SELECT id, name, email, position, department, salary, phone, hire_date FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'employee' => $employee
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Employee not found'
        ]);
    }
    $stmt->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
} finally {
    $conn->close();
}
?>
