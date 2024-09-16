<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Status Aplikasi: Ditolak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #e74c3c;
        }

        p {
            margin-bottom: 16px;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <p>Yth. {{ $applicantName }},</p>

        <p>Dengan berat hati kami informasikan bahwa aplikasi Anda di {{ $companyName }} telah ditolak.</p>

        <p>Terima kasih atas minat Anda dan kami berharap yang terbaik untuk usaha Anda di masa depan.</p>

        <p>Salam hormat,<br>
            {{ $companyName }}</p>

        <div class="footer">
            &copy; {{ date('Y') }} {{ $companyName }}. Semua hak dilindungi.
        </div>
    </div>
</body>

</html>
