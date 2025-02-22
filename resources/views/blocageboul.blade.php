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
    <div class="col-lg-12 grid-margin stretch-card">

        <div class="card">

            <div class="card-body">

                <h4 class="card-title">Espas pou bloke boul e debloke</h4>
                <p class="card-description">On-boule bloke <code>Off- Boul debloke</code>
                </p>

                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                @foreach ($tirageRecord as $tirages)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="optionsRadios"
                                                id="optionsRadios1"
                                                value="{{ $tirages->id }}">{{ $tirages->tirage->name }}<i
                                                class="input-helper"></i></label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th> Boul </th>
                                <th> Boul </th>
                                <th> Boul </th>
                                <th> Boul </th>
                                <th> Boul </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $numbers = range(0, 99);
                            @endphp

                            @foreach ($numbers as $number)
                                @if ($loop->iteration % 5 === 1)
                                    <tr>
                                @endif

                                <td style="font-size: 18px;">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
                                    <div class="flipswitch" style="margin-top: 13px;">
                                        <input type="checkbox" name="flipswitch" class="flipswitch-cb"
                                            id="fs{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}"
                                            @if ($boul->contains('boul', $number)) checked @endif>
                                        <label class="flipswitch-label"
                                            for="fs{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}">
                                            <div class="flipswitch-inner"></div>
                                            <div class="flipswitch-switch"></div>
                                        </label>
                                    </div>
                                </td>

                                @if ($loop->iteration % 5 === 0 || $loop->last)
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {

                            var flipswitches = document.querySelectorAll('.flipswitch-cb');

                            flipswitches.forEach(function(flipswitch) {

                                flipswitch.addEventListener('change', function() {

                                    var inner = this.nextElementSibling.querySelector('.flipswitch-inner');
                                    var switchElem = this.nextElementSibling.querySelector('.flipswitch-switch');

                                    var number = this.id.substr(
                                    2); // Récupérer le numéro à partir de l'ID de l'interrupteur
                                    var status = this.checked ? 1 : 0; // Vérifier l'état de l'interrupteur
                                    var selectedOptionValue = document.querySelector(
                                        'input[name="optionsRadios"]:checked').value;
                                    console.log(number);
                                    console.log(selectedOptionValue);

                                    $.ajax({
                                        url: '/block/update-switch',
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        data: {
                                            number: number,
                                            status: status,
                                            selectedOptionValue: selectedOptionValue,

                                        },
                                        success: function(response) {

                                            console.log(response);
                                            // Mettre à jour les styles en fonction du statut reçu
                                            if (response.statut === 1) {
                                                console.log('Switching ON');
                                                inner.style.marginLeft = '0';
                                                switchElem.style.right = '0';
                                                flipswitch.checked = true;
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


                        $(document).ready(function() {
                            // Écouter les changements dans la sélection
                            $('input[name="optionsRadios"]').change(function() {
                                // Collecter les valeurs des champs de l'option sélectionnée
                                var selectedOption = $('input[name="optionsRadios"]:checked');
                                var selectedValues = {
                                    id: selectedOption.val(),
                                    // Ajoutez d'autres champs ici en fonction de vos besoins
                                };

                                // Ajouter les valeurs collectées comme données du formulaire
                                $('form').append('<input type="hidden" name="selectedOption" value="' + JSON.stringify(
                                    selectedValues) + '">');

                                // Soumettre automatiquement le formulaire lorsqu'une option est sélectionnée
                                $('form').submit();
                            });
                        });



                        //verifier url
                        $(document).ready(function() {
                            // Fonction pour obtenir la valeur d'un paramètre dans l'URL
                            function getParameterByName(name, url) {
                                if (!url) url = window.location.href;
                                name = name.replace(/[\[\]]/g, '\\$&');
                                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                                    results = regex.exec(url);
                                if (!results) return null;
                                if (!results[2]) return '';
                                return decodeURIComponent(results[2].replace(/\+/g, ' '));
                            }

                            // Obtenez la valeur de 'optionsRadios' de l'URL
                            var selectedOptionValue = getParameterByName('optionsRadios');

                            // Si la valeur existe, sélectionnez automatiquement l'option correspondante
                            if (selectedOptionValue !== null) {
                                $('input[name="optionsRadios"]').filter('[value="' + selectedOptionValue + '"]').prop('checked',
                                    true);
                            }else{
                                $('input[name="optionsRadios"][value="1"]').prop('checked', true);
                            }
                        });
                    </script>
                </div>
            </div>
        </div>



    @stop