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
            padding: 10px;
            z-index: 100;
            display: none;
            overflow-y: scroll;
            overflow-x: scroll;
            /*to hide popup initially*/
        }

        #search div {
            display: inline-block;
        }

        .row_head {
            border: 1px solid blueviolet;
            padding: 10px;

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

    <div class="row">



        <div class="card">
            <div class="card-body">
                <div class="row_head">
                    <form method="get" action="rapport" id="search">
                        @csrf

                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label for="dateFilter">komanse</label>
                                <input style="height:10px;margin-top: 10px;" type="date" class="form-control"
                                    name="date_debut" value="{{ old('date_debut') }}" required />

                                <label for="dateFilter" style="margin-top: 5px;">Fini</label>

                                <input style="height:10px;margin-top: 10px;" type="date" class="form-control"
                                    value="" name="date_fin" value="{{ old('date_fin') }}" required />
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label for="dateFilter">Bank</label>
                                <select class="form-control selectpicker" name="bank" data-live-search="true"
                                    value="{{ old('bank') }}">
                                    <option>Tout</option>
                                    @foreach ($vendeur as $row)
                                        <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                                    @endforeach
                                </select>
                                <label for="dateFilter" style="margin-top: 5px;">Tiraj</label>
                                <select class="form-control selectpicker" name="tirage" data-live-search="true"
                                    value="{{ old('bank') }}">
                                    <option>Tout</option>
                                    @foreach ($tirage as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="student-submit">
                                <button style="margin-top: 18px;" type="submit"
                                    class="btn btn-gradient-primary me-2">Rapo</button>
                            </div>
                        </div>



                    </form>



                </div>
                <div class="table-responsive">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                              



                            </tr>
                        </thead>
                        <tbody style="border: 1px solid #ac32cb;">
                            @if($is_calculated==1)
                            <tr>
                                <td colspan="2" class="text-center">Rapo soti {{$date_debut}} Rive {{$date_fin}}</td>
                                

                            </tr>
                           

                            <tr>
                                <td>Bank:</td>
                                <td>{{$bank}}</td>

                            </tr>
                            <tr>
                                <td>Tiraj:</td>
                                <td>{{$tirage_}}</td>

                            </tr>
                            <tr>
                                <td>Fich Total:</td>
                                <td>{{$ticket_win + $ticket_lose}}</td>

                            </tr>
                            <tr>
                                <td>Fich Genyen:</td>
                                <td>{{$ticket_win}}</td>

                            </tr>
                            <tr>
                                <td>Fich Pedi:</td>
                                <td>{{$ticket_lose}}</td>

                            </tr>
                            <tr>
                                <td>Vant:</td>
                                <td>{{$vente}} HTG</td>

                            </tr>
                            <tr>
                                <td>Pet:</td>
                                <td>{{$perte}} HTG</td>

                            </tr>
                            <tr>
                                <td>Komisyon:</td>
                                <td>{{$commission}} HTG</td>

                            </tr>
                            <tr>
                                <td>Balans:</td>
                                <td>{{$vente- ($perte + $commission)}} HTG</td>

                            </tr>
                            @endif
                            
                            
                        </tbody>

                    </table>

                </div>
            </div>



        </div>


        <div class="content" id="tent">
            <div onclick="closePopup()" class="close-btn" id="close" style="position: fixed;">
                <i class="mdi mdi-close-octagon" style="color: red;"></i>
            </div>
            <div class="table-responsive">



                <table class="table table-striped" id="mytable">

                    <thead>
                        <tr style="background-color: #b66dff;">
                            <th> Boul </th>

                            <th>Prix</th>



                        </tr>

                    </thead>
                    <tbody>
                        <tr id="bor" style="background-color: #30be64;">
                            <td colspan="2" style="color: white; font-weight: bold">Bolèt</td>
                        </tr>
                        <table class="table table-striped" id="mytable1">

                            <tbody>
                                <tr id="mar" style="background-color: #06aafd;">
                                    <td colspan="2">Maryaj</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-striped" id="mytable2">

                            <tbody>
                                <tr id="l3" style="background-color: #be307c">
                                    <td colspan="2">Loto 3</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-striped" id="mytable3">

                            <tbody>
                                <tr id="l4" style="background-color: #f4d910;">
                                    <td colspan="2" style="color: white; font-weight: bold;">Loto 4</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-striped" id="mytable4">

                            <tbody>
                                <tr id="l5" style="background-color: #f38b03;">
                                    <td colspan="2" style="color:white; font-weight: bold;">Loto 5</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-striped" id="mytable5">

                            <tbody>
                                <tr id="mar_g" style="background-color: #be307c;">
                                    <td colspan="2" style="color: white; font-weight: bold;">Maryaj gratis</td>
                                </tr>
                            </tbody>
                        </table>
                    </tbody>
                </table>
            </div>
        </div>


        <script type="text/javascript">
            $(document).ready(function() {
                // Function to show and hide the popup 
                function togglePopup() {
                    $(".content").show();

                }

                function closePopup() {
                    $(".content").hide();
                    $('.content-clear').remove();

                }

                $('.form').on('submit', function(event) {
                    event.preventDefault();

                    $.ajax({
                        url: "{{ url('boule-show') }}",
                        data: $(this).serialize(),
                        type: 'get',
                        success: function(response) {

                            if (response.status == 'true') {
                                const jsonData = response.boule['boule'];

                                // Parse JSON string into a JavaScript object
                                const jsonObject = JSON.parse(jsonData);

                                // Iterate over the properties of the object
                                jsonObject.forEach(function(key) {
                                    //bolete
                                    if (Array.isArray(key.bolete) && key.bolete.length >
                                        0) {


                                        key.bolete.forEach(function(item) {
                                            const table = document.getElementById(
                                                "mytable");


                                            const row = document.createElement(
                                                "tr");

                                            row.classList.add('content-clear');
                                            const bo = document.createElement("td");
                                            const prix = document.createElement(
                                                "td");


                                            bo.textContent = item.boul1;
                                            prix.textContent = item.montant +
                                                ' HTG';
                                            row.appendChild(bo);
                                            row.appendChild(prix);
                                            table.appendChild(row);
                                        });

                                    } else {
                                        //  $('#bor').remove();

                                    }

                                    //mariaj
                                    if (Array.isArray(key.maryaj) && key.maryaj.length >
                                        0) {
                                        key.maryaj.forEach(function(item) {
                                            const table = document.getElementById(
                                                "mytable1");


                                            const row = document.createElement(
                                                "tr");

                                            row.classList.add('content-clear');
                                            const bo = document.createElement("td");
                                            const prix = document.createElement(
                                                "td");


                                            bo.textContent = item.boul1 + 'X' + item
                                                .boul2;
                                            prix.textContent = item.montant +
                                                ' HTG';
                                            row.appendChild(bo);
                                            row.appendChild(prix);
                                            table.appendChild(row);
                                        });

                                    } else {
                                        // $('#mar').remove();

                                    }

                                    //loto3
                                    if (Array.isArray(key.loto3) && key.loto3.length > 0) {
                                        key.loto3.forEach(function(item) {
                                            const table = document.getElementById(
                                                "mytable2");
                                            const row = document.createElement(
                                                "tr");
                                            row.classList.add('content-clear');
                                            const bo = document.createElement("td");
                                            const prix = document.createElement(
                                                "td");
                                            bo.textContent = item.boul1;
                                            prix.textContent = item.montant +
                                                ' HTG';
                                            row.appendChild(bo);
                                            row.appendChild(prix);
                                            table.appendChild(row);
                                        });
                                    } else {
                                        // $('#l3').remove();

                                    }


                                    //loto4
                                    if (Array.isArray(key.loto4) && key.loto4.length > 0) {

                                        key.loto4.forEach(function(item) {
                                            var op = ' ';
                                            const table = document.getElementById(
                                                "mytable3");
                                            const row = document.createElement(
                                                "tr");
                                            row.classList.add('content-clear');
                                            const bo = document.createElement("td");
                                            const prix = document.createElement(
                                                "td");

                                            bo.textContent = item.boul1;
                                            if (item.option1) {
                                                op += 'option1: ' + item.option1 +
                                                    'HTG';

                                            }
                                            if (item.option2) {
                                                op += '  option2: ' + item.option2 +
                                                    'HTG';

                                            }
                                            if (item.option3) {
                                                op += '  option:3 ' + item.option3 +
                                                    'HTG';

                                            }
                                            prix.textContent = op;
                                            row.appendChild(bo);
                                            row.appendChild(prix);
                                            table.appendChild(row);
                                        });

                                    } else {
                                        //  $('#l4').remove();

                                    }
                                    //loto 5
                                    if (Array.isArray(key.loto5) && key.loto5.length > 0) {
                                        key.loto5.forEach(function(item) {
                                            var op = "";
                                            const table = document.getElementById(
                                                "mytable4");
                                            const row = document.createElement(
                                                "tr");
                                            row.classList.add('content-clear');
                                            const bo = document.createElement("td");
                                            const prix = document.createElement(
                                                "td");
                                            bo.textContent = item.boul1;
                                            if (item.option1) {
                                                op += 'option1: ' + item.option1 +
                                                    'HTG';

                                            }
                                            if (item.option2) {
                                                op += '  option2: ' + item.option2 +
                                                    'HTG';

                                            }
                                            if (item.option3) {
                                                op += '  option:3 ' + item.option3 +
                                                    'HTG';

                                            }
                                            prix.textContent = op;
                                            row.appendChild(bo);
                                            row.appendChild(prix);
                                            table.appendChild(row);

                                        });

                                    } else {
                                        //  $('#l5').remove();

                                    }
                                    //mariage gratuit


                                    // Check if the value of the property is an array

                                });

                            } else {

                                alert('li pa mache');
                            }


                        },
                        error: function(jqXHR, textStatus, errorThrown) {


                        }
                    });





                });



                jQuery(".deleting_form").on('submit', function(e) {
                    e.preventDefault();
                    const formAction = this.getAttribute('action');
                    jQuery.getScript('https://cdn.jsdelivr.net/npm/sweetalert2@11', function() {
                        Swal.fire({
                            title: 'Vous voulez vraiment continuer ?',
                            text: "Vous voulez supprimer",
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: 'green',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Oui',
                            cancelButtonText: 'Annulez'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Log the form action
                                window.location.href = formAction;


                            } else {
                                console.log('clicked cancel');
                            }
                        })

                    })

                    // rest of the code
                });


            });
        </script>
    @endsection
