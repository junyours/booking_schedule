<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Your head content here -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Your custom CSS here */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: normal;
            background: #e9f0f4;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
        }
        .sticky-footer {
            background-color: #ffffff;
            text-align: center;
            padding: 10px;
            width: 100%;
            height: 50px; /* Ensure the footer has a fixed height */
            font-size: 14px;
            border-top: 1px solid #ddd; /* Optional: Add a border for better visibility */
            position: relative;
        }
    </style>
</head>
<body>
    <div class="content">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="sticky-footer">
        <div class="container">
            <div class="copyright text-center">
                <span>Copyright © Arfil's Landscaping Services</span>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @yield('scripts')
</body>
</html>
