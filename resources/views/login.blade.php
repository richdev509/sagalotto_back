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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
    <style>
        /* Modern color palette */
        :root {
            --primary-color: #6c5ce7;
            --primary-light: #a29bfe;
            --secondary-color: #00cec9;
            --background-color: #f8f9fa;
            --text-color: #2d3436;
            --light-gray: #dfe6e9;
            --border-radius: 12px;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
            background-image: radial-gradient(circle at 10% 20%, rgba(108, 92, 231, 0.05) 0%, rgba(108, 92, 231, 0.05) 90%);
        }

        .auth {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .auth-form-light {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .auth-form-light:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo img {
            width: 90px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .brand-logo:hover img {
            transform: scale(1.05);
        }

        .brand-logo h4 {
            margin-top: 15px;
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-control {
            border: 2px solid var(--light-gray);
            border-radius: var(--border-radius);
            padding: 12px 20px;
            font-size: 15px;
            transition: all 0.3s ease;
            height: 50px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--primary-color);
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--secondary-color);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: var(--border-radius);
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
            height: 50px;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 92, 231, 0.4);
        }

        .btn-gradient-primary:active {
            transform: translateY(0);
        }

        .btn-gradient-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-gradient-primary:hover::after {
            opacity: 1;
        }

        .auth-link {
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .auth-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .form-check-label {
            font-weight: 500;
            color: var(--text-color);
            cursor: pointer;
        }

        .form-check-input {
            margin-right: 10px;
            cursor: pointer;
            width: 18px;
            height: 18px;
            border: 2px solid var(--light-gray);
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.2);
        }

        .font-weight-light {
            font-weight: 400 !important;
            color: #636e72;
        }

        .text-primary {
            color: var(--primary-color) !important;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .auth-form-light {
                padding: 30px;
                margin: 20px;
            }
            
            .brand-logo h4 {
                font-size: 24px;
            }
        }

        /* Loading animation for submit button */
        .loa.loading {
            position: relative;
            color: transparent;
        }

        .loa.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
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
                                <h4>Sagaloto</h4>
                            </div>
                            <h6 class="font-weight-light">Konekte pouw kontinye.</h6>
                            <form class="pt-3" method="POST" action="login">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Itilizatè" required>
                                    <i class="mdi mdi-account-outline" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #a29bfe;"></i>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="modepas" required>
                                    <i class="mdi mdi-eye-off-outline password-toggle" id="togglePassword"></i>
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
                                    <a href="/contact" class="auth-link">Ou bliye modepas ou? Kontakte nou</a>
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
    <script>
        // Password toggle functionality
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#exampleInputPassword1');
        
        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            this.classList.toggle('mdi-eye-off-outline');
            this.classList.toggle('mdi-eye-outline');
        });

        // Add loading state to submit button
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('.loa');
        
        form.addEventListener('submit', function() {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });
    </script>
    <x-notify::notify />
    @notifyJs
</body>
</html>