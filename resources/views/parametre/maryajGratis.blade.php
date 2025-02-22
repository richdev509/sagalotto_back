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

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 30px;
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

        .input-group {
            margin-bottom: 15px;
        }

        .input-group-prepend .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
        }

        .card-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .card-description {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .alert {
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Maryaj Gratis</h4>
            <p class="card-description">Espas parametraj</p>
            <div class="flipswitch" style="margin-top: 13px;">
                <input type="checkbox" name="flipswitch" class="flipswitch-cb" id="fs">
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
                <p class="alert alert-danger" id="alert1">Ou poko ka fixe prix maryajGratis, Svp ON maryaj gratis</p>
            @endif
            @if ($data && $data->prix == 0)
                <p class="alert alert-warning" id="alert2">📌 Ajoute yon montant</p>
            @endif
            <form class="forms-sample" action="{{ route('updatemontantmg') }}" method="POST">
                @csrf
                <label>Chwazi Branch</label>
                <select name="branch" id="branch" class="form-control" required style="height: 40px;">
                    <option value="" disabled selected>Chwazi branch wap parametre a</option>
                    @foreach ($branch as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </select>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_1">
                            <option id='q_inter_1'></option>
                            <option disabled>----</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                        </select>
                        <input type="number" name="min_inter_1" class="form-control" placeholder="Min kob" id="min_inter_1"
                            required min="0">
                        <input type="number" name="max_inter_1" id="max_inter_1" class="form-control" placeholder="Max kob"
                            required min="0">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_2">
                            <option id="q_inter_2"></option>
                            <option disabled>----</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                        </select>
                        <input type="number" name="min_inter_2" id="min_inter_2" class="form-control" placeholder="Min kob"
                            required min="0">
                        <input type="number" name="max_inter_2" id="max_inter_2" class="form-control" placeholder="Min kob"
                            required min="0">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_3">
                            <option id="q_inter_3"></option>
                            <option disabled>----</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                        </select>
                        <input type="number" name="min_inter_3" id="min_inter_3" class="form-control" placeholder="Min kob"
                            required min="0">
                        <input type="number" name="max_inter_3" id="max_inter_3" class="form-control" placeholder="Max kob"
                            required min="0">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_4">
                            <option id="q_inter_4"></option>
                            <option disabled>----</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                            <option>11</option>
                        </select>
                        <input type="number" name="min_inter_4" id="min_inter_4" class="form-control"
                            placeholder="Min kob" required min="0">
                        <input type="number" name="max_inter_4" id="max_inter_4" class="form-control"
                            placeholder="Max kob" required min="0">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_5">
                            <option id="q_inter_5"></option>
                            <option disabled>----</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                            <option>11</option>
                            <option>12</option>
                            <option>13</option>
                        </select>
                        <input type="number" name="min_inter_5" id="min_inter_5" class="form-control"
                            placeholder="Min kob" required min="0">
                        <input type="number" name="max_inter_5" id="max_inter_5" class="form-control"
                            placeholder="Max kob" required min="0">
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Kantite maryaj</span>
                        </div>
                        <select class="form-control" name="q_inter_6">
                            <option id="q_inter_6"></option>
                            <option disabled>----</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                            <option>11</option>
                            <option>12</option>
                            <option>13</option>
                            <option>14</option>
                            <option>15</option>
                            <option>16</option>
                            <option>17</option>
                            <option>18</option>
                        </select>
                        <input type="number" name="min_inter_6" id="min_inter_6" class="form-control"
                            placeholder="Min kob" required min="0">
                        <input type="number" name="max_inter_6" id="max_inter_6" class="form-control"
                            placeholder="Max kob" required min="0">
                    </div>
                    <h4 class="card-title">Pri marya Gratis</h4>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">HTG</span>
                        </div>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="labelPrice"></span>
                        </div>
                        <input type="text" name="montant" class="form-control" id="price"
                            placeholder="Antre vale kob vle mete an" required>
                        <button type="submit" class="btn btn-primary">Mete a jou</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var flipswitches = document.querySelectorAll('.flipswitch-cb');
            flipswitches.forEach(function(flipswitch) {
                flipswitch.addEventListener('change', function() {
                    var inner = this.nextElementSibling.querySelector('.flipswitch-inner');
                    var switchElem = this.nextElementSibling.querySelector('.flipswitch-switch');
                    var status = this.checked ? 1 : 0;
                    var branch = document.getElementById('branch');

                    $.ajax({
                        url: 'updatestatutmg',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            status: status,
                            branch: branch.value,
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.statut === 1) {
                                inner.style.marginLeft = '0';
                                switchElem.style.right = '0';
                                flipswitch.checked = true;
                                $('#alert1').css('display', 'none');
                                $('#alert2').css('display', 'none');
                                $('#alert3').css('display', 'none');
                            } else {
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

            document.getElementById('branch').addEventListener('change', function() {
                let current_id = this.value;
                $.ajax({
                    url: 'maryajByBranch',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: current_id
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            document.getElementById('q_inter_1').innerText = response.data.q_inter_1;
                            document.getElementById('min_inter_1').value = response.data.min_inter_1;
                            document.getElementById('max_inter_1').value = response.data.max_inter_1;

                            document.getElementById('q_inter_2').innerText = response.data.q_inter_2;
                            document.getElementById('min_inter_2').value = response.data.min_inter_2;
                            document.getElementById('max_inter_2').value = response.data.max_inter_2;

                            document.getElementById('q_inter_3').innerText = response.data.q_inter_3;
                            document.getElementById('min_inter_3').value = response.data.min_inter_3;
                            document.getElementById('max_inter_3').value = response.data.max_inter_3;

                            document.getElementById('q_inter_4').innerText = response.data.q_inter_4;
                            document.getElementById('min_inter_4').value = response.data.min_inter_4;
                            document.getElementById('max_inter_4').value = response.data.max_inter_4;

                            document.getElementById('q_inter_5').innerText = response.data.q_inter_5;
                            document.getElementById('min_inter_5').value = response.data.min_inter_5;
                            document.getElementById('max_inter_5').value = response.data.max_inter_5;

                            document.getElementById('q_inter_6').innerText = response.data.q_inter_6;
                            document.getElementById('min_inter_6').value = response.data.min_inter_6;
                            document.getElementById('max_inter_6').value = response.data.max_inter_6;

                            document.getElementById('labelPrice').innerText = response.data.prix;
                            document.getElementById('price').value = response.data.prix;

                            var etat = document.getElementById('fs');
                            if (response.data.etat == 1) {
                                etat.checked = true;
                            } else {
                                etat.checked = false;
                            }
                        }
                    }
                });
            });
        });
    </script>
@stop