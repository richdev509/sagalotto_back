<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Page</title>
    <!-- Link to Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn i {
            margin-right: 10px; /* Space between icon and text */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Welcome Title -->
        <h1>Welcome</h1>
        
        <!-- Superviseur Button with User Icon -->
        <a href="superviseur/login" class="btn">
            <i class="fas fa-user-tie"></i> Superviseur
        </a>
        <!-- Directeur Button with Chart Line Icon -->
        <a href="login" class="btn">
            <i class="fas fa-chart-line"></i> Directeur
        </a>
    </div>
</body>
</html>