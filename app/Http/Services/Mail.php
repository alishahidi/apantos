<?php

namespace App\Http\Services;

use System\Config\Config;
use System\Notify\Notify;

class Mail
{
    // ex
    public static function sendTemplateMail($to, $link, $title, $subtitle, $img)
    {
        $template = '
        <!DOCTYPE html>
        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width,initial-scale=1">
                <meta name="x-apple-disable-message-reformatting">
                <title></title>
                <!--[if mso]>
                <noscript>
                    <xml>
                    <o:OfficeDocumentSettings>
                        <o:PixelsPerInch>96</o:PixelsPerInch>
                    </o:OfficeDocumentSettings>
                    </xml>
                </noscript>
                <![endif]-->
                <style>
                    * {
                        direction: rtl;
                        text-align: right;
                        word-wrap: break-word;
                        margin: 0 auto;
                        font-family: Roboto;
                    }
                </style>
            </head>
            <body style="margin:0;padding:0;" dir="rtl">
                <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                    <tr>
                    <td align="center" style="padding:0;">
                        <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                        <tr>
                            <td align="center" style="background:#70bbd9; background-image: url('. asset($img) .');height: 250px;width: 100%;background-position: center;background-size: cover;background-repeat: no-repeat;">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                <td style="padding:0 0 36px 0;color:#153643;">
                                    <h1 style="font-size:24px;margin:0 0 20px 0;">'. $title .'</h1>
                                    <p style="margin:0 0 12px 0;font-size:14.5px;line-height:24px;">'. $subtitle .'</p>
                                    <p style="margin:0;font-size:14.5px;line-height:24px;"><a href="' . $link . '" style="color:#ee4c50;text-decoration:underline;">فعالسازی اکانت کاربری</a></p>
                                </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:30px;background:#333;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;">
                                <tr>
                                <td style="padding:0;width:50%;" align="left">
                                    <p style="margin:0;font-size:14px;line-height:14.5px;color:#ffffff;text-align:center;">
                                    ' . Config::get("app.APP_TITLE") . '
                                    </p>
                                </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                </table>
            </body>
        </html>
        ';

        Notify::sendMail($to, $title . "-" . Config::get("app.APP_TITLE"), $template);
    }
}
