<!DOCTYPE html>
<html>
<head>
    <title>Reservatie Aanvraag</title>
</head>
<body>
    <p>Beste {{ $userName }},</p>
    <p>We hebben jouw reservatieaanvraag goed ontvangen! Houd je e-mail in de gaten om te zien of je reservatie wordt goedgekeurd:</p>

    <table>
        <tr>
            <th>Sportartikel</th>
            <td>{{ $sportArticleName }}</td>
        </tr>
        <tr>
            <th>Reservatie Startdatum</th>
            <td>{{ $reservationStartDate }}</td>
        </tr>
        <tr>
            <th>Reservatie Einddatum</th>
            <td>{{ $reservationEndDate }}</td>
        </tr>
    </table>

    <p>Bedankt voor het gebruik van ons systeem!</p>

    <p>Met vriendelijke groeten,</p>
    <p>Sportinnovatiecampus Brugge</p>
</body>
</html>
