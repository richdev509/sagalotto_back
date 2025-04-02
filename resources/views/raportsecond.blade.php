@extends('admin-layout')


@section('content')

<style>
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




    :root {
        --vs-primary: 29 92 255;
    }

    /*Dialog Styles*/
    #dialog {
        top: 150px;
        border: 1px solid #dc61e7;
    }

    .search_rapport {
        margin-top: 13px;
        margin-left: 5px;
        margin-right: 5px;
    }

    dialog {
        padding: 1rem 3rem;
        background: white;
        max-width: 400px;
        padding-top: 2rem;
        border-radius: 20px;
        border: 0;
        box-shadow: 0 5px 30px 0 rgb(0 0 0 / 10%);


        .x {
            filter: grayscale(1);
            border: none;
            background: none;
            position: absolute;
            top: 15px;
            right: 10px;
            transition: ease filter, transform 0.3s;
            cursor: pointer;
            transform-origin: center;

            &:hover {
                filter: grayscale(0);
                transform: scale(1.1);
            }
        }

        h2 {
            font-weight: 600;
            font-size: 2rem;
            padding-bottom: 1rem;
        }

        p {
            font-size: 1rem;
            line-height: 1.3rem;
            padding: 0.5rem 0;

            a {
                &:visited {
                    color: rgb(var(--vs-primary));
                }
            }
        }
    }

    .head_rapport {
        background-color: gray;

    }

    .head_rapport th {
        font-weight: bold;
    }

    .body_rapport {
        border: 1px solid #403c41;
    }

    .balance div {}

    .row_head {}

    /*General Styles*/

    button.primary {
        display: inline-block;
        font-size: 0.8rem;
        color: #fff !important;
        background: rgb(var(--vs-primary) / 100%);
        padding: 13px 25px;
        border-radius: 17px;
        transition: background-color 0.1s ease;
        box-sizing: border-box;
        transition: all 0.25s ease;
        border: 0;
        cursor: pointer;
        box-shadow: 0 10px 20px -10px rgb(var(--vs-primary) / 50%);

        &:hover {
            box-shadow: 0 20px 20px -10px rgb(var(--vs-primary) / 50%);
            transform: translateY(-5px);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>

<div class="row"
    style="margin: 10px; border-style: ridge; border-width: 1px; border-color: rgb(209, 163, 252); border-radius: 10px; background-color: #f9f9f9;">
    <div class="card">
        <div class="card-body" style="padding: 22px 3px;">
            <div class="row align-items-center">
                <h4 class="card-title"
                    style="color: #0d2a95; font-weight: bold; text-align: center; margin-bottom: 20px;">Rapo general pou
                    chak bank</h4>
                <div class="row_head">
                    <form method="get" action="raport2" id="search">
                        @csrf
                        <div class="row col-12">
                            <div class="col-12 col-md-4">
                                <label style="font-weight: bold;">Komanse</label>
                                <input type="date" class="form-control dateInput" name="date_debut" value="{{ $date_debut }}"
                                    required style="height: 40px; border-radius: 5px; border: 1px solid #ccc;" />
                            </div>
                            <div class="col-12 col-md-4">
                                <label style="font-weight: bold;">Fini</label>
                                <input type="date" class="form-control dateInput" name="date_fin" value="{{ $date_fin }}" required
                                    style="height: 40px; border-radius: 5px; border: 1px solid #ccc;" />
                            </div>
                            <div class="col-12 col-md-4">
                                <label style="font-weight: bold;">Branch</label>
                                <select class="form-control" name="branch" value="{{ old('period') }}"
                                    style="height: 40px; border-radius: 5px; border: 1px solid #ccc;">
                                    <option value="tout">Tout</option>
                                    @foreach ($branch as $row)
                                        <option value="{{$row->id }}">{{$row->name}}</option>
                                    @endforeach

                                </select>
                            </div>


                        </div>
                        <div class="student-submit" style="text-align: center; margin-top: 10px;">
                            <button type="submit" class="btn primary me-2 loa"
                                style="color: white; padding: 12px 15px; border-radius: 5px; border: none; cursor: pointer; font-size: 16px;">Fe
                                rapo</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive" style="margin-top: 30px;">
                    <table class="table table-striped" id="myRapport" style="border-radius: 10px; overflow: hidden;">
                        <thead class="head_rapport" style="background: #0d2a95; color: white;">
                            <tr>
                                <th>Bank <i class="mdi mdi-cash-register mdi-16px float-right"></i></th>
                                <th>Dat <i class="mdi mdi-calendar mdi-16px"></i></th>
                                <th>Branch <i class="mdi mdi-source-branch mdi-16px"></i></th>
                                <th>Vant <i class="mdi mdi-tag mdi-16px"></i></th>
                                <th>Pedi <i class="mdi mdi-arrow-down-bold mdi-16px"></i></th>
                                <th>Komisyon <i class="mdi mdi-percent mdi-16px"></i></th>
                                <th>Balans <i class="mdi mdi-wallet mdi-16px"></i></th>
                            </tr>
                        </thead>
                        <tbody class="body_rapport">
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($vendeur as $row)
                                                        <tr>
                                                            <td>
                                                                <?php
                                $value = DB::table('users')
                                    ->where('id', $row['bank_name'])
                                    ->first(['bank_name', 'branch_id'])
                                                                    ?>
                                                                {{ $value->bank_name }}
                                                            </td>
                                                            </td>
                                                            <td>{{ $date_debut }} => {{ $date_fin }}</td>
                                                            <td>
                                                                <?php
                                $branch = DB::table('branches')
                                    ->where('id', $value->branch_id)
                                    ->value('name');

                                                                    ?>
                                                                {{ $branch }}
                                                            </td>
                                                            <td>{{ round($row['vente'], 2)}} {{ Session('devise') }}</td>
                                                            <td>{{ round($row['perte'], 2) }} {{ Session('devise') }}</td>
                                                            <td>{{ round($row['commission'], 2) }} {{ Session('devise') }}</td>
                                                            @php
                                                                $total = $total + ($row['vente'] - ($row['commission'] + $row['perte']));
                                                            @endphp
                                                            @if ($row['vente'] < $row['commission'] + $row['perte'])
                                                                <td style="color: red;">
                                                                    {{ round($row['vente'] - ($row['commission'] + $row['perte']), 2) }}
                                                                    {{ Session('devise') }}
                                                                </td>
                                                            @else
                                                                <td style="color: green;">
                                                                    {{ round($row['vente'] - ($row['commission'] + $row['perte']), 2) }}
                                                                    {{ Session('devise') }}
                                                                </td>
                                                            @endif
                                                        </tr>
                            @endforeach
                            @if($vendeur->isEmpty())
                                <tr>
                                    <td colspan="7" style="text-align: center;">Aucune donnée disponible</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot style="background: #0d2a95; color: white; height: 50px; padding: 0px;">
                            <tr style="height: 50px;">

                                <td class="text-right" colspan="7">
                                    Total <i class="mdi mdi-wallet mdi-16px"></i>:
                                    @if ($total > 0)
                                        <span style="color: green;">{{ round($total, 2) }} {{ Session('devise') }}</span>
                                    @else
                                        <span style="color: red;">{{ round($total, 2) }} {{ Session('devise') }}</span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <button id="exportJPG" class="btn primary"
                        style=" color: white; padding: 12px 24px; border-radius: 5px; border: none; cursor: pointer; font-size: 16px;">Telechaje</button>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="card" style='display: none;'>
    <div class="border-bottom-0 p-0 card-header">
        <div class="nav-lb-tab nav card-header-undefined" role="tablist">

            <div class="row">

                <form id="rapport_form">
                    @csrf
                    <div class="form-group" style="display:inline-flex;border: 1px solid #dc61e7;padding: 0px;">
                        <div>
                            <label>chwazi bank</label>
                            <select class="form-control selectpicker" data-live-search="true" name="user">
                                @foreach ($bank as $row)
                                    <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>dat rapo</label>

                            <input class="form-control" type="date" name="date" required>
                        </div>
                        <div>
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-primary search_rapport" data-toggle="modal" data-target="#model">
                                Chache
                            </button>
                        </div>
                    </div>




                </form>
            </div>

        </div>
    </div>
    <div class="p-0 card-body">
        <div class="tab-content">
            <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                class="fade pb-4 p-4 tab-pane active show">
                <div class="table-responsive">
                    <table class="text table" id="rapport_table">
                        <thead>
                            <tr>
                                <th scope="col">kod_bank</th>
                                <th scope="col">Bank</th>
                                <th scope="col">Montan</th>
                                <th scope="col">Dat</th>
                                <th scope="col">aksyon</th>


                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card" style="margin-top: 15px;display:none;">
    <div class="border-bottom-0 p-0 card-header">
        <h5>Historik peyman rapo yo</h5>

        <div class="nav-lb-tab nav card-header-undefined" role="tablist">
            <div class="row">

                <form id="rapport_form">
                    @csrf
                    <div class="form-group" style="display:inline-flex;border: 1px solid #dc61e7;padding: 0px;">
                        <div>
                            <label>chwazi bank</label>
                            <select class="form-control selectpicker" data-live-search="true" name="user">
                                @foreach ($bank as $row)
                                    <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>dat komanse</label>
                            <input class="form-control" type="date" name="date" required>
                        </div>
                        <div>
                            <label>dat fini</label>
                            <input class="form-control" type="date" name="date" required>
                        </div>
                        <div>

                            <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                class="btn btn-primary search_rapport" data-toggle="modal" data-target="#model">
                                Chache
                            </button>
                        </div>
                    </div>




                </form>
            </div>
            <div class="nav-item">

            </div>
        </div>
    </div>

    <div class="p-0 card-body">
        <div class="tab-content">
            <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                class="fade pb-4 p-4 tab-pane active show">
                <div class="table-responsive">
                    <table class="text table">
                        <thead>
                            <tr>
                                <th scope="col">Kod</th>
                                <th scope="col">bank</th>
                                <th scope="col">Montant</th>

                                <th scope="col">Balans</th>
                                <th scope="col">Dat</th>
                                <th scope="col">Aksyon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($control as $row)
                                <tr>
                                    <td scope="row">{{ $row->id }}</td>
                                    <td>{{ $row->bank_name }}</td>
                                    @if ($row->balance == 0)
                                        <td style="color: green;">{{ $row->montant }}</td>
                                    @else
                                        <td>{{ $row->montant }}</td>
                                    @endif
                                    @if ($row->balance != 0)
                                        <td style="color: red;">{{ $row->balance }} {{ Session('devise') }}</td>
                                    @else
                                        <td style="color: green;">{{ $row->balance }} {{ Session('devise') }}
                                        </td>
                                    @endif
                                    <td>{{ $row->date_rapport }}</td>
                                    <td>
                                        @if ($row->balance != 0)
                                            <button class="btn btn-primary btn_finpeye"><i class="mdi mdi-plus-circle"></i> Fin
                                                peye</button>
                                        @else
                                            <button class="btn btn-primary  btn_finpeye" disabled><i
                                                    class="mdi mdi-plus-circle"></i> Fin peye</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- Button trigger modal -->




<dialog id="dialog">
    <h5>Peyman rapo</h5>
    <span id="error_m" style="color: red;"></span>
    <span id="success_m" style="color: green"></span>
    <form method="POST" action="save_reglement" id="save_reglement">
        @csrf
        <div class="row">
            <div>
                <label class="col-sm-3 col-form-label">Vandè</label>
                <input class="form-control" type="hidden" name="vendeur" id="bank" required>
                <input class="form-control" type="code" name="" id="bank_v" disabled>


            </div>
            <div>
                <label class="col-sm-3 col-form-label">Raport</label>
                <input class="form-control" type="hidden" name="ddate" id="ddate" required>
                <input class="form-control" type="date" name="" id="ddate_v" disabled>

            </div>
            <div>
                <label class="col-sm-3 col-form-label">Vant/pet</label>
                <input class="form-control" type="hidden" name="amount" id="amount" required>
                <input class="form-control" type="number" name="" id="amount_v" disabled>


            </div>
            <div>
                <label class="col-sm-3 col-form-label">Peyman</label>
                <input class="form-control" type="number" name="amount_" required placeholder="Antre kantite kob la">
                <span>NB: ajoute - devan kantite a si se pet</span>
            </div>

            <div style="margin-top: 5px;">
                <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary"
                    data-toggle="modal" data-target="#model">
                    Anrejistre
                </button>
            </div>
        </div>
    </form>
    <button onclick="window.dialog.close();" aria-label="close" class="x">❌</button>
</dialog>




<!-- Modal -->

<!-- Initialisation de la modale Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.3.2/dist/html2canvas.min.js"></script>

<script>
    $(document).ready(function () {
        $("#rapport_form").submit(function (event) {
            // Prevent the default form submission
            event.preventDefault();

            // Get the form data
            var formData = $(this).serialize();

            // AJAX request
            $.ajax({
                type: "POST",
                url: "raport2_get_amount",
                data: formData,
                success: function (response) {
                    // get result if control is not already registred
                    if (response.control == 0) {
                        $('.content-clear').remove();

                        const table = document.getElementById(
                            "rapport_table");


                        const row = document.createElement(
                            "tr");

                        row.classList.add('content-clear');
                        const code = document.createElement("td");
                        const bank = document.createElement(
                            "td");
                        const amount = document.createElement(
                            "td");
                        const dated = document.createElement(
                            "td");
                        const action = document.createElement(
                            "td");


                        const link = document.createElement('button');
                        link.textContent = 'regleman/rapo';
                        link.href = '#';
                        link.classList.add('reglement');
                        action.id = 'gg';
                        action.onclick = callPopup;
                        link.setAttribute('data-target', '#model');
                        link.setAttribute('data-toggle', 'modal')
                        code.textContent = response.bank_code;
                        bank.textContent = response.bank;
                        amount.textContent = response.montant;
                        dated.textContent = response.date;
                        row.appendChild(code);
                        row.appendChild(bank);
                        row.appendChild(amount);
                        row.appendChild(dated);
                        action.appendChild(link);
                        row.appendChild(action);



                        table.appendChild(row);

                    }

                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        });

        //save control
        $("#save_reglement").submit(function (event) {
            // Prevent the default form submission
            event.preventDefault();
            //clear the message before event
            const success_m = document.getElementById('success_m');
            success_m.textContent = '';

            const error_m = document.getElementById('error_m');
            error_m.textContent = '';
            // Get the form data
            var formData = $(this).serialize();

            // AJAX request
            $.ajax({
                type: "POST",
                url: "save_reglement",
                data: formData,
                success: function (response) {
                    // get result if control is not already registred
                    if (response.save == 1) {
                        const success_m = document.getElementById('success_m');
                        success_m.textContent = response.message;
                    }
                    if (response.save == 0) {
                        const error_m = document.getElementById('error_m');
                        error_m.textContent = response.message;
                    }

                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        });

        function callPopup() {
            $('#dialog').show();
            var Currentrow = $(this).closest('tr');
            var vendeur = Currentrow.find('td:eq(0)').text();
            var bank = Currentrow.find('td:eq(1)').text();
            var amount = Currentrow.find('td:eq(2)').text();
            var ddate = Currentrow.find('td:eq(3)').text();


            vendeur = vendeur.trimStart();
            bank = bank.trimStart();
            amount = Math.round(amount).toString();
            amount = amount.trimStart();



            $('#bank').val(vendeur);
            $('#amount').val(amount);
            $('#ddate').val(ddate);

            $('#bank_v').val(vendeur);
            $('#amount_v').val(amount);
            $('#ddate_v').val(ddate);

        }
        $('.x').click(function () {
            const success_m = document.getElementById('success_m');
            success_m.textContent = '';

            const error_m = document.getElementById('error_m');
            error_m.textContent = '';
            $('#dialog').hide();
        });

        //export to image
        document.getElementById('exportJPG').addEventListener('click', function () {
            html2canvas(document.getElementById('myRapport')).then(function (canvas) {
                var link = document.createElement('a');
                link.href = canvas.toDataURL('image/jpeg');
                link.download = 'rapport_vendeur.jpg';
                link.click();
            });
        });

        const today = new Date().toISOString().split('T')[0];

        // Select all date inputs with the class 'max-today'
        const dateInputs = document.querySelectorAll('.dateInput');

        // Loop through each input and set the max attribute
        dateInputs.forEach(input => {
            input.setAttribute('max', today);
        });
    });
</script>
@stop