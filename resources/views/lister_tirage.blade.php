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

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table tbody tr {
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .table tbody td {
            padding: 15px;
            border: none;
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

        .form-check-label {
            font-weight: 500;
            color: var(--text-color);
        }

        .form-check-input {
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-calendar-multiple"></i>
            </span> Lis tiraj yo: {{ $tirage->count() }}
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
            </ol>
        </nav>
    </div>

    <div class="col-lg-12 stretch-card" style="margin: 10px;">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kòd</th>
                                <th>Non</th>
                                <th>Lè ouvri</th>
                                <th>Lè fèmen</th>
                                <th>Lè tire</th>
                                <th>Aktif</th>
                                <th>Aksyon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tirage as $row)
                                <form method="post" action="editertirage">
                                    @csrf
                                    <tr class="table-info">
                                        <input type="hidden" name="id" value="{{ $row->id }}" />
                                        <td>{{ $row->id }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>
                                            <input type="time" class="form-control" value="{{ $row->hour_open }}" name="time_open" />
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" value="{{ $row->hour }}" name="time" />
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" value="{{ $row->hour_tirer }}" name="time_tirer" readonly />
                                        </td>
                                        <td>
                                            <label>
                                                <input type="checkbox" @if ($row->is_active == '1') @checked(true) @endif class="form-check-input" name="active" value="1" id="membershipRadios2"> Aktif
                                            </label>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-gradient-primary me-2">
                                                <i class="mdi mdi-pencil"></i> Modifye
                                            </button>
                                        </td>
                                    </tr>
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection