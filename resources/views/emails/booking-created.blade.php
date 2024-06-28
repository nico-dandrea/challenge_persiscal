<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        h2 {
            color: #007bff;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        p {
            margin-bottom: 15px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Confirmation</h1>

        <p>Dear {{ $customerName }},</p>

        <p>Thank you for choosing our hotel. Here are the details of your reservation:</p>

        <h2>Booking Information</h2>
        <ul>
            <li><strong>Number of Guests:</strong> {{ $numberOfPeople }}</li>
        </ul>

        <h2>Tour Information</h2>
        <ul>
            <li><strong>Tour Name:</strong> {{ $tourName }}</li>
        </ul>

        <h2>Hotel Information</h2>
        <ul>
            <li><strong>Hotel Name:</strong> {{ $hotelName }}</li>
        </ul>

        <p>We look forward to providing you with an exceptional stay. If you have any questions or need further assistance, please do not hesitate to contact us at <a href="mailto:{{ config('mail.reply_to.address') }}">{{ config('mail.reply_to.address') }}</a>.</p>

        <div class="footer">
            <p>Sincerely,<br>{{ config('mail.reply_to.name') }}</p>
        </div>
    </div>
</body>
</html>
