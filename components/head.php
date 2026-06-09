<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'RestoMaju') ?></title>
    <link rel="shortcut icon" href="/resto/assets/img/resto.ico" type="image/x-icon">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" href="/resto/assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .blur-background {
            background-image: url('./assets/img/resto-bg.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0) !important;
            }
            #sidebar-overlay {
                display: none !important;
            }
        }
    </style>
</head>
