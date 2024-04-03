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
    </style>
    <div class="page-header">
        <h3 class="page-title">Lis Fich</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fich</li>
            </ol>
        </nav>
    </div>

    <div class="row">



        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <tbody>
                        <form method="post" action="editertirage">
                            @csrf
                            <tr class="table-info filter-head" style="border: 2px solid blue;">

                                <td>
                                    <label for="dateFilter">komanse</label>
                                    <input style="height:10px;margin-top: 10px;" type="date" class="form-control" value=""
                                        name="time_tirer" />
                                    <label for="dateFilter" style="margin-top: 5px;">Fini</label>

                                    <input style="height:10px;margin-top: 10px;" type="date" class="form-control" value=""
                                        name="time_tirer" />
                                    <button style="margin-top: 18px;" type="submit"
                                        class="btn btn-gradient-primary me-2">Chache</button>
                                </td>


                            </tr>
                        </form>


                    </tbody>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> #fich </th>
                                <th> Bank </th>
                                <th> Tirage </th>
                                <th> Boul </th>
                                <th> Jwe</th>
                                <th> Genyen</th>
                                <th> Kakile</th>
                                <th> Dat</th>
                                <th> Aksyon</th>



                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ticket as $row)
                                <tr>
                                    <td> {{ $row->ticket_id }} </td>
                                    <td class="py-1">
                                        {{ $row->bank }}
                                    </td>
                                    @if ($row->tirage == 'New York Soir')
                                        <td style="color:blue;">
                                            {{ $row->tirage }}
                                        </td>
                                    @elseif($row->tirage == 'New York Matin')
                                        <td style="color: #06aafd">
                                            {{ $row->tirage }}
                                        </td>
                                    @elseif($row->tirage == 'Florida Matin')
                                        <td style="color: #58dc0b">
                                            {{ $row->tirage }}
                                        </td>
                                    @elseif($row->tirage == 'Florida Soir')
                                        <td style="color: #30be64">
                                            {{ $row->tirage }}
                                        </td>
                                    @else
                                        <td>
                                            {{ $row->tirage }}
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <form action="boule-show" method="GET" class="form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $row->id }}">
                                            <button type="submit" onclick="togglePopup()" style="color: blue;"><i
                                                    class="mdi mdi-eye"></i></button>

                                        </form>



                                    </td>

                                    <td> {{ $row->amount }} HTG</td>


                                    @if ($row->winning == null)
                                        <td style="color: red">
                                            {{ 0 }} HTG

                                        </td>
                                    @else
                                        <td style="color: green;">
                                            {{ $row->winning }} HTG
                                        </td>
                                    @endif
                                    </td>
                                    @if ($row->is_calculated == 0)
                                        <td style="color: red;"> Non </td>
                                    @else
                                        <td style="color:#58dc0b;"> Wi </td>
                                    @endif


                                    <td> {{ $row->created_at }} </td>

                                    <td class="text-center"><a href="#"><i class="mdi mdi-delete"></i>
                                        </a></td>

                                </tr>
                            @endforeach
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
                                if (Array.isArray(key.bolete) && key.bolete.length > 0) {


                                    key.bolete.forEach(function(item) {
                                        const table = document.getElementById("mytable");


                                        const row = document.createElement("tr");

                                        row.classList.add('content-clear');
                                        const bo = document.createElement("td");
                                        const prix = document.createElement("td");


                                        bo.textContent = item.boul1;
                                        prix.textContent = item.montant + ' HTG';
                                        row.appendChild(bo);
                                        row.appendChild(prix);
                                        table.appendChild(row);
                                    });

                                } else {
                                    //  $('#bor').remove();

                                }

                                //mariaj
                                if (Array.isArray(key.maryaj) && key.maryaj.length > 0) {
                                    key.maryaj.forEach(function(item) {
                                        const table = document.getElementById("mytable1");


                                        const row = document.createElement("tr");

                                        row.classList.add('content-clear');
                                        const bo = document.createElement("td");
                                        const prix = document.createElement("td");


                                        bo.textContent = item.boul1 + 'X' + item.boul2;
                                        prix.textContent = item.montant + ' HTG';
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
                                        const table = document.getElementById("mytable2");
                                        const row = document.createElement("tr");
                                        row.classList.add('content-clear');
                                        const bo = document.createElement("td");
                                        const prix = document.createElement("td");
                                        bo.textContent = item.boul1;
                                        prix.textContent = item.montant + ' HTG';
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
                                        const table = document.getElementById("mytable3");
                                        const row = document.createElement("tr");
                                        row.classList.add('content-clear');
                                        const bo = document.createElement("td");
                                        const prix = document.createElement("td");

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
                                        var op ="";
                                        const table = document.getElementById("mytable4");
                                        const row = document.createElement("tr");
                                        row.classList.add('content-clear');
                                        const bo = document.createElement("td");
                                        const prix = document.createElement("td");
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

            document.addEventListener('click', function(event) {
                var myDiv = document.getElementById('tent');
                var targetElement = event.target;
               /* if (window.getComputedStyle(myDiv).display !== 'none') {
                    if (!myDiv.contains(targetElement)) {
                        $(".content").hide();
                        $('.content-clear').remove();

                    }else{
                        $(".content").show();

                    }

                } */

                // Check if the clicked element is not within the myDiv

            });
        </script>
    @endsection
