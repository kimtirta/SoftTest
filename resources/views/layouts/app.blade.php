<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title') - Library Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <nav class="bg-gray-800 p-4 text-white">
        <div class="container mx-auto flex justify-between">
            <a href="/" class="text-lg font-semibold">Library Management</a>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="mr-4">Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto py-8">
        @yield('content')
    </div>
</body>
</html>
