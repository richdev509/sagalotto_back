@extends('admin-layout')

@section('content')
    <style>
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --light-purple: #f8f6ff;
            --white: #ffffff;
            --dark-text: #2d3436;
            --light-gray: #f5f5f5;
            --border-radius: 12px;
            --box-shadow: 0 8px 24px rgba(108, 92, 231, 0.12);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .settings-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 150px);
            padding: 2rem;
            background-color: var(--light-gray);
        }

        .settings-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            background: var(--white);
            transition: var(--transition);
            width: 100%;
            max-width: 800px;
            overflow: hidden;
        }

        .settings-card:hover {
            box-shadow: 0 12px 28px rgba(108, 92, 231, 0.15);
        }

        .input-group-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            font-weight: 500;
            min-width: 220px;
            justify-content: flex-start;
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.25rem;
            transition: var(--transition);
            background-color: var(--light-gray);
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.15);
            background-color: var(--white);
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 500;
            padding: 12px 30px;
            transition: var(--transition);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.85rem;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
        }

        .btn-gradient:active {
            transform: translateY(0);
        }

        .btn-gradient::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            opacity: 0;
            transition: var(--transition);
        }

        .btn-gradient:hover::after {
            opacity: 1;
        }

        .settings-header {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 15px;
            font-size: 1.5rem;
            text-align: center;
        }

        .settings-header::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 4px;
        }

        .card-body {
            padding: 2.5rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: var(--transition);
        }

        .input-group:hover {
            box-shadow: 0 4px 12px rgba(108, 92, 231, 0.1);
        }

        .checkbox-section {
            background: var(--light-purple);
            padding: 1.75rem;
            border-radius: var(--border-radius);
            margin: 2.5rem 0;
            border: 1px solid rgba(108, 92, 231, 0.1);
        }

        .checkbox-section h6 {
            color: var(--primary-color);
            margin-bottom: 1.25rem;
            font-weight: 600;
            font-size: 1.1rem;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }

        .checkbox-section h6::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 0;
            width: 40px;
            height: 2px;
            background: rgba(108, 92, 231, 0.3);
        }

        .form-check {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-top: 0;
            margin-right: 10px;
            cursor: pointer;
            border: 2px solid #dee2e6;
            transition: var(--transition);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            padding-left: 5px;
            color: var(--dark-text);
            cursor: pointer;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-check:hover .form-check-label {
            color: var(--primary-color);
        }

        .page-header {
            background-color: var(--white);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            font-size: 0.9rem;
        }

        .breadcrumb-item a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: var(--dark-text);
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">Paramèt Fich</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Paramèt</li>
            </ol>
        </nav>
    </div>

    <div class="settings-container">
        <div class="settings-card">
            <form method="POST" action="fich_update">
                @csrf
                <div class="card-body">
                    <h4 class="settings-header">Konfigirasyon Fich</h4>

                    <!-- Quantity Inputs -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text">Kantite bolet pa fich:</span>
                            <input type="number" name="qt_bolet" value="{{ $fich->qt_bolet }}" 
                                   class="form-control" placeholder="Antre 1-100" min="1" max="100">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text">Kantite maryaj pa fich:</span>
                            <input type="number" name="qt_maryaj" value="{{ $fich->qt_maryaj }}" 
                                   class="form-control" placeholder="Antre 1-1000" min="1" max="1000">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text">Kantite loto3 pa fich:</span>
                            <input type="number" name="qt_loto3" value="{{ $fich->qt_loto3 }}" 
                                   class="form-control" min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text">Kantite loto4 pa fich:</span>
                            <input type="number" name="qt_loto4" value="{{ $fich->qt_loto4 }}" 
                                   class="form-control" min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text">Kantite loto5 pa fich:</span>
                            <input type="number" name="qt_loto5" value="{{ $fich->qt_loto5 }}" 
                                   class="form-control" min="0">
                        </div>
                    </div>

                    <!-- Checkbox Section -->
                    <div class="checkbox-section">
                        <h6>Opsyon Afichaj</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" name="show_logo" class="form-check-input" id="show_logo" value="1" @if($fich->show_logo=='1') checked @endif>
                                    <label class="form-check-label" for="show_logo">Afiche Logo</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="show_address" class="form-check-input" id="show_address" value="1" @if($fich->show_address=='1') checked @endif>
                                    <label class="form-check-label" for="show_address">Afiche Adrès</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="show_phone" class="form-check-input" id="show_phone" value="1" @if($fich->show_phone=='1') checked @endif>
                                    <label class="form-check-label" for="show_phone">Afiche Telefòn</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" name="show_footer" class="form-check-input" id="show_footer" value="1" @if($fich->show_footer=='1') checked @endif>
                                    <label class="form-check-label" for="show_footer">Afiche Pied Fich</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="show_name" class="form-check-input" id="show_name" value="1" @if($fich->show_name=='1') checked @endif>
                                    <label class="form-check-label" for="show_name">Afiche nom bolet</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="show_mariage_price" class="form-check-input" id="show_mariage_price" value="1" @if($fich->show_mariage_price=='1') checked @endif>
                                    <label class="form-check-label" for="show_mariage_price">Afiche Pri Maryaj gratis</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-gradient">
                            <i class="mdi mdi-content-save mr-2"></i> Modifye Konfigirasyon
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop