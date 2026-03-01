<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
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
            max-width: 600px;
            margin: 50px auto;
        }

        .form-wrapper {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            font-size: 0.95em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="tel"],
        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus,
        input[type="tel"]:focus,
        input[type="date"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }

        .error {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            font-size: 1.1em;
            color: #666;
        }

        .loading::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }

        @keyframes dots {
            0%, 20% {
                content: '';
            }
            40% {
                content: '.';
            }
            60% {
                content: '..';
            }
            80%, 100% {
                content: '...';
            }
        }

        .form-wrapper.hidden {
            display: none;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px auto;
            }

            .form-wrapper {
                padding: 20px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="loadingForm" class="form-wrapper">
            <div class="loading">Loading employee data</div>
        </div>

        <form id="editForm" class="form-wrapper hidden" method="POST" action="update.php">
            <div class="form-header">
                <h1>✏️ Edit Employee</h1>
                <p>Update the employee information below</p>
            </div>

            <input type="hidden" id="employeeId" name="id" value="">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
                <div class="error" id="nameError"></div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
                <div class="error" id="emailError"></div>
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" id="position" name="position" required>
                <div class="error" id="positionError"></div>
            </div>

            <div class="form-group">
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="">-- Select Department --</option>
                    <option value="IT">IT</option>
                    <option value="HR">HR</option>
                    <option value="Finance">Finance</option>
                    <option value="Design">Design</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Sales">Sales</option>
                </select>
                <div class="error" id="departmentError"></div>
            </div>

            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="number" id="salary" name="salary" step="0.01" required>
                <div class="error" id="salaryError"></div>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
                <div class="error" id="phoneError"></div>
            </div>

            <div class="form-group">
                <label for="hireDate">Hire Date</label>
                <input type="date" id="hireDate" name="hire_date" required>
                <div class="error" id="hireDateError"></div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-submit">Update Employee</button>
                <a href="index.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // Get ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const employeeId = urlParams.get('id');

        // Load employee data
        async function loadEmployeeData() {
            if (!employeeId) {
                document.getElementById('loadingForm').innerHTML = '<div style="color: #dc3545; padding: 40px; text-align: center;">Error: Employee ID not provided!</div>';
                return;
            }

            try {
                const response = await fetch('get_employee.php?id=' + employeeId);
                const data = await response.json();

                if (data.success) {
                    const employee = data.employee;
                    document.getElementById('employeeId').value = employee.id;
                    document.getElementById('name').value = employee.name;
                    document.getElementById('email').value = employee.email;
                    document.getElementById('position').value = employee.position;
                    document.getElementById('department').value = employee.department;
                    document.getElementById('salary').value = employee.salary;
                    document.getElementById('phone').value = employee.phone;
                    document.getElementById('hireDate').value = employee.hire_date;

                    document.getElementById('loadingForm').classList.add('hidden');
                    document.getElementById('editForm').classList.remove('hidden');
                } else {
                    document.getElementById('loadingForm').innerHTML = '<div style="color: #dc3545; padding: 40px; text-align: center;">Error: ' + (data.message || 'Employee not found!') + '</div>';
                }
            } catch (error) {
                document.getElementById('loadingForm').innerHTML = '<div style="color: #dc3545; padding: 40px; text-align: center;">Error: Failed to load employee data!</div>';
            }
        }

        // Handle form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = form.querySelector('.btn-submit');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Updating...';
            submitBtn.disabled = true;

            fetch('update.php', {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php?status=success&message=' + encodeURIComponent(data.message);
                } else {
                    window.location.href = 'index.php?status=error&message=' + encodeURIComponent(data.message);
                }
            })
            .catch(error => {
                window.location.href = 'index.php?status=error&message=An error occurred while updating!';
            });
        });

        // Load data on page load
        window.addEventListener('load', loadEmployeeData);
    </script>
</body>
</html>
