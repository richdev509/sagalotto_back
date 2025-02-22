@extends('superviseur.admin-layout')

@section('content')
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d2a95;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #0d2a95;
            color: white;
            font-weight: bold;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-primary {
            background-color: #0d2a95;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1a3bb0;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .search-wrapper {
            margin: 5px;
        }

        .search-input {
            position: relative;
            max-width: 300px;
            margin: auto;
        }

        input[type="text"] {
            width: calc(100% - -9px);
            padding: 8px;
            padding-left: 35px;
            outline: none;
            border: 2px solid #ccc;
            border-radius: 50px;
        }

        input[type="text"]:focus {
            border: 2px solid rgb(146, 146, 146) !important;
        }

        #suggestions {
            list-style: none;
            padding: 0;
            display: none;
            position: absolute;
            background-color: white;
            width: 100%;
            border: 1px solid #ccc;
            z-index: 1;
        }

        #suggestions a {
            display: block;
            padding: 8px;
            border-bottom: 1px solid #eee;
            text-decoration: none;
            color: #333;
        }

        #suggestions a:last-child {
            border-bottom: none;
        }

        input::placeholder {
            color: rgb(146, 146, 146);
        }

        .error-message {
            display: none;
            color: red;
            margin-top: 5px;
        }

        .cancel-button {
            position: absolute;
            top: 19px;
            right: 0px;
            transform: translateY(-50%);
            cursor: pointer;
            display: none;
        }

        .search-icon {
            position: absolute;
            top: 8px;
            left: 10px;
        }

        .reglement {
            border: 1px solid #dc61e7;
            border-radius: 5px;
        }

        .btn_finpeye {
            width: auto;
            font-size: 15px;
            padding: 5px;
        }

        dialog {
            padding: 1rem 3rem;
            background: white;
            max-width: 400px;
            padding-top: 2rem;
            border-radius: 20px;
            border: 0;
            box-shadow: 0 5px 30px 0 rgb(0 0 0 / 10%);
        }

        dialog .x {
            filter: grayscale(1);
            border: none;
            background: none;
            position: absolute;
            top: 15px;
            right: 10px;
            transition: ease filter, transform 0.3s;
            cursor: pointer;
            transform-origin: center;
        }

        dialog .x:hover {
            filter: grayscale(0);
            transform: scale(1.1);
        }

        dialog h2 {
            font-weight: 600;
            font-size: 2rem;
            padding-bottom: 1rem;
        }

        dialog p {
            font-size: 1rem;
            line-height: 1.3rem;
            padding: 0.5rem 0;
        }

        dialog a {
            color: rgb(var(--vs-primary));
        }

        dialog a:visited {
            color: rgb(var(--vs-primary));
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Rapo general pou chak bank</h4>
                    <form method="get" action="sup_rapport2" id="search">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Komanse</label>
                                    <input type="date" class="form-control" name="date_debut" value="{{ $date_debut }}" required />
                                </div>
                                <div class="form-group">
                                    <label>Fini</label>
                                    <input type="date" class="form-control" name="date_fin" value="{{ $date_fin }}" required />
                                </div>
                                <div class="form-group">
                                    <label>Peryod</label>
                                    <select class="form-control" name="period" style="height: 47px; border: 1px solid black;">
                                        <option value="tout">Tout</option>
                                        <option value="matin">Matin (12H AM - 2H30 PM)</option>
                                        <option value="soir">Soir (2H31 PM - 11H59 PM)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="student-submit">
                                <button type="submit" class="btn btn-primary">Rapo</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped" id="myRapport">
                            <thead>
                                <tr>
                                    <th>Bank</th>
                                    <th>Dat</th>
                                    <th>Peryod</th>
                                    <th>Vant</th>
                                    <th>Pedi</th>
                                    <th>Komisyon</th>
                                    <th>Balans</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $vent = 0;
                                @endphp
                                @forelse ($vendeur as $row)
                                    <tr>
                                        <td>{{ DB::table('users')->where('id', $row['bank_name'])->value('bank_name') }}</td>
                                        <td>{{ $date_debut }} => {{ $date_fin }}</td>
                                        <td>{{ $period }}</td>
                                        <td>{{ $row['vente'] }} HTG</td>
                                        <td>{{ $row['perte'] }} HTG</td>
                                        <td>{{ $row['commission'] }} HTG</td>
                                        @php
                                            $total += $row['vente'] - ($row['commission'] + $row['perte']);
                                            $vent += $row['vente'];
                                        @endphp
                                        <td style="color: {{ $row['vente'] < $row['commission'] + $row['perte'] ? 'red' : 'green' }};">
                                            {{ $row['vente'] - ($row['commission'] + $row['perte']) }} HTG
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center">Aucune donnée disponible</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><button id="exportJPG" class="btn btn-primary">Telechaje</button></td>
                                    <td class="text-right" colspan="6">Total: 
                                        <span style="color: {{ $total > 0 ? 'green' : 'red' }};">{{ $total }} HTG</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6">% Superviseur: 
                                        <span style="color: green;">{{ $vent * (session('percent')) / 100 }} HTG</span>
                                    </td>
                                    <td class="text-right" colspan="6">Balans: 
                                        <span style="color: {{ $total - $vent * (session('percent') / 100) > 0 ? 'green' : 'red' }};">
                                            {{ $total - $vent * (session('percent') / 100) }} HTG
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.3.2/dist/html2canvas.min.js"></script>
    <script>
        $(document).ready(function() {
            // Export to Image
            document.getElementById('exportJPG').addEventListener('click', function() {
                html2canvas(document.getElementById('myRapport')).then(function(canvas) {
                    var link = document.createElement('a');
                    link.href = canvas.toDataURL('image/jpeg');
                    link.download = 'rapport_vendeur.jpg';
                    link.click();
                });
            });
        });
    </script>
@endsection