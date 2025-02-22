<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Konekte Sagalotto</title>
    @notifyCss
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
    <style>
        /* Modern color palette */
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --background-color: #f8f9fa;
            --text-color: #2d3436;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
        }

        .auth {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .auth-form-light {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .brand-logo img {
            width: 80px;
            height: auto;
        }

        .brand-logo h4 {
            margin-top: 10px;
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(108, 92, 231, 0.2);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: opacity 0.3s;
        }

        .btn-gradient-primary:hover {
            opacity: 0.9;
        }

        .auth-link {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .auth-link:hover {
            color: var(--secondary-color);
        }

        .form-check-label {
            font-weight: 500;
            color: var(--text-color);
        }

        .form-check-input {
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .auth-form-light {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img src="assets/images/logo.png" alt="Sagalotto Logo">
                                <h4>Sagalotto</h4>
                            </div>
                            <h6 class="font-weight-light">Konekte pouw kontinye.</h6>
                            <form class="pt-3" method="POST" action="login">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Itilizatè" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="modepas" required>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn loa" type="submit">KONEKTE</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input"> Kenbem konekte pou lontan
                                        </label>
                                    </div>
                                    <a href="/contact" class="auth-link text-black">Ou bliye modepas ou? Kontakte nou</a>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    Ou bezwen sistèm pou bolet ou? <a href="/contact" class="text-primary">Ranpli fom nan</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <x-notify::notify />
    @notifyJs
</body>
</html>