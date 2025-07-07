{{--
--}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Akun Admin Baru</title>
</head>

<body>
    <p>Halo {{ $user->name }},</p>

    <p>Akun admin Anda sudah dibuat. Berikut detail login:</p>

    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>Silakan login dan ganti password Anda segera.</p>

    <p>Terima kasih.</p>
</body>

</html>