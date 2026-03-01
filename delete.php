<?php
include 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo 'error';
    $conn->close();
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
        echo 'error';
        $checkStmt->close();
        $conn->close();
        exit;
    }
    $checkStmt->close();

    // Delete employee
    $deleteSql = "DELETE FROM employees WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param('i', $id);

    if ($deleteStmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $deleteStmt->close();

} catch (Exception $e) {
    echo 'error';
} finally {
    $conn->close();
}
?>
