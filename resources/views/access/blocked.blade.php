<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Restricted - {{ config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .blocked-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .blocked-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            width: 100%;
            margin: 20px;
        }

        .blocked-icon {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }

        .country-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
        }

        .btn-home {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body>
    <div class="blocked-container">
        <div class="blocked-card p-5 text-center">
            <div class="blocked-icon">
                <i class="fas fa-globe-americas"></i>
            </div>

            <h1 class="h2 mb-3 text-dark">Access Restricted</h1>
            <p class="text-muted mb-4">We're sorry, but our service is currently not available in your region.</p>

            <div class="country-info">
                <div class="row text-start">
                    <div class="col-sm-6">
                        <strong>Your Location:</strong>
                        <p class="mb-0 text-muted">{{ $country }}</p>
                    </div>
                    <div class="col-sm-6">
                        <strong>IP Address:</strong>
                        <p class="mb-0 text-muted">{{ $ip }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="text-dark">Available Regions:</h5>
                <div class="row text-start">
                    <div class="col-md-6">
                        <ul class="list-unstyled small text-muted">
                            <li><i class="fas fa-check text-success me-2"></i>North America</li>
                            <li><i class="fas fa-check text-success me-2"></i>South America</li>
                            <li><i class="fas fa-check text-success me-2"></i>Europe</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled small text-muted">
                            <li><i class="fas fa-check text-success me-2"></i>Australia & Oceania</li>
                            <li><i class="fas fa-check text-success me-2"></i>India</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                <p class="small text-muted mb-0">
                    If you believe this is an error, please contact our support team.
                </p>

                <div>
                    <button onclick="window.location.reload()" class="btn btn-home text-white me-2">
                        <i class="fas fa-redo me-2"></i>Try Again
                    </button>
                    <a href="mailto:support@{{ request()->getHost() }}" class="btn btn-outline-secondary">
                        <i class="fas fa-envelope me-2"></i>Contact Support
                    </a>
                </div>
            </div>

            <hr class="my-4">

            <div class="text-center">
                <small class="text-muted">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
