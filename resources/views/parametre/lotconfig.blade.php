@extends('admin-layout')

@section('content')
    <!-- partial -->
    <style>
        .flipswitch {
            position: relative;
            width: 61px;
        }

        .flipswitch input[type=checkbox] {
            display: none;
        }

        .flipswitch-label {
            display: block;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid #999999;
            border-radius: 8px;
        }

        .flipswitch-inner {
            width: 200%;
            margin-left: -100%;
            transition: margin 0.3s ease-in 0s;
        }

        .flipswitch-inner:before,
        .flipswitch-inner:after {
            float: left;
            width: 50%;
            height: 18px;
            padding: 0;
            line-height: 18px;
            font-size: 15px;
            color: white;
            font-family: Trebuchet, Arial, sans-serif;
            font-weight: bold;
            box-sizing: border-box;
        }

        .flipswitch-inner:before {
            content: "ON";
            padding-left: 11px;
            background-color: #256799;
            color: #FFFFFF;
        }

        .flipswitch-inner:after {
            content: "OFF";
            padding-right: 11px;
            background-color: #EBEBEB;
            color: #888888;
            text-align: right;
        }

        .flipswitch-switch {
            width: 27px;
            margin: -4.5px;
            background: #FFFFFF;
            border: 1px solid #999999;
            border-radius: 8px;
            position: absolute;
            top: 0;
            bottom: 0;
            right: 40px;
            transition: all 0.3s ease-in 0s;
        }

        .flipswitch-cb:checked+.flipswitch-label .flipswitch-inner {
            margin-left: 0;
        }

        .flipswitch-cb:checked+.flipswitch-label .flipswitch-switch {
            right: 0;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 30px;
        }

        .table-responsive {
            margin-bottom: 20px;
        }

        .table-bordered {
            border-radius: 10px;
            overflow: hidden;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 12px;
        }

        .table-bordered th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #1469e9;
            outline: none;
            box-shadow: 0 0 5px rgba(20, 105, 233, 0.5);
        }

        .btn-primary {
            background: linear-gradient(45deg, #1469e9, #14e9e9);
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #14e9e9, #1469e9);
        }

        .login-danger {
            font-size: 12px;
            color: red;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .page-header h3 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item a {
            color: #1469e9;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #666;
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">Konfigirasyon</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
            </ol>
        </nav>
    </div>
    <div class="col-lg-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <h5>Aktive tan pou siprime yon fich</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Aktive</th>
                                <th>Tan en minit</th>
                                <th class="text-center">Aksyon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form method="post" action="editerdelai">
                                @csrf
                                <tr class="table-info">
                                    <td>
                                        <label>
                                            <input type="checkbox" class="flipswitch-cb" name="active" value="1"
                                                id="optionsRadios1" @if ($suppression->is_active == 1) checked @endif>
                                            Aktif
                                        </label>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" value="{{ $suppression->delai }}"
                                            name="time" id="minutes" min="0" max="59" />
                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-primary">Enrejistre</button>
                                    </td>
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive" style="margin-top: 20px;">
                    <h5>Chanje modepas ou</h5>
                    <table class="table table-bordered">
                        <thead></thead>
                        <tbody>
                            <form method="post" action="editpassword">
                                @csrf
                                <tr class="table-info">
                                    <td>
                                        <label>Modepas ou gen kounya</label>
                                        <input type="password" class="form-control" name="old_password" />
                                        <span class="login-danger">
                                            @error('old_password')
                                                {{ $message }}
                                            @enderror
                                        </span><br />
                                        <label>Modepas wap mete a</label>
                                        <input type="password" class="form-control" name="password" />
                                        <span class="login-danger">
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </span><br />
                                        <label>Remete Modepas wap mete a</label>
                                        <input type="password" class="form-control" name="password_confirmation" />
                                        <span class="login-danger">
                                            @error('password_confirmation')
                                                {{ $message }}
                                            @enderror
                                        </span><br />
                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-primary">Enrejistre</button>
                                    </td>
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        const minutesInput = document.getElementById("minutes");
        minutesInput.addEventListener("change", (event) => {
            const value = parseInt(event.target.value);
            if (value < 0 || value > 59) {
                event.target.value = ""; // Reset value if invalid
                alert("Tan an dwe ant 0 ak 59 minit.");
            }
        });
    </script>
@stop