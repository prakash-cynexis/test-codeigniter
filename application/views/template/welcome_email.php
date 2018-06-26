<!doctype html>
<html>
<head>
    <title>Email</title>
    <style>
        .btn-primary table td:hover {
            background-color: #34495e !important;
        }

        .btn-primary a:hover {
            background-color: #34495e !important;
            border-color: #34495e !important;
        }

        .p_style {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
            margin: 0 0 15px;
        }

        .content {
            padding: 10px;
        }
    </style>
</head>
<body style="background: #c1c7c5">
<div class="content">
    <table style="padding: 3px; width: 100%; background: #ffffff; border-radius: 3px;">
        <tr>
            <td>
                <p class="p_style">Dear {{name}},</p>
                <p class="p_style">Please click on the link below to verify your email address:</p>
                <p class="p_style">{{action_url}}</p>
                <p class="p_style">
                    Thanks again,<br/>
                    <?= COMPANY_NAME ?>
                </p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>