@extends('admin-layout')

@section('content')
    <style>
        /* Modern color palette */
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --success-color: #00b894;
            --danger-color: #d63031;
            --background-color: #f8f9fa;
            --text-color: #2d3436;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 20px;
        }

        .card-description {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 1.5rem;
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

        .form-label {
            font-weight: 600;
            color: var(--text-color);
        }

        .error {
            color: var(--danger-color);
            font-size: 12px;
            margin-top: 5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: opacity 0.3s;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .form-check-label {
            font-weight: 500;
            color: var(--text-color);
        }

        .form-check-input {
            margin-right: 10px;
        }

        .icon {
            font-size: 18px;
            color: var(--primary-color);
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .col-sm-9 {
                padding-left: 0;
            }

            .col-sm-3 {
                padding-right: 0;
            }
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-plus"></i>
            </span> Fom ajoute vandè
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Vandè</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-sample" method="post" action="ajouterVendeur">
                        @csrf
                        <p class="card-description">Info sou vandè a</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tout non vandè a</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="non konplè" />
                                        <span class="error">@error('name') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Adrès vandè a</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="address" value="{{ old('address') }}" class="form-control" placeholder="adrès vandè a" />
                                        <span class="error">@error('address') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Seks</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="gender">
                                            <option>Fi</option>
                                            <option>Gason</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Telefon vandè a</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" value="{{ old('phone') }}" placeholder="Telefon vandè a" name="phone" />
                                        <span class="error">@error('phone') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Pousantaj sou vant</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" value="{{ old('percent') }}" placeholder="pousantaj" name="percent" />
                                        <span class="error">@error('percent') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Branch</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="branch">
                                            @foreach ($branch as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-9 offset-sm-3">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="block" value="1"> Bloke
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="card-description">Bank info</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Non bank lan</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="form-control" placeholder="Egzanp: bank1" />
                                        <span class="error">@error('bank_name') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">ID machin nan</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="bank_id" value="{{ old('bank_id') }}" placeholder="id machin kap konekte a" />
                                        <span class="error">@error('bank_id') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Itilizatè</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="470000" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Modepas</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" value="{{ old('password') }}" name="password" placeholder="" />
                                        <span class="error">@error('password') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Anrejistre
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection