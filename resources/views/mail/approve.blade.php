<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #555;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        a {
            color: white;
            text-decoration: none;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Selamat, Anda Diterima!</h1>
        <p>Halo <strong>{{ $applicant_name }}</strong>,</p>
        <p>Kami dengan senang hati menginformasikan bahwa Anda telah diterima sebagai bagian dari tim kami di
            <strong>{{ $company_name }}</strong>. Selamat bergabung! Kami sangat antusias untuk melihat kontribusi Anda
            dan bekerja sama dengan Anda.
        </p>
        <p><strong>Apa yang harus dilakukan selanjutnya?</strong></p>
        <p>Untuk memulai, silakan klik tombol di bawah ini untuk mendaftar akun di sistem kami.</p>
        <a href="http://127.0.0.1:8000/register-employee?company={{ $invitation_code }}" class="btn">
            <span style="color: white; font-size: 22px; padding: 20px 20px">Daftar Akun</span>
        </a>
        <div class="footer">
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami di <a style="text-decoration: none"
                    href="mailto:{{ $company_email }}">{{ $company_email }}</a>.</p>
            <p>Salam hangat,<br>
                <strong>{{ $company_name }}</strong><br>
        </div>
    </div>
</body>

</html>
