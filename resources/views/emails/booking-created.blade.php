<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
    <h1>Booking Confirmation</h1>

    <p>Dear {{ $customerName }},</p>

    <p>Thank you for your booking. Here are the details of your reservation:</p>

    <h2>Booking Information</h2>
    <ul>
        <li><strong>Number of People:</strong> {{ $numberOfPeople }}</li>
        <li><strong>Booking Date:</strong> {{ $bookingDate }}</li>
    </ul>

    <h2>Tour Information</h2>
    <ul>
        <li><strong>Tour Name:</strong> {{ $tour['name'] }}</li>
        <li><strong>Tour Price:</strong> ${{ number_format($tour['price'], 2) }}</li>
        <li><strong>Start Date:</strong> {{ $tour['startDate'] }}</li>
        <li><strong>End Date:</strong> {{ $tour['endDate'] }}</li>
    </ul>

    <h2>Hotel Information</h2>
    <ul>
        <li><strong>Hotel Name:</strong> {{ $hotel['name'] }}</li>
        <li><strong>Price Per Night:</strong> ${{ number_format($hotel['pricePerNight'], 2) }}</li>
        <li><strong>Address:</strong> {{ $hotel['address'] }}</li>
    </ul>

    <p>We look forward to providing you with an excellent experience. If you have any questions or need further assistance, please do not hesitate to contact us at {{ config('mail.reply_to.address') }}.</p>

    <p>Sincerely,<br>{{ config('mail.reply_to.name') }}</p>
</body>
</html>
