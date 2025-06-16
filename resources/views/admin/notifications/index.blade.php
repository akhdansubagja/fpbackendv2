<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; padding: 2rem; }
        .container { max-width: 900px; margin: auto; background: white; padding: 2rem; border-radius: 8px; }
        .notification-list { list-style: none; padding: 0; }
        .notification-item { border: 1px solid #eee; padding: 1rem; margin-bottom: 1rem; border-radius: 5px; }
        .notification-item h4 { margin-top: 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notifikasi</h1>
        <a href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
        <hr>
        <ul class="notification-list">
            @forelse ($notifications as $notification)
                <li class="notification-item">
                    <h4>{{ $notification->title }}</h4>
                    <p>{{ $notification->message }}</p>
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                </li>
            @empty
                <li>Tidak ada notifikasi baru.</li>
            @endforelse
        </ul>
    </div>
</body>
</html>