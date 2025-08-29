@extends('admin-layout')

@section('content')
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --white: #ffffff;
            --gray: #6c757d;
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: var(--white);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0;
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            border: 1px solid #e0e6ed;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            height: calc(1.5em + 0.75rem + 2px);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .btn {
            font-weight: 500;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #3a56d5;
            border-color: #3750c7;
        }

        .btn-warning {
            background-color: var(--warning);
            border-color: var(--warning);
            color: var(--white);
        }

        .btn-warning:hover {
            background-color: #e68a19;
            border-color: #db8317;
            color: var(--white);
        }

        .btn-danger {
            background-color: var(--danger);
            border-color: var(--danger);
            color: var(--white);
        }

        .btn-danger:hover {
            background-color: #e5177b;
            border-color: #d91572;
            color: var(--white);
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.15em;
        }

        .form-check-label {
            margin-left: 0.5rem;
            font-weight: 500;
        }

        .text-danger {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(67, 97, 238, 0.2);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            overflow-y: auto;
        }

        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }

        .modal-content {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 0;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
        }

        /* Custom grid for form layout */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        /* Switch button styles */
        .switch-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .form-switch {
            padding-left: 0;
            margin-right: 1rem;
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-right: 0.5em;
        }

        .no-pay-btn {
            min-width: 220px;
        }

        @media (max-width: 576px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }

            .switch-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .no-pay-btn {
                width: 100%;
                min-width: auto;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="page-header pb-3">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-account-edit"></i>
                </span> Modifye Vandè
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifye Vandè</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form-sample" method="post" action="editervendeur">
                            @csrf
                            <input type="hidden" name="id" value="{{ $vendeur->id }}" />

                            <h5 class="section-title">Enfòmasyon Pèsonèl</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Non Konplè</label>
                                    <input type="text" name="name" value="{{ $vendeur->name }}" class="form-control"
                                        placeholder="Antre non konplè vandè a" />
                                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Adrès</label>
                                    <input type="text" name="address" value="{{ $vendeur->address }}" class="form-control"
                                        placeholder="Antre adrès vandè a" />
                                    @error('address') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Seks</label>
                                    <select class="form-control" name="gender">
                                        <option selected>{{ $vendeur->gender }}</option>
                                        @if ($vendeur->gender == 'Fi')
                                            <option>Gason</option>
                                        @else
                                            <option>Fi</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Telefòn</label>
                                    <input type="text" class="form-control" value="{{ $vendeur->phone }}"
                                        placeholder="Antre nimewo telefòn nan" name="phone" />
                                    @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <h5 class="section-title mt-4">Enfòmasyon Pwopriyetè</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Pousantaj sou vant</label>
                                    <input type="number" class="form-control" value="{{ $vendeur->percent }}"
                                        placeholder="Egzanp: 10" name="percent" />
                                    @error('percent') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Branch</label>
                                    <select class="form-control" name="branch">
                                        @foreach ($branch as $row)
                                            @if ($row->id == $vendeur->branch_id)
                                                <option value="{{ $row->id }}" selected>{{ $row->name }}</option>
                                            @else
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <div class="switch-container row">
                                        <div class="form-check form-switch col-5" style="margin-left: 50px;">
                                            <input class="form-check-input" type="checkbox" role="switch" id="blockCheck"
                                                name="block" value="1" @if ($vendeur->is_block == '1') checked @endif>
                                            <label class="form-check-label fw-bold" for="blockCheck">
                                                @if ($vendeur->is_block == '1')
                                                    <span class="text-danger">OFF</span> (Vandè a bloke)
                                                @else
                                                    <span class="text-success">ON</span> (Vandè a aktif)
                                                @endif
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <button style="background-color: var(--primary); padding: 7px;min-width: 250px;" type="button"
                                                class="btn btn-warning no-pay-btn" id="noPayBtn" onclick="openModal()">
                                                <input class="form-check-input" style="margin-right: 5px;" type="checkbox" @if($rules_vendeur) checked @endif readonly> M pap peye sou branch la
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="section-title mt-4">Enfòmasyon Bankè</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Non Bank</label>
                                    <input type="text" name="bank_name" value="{{ $vendeur->bank_name }}"
                                        class="form-control" placeholder="Egzanp: Sogebank" />
                                    @error('bank_name') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">ID Machin</label>
                                    <input type="text" name="bank_id" value="{{ $vendeur->android_id }}"
                                        class="form-control" />
                                    @error('bank_id') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <h5 class="section-title mt-4">Enfòmasyon Kont</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Itilizatè(username)</label>
                                    <input type="text" name="username" value="{{ $vendeur->username }}" class="form-control"
                                        disabled />
                                    @error('username') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nouvo Modpas(password)</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Si w vle modifye modpas la" />
                                    @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="action-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-1"></i> Anrejistre
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-light">
                                    <i class="mdi mdi-arrow-left me-1"></i> Retounen
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HTML for the Modal -->
    <div id="customModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3>Pri gagnan Lo</h3>
                <span class="custom-close-btn">&times;</span>
            </div>
            <div class="custom-modal-body">
                <p class="modal-description">Ajiste pri lo yo pou {{$vendeur->bank_name}}</p>

                <form class="price-form" action="{{ route('updateprilovendeur') }}" method="POST">
                    @csrf

                    <!-- Bolet Prices Section -->
                    <div class="price-section">
                        <div class="section-header">
                            <h4>Pri Bolet</h4>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="price-row">
                                <div class="price-group">
                                    @if($rules_vendeur)
                                      <input type="hidden" name="id" value="{{ $rules_vendeur->id }}" />
                                    @else
                                      <input type="hidden" name="user_id" value="{{$vendeur->id}}" />
                                    @endif
                                    <label>Pri 1e lo</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix" class="price-select" required>
                                            @if(isset($rules_vendeur->prix))
                                                <option id="prix1">{{ $rules_vendeur->prix }}</option>
                                            @else
                                                <option value="">null</option>
                                            @endif
                                            <option value="60">60</option>
                                            <option value="50">50</option>
                                            <option value="55">55</option>
                                            <option value="65">65</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri 2e lo</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_second" class="price-select" required>
                                            @if(isset($rules_vendeur->prix_second))
                                                <option id="prix2">{{ $rules_vendeur->prix_second }}</option>
                                            @else
                                                <option value="">null</option>
                                            @endif
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="15">15</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri 3e lo</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_third" class="price-select" required>
                                            @if(isset($rules_vendeur->prix_third))
                                                <option id="prix3">{{ $rules_vendeur->prix_third }}</option>
                                            @else
                                                <option value="">null</option>
                                            @endif
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loto Prices Section -->
                    <div class="price-section">
                        <div class="section-header">
                            <h4>Pri maryaj ak loto</h4>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="price-row">
                                <div class="price-group">
                                    <label>Pri maryaj</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_maryaj" class="price-select" required>
                                            @if(isset($rules_vendeur->prix_maryaj))
                                                <option id="prix_maryaj">{{ $rules_vendeur->prix_maryaj }}</option>
                                            @else
                                                <option id="prix_maryaj" value="">null</option>
                                            @endif
                                            <option value="1000">1000</option>
                                            <option value="1250">1250</option>
                                            <option value="1500">1500</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri Loto3</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_loto3" class="price-select" required>
                                            @if(isset($rules_vendeur->prix_loto3))
                                                <option id="prix_loto3">{{ $rules_vendeur->prix_loto3 }}</option>
                                             @else
                                                <option id="prix_loto3" value="">null</option>
                                            @endif
                                            <option value="500">500</option>
                                            <option value="650">650</option>
                                            <option value="700">700</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri loto4</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_loto4" class="price-select" required>
                                            @if(isset($rules_vendeur->prix_loto4))
                                                <option id="prix_loto4">{{ $rules_vendeur->prix_loto4 }}</option>
                                            @else
                                                <option id="prix_loto4" value="">null</option>
                                            @endif
                                            <option value="5000">5000</option>
                                            <option value="6000">6000</option>
                                            <option value="7500">7500</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri loto5</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_loto5" class="price-select" required>
                                            @if(isset($rules_vendeur->prix_loto5))
                                                <option id="prix_loto5">{{ $rules_vendeur->prix_loto5 }}</option>
                                            @else
                                                <option id="prix_loto5" value="">null</option>
                                            @endif
                                            <option value="25000">25000</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                        <!-- Loto Prices Section -->
                    <div class="price-section">
                        <div class="section-header">
                            <h4>Pri maryaj gratis</h4>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="price-row">
                                <div class="price-group">
                                    <label>Pri maryaj gratis</label>
                                    <div class="input-group">
                                        <select name="prix_maryaj_gratis" class="price-select" required>
                                            @if(isset($rules_vendeur->prix_maryaj_gratis))
                                                <option id="prix_maryaj">{{ $rules_vendeur->prix_maryaj_gratis }}</option>
                                            @else
                                                <option id="prix_maryaj" value="">null</option>
                                            @endif
                                            <option value="1000">1000</option>
                                            <option value="1500">1500</option>
                                            <option value="2000">2000</option>
                                            <option value="2500">2500</option>
                                            <option value="3000">3000</option>
                                            <option value="3500">3500</option>
                                            <option value="4000">4000</option>
                                            <option value="4500">4500</option>
                                            <option value="5000">5000</option>
                                            <option value="5500">5500</option>
                                            <option value="6000">6000</option>

                                            
                                        </select>
                                    </div>
                                </div>

                               
       
                            </div>
                        </div>
                    </div>
                    <!-- Gabel Prices Section -->
                    <div class="price-section">
                        <div class="section-header">
                            <div class="section-title">
                                <h4>Pri Gabel</h4>
                                <label class="toggle-switch">
                                    <input type="checkbox" id="toggleGabel" name="gabel_statut" value="1">
                                    <span class="slider"></span>
                                    <span class="toggle-label">Active</span>
                                </label>
                            </div>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="price-row">
                                <div class="price-group">
                                    <label>Pri gabel 1e</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_gabel1" class="price-select" id="prix_gabel1_select">
                                            <option id="prix_gabel1">null</option>
                                            <option value="1000">20</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri gabel 2e</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_gabel2" class="price-select" id="prix_gabel2_select">
                                            <option id="prix_gabel2">null</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="cancel-btn">Fèmen</button>
                        @if(isset($rules_vendeur->id))
                           <a href="deleteprilo_vendeur/{{ $rules_vendeur->id }}" class="btn btn-danger">
                                <i class="mdi mdi-delete me-1"></i> Efase tout pri lo
                            </a>
                        @endif
                        <button type="submit" class="submit-btn">Mete a jou</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CSS Styling -->
    <style>
        /* Modal Styles */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow-y: auto;
        }

        .custom-modal-content {
            background-color: white;
            margin: 5% auto;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            animation: modalOpen 0.3s ease-out;
        }

        @keyframes modalOpen {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .custom-modal-header {
            padding: 16px 24px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px 8px 0 0;
        }

        .custom-modal-header h3 {
            margin: 0;
            color: #4361ee;
        }

        .custom-close-btn {
            font-size: 24px;
            cursor: pointer;
            color: #6c757d;
        }

        .custom-close-btn:hover {
            color: #495057;
        }

        .custom-modal-body {
            padding: 24px;
        }

        .modal-description {
            color: #6c757d;
            margin-bottom: 20px;
        }

        /* Form Styles */
        .price-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .price-section {
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
        }

        .section-header {
            padding: 12px 16px;
            background-color: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .section-header h4 {
            margin: 0;
            color: #212529;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toggle-icon {
            font-size: 14px;
            transition: transform 0.2s;
        }

        .section-content {
            padding: 16px;
            background-color: white;
        }

        .price-row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .price-group {
            flex: 1;
            min-width: 200px;
        }

        .price-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #495057;
        }

        .input-group {
            display: flex;
            align-items: center;
            border: 1px solid #ced4da;
            border-radius: 4px;
            overflow: hidden;
        }

        .input-label {
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-right: 1px solid #ced4da;
            font-size: 14px;
        }

        .current-price {
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-right: 1px solid #ced4da;
            font-size: 14px;
            min-width: 40px;
            text-align: center;
        }

        .price-select {
            flex: 1;
            padding: 8px 12px;
            border: none;
            outline: none;
            background-color: white;
            color: #dc3545;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: relative;
            cursor: pointer;
            width: 50px;
            height: 24px;
            background-color: #ced4da;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4361ee;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        .toggle-label {
            font-size: 14px;
            color: #495057;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .cancel-btn {
            padding: 10px 20px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 4px;
            color: #495057;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background-color: #e9ecef;
        }

        .submit-btn {
            padding: 10px 20px;
            background-color: #4361ee;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #3a56d5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .custom-modal-content {
                width: 90%;
                margin: 10% auto;
            }

            .price-row {
                flex-direction: column;
                gap: 12px;
            }

            .price-group {
                min-width: 100%;
            }
        }
    </style>

    <!-- JavaScript -->
    <script>
        // Modal functionality
        const modal = document.getElementById('customModal');
        const closeBtn = document.querySelector('.custom-close-btn');
        const cancelBtn = document.querySelector('.cancel-btn');

        // Function to open modal
        function openModal() {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        // Function to close modal
        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Toggle sections
        document.querySelectorAll('.section-header').forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                const icon = header.querySelector('.toggle-icon');

                if (content.style.display === 'none') {
                    content.style.display = 'block';
                    icon.textContent = '▼';
                } else {
                    content.style.display = 'none';
                    icon.textContent = '▶';
                }
            });
        });

        // Initialize all sections as open
        document.querySelectorAll('.section-content').forEach(content => {
            content.style.display = 'block';
        });

        // Close modal when clicking X or cancel button
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // To open this modal from your button:
        // document.getElementById('noPayBtn').addEventListener('click', openModal);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize modal
            const noPayModal = new bootstrap.Modal(document.getElementById('noPayModal'));

            // Show modal when button is clicked
            document.getElementById('noPayBtn').addEventListener('click', function () {
                noPayModal.show();
            });

            // Handle confirmation
            document.getElementById('confirmNoPay').addEventListener('click', function () {
                // Here you would typically make an AJAX call
                alert('Aksyon anrejistre! Vandè a p ap ka touche lajan sou branch la.');
                noPayModal.hide();

                // Example AJAX implementation:
                /*
                fetch('/api/disable-payments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        vendeur_id: {{ $vendeur->id }}
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert('Ere: ' + data.message);
                }
                noPayModal.hide();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Yon erè fèt pandan pwosesis la');
                noPayModal.hide();
            });
                    */
                });

        const blockCheck = document.getElementById('blockCheck');
        const blockLabel = document.querySelector('label[for="blockCheck"]');

        blockCheck.addEventListener('change', function () {
            if (this.checked) {
                blockLabel.innerHTML = '<span class="text-danger">OFF</span> (Vandè a bloke)';
            } else {
                blockLabel.innerHTML = '<span class="text-success">ON</span> (Vandè a aktif)';
            }
        });
            });
    </script>
@endsection