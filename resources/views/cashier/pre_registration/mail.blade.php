<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>AC School Information System</title>
        <style type="text/css">
            body {
                width:100% !important;
                -webkit-text-size-adjust:100%;
                -ms-text-size-adjust:100%;
                margin:0;
                padding:0;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            #backgroundTable {
                margin:0;
                padding:0;
                width:100% !important;
                line-height: 100% !important;
            }
            img {
                outline:none;
                text-decoration:none;
                -ms-interpolation-mode: bicubic;
            } 
            a img {
                border:none;
            }
            p {
                margin: 1em 0;
            }
  
            h1, h2, h3, h4, h5, h6 {color: black !important;}
            h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: black;}
            h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {color: black;}
            h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {color: black;}
  
            table td {border-collapse: collapse;}
            table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

            a {color: #3498db;}
            p.domain a{color: black;}

            hr {border: 0; background-color: #d8d8d8; margin: 0; margin-bottom: 0; height: 1px;}
        </style>
    </head>
    
    <body style="width:100% !important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;">
    <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="margin:0; padding:0; width:100% !important; line-height: 100% !important; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" width="100%">
        <tr>
            <td width="10" valign="top">&nbsp;</td>
            <td valign="top" align="center">
                <table cellpadding="0" cellspacing="0" border="0" align="center" style="width: 100%; max-width: 600px; background-color: #FFF; border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;" id="contentTable">
                    <tr>
                        <td width="600" valign="top" align="center" style="border-collapse:collapse;">
                            <table align='center' border='0' cellpadding='0' cellspacing='0' style='border: 1px solid #E0E4E8;' width='100%'>
                                <tr>
                                    <td align='left' style='padding: 56px 56px 28px 56px;' valign='top'>
                                        <div style='font-family: "lato", "Helvetica Neue", Helvetica, Arial, sans-serif; line-height: 28px;font-size: 18px; color: #333;font-weight:bold;'>Hello {{$applicant_details->firstname}}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align='left' style='padding: 0 56px 28px 56px;' valign='top'>
                                        <div style='font-family: "lato", "Helvetica Neue", Helvetica, Arial, sans-serif; line-height: 28px;font-size: 18px; color: #333 !important;'>
                                            <span style="color: #333">

                                            Greetings of Peace!<br><br>
                                            
                                            We have received your payment for your daughter's application & test fee. This serves as your acknowledgment receipt.<br><br>
                                            
                                                <?php $payment_details = \App\Payment::where('reference_id', $reference_id)->first(); ?>
                                                <table border="1">
                                                    <tr>
                                                        <td><strong>Date</strong></td>
                                                        <td>{{$payment_details->transaction_date}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Receipt Number</strong></td>
                                                        <td>{{$payment_details->receipt_no}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Name</strong></td>
                                                        <td>{{$payment_details->paid_by}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Description</strong></td>
                                                        <td>Application & Testing Fee</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Amount</strong></td>
                                                        <td>{{$payment_details->check_amount+$payment_details->cash_amount+$payment_details->credit_card_amount+$payment_details->deposit_amount}}</td>
                                                    </tr>
                                                </table><br><br>
                                                
                                                To complete the application, you may now use the username and password assigned to you to access the <a href="https://portal.assumption.edu.ph/">Assumption College Student Portal.</a><br><br>
                                                
                                                <small>
                                                    Username:&nbsp;<strong>{{$applicant_details->idno}}</strong><br>
                                                    Password:&nbsp;<strong>{{$six_number}}</strong></small><br><br>
                                                    <?php $date = strtotime($payment_details->transaction_date); ?>
                                                    <?php $date = strtotime("+14 days", $date); ?>
                                                   
                                                    Kindly submit the complete requirement indicated on the page to the Admission's Office not later than {{date("F j, Y",$date)}}. You will be receiving an e-mail indicating your test and interview schedule.<br><br>
                                                    
                                                    <strong>Note:</strong> Failure to submit your requirements on the given date will temporarily end your access to the Assumption College Student Portal until the requirements are completed.<br><br>
                                                    
                                                    Thank you very much.<br><br>

                                                If you have questions kindly call Admission's Office(8817-0757).
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table align='center' border='0' cellpadding='0' cellspacing='0' width='100%'>
<!--                                <tr>
                                    <td align='center' style='padding: 30px 56px 28px 56px;' valign='middle'>
                                        <span style='font-family: "lato", "Helvetica Neue", Helvetica, Arial, sans-serif; line-height: 28px;font-size: 16px; color: #A7ADB5; vertical-align: middle;'>If this email doesn't make any sense, please <a href="mailto:acmakatibedrecords@yahoo.com.ph">let us know</a>!</span>
                                    </td>
                                </tr>-->
                                <tr>
                                    <td align='center' style='padding: 0 56px 28px 56px;' valign='middle'>
                                        <a style="border: 0;" href="https://www.assumption.edu.ph/">
                                            <img alt="Assumption College - San Lorenzo" width="70" height="70" style="vertical-align: middle;" src="http://ac-apps.assumption.edu.ph/images/assumption-logo.png"/>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="10" valign="top">&nbsp;</td>
        </tr>
    </table>
  </body>
</html>