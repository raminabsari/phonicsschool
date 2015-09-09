<?php
/*
 * Copy this file into
 *
 *     wp-content/extensions/newsletter/subscription
 *
 * and RENAME it to email.php.
 *
 * It will be used insted of the standard email.php file to generate the body of
 * confirmation and welcome emails.
 *
 * A globally available $message variable contains the generated message as resulted
 * by merging your configured message (on subscription steps panel) and the user's
 * data.
 *
 */
?>
    <html>

    <head>
        <style>
            body {
                color: #666;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>

    <body style="font-family:sans-serif;font-size:12px">

        <table cellpadding="0" cellspacing="0" class="email-subject" align="center" style="background: #fff none repeat scroll 0 0;margin:8% auto;width:550px;">
            <tr>
                <th style="background:#fff none repeat scroll 0 0;color:#fff;padding:5px 23px;text-align:center;">
                    <img src="http://www.enablinggenius.com/new-phonicsschool/wp-content/uploads/2015/08/ps-logo-stacked-shadow-338x180.png" />
                </th>
            </tr>
            <tr>
                <td style="background:#fff none repeat scroll 0 0;border:1px solid #e8e8e8;color:#666;padding:50px 23px;">
                    <?php echo $message; ?>
                </td>
            </tr>
        </table>

    </body>

    </html>