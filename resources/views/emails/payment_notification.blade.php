<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Submitted</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #e9f5ff;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid grey;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        .card-header {
            background-color: #17a2b8;
            color: white;
            padding: 10px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .card-header img {
            max-width: 80px;
            height: auto;
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin: 10px 0;
        }
        p, ul {
            color: #555;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        li:last-child {
            border-bottom: none;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 10px 20px;
            text-align: center;
            margin-top: 20px;
            border-top: 2px solid grey;
        }
        .contact-info p {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <img src="{{ asset('path/to/logo.png') }}" alt="Arfil's Landscaping Logo">
            <h1>Arfil's Landscaping Services</h1>
            <div class="contact-info">
                <p><i class="fas fa-map-marker-alt icon"></i> Zone 10, Carmen, Cagayan de Oro City</p>
                <p><i class="fas fa-phone-alt icon"></i> Contact: 09776912110</p>
                <p><i class="fas fa-envelope icon"></i> Email: <a href="mailto:arfilslandscaping@gmail.com">arfilslandscaping@gmail.com</a></p>
            </div>
        </div>

        <h1>Payment Submitted for Project {{ $project->id }}</h1>

        <p>Dear {{ $project->booking->user->name }},</p>

        <p>A payment of PHP {{ number_format($payment->amount, 2) }} has been submitted for your project.</p>
        
        <h3>Payment Details:</h3>
        <ul>
            <li><span>Payment Type:</span><span>{{ ucfirst($payment->payment_type) }}</span></li>
            <li><span>Payment Method:</span><span>{{ ucfirst($payment->payment_method) }}</span></li>
            <li><span>Amount:</span><span>PHP {{ number_format($payment->amount, 2) }}</span></li>
        </ul>

        <p>If you have any questions, feel free to contact us at <a href="mailto:support@arfil-landscaping.com">support@arfil-landscaping.com</a>.</p>

        <div class="footer">
            <p>Thank you for choosing Arfil's Landscaping Services!</p>
            <p>Best regards, <br> Arfil's Landscaping Services Team</p>
        </div>
    </div>
</body>
</html>
