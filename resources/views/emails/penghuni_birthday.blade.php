<!DOCTYPE html>
<html>
<head>
    <title>Selamat Ulang Tahun!</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .footer { margin-top: 20px; text-align: center; font-size: 0.8em; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Ulang Tahun, {{ $penghuni->nama }}!</h1>
        </div>
        
        <div class="content">
            <p>Kami dari manajemen mengucapkan selamat ulang tahun yang ke-{{ Carbon\Carbon::parse($penghuni->tanggal_lahir)->age + 1 }}.</p>
            
            <p>Semoga hari ini menjadi hari yang istimewa dan membawa kebahagiaan untuk Anda!</p>
            
            <p>Terima kasih telah menjadi bagian dari komunitas kami.</p>
        </div>
        
        <div class="footer">
            <p>Hormat kami,</p>
            <p>Tim Manajemen</p>
            <p>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>