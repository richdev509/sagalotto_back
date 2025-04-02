@extends('admin-layout')


@section('content')
    <style type="text/css">
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border: none;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Popup Styling */
        .content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 800px;
            max-height: 80vh;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 1000;
            display: none;
            overflow-y: auto;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .close-btn:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content {
                width: 90%;
            }

            .table th,
            .table td {
                padding: 8px;
            }
        }

        /* Table Styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f3f5;
        }

        /* Button Styling */
        .btn-icon {
            padding: 8px;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #6a11cb;
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2575fc;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination li a {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #6a11cb;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .pagination li a:hover {
            background-color: #f1f3f5;
        }

        .pagination .active a {
            background-color: #6a11cb;
            color: white;
            border-color: #6a11cb;
        }

        /* Form Styling */
        .row_head {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control-lg {
            height: 30px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control-lg:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.1);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-control-lg {
                height: 30px;
                font-size: 14px;
            }

            .btn-gradient-primary {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
    <div class="page-header">
        <h3 class="page-title">Fich siprime</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fich</li>
            </ol>
        </nav>
    </div>




    <div class="card">
        <div class="card-body">


            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#Fich</th>
                            <th>Bank</th>
                            <th>Tirage</th>
                            <th>Boul</th>
                            <th>Jwe</th>
                            <th>Kreye</th>
                            <th>Siprime</th>
                            <th>Pa</th>
                        </tr>
                    </thead>
                    <tbody style="color: #ef5555">
                        @foreach ($ticket as $row)
                            <tr>
                                <td>{{ $row->ticket_id }}</td>
                                <td>{{ $row->bank }}</td>
                                <td
                                    style="color: {{ $row->tirage == 'NewYork Soir' ? 'blue' : ($row->tirage == 'NewYork Matin' ? '#06aafd' : ($row->tirage == 'Florida Matin' ? '#53ca8c' : ($row->tirage == 'Florida Soir' ? '#30be64' : ($row->tirage == 'Georgia Matin' ? '#be3030' : ($row->tirage == 'Georgia ApresMidi' ? '#fa8e8e' : 'inherit'))))) }};">
                                    {{ $row->tirage }}
                                </td>
                                <td class="text-center">
                                    <form action="boule-show" method="GET" class="form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $row->id }}">
                                        <button type="submit" class="btn btn-icon btn-primary btn_boule">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $row->amount }} HTG</td>
                                <td>
                                    {{ $row->date }} 
                                </td>
                                <td>{{ $row->date_delete }}</td>
                                @if ($row->is_delete == 1)
                                    <td>Admin</td>
                                @else
                                     <td>{{ $row->bank }}</td>
                                @endif
                              


                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10">
                                {{ $ticket->links() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>



        </div>



    </div>


    <div class="content" id="tent">
        <div class="close-btn" id="close" style="position: fixed;">
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
            $('.btn_boule').on('click', function() {
                $(".content").show();

            });
            $('.close-btn').on('click', function() {
                $(".content").hide();
                $('.content-clear').remove();

            })

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
                                if (Array.isArray(key.mariage_gratis) && key
                                    .mariage_gratis.length > 0) {
                                    key.mariage_gratis.forEach(function(item) {
                                        const table = document.getElementById(
                                            "mytable5");


                                        const row = document.createElement(
                                            "tr");

                                        row.classList.add('content-clear');
                                        const bo = document.createElement("td");
                                        const prix = document.createElement(
                                            "td");


                                        bo.textContent = item.boul1 + 'X' + item
                                            .boul2;
                                        prix.textContent = 'Gagnant';
                                        row.appendChild(bo);
                                        row.appendChild(prix);
                                        table.appendChild(row);

                                    });

                                } else {
                                    //  $('#l5').remove();

                                }

                                // Check if the value of the property is an array

                            });

                        } else {

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
