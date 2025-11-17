<!-- resources/views/emails/user_created.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Account Created</title>
    <style>
        .button {
            background-color: #007bff; /* Primary button color */
            color: white; /* Button text color */
            padding: 10px 20px; /* Padding */
            text-decoration: none; /* Remove underline */
            border-radius: 5px; /* Rounded corners */
            font-weight: bold; /* Bold text */
            display: inline-block; /* Inline block to respect padding */
        }
        .button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <h1>Admin Account Created</h1>
    <p>Hello there Admin, your account has been created successfully.</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>

</body>
</html>
