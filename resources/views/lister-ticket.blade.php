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

        .custom-confirm-btn {
            background: green !important;
            border: none !important;
            padding: 10px 20px !important;
        }

        .custom-cancel-btn {
            background: red !important;
            border: none !important;
            padding: 10px 20px !important;
        }


        .custom-select-visible {
    background-color: #ffffff !important;
    border: 2px solid #0066cc !important;
    border-radius: 10px !important;
    padding: 12px 45px 12px 15px !important;
    font-size: 18px !important;
    font-weight: 500;
    color: #222;
    appearance: none;            /* enlève la flèche native */
    -webkit-appearance: none;
    -moz-appearance: none;
    box-shadow: 0 3px 10px rgba(0, 102, 204, 0.15);
    cursor: pointer;
    transition: all 0.3s ease;
}

.custom-select-visible:hover,
.custom-select-visible:focus {
    border-color: #ff6b35 !important;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.25);
    outline: none;
}

.position-relative {
    position: relative;
}

.custom-arrow {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #0066cc;
    font-size: 16px;
    pointer-events: none; /* empêche la flèche d'intercepter le clic */
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




    <div class="card">
        <div class="card-body">
            <div class="row_head">
                <form method="get" action="lister-ticket" id="search">
                    @csrf
                    <div class="row">
                        <!-- Date Filters -->
                   <div class="container-fluid">
    <div class="border p-0 rounded bg-light">
  <div class="d-flex align-items-end justify-content-between gap-2 flex-nowrap w-100 overflow-hidden">

    <div class="form-group flex-fill position-relative" style="margin-bottom: 8px;">
      <label for="date_debut" class="form-label">Komanse</label>
      <input type="date" class="form-control form-control-lg pe-5" name="date_debut">
      <i class="mdi mdi-calendar position-absolute text-primary"
         style="right:12px; top:58%; transform:translateY(-50%); pointer-events:none; font-size:22px;"></i>
    </div>

    <div class="form-group flex-fill position-relative" style="margin-bottom: 8px;">
      <label for="date_fin" class="form-label">Fini</label>
      <input type="date" class="form-control form-control-lg pe-5" name="date_fin">
      <i class="mdi mdi-calendar position-absolute text-primary"
         style="right:12px; top:58%; transform:translateY(-50%); pointer-events:none; font-size:22px;"></i>
    </div>

  </div>
</div>

</div>

<div class="container-fluid">
    <div class="border p-0 rounded bg-light">
        <div class="d-flex align-items-end justify-content-between gap-2 flex-nowrap w-100 overflow-hidden">

<div class="form-group flex-fill" style="margin-bottom: 8px;">
                                <label for="bank" class="form-label">Bank</label>
                                <select class="form-select form-select-lg custom-select-visible" name="bank">
                                    <option value="Tout">Tout</option>
                                    @foreach ($vendeur as $row)
                                        <option value="{{ $row->id }}" {{ old('bank') == $row->id ? 'selected' : '' }}>
                                            {{ $row->bank_name }}
                                        </option>
                                    @endforeach
                                </select>
                              
                            </div>
                            <div class="form-group flex-fill" style="margin-bottom: 8px;">
                                <label for="tirage" class="form-label">Tirage</label>
                                <select class="form-select form-select-lg custom-select-visible" name="tirage">
                                    <option value='Tout'>Tout</option>
                                    @foreach ($tirage as $row)
                                        <option value="{{ $row->id }}" {{ old('tirage') == $row->id ? 'selected' : '' }}>
                                            {{ $row->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
        </div>
    </div>
</div>

                      

                        <!-- Ticket Number Filter -->
                        <div class="col-12 col-md-4">
                            <div class="form-group" style="margin-bottom: 0px;">
                                <label for="ticket" class="form-label">#Fich</label>
                                <input type="text" class="form-control form-control-lg" name="ticket"
                                    placeholder="Antre yon #fich" value="{{ old('ticket') }}" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-gradient-primary btn-lg">
                                <i class="mdi mdi-magnify"></i> Chache
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#Fich</th>
                            <th>Bank</th>
                            <th>Tirage</th>
                            <th>Boul</th>
                            <th>Jwe</th>
                            <th>Genyen</th>
                            <th>Kakile</th>
                            <th>Peye</th>
                            <th>Dat</th>
                            <th>Aksyon</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <td style="color: {{ $row->winning ? 'green' : 'red' }};">
                                    {{ $row->winning ?? 0 }} HTG
                                </td>
                                <td style="color: {{ $row->is_calculated ? '#58dc0b' : 'red' }};">
                                    {{ $row->is_calculated ? 'Wi' : 'Non' }}
                                </td>
                                <td style="color: {{ $row->is_payed ? 'green' : 'red' }};">
                                    {{ $row->is_payed ? 'Wi' : 'Non' }}
                                </td>
                                <td>{{ $row->date }}</td>
                                <td class="text-center">
                                    <form action="delete-ticket?id={{ $row->id }}" method="get" class="deleting_form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $row->id }}">
                                        <button type="submit" class="btn btn-icon btn-danger">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
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

    <!-- Overlay noir -->
<div  id="filter-bloc" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);  /* ✅ au lieu d’opacity */
    z-index: 1000;                 /* s’affiche au-dessus du contenu */
    display: none;
    align-items: center;
    justify-content: center;
">
    <!-- Bloc visible au-dessus -->
    <div class="container-fluid" style="z-index: 1001; max-width: 600px;">
        <div class="border p-3 rounded bg-light shadow-lg">
            <div class="d-flex align-items-end justify-content-between gap-2 flex-nowrap w-100 overflow-hidden">
                <div class="form-group flex-fill mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded shadow-sm">
    <label for="bank" class="form-label mb-0 fw-semibold text-primary">Filtè fich genyen ou pèdi</label>

    <button onclick="showFiltere()" 
            class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center" 
            style="width: 40px; height: 40px; border: none;">
        <i class="mdi mdi-close fs-5 text-white"></i>
    </button>
</div>

                   <div class="position-relative w-100">
    <select class="form-select form-select-lg custom-select-visible" name="bank">
        <option value="Tout">Tout</option>
        <option value="gagnant">Fich ki genyen</option>
        <option value="perdu">Fich ki pèdi</option>
    </select>
    <span class="custom-arrow">
        ▼
    </span>
</div>

                    
                </div>
            </div>
        </div>
    </div>
</div>

<div  style="position: fixed;
    bottom: 0px;
    /* display: flex; */
    right: 30px;
    margin-bottom: 30px;
   
    width: 15%;
    height: 30px;">
    <button onclick="showFiltere()" class="btn btn-gradient-primary">filter</button>
</div>

<script>
     
    function showFiltere() {
    const bloc = document.getElementById('filter-bloc');
    if (bloc.style.display === "none" || bloc.style.display === "") {
        bloc.style.display = "flex";
    } else {
        bloc.style.display = "none";
    }
}
    </script>

    <script
   
    
    type="text/javascript">
        $(document).ready(function () {
            // Function to show and hide the popup 
            function togglePopup() {
                $(".content").show();

            }
            $('.btn_boule').on('click', function () {
                $(".content").show();

            });
            $('.close-btn').on('click', function () {
                $(".content").hide();
                $('.content-clear').remove();

            })

            $('.form').on('submit', function (event) {
                event.preventDefault();

                $.ajax({
                    url: "{{ url('boule-show') }}",
                    data: $(this).serialize(),
                    type: 'get',
                    success: function (response) {
                        if (response.status == 'true') {
                            const jsonData = response.boule['boule'];

                            // Parse JSON string into a JavaScript object
                            const jsonObject = JSON.parse(jsonData);

                            // Iterate over the properties of the object
                            jsonObject.forEach(function (key) {
                                //bolete
                                if (Array.isArray(key.bolete) && key.bolete.length >
                                    0) {


                                    key.bolete.forEach(function (item) {
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
                                    key.maryaj.forEach(function (item) {
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
                                    key.loto3.forEach(function (item) {
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

                                    key.loto4.forEach(function (item) {
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
                                    key.loto5.forEach(function (item) {
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
                                    key.mariage_gratis.forEach(function (item) {
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
                    error: function (jqXHR, textStatus, errorThrown) {


                    }
                });





            });



            jQuery(".deleting_form").on('submit', function (e) {
                e.preventDefault();
                const formAction = this.getAttribute('action');

                // Use the latest version (11.19.1) instead of just @11
                jQuery.getScript('https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/dist/sweetalert2.all.min.js', function () {
                    Swal.fire({
                        title: 'Ou vle siprime fich la tout bon ?',
                        text: "Ou vle siprimel vre",
                        icon: 'warning', // Changed from 'success' to 'warning' for delete confirmation
                        showCancelButton: true,
                        confirmButtonColor: 'green',
                        cancelButtonColor: 'red',
                        confirmButtonText: 'Wi',
                        cancelButtonText: 'Anile',
                        buttonsStyling: true, // Ensures default SweetAlert styles apply
                        customClass: {
                            confirmButton: 'custom-confirm-btn', // Optional extra class
                            cancelButton: 'custom-cancel-btn'   // Optional extra class
                        },
                        allowOutsideClick: false, // Prevent closing by clicking outside
                        backdrop: true, // Show backdrop
                        reverseButtons: true, // Show cancel button first (better UX)
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown' // Optional animation
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp' // Optional animation
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = formAction;
                        } else {
                            console.log('Action anile');
                            // Optional: Show cancellation message
                            Swal.fire({
                                title: 'Action anile',
                                icon: 'info',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                });
            });

        });
    </script>
@endsection