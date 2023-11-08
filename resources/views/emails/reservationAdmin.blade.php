<!DOCTYPE html>
<html>
<head>
    <title>Nieuwe Reservatie Aanvraag</title>
</head>
<body>
    <p>Beste,</p>
    <p>Er is een nieuwe reservatieaanvraag toegekomen! Bevestig of annuleer deze aanvraag in de backoffice:</p>

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

    <p>Met vriendelijke groeten,</p>
    <p>Sportinnovatiecampus Brugge Systeem</p>
</body>
</html>
