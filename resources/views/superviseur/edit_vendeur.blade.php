@extends('superviseur.admin-layout')

@section('content')
    <style>
        .page-header {
            margin-bottom: 25px;
        }
        
        .page-header h3 {
            color: #333;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .card-description{
            font-weight: bold;
            color: #333;
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .form-content {
            background: white;
            border-radius: 10px;
            padding: 25px;
        }
        
        .form-group label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px 15px;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        
        .form-control:focus {
            border-color: #4B49AC;
            box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.15);
        }
        
        .form-control:disabled {
            background-color: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
        }
        
        .btn-submit {
            background: #4B49AC;
            border: none;
            padding: 10px 30px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 5px;
            color: white;
            transition: all 0.2s ease;
        }
        
        .btn-submit:hover {
            background: #3d3a8c;
            box-shadow: 0 2px 8px rgba(75, 73, 172, 0.3);
        }
        
        .icon-input {
            position: relative;
        }
        
        .icon-input i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4B49AC;
            font-size: 16px;
        }
        
        .icon-input .form-control {
            padding-left: 45px;
        }
        
        .section-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: #4B49AC;
            border-radius: 5px;
            margin-right: 8px;
            color: white;
            font-size: 14px;
        }
        
        .error {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }
        
        .vendor-info-header {
            background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%);
            padding: 15px 25px;
            margin: -30px -30px 25px -30px;
            border-radius: 10px 10px 0 0;
        }
        
        .vendor-code-display {
            color: white;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .vendor-code-display i {
            font-size: 24px;
        }
        
        .vendor-code-display strong {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 15px;
            border-radius: 6px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .percent-info-box {
            background: #e8f5e9;
            border-left: 4px solid #28a745;
            padding: 12px 15px;
            border-radius: 5px;
            margin-top: 8px;
            font-size: 13px;
            color: #155724;
        }
        
        .percent-info-box i {
            margin-right: 8px;
        }
        
        .percent-warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            border-radius: 5px;
            margin-top: 8px;
            font-size: 13px;
            color: #856404;
        }
        
        .percent-warning-box i {
            margin-right: 8px;
        }
        
        .form-check {
            padding-left: 0;
        }
        
        .form-check-input {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            cursor: pointer;
        }
        
        .form-check-label {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
            cursor: pointer;
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">Modifye Vandè</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Modifye Vandè</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card form-card">
                <div class="card-body form-content">
                    <div class="vendor-info-header">
                        <div class="vendor-code-display">
                            <i class="mdi mdi-barcode"></i>
                            <span>Kòd Vandè:</span>
                            <strong>{{ $vendeur->code }}</strong>
                        </div>
                    </div>
                    
                    <form class="form-sample" method="post" action="sup_update-vendeur">
                        @csrf
                        <p class="card-description">
                            <span class="section-icon">
                                <i class="mdi mdi-information"></i>
                            </span>
                            Info Sou Vandè A
                        </p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tout Non Vandè A</label>
                                    <div class="col-sm-9 icon-input">
                                        <input type="hidden" name="id" value="{{ $vendeur->id }}" class="form-control" />
                                        <i class="mdi mdi-account"></i>
                                        <input type="text" name="name" value="{{ $vendeur->name }}" class="form-control" placeholder="Non konplè" />
                                        <span class="error">@error('name') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Adrès Vandè A</label>
                                    <div class="col-sm-9 icon-input">
                                        <i class="mdi mdi-map-marker"></i>
                                        <input type="text" name="address" value="{{ $vendeur->address }}" class="form-control" placeholder="Adrès vandè a" />
                                        <span class="error">@error('address') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Sèks</label>
                                    <div class="col-sm-9 icon-input">
                                        <i class="mdi mdi-gender-male-female"></i>
                                        <select class="form-control" name="gender">
                                            <option selected>{{ $vendeur->gender }}</option>
                                            @if ($vendeur->gender == 'Fi')
                                                <option>Gason</option>
                                            @else
                                                <option>Fi</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Telefòn Vandè A</label>
                                    <div class="col-sm-9 icon-input">
                                        <i class="mdi mdi-phone"></i>
                                        <input type="text" class="form-control" value="{{ $vendeur->phone }}" placeholder="Nimewo telefòn" name="phone" />
                                        <span class="error">@error('phone') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Pousantaj Sou Vant</label>
                                    <div class="col-sm-9 icon-input">
                                        <i class="mdi mdi-percent"></i>
                                        @if($branch && $branch->percent_agent_only == 0)
                                            <input type="number" class="form-control" value="{{ $vendeur->percent }}" placeholder="Pousantaj" name="percent" min="0" max="{{ $branch->percent }}" step="0.01" id="percentInput"/>
                                            <div class="percent-info-box">
                                                <i class="mdi mdi-information"></i>
                                                Ou ka ajiste pousantaj la jiska maksimòm {{ $branch->percent }}%
                                            </div>
                                        @else
                                            <input type="number" class="form-control" value="{{ $vendeur->percent }}" placeholder="Pousantaj" name="percent" disabled/>
                                            <div class="percent-warning-box">
                                                <i class="mdi mdi-lock"></i>
                                                Pousantaj la pa ka modifye (Branch konfigire pou sipèvizè sèlman)
                                            </div>
                                        @endif
                                        <span class="error">@error('percent') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        
                        @if($branch && $branch->percent_agent_only == 0)
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const percentInput = document.getElementById('percentInput');
                                const maxPercent = {{ $branch->percent }};
                                
                                percentInput.addEventListener('input', function() {
                                    if (parseFloat(this.value) > maxPercent) {
                                        this.value = maxPercent;
                                        alert('Pousantaj la pa ka depase ' + maxPercent + '%');
                                    }
                                    if (parseFloat(this.value) < 0) {
                                        this.value = 0;
                                    }
                                });
                            });
                        </script>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" @if ($vendeur->is_block == '1') @checked(true) @endif class="form-check-input" name="block" value="1"> Bloke
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="card-description">
                            <span class="section-icon">
                                <i class="mdi mdi-cellphone"></i>
                            </span>
                            Info Bank
                        </p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Non Bank Lan</label>
                                    <div class="col-sm-9 icon-input">
                                        <i class="mdi mdi-store"></i>
                                        <input type="text" name="bank_name" value="{{ $vendeur->bank_name }}" class="form-control" placeholder="Egzanp: bank1" disabled />
                                        <span class="error">@error('bank_name') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">ID Machi Nan</label>
                                    <div class="col-sm-9 icon-input">
                                        <i class="mdi mdi-cellphone-android"></i>
                                        <input type="text" name="bank_id" value="{{ $vendeur->android_id }}" class="form-control" disabled />
                                        <span class="error">@error('bank_id') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Itilizatè</label>
                                    <div class="col-sm-9 icon-input">
                                        <i class="mdi mdi-account-key"></i>
                                        <input type="text" name="username" value="{{ $vendeur->username }}" class="form-control" disabled />
                                        <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 5px;">
                                            <i class="mdi mdi-information-outline"></i> Non itilizatè pa ka chanje
                                        </small>
                                        <span class="error">@error('username') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Modpas</label>
                                    <div class="col-sm-9 icon-input">
                                        <i class="mdi mdi-lock"></i>
                                        <input type="password" name="password" class="form-control" placeholder="Kite vid si w pa vle chanje" />
                                        <span class="error">@error('password') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-center" style="margin-top: 30px;">
                                <button type="submit" class="btn-submit">
                                    <i class="mdi mdi-check-circle"></i>
                                    Modifye Vandè A
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection