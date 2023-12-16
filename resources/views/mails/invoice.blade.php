<!DOCTYPE html>
<html>
    <body>
        <p>Dear {{ $dataForMail['names'] }} </p>
        <h3>Succesful Subscription</h3>

        <p>Thank you for subscribing to our premium plan</p>
        <p>This is a notification on successful payment for your premium subscription, please find email attachment to view and download your receipt</p>
        <p></p>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="border-radius: 2px;" bgcolor="#46166b">
                                <a href="{{$dataForMail['hosted_invoice_url']}}" target="_blank" style="padding: 8px 12px; border: 1px solid #46166B;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
                                    Download             
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
