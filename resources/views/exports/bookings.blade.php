<table>
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Number of People</th>
            <th>Booking Date</th>
            <th>Tour Name</th>
            <th>Tour Start Date - Tour End Date</th>
            <th>Hotel Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->customer_name }}</td>
            <td>{{ $booking->customer_email }}</td>
            <td>{{ $booking->number_of_people }}</td>
            <td>{{ $booking->booking_date }}</td>
            <td>{{ $booking->tour->name }}</td>
            <td>{{ $booking->tour->start_date }} - {{ $booking->tour->end_date }}</td>
            <td>{{ $booking->hotel_name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>