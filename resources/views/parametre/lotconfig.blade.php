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

                                <th> Aktive </th>
                                <th> Tan en minit </th>
                                <th class="text-center"> Aksyon </th>
                            </tr>
                        </thead>
                        <tbody>


                            <form method="post" action="editerdelai">
                                @csrf
                                <tr class="table-info">


                                    <td>
                                        <label> <input type="checkbox" class="flipswitch-cb" name="active" value="1"
                                                id="optionsRadios1"
                                                @if ($suppression->is_active == 1) @checked(true) @endif> Aktif
                                        </label>

                                    </td>
                                    <td>
                                        <input style="height:10px;color:black;" type="number" class="form-control"
                                            value="{{ $suppression->delai }}" name="time" id="minutes" />

                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn primary" style="background:rgb(0 94 254)">Enrejistre</button>
                                    </td>
                                </tr>
                            </form>


                        </tbody>
                    </table>
                </div>
                <div class="table-responsive" style="margin-top: 20px;">
                    <h5>Chanje modepas ou</h5>
                    <table class="table table-bordered">
                        <thead>


                        </thead>
                        <tbody>


                            <form method="post" action="editpassword">
                                @csrf
                                <tr class="table-info">


                                    <td>
                                        <label>
                                            Modepas ou gen kounya
                                        </label>
                                        <input style="height:10px;color:black;" type="password" class="form-control"
                                            name="old_password" /><br />
                                        <span class="login-danger" style="color: red;">
                                            @error('old_password')
                                                {{ $message }}
                                            @enderror
                                        </span><br />
                                        <label>
                                            Modepas wap mete a
                                        </label>
                                        <input style="height:10px;color:black;" type="password" class="form-control"
                                            name="password" /> <label>
                                            <span class="login-danger" style="color: red;">
                                                @error('password')
                                                    {{ $message }}
                                                @enderror
                                            </span><br />
                                            Remete Modepas wap mete a
                                        </label>


                                        <input style="height:10px;color:black;" type="password" class="form-control"
                                            name="password_confirmation" />
                                        <span class="login-danger" style="color: red;">
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </span><br />

                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn primary me-2" style="background:rgb(0 94 254)">Enrejistre</button>
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
                alert("Please enter a valid minute value between 0 and 59.");
            } else {
                // Format value as desired, e.g., "05" for single-digit minutes
            }
        });
    </script>


    <!-- main-panel ends -->

@stop
