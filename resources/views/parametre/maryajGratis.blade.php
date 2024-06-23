@extends('admin-layout')
@section('content')
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
            padding-right: 4px;
            background-color: #db2424;
            color: #f5f4f4;
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
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Maryaj Gratis</h4>
            <p class="card-description">Espas parametraj</p>
            @if (!$data)
                <p class="card-description" id="alert3" style="color:chocolate">Ou poko gen maryaj gratis :veuiller
                    l'initialiser sur ON</p>
            @endif
            <div class="flipswitch" style="margin-top: 13px;">
                <input type="checkbox" name="flipswitch" class="flipswitch-cb" id="fs"
                    @if ($data && $data->etat == 1) checked @endif >
                <label class="flipswitch-label" for="fs">
                    <div class="flipswitch-inner"></div>
                    <div class="flipswitch-switch"></div>
                </label>
            </div>




        </div>
        <div class="card-body">
            <h4 class="card-title">Kantite ak enteval kob</h4>
            @if ($data)
            @else
                <p class="card-description" id="alert1" style="color:red">Ou poko ka fixe prix maryajGratis, Svp ON
                    maryaj gratis</p>
            @endif
            @if ($data && $data->prix == 0)
                <p class="card-description" id="alert2" style="color:rgba(255, 166, 0, 0.945)">📌 Ajoute yon montant</p>
            @endif
            <form class="forms-sample" action="{{ route('updatemontantmg') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_1">
                            @if ($data)
                                <option>{{ $data->q_inter_1 }}</option>
                                <option disabled>----</option> <!-- Ajoutez une option désactivée pour séparer les valeurs -->
                            @endif
                            @for ($i = 0; $i <= 6; $i++)
                                @if ($data && $data->q_inter_1 == $i) <!-- Assurez-vous que la valeur de données est ignorée -->
                                    @continue
                                @endif
                                <option>{{ $i }}</option>
                            @endfor
                        </select>
                        

                        <input type="number" name="min_inter_1" class="form-control" placeholder="Min kob"
                            value="{{ isset($data) ? $data->min_inter_1 : '' }}" required min="0">

                        <input type="number" name="max_inter_1" value="{{ isset($data) ? $data->max_inter_1 : '' }}" class="form-control"
                            placeholder="Max kob" required min="0">

                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_2">
                            @if ($data)
                            <option>{{ $data->q_inter_2}}</option>
                            <option disabled>----</option> <!-- Ajoutez une option désactivée pour séparer les valeurs -->
                        @endif
                        @for ($i = 0; $i <= 10; $i++)
                            @if ($data && $data->q_inter_1 == $i) <!-- Assurez-vous que la valeur de données est ignorée -->
                                @continue
                            @endif
                            <option>{{ $i }}</option>
                        @endfor
                        </select>
                        <input type="number" name="min_inter_2" value="{{ isset($data) ? $data->min_inter_2 : '' }}" class="form-control" placeholder="Min kob" required min="0">
                        <input type="number" name="max_inter_2" value="{{ isset($data) ? $data->max_inter_2 : '' }}" class="form-control"
                            placeholder="Min kob" required min="0">

                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_3">
                            @if ($data)
                                <option>{{ $data->q_inter_3 }}</option>
                                <option disabled>----</option> <!-- Ajoutez une option désactivée pour séparer les valeurs -->
                            @endif
                            @for ($i = 0; $i <= 15; $i++)
                                @if ($data && $data->q_inter_1 == $i) <!-- Assurez-vous que la valeur de données est ignorée -->
                                    @continue
                                @endif
                                <option>{{ $i }}</option>
                            @endfor

                        </select>

                        <input type="number" name="min_inter_3" value="{{ isset($data) ? $data->min_inter_3 : '' }}" class="form-control"
                            placeholder="Min kob" required min="0">

                        <input type="number" name="max_inter_3" value="{{ isset($data) ? $data->max_inter_3 : '' }}" class="form-control"
                            placeholder="Max kob" required min="0">


                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_4">
                            @if ($data)
                                <option>{{ $data->q_inter_4 }}</option>
                                <option disabled>----</option> <!-- Ajoutez une option désactivée pour séparer les valeurs -->
                            @endif
                            @for ($i = 0; $i <= 20; $i++)
                                @if ($data && $data->q_inter_1 == $i) <!-- Assurez-vous que la valeur de données est ignorée -->
                                    @continue
                                @endif
                                <option>{{ $i }}</option>
                            @endfor

                        </select>

                        <input type="number" name="min_inter_4" value="{{ isset($data) ? $data->min_inter_4 : '' }}" class="form-control"
                            placeholder="Min kob" required min="0">

                        <input type="number" name="max_inter_4" value="{{ isset($data) ? $data->max_inter_4 : '' }}" class="form-control"
                            placeholder="Max kob" required min="0">


                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_5">
                            @if ($data)
                                <option>{{ $data->q_inter_5 }}</option>
                                <option disabled>----</option> <!-- Ajoutez une option désactivée pour séparer les valeurs -->
                            @endif
                            @for ($i = 0; $i <= 25; $i++)
                                @if ($data && $data->q_inter_1 == $i) <!-- Assurez-vous que la valeur de données est ignorée -->
                                    @continue
                                @endif
                                <option>{{ $i }}</option>
                            @endfor

                        </select>

                        <input type="number" name="min_inter_5" value="{{ isset($data) ? $data->min_inter_5 : '' }}" class="form-control"
                            placeholder="Min kob" required min="0">

                        <input type="number" name="max_inter_5" value="{{ isset($data) ? $data->max_inter_5 : '' }}" class="form-control"
                            placeholder="Max kob" required min="0">


                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_6">
                            @if ($data)
                                <option>{{ $data->q_inter_6 }}</option>
                                <option disabled>----</option> <!-- Ajoutez une option désactivée pour séparer les valeurs -->
                            @endif
                            @for ($i = 0; $i <= 30; $i++)
                                @if ($data && $data->q_inter_1 == $i) <!-- Assurez-vous que la valeur de données est ignorée -->
                                    @continue
                                @endif
                                <option>{{ $i }}</option>
                            @endfor

                        </select>

                        <input type="number" name="min_inter_6" value="{{ isset($data) ? $data->min_inter_6 : '' }}" class="form-control"
                            placeholder="Min kob" required min="0">

                        <input type="number" name="max_inter_6" value="{{ isset($data) ? $data->max_inter_6 : '' }}" class="form-control"
                            placeholder="Max kob" required min="0">


                    </div>
                    <h4 class="card-title">Pri marya Gratis</h4>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">HTG</span>
                        </div>
                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                @if ($data && $data->prix > 0)
                                    {{ $data->prix }}
                                @else
                                    0.00
                                @endif
                            </span>
                        </div>
                        <input type="text" name="montant" class="form-control"
                            aria-label="Montant (ajoute montant a mise a jour)" value=" @if ($data) {{ $data->prix }} @endif"
                            placeholder="Antre vale kob vle mete an">

                        @if ($data && $data->id)
                            <button type="submit" class="btn btn-gradient-primary me-2">Mete a jou</button>
                        @endif
                    </div>
                </div>
            </form>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var flipswitches = document.querySelectorAll('.flipswitch-cb');

            flipswitches.forEach(function(flipswitch) {

                flipswitch.addEventListener('change', function() {

                    var inner = this.nextElementSibling.querySelector('.flipswitch-inner');
                    var switchElem = this.nextElementSibling.querySelector('.flipswitch-switch');
                    var status = this.checked ? 1 : 0; // Vérifier l'état de l'interrupteur


                    $.ajax({
                        url: 'updatestatutmg',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {

                            status: status,


                        },
                        success: function(response) {

                            console.log(response);
                            // Mettre à jour les styles en fonction du statut reçu
                            if (response.statut === 1) {
                                console.log('Switching ON');

                                inner.style.marginLeft = '0';
                                switchElem.style.right = '0';
                                flipswitch.checked = true;
                                $('#alert1').css('display', 'none');
                                $('#alert2').css('display', 'none');
                                $('#alert3').css('display', 'none');
                            } else {
                                console.log('Switching OFF');
                                inner.style.marginLeft = '-100%';
                                switchElem.style.right = '40px';
                                flipswitch.checked = false;
                            }

                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });






                });
            });
        });
    </script>



@stop
