<!DOCTYPE html>
<html>
<head>
    <title>Reservatie Goedgekeurd</title>
</head>
<body>
    <p>Beste {{ $userName }},</p>
    <p>Jouw reservatie is goedgekeurd! Je kan deze afhalen aan het onthaal op {{ $reservationStartDate }} tussen 8u-12u en 13u-15u. U dient deze terug te brengen op {{ $reservationEndDate }} tussen 9u-16u.</p>
    <p>Overzicht van de reservatie:</p>

    <table style="border: none; width: 100%;">
        <tr style="border: none;">
            <td style="border: none; padding: 0;">
                <strong>Sportartikel:</strong>
            </td>
            <td style="border: none; padding: 0;">
                {{ $sportArticleName }}
            </td>
        </tr>
        <tr style="border: none;">
            <td style="border: none; padding: 0;">
                <strong>Aantal:</strong>
            </td>
            <td style="border: none; padding: 0;">
                {{ $count }}
            </td>
        </tr>
        <tr style="border: none;">
            <td style="border: none; padding: 0;">
                <strong>Reservatie Startdatum:</strong>
            </td>
            <td style="border: none; padding: 0;">
                {{ $reservationStartDate }}
            </td>
        </tr>
        <tr style="border: none;">
            <td style="border: none; padding: 0;">
                <strong>Reservatie Einddatum:</strong>
            </td>
            <td style="border: none; padding: 0;">
                {{ $reservationEndDate }}
            </td>
        </tr>
    </table>

    <p>Bedankt voor het gebruik van ons systeem!</p>

    <p>Met vriendelijke groeten,</p>
    <p>Sportinnovatiecampus Brugge</p>
</body>
</html>
