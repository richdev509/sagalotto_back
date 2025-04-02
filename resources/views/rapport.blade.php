@extends('admin-layout')


@section('content')
    <style type="text/css">
        .content {
            position: fixed;
            margin-top: 100px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: 400px;
            text-align: center;
            background-color: white;
            border: 1px solid blueviolet;
            box-sizing: border-box;
            padding: 20px;
            z-index: 100;
            display: none;
            overflow-y: scroll;
            overflow-x: scroll;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        #search div {
            display: inline-block;
        }

        .row_head {
            border: 1px solid blueviolet;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .content-clear td {
            font-size: 11px;
        }

        .close-btn {
            position: absolute;
            right: 20px;
            top: 15px;
            background-color: black;
            color: white;
            border-radius: 50%;
            padding: 4px;
            width: 26px;
            cursor: pointer;
        }

        h1 {
            text-align: center;
            font-family: Tahoma, Arial, sans-serif;
            color: #06D85F;
            margin: 80px 0;
        }

        .box {
            width: 40%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.2);
            padding: 35px;
            border: 2px solid #fff;
            border-radius: 20px/50px;
            background-clip: padding-box;
            text-align: center;
        }

        .button {
            font-size: 1em;
            padding: 10px;
            color: gray;
            border-radius: 20px/50px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease-out;
        }

        .overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            transition: opacity 500ms;
            visibility: hidden;
            opacity: 0;
        }

        .overlay:target {
            visibility: visible;
            opacity: 1;
        }

        .popup {
            margin: 200px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            width: 50%;
            position: relative;
            transition: all 5s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .popup h2 {
            margin-top: 0;
            color: #333;
            font-family: Tahoma, Arial, sans-serif;
        }

        .popup .close {
            position: absolute;
            top: 20px;
            right: 30px;
            transition: all 200ms;
            font-size: 30px;
            font-weight: bold;
            text-decoration: none;
            color: #333;
        }

        .popup .close:hover {
            color: black;
        }

        .popup .content {
            max-height: auto;
            overflow: auto;
        }

        @media screen and (max-width: 700px) {
            .box {
                width: 70%;
            }

            .popup {
                width: 70%;
            }
        }

        /* Enhanced Form Styling */
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .form-control {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 8px;
            width: 100%;
            margin-bottom: 10px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(45deg, #0056b3, #003d80);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
    <div class="page-header">
        <h3 class="page-title">Rapo</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Rapo</li>
            </ol>
        </nav>
    </div>

    <div class="row" style="margin: 10px; border-style: ridge; border-width: 1px; border-color: rgb(209, 163, 252);">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <form method="get" action="rapport" id="search">
                        @csrf
                        <div class="col-12 col-md-5">
                            <label for="dateFilter">komanse</label>
                            <input type="date" class="form-control dateInput" <?php if (isset($_GET['date_debut'])) {?>
                                value="{{$_GET["date_debut"]}}" <?php } ?> requiered name="date_debut" required />

                            <label for="dateFilter" style="margin-top: 10px;">Fini</label>
                            <input type="date" class="form-control dateInput" name="date_fin" <?php if (isset($_GET['date_fin'])) {?> value="{{$_GET["date_fin"]}}" <?php } ?> requiered
                                required />
                        </div>

                        <div class="col-12 col-md-5">
                            <label for="dateFilter">Bank</label>
                            <select class="form-control" name="bank" value="{{ old('bank') }}" style="height: 33px;">
                                <option>Tout</option>
                                @foreach ($vendeur as $row)
                                    <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                                @endforeach
                            </select>

                            <label for="dateFilter" style="margin-top: 10px;">Tiraj</label>
                            <select class="form-control" name="tirage" value="{{ old('bank') }}" style="height: 33px;">
                                <option>Tout</option>
                                @foreach ($tirage as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-5">
                            <label for="dateFilter" style="margin-top: 10px;">Branch</label>
                            <select class="form-control" name="branch" value="{{ old('branch') }}" style="height: 33px;">
                                <option>Tout</option>
                                @foreach ($branch as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="student-submit">
                                <button type="submit" class="btn">Fe rapo</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <form class="form">
                        <table class="table" id="myRapport">
                            <thead>
                                <tr>
                                    <th>Detay</th>
                                    <th>Valè</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($is_calculated == 1)
                                    <tr>
                                        <td colspan="2" class="text-center">Rapo soti {{ $date_debut }} Rive
                                            {{ $date_fin }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Bank:</td>
                                        <td>{{ $bank }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tiraj:</td>
                                        <td>{{ $tirage_ }}</td>
                                    </tr>
                                    <tr>
                                        <td>Branch:</td>
                                        <td>{{ $branch_ }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fich Total:</td>
                                        <td>{{ $ticket_win + $ticket_lose }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fich Genyen:</td>
                                        <td>{{ $ticket_win }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fich Pedi:</td>
                                        <td>{{ $ticket_lose }}</td>
                                    </tr>
                                    <tr>
                                        <td>Vant:</td>
                                        <td>{{ $vente }} {{ Session('devise') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pet:</td>
                                        <td>{{ $perte }} {{ Session('devise') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Komisyon:</td>
                                        <td>{{ $commission }} {{ Session('devise') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Balans:</td>
                                        <td>{{ $vente - ($perte + $commission) }} {{ Session('devise') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </form>
                    <button id="exportJPG" class="btn primary"
                        style=" color: rgb(33, 9, 251); padding: 12px 24px; border-radius: 5px; border: none; cursor: pointer; font-size: 16px;">Telechaje</button>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"
        integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function () {
            var form = $('.form'),
                cache_width = form.width(),
                a4 = [595.28, 841.89]; // for a4 size paper width and height  

            $('#create_pdf').on('click', function () {
                $('body').scrollTop(0);
                createPDF();
            });



            function getCanvas() {
                form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');
                return html2canvas(form, {
                    imageTimeout: 2000,
                    removeContainer: true
                });
            }

            document.getElementById('exportJPG').addEventListener('click', function () {
                html2canvas(document.getElementById('myRapport')).then(function (canvas) {
                    var link = document.createElement('a');
                    link.href = canvas.toDataURL('image/jpeg');
                    link.download = 'rapport_vendeur.jpg';
                    link.click();
                });
            });
        });
        const today = new Date().toISOString().split('T')[0];

        // Select all date inputs with the class 'max-today'
        const dateInputs = document.querySelectorAll('.dateInput');

        // Loop through each input and set the max attribute
        dateInputs.forEach(input => {
            input.setAttribute('max', today);
        });
    </script>
@endsection