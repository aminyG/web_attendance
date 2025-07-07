<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Halo {{ $user->name }},</h2>
        <p>Anda telah didaftarkan sebagai Admin di Sistem Absensi.</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Password:</strong> {{ $password }}</p>
        <p>Silakan login dan segera ubah password Anda.</p>
        <p>Terima kasih,<br>Super Admin</p>
    </div>
</body>

</html>