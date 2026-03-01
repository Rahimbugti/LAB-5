<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        header p {
            color: #666;
            font-size: 1.1em;
        }

        /* Alert Styles */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .alert-close {
            float: right;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            color: inherit;
            opacity: 0.7;
        }

        .alert-close:hover {
            opacity: 1;
        }

        .main-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .toolbar h2 {
            color: #333;
            font-size: 1.8em;
        }

        .btn-add {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn-add:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }

        tbody tr {
            transition: background-color 0.2s;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .btn-edit {
            background-color: #007bff;
            color: white;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 1.1em;
        }

        @media (max-width: 768px) {
            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-add {
                width: 100%;
                text-align: center;
            }

            table {
                font-size: 0.9em;
            }

            th, td {
                padding: 10px;
            }

            .action-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>👔 Employee Management System</h1>
            <p>Manage and update employee information efficiently</p>
        </header>

        <!-- Alert Messages -->
        <div id="successAlert" class="alert alert-success">
            <span class="alert-close" onclick="closeAlert(this)">&times;</span>
            <strong>Success!</strong> <span id="successMessage"></span>
        </div>

        <div id="errorAlert" class="alert alert-error">
            <span class="alert-close" onclick="closeAlert(this)">&times;</span>
            <strong>Error!</strong> <span id="errorMessage"></span>
        </div>

        <div id="warningAlert" class="alert alert-warning">
            <span class="alert-close" onclick="closeAlert(this)">&times;</span>
            <strong>Warning!</strong> <span id="warningMessage"></span>
        </div>

        <div id="infoAlert" class="alert alert-info">
            <span class="alert-close" onclick="closeAlert(this)">&times;</span>
            <strong>Info:</strong> <span id="infoMessage"></span>
        </div>

        <div class="main-content">
            <div class="toolbar">
                <h2>Employees</h2>
                <a href="#" class="btn-add">+ Add New Employee</a>
            </div>

            <?php
            include 'db.php';

            // Fetch all employees
            $sql = "SELECT * FROM employees ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Position</th><th>Department</th><th>Salary</th><th>Phone</th><th>Hire Date</th><th>Actions</th></tr></thead>";
                echo "<tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['position']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                    echo "<td>$" . number_format($row['salary'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['hire_date']) . "</td>";
                    echo "<td>";
                    echo "<div class='action-buttons'>";
                    echo "<a href='edit.php?id=" . $row['id'] . "' class='btn btn-edit'>Edit</a>";
                    echo "<a href='javascript:deleteEmployee(" . $row['id'] . ")' class='btn btn-delete'>Delete</a>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='no-data'>No employees found. <a href='#' style='color: #007bff; text-decoration: underline;'>Add one now</a></div>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        // Show alerts based on URL parameters
        function showAlert() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const message = urlParams.get('message');

            if (status === 'success') {
                showSuccessAlert(message || 'Operation completed successfully!');
            } else if (status === 'error') {
                showErrorAlert(message || 'An error occurred. Please try again.');
            } else if (status === 'warning') {
                showWarningAlert(message || 'Please check your input.');
            } else if (status === 'info') {
                showInfoAlert(message || 'Information updated.');
            }
        }

        function showSuccessAlert(message) {
            const alert = document.getElementById('successAlert');
            document.getElementById('successMessage').textContent = message;
            alert.classList.add('show');
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }

        function showErrorAlert(message) {
            const alert = document.getElementById('errorAlert');
            document.getElementById('errorMessage').textContent = message;
            alert.classList.add('show');
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }

        function showWarningAlert(message) {
            const alert = document.getElementById('warningAlert');
            document.getElementById('warningMessage').textContent = message;
            alert.classList.add('show');
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }

        function showInfoAlert(message) {
            const alert = document.getElementById('infoAlert');
            document.getElementById('infoMessage').textContent = message;
            alert.classList.add('show');
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }

        function closeAlert(element) {
            element.parentElement.classList.remove('show');
        }

        function deleteEmployee(id) {
            if (confirm('Are you sure you want to delete this employee?')) {
                fetch('delete.php?id=' + id, { method: 'GET' })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            window.location.href = 'index.php?status=success&message=Employee deleted successfully!';
                        } else {
                            window.location.href = 'index.php?status=error&message=Failed to delete employee!';
                        }
                    })
                    .catch(error => {
                        window.location.href = 'index.php?status=error&message=An error occurred!';
                    });
            }
        }

        // Show alert on page load if status parameter exists
        window.addEventListener('load', showAlert);
    </script>
</body>
</html>
