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
                <i class="mdi mdi-calendar-plus"></i>
            </span> Fom ajoute tiraj
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tiraj</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-sample" method="post" action="ajouterTirage">
                        @csrf
                        <p class="card-description">Info sou tiraj la</p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tiraj</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="tirage" style="height: 40px;">
                                            @foreach ($tirage as $row)
                                                <option>{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error">@error('tirage') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Lè wap ouvril lan</label>
                                    <div class="col-sm-9">
                                        <input type="time" class="form-control" value="00:00:00" name="time_open" />
                                        <span class="error">@error('time_open') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Lè wap fèmenl lan</label>
                                    <div class="col-sm-9">
                                        <input type="time" class="form-control" value="{{ old('time') }}" name="time" />
                                        <span class="error">@error('time') {{ $message }} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Lè li tire a</label>
                                    <div class="col-sm-9">
                                        <input type="time" class="form-control" value="{{ old('time_tirer') }}" name="time_tirer" />
                                        <span class="error">@error('time_tirer') {{ $message }} @enderror</span>
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