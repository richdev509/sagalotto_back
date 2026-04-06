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
            width: 90%;
            max-width: 900px;
            max-height: 85vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 30px 30px 30px;
            z-index: 1050;
            display: none;
            overflow-y: auto;
        }

        .content::-webkit-scrollbar {
            width: 8px;
        }

        .content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
        }

        .content::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.7);
        }

        .close-btn {
            position: sticky;
            top: 10px;
            float: right;
            margin-top: -45px;
            margin-right: -15px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
            transition: all 0.3s ease;
            z-index: 1051;
        }

        .close-btn:hover {
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.6);
        }

        .close-btn i {
            font-size: 24px;
        }

        /* Popup Header */
        .popup-header {
            text-align: center;
            color: white;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }

        .popup-header h3 {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Section Headers */
        .section-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            color: #333;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 18px;
            margin: 20px 0 10px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Table inside popup */
        .content .table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .content .table thead tr {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .content .table thead th {
            padding: 15px;
            font-weight: 600;
            font-size: 16px;
            text-align: left;
            border: none;
        }

        .content .table tbody tr {
            transition: all 0.3s ease;
        }

        .content .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }

        .content .table tbody td {
            padding: 12px 15px;
            font-size: 15px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
        }

        .content .table tbody td:first-child {
            font-weight: 600;
            color: #667eea;
        }

        .content .table tbody td:last-child {
            color: #28a745;
            font-weight: 600;
        }

        /* Category specific colors */
        .category-bolete { background: linear-gradient(90deg, #30be64, #2da558); }
        .category-maryaj { background: linear-gradient(90deg, #06aafd, #0590d6); }
        .category-loto3 { background: linear-gradient(90deg, #be307c, #a02868); }
        .category-loto4 { background: linear-gradient(90deg, #f4d910, #d4b90e); }
        .category-loto5 { background: linear-gradient(90deg, #f38b03, #d47803); }
        .category-maryaj-gratis { background: linear-gradient(90deg, #be307c, #a02868); }

        @media (max-width: 768px) {
            .content {
                width: 95%;
                padding: 20px 15px;
            }
            
            .popup-header h3 {
                font-size: 22px;
            }
            
            .section-header {
                font-size: 16px;
                padding: 10px 15px;
            }
            
            .content .table thead th,
            .content .table tbody td {
                padding: 10px 8px;
                font-size: 13px;
            }
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
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.2rem;
            font-size: 14px;
        }

        .form-control-lg {
            height: 38px;
            font-size: 14px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            padding: 6px 12px;
        }

        .form-control-lg:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.1);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border: none;
            padding: 8px 20px;
            font-size: 15px;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-control-lg {
                height: 36px;
                font-size: 13px;
            }

            .btn-gradient-primary {
                width: 100%;
                padding: 8px;
            }
            
            .row_head {
                padding: 8px;
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
            border-radius: 6px !important;
            padding: 8px 40px 8px 12px !important;
            font-size: 14px !important;
            font-weight: 500;
            color: #222;
            appearance: none;
            /* enlève la flèche native */
            -webkit-appearance: none;
            -moz-appearance: none;
            box-shadow: 0 2px 6px rgba(0, 102, 204, 0.15);
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
            pointer-events: none;
            /* empêche la flèche d'intercepter le clic */
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
                        <div class="container-fluid px-0 mb-2">
                            <div class="border p-2 rounded bg-light">
                                <div class="d-flex align-items-end justify-content-between gap-2 flex-nowrap w-100 overflow-hidden">

                                    <div class="form-group flex-fill position-relative mb-0">
                                        <label for="date_debut" class="form-label">Komanse</label>
                                        <input type="date" class="form-control form-control-lg pe-5" name="date_debut">
                                        <i class="mdi mdi-calendar position-absolute text-primary"
                                            style="right:12px; top:58%; transform:translateY(-50%); pointer-events:none; font-size:18px;"></i>
                                    </div>

                                    <div class="form-group flex-fill position-relative mb-0">
                                        <label for="date_fin" class="form-label">Fini</label>
                                        <input type="date" class="form-control form-control-lg pe-5" name="date_fin">
                                        <i class="mdi mdi-calendar position-absolute text-primary"
                                            style="right:12px; top:58%; transform:translateY(-50%); pointer-events:none; font-size:18px;"></i>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="container-fluid px-0 mb-2">
                            <div class="border p-2 rounded bg-light">
                                <div class="d-flex align-items-end justify-content-between gap-2 flex-nowrap w-100 overflow-hidden">

                                    <div class="form-group flex-fill mb-0">
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
                                    <div class="form-group flex-fill mb-0">
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
                        <div class="col-12 col-md-4 mb-2">
                            <div class="form-group mb-0">
                                <label for="ticket" class="form-label">#Fich</label>
                                <input type="text" class="form-control form-control-lg" name="ticket"
                                    placeholder="Antre yon #fich" value="{{ old('ticket') }}" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-center mt-2">
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
        <div class="close-btn" id="close">
            <i class="mdi mdi-close"></i>
        </div>
        
        <div class="popup-header">
            <h3><i class="mdi mdi-ticket-account"></i> Detay Fich</h3>
        </div>
        
        <div class="table-responsive">
            <!-- Bolèt Section -->
            <div class="section-header category-bolete" id="bor" style="color: white;">
                Bolèt
            </div>
            <table class="table" id="mytable">
                <thead>
                    <tr>
                        <th>Boul</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Bolete rows will be inserted here -->
                </tbody>
            </table>

            <!-- Maryaj Section -->
            <div class="section-header category-maryaj" id="mar" style="color: white;">
                Maryaj
            </div>
            <table class="table" id="mytable1">
                <thead>
                    <tr>
                        <th>Boul</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Maryaj rows will be inserted here -->
                </tbody>
            </table>

            <!-- Loto 3 Section -->
            <div class="section-header category-loto3" id="l3" style="color: white;">
                Loto 3
            </div>
            <table class="table" id="mytable2">
                <thead>
                    <tr>
                        <th>Boul</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loto 3 rows will be inserted here -->
                </tbody>
            </table>

            <!-- Loto 4 Section -->
            <div class="section-header category-loto4" id="l4" style="color: #333;">
                Loto 4
            </div>
            <table class="table" id="mytable3">
                <thead>
                    <tr>
                        <th>Boul</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loto 4 rows will be inserted here -->
                </tbody>
            </table>

            <!-- Loto 5 Section -->
            <div class="section-header category-loto5" id="l5" style="color: white;">
                Loto 5
            </div>
            <table class="table" id="mytable4">
                <thead>
                    <tr>
                        <th>Boul</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loto 5 rows will be inserted here -->
                </tbody>
            </table>

            <!-- Maryaj Gratis Section -->
            <div class="section-header category-maryaj-gratis" id="mar_g" style="color: white;">
                Maryaj Gratis
            </div>
            <table class="table" id="mytable5">
                <thead>
                    <tr>
                        <th>Boul</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Maryaj gratis rows will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Overlay noir -->
    <div id="filter-bloc" style="
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
                            <label for="bank" class="form-label mb-0 fw-semibold text-primary">Filtè fich genyen ou
                                pèdi</label>

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

    <div style="position: fixed;
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

        $(document).ready(function () {
            // Handle filter change
            $('#filter-bloc select[name="bank"]').on('change', function () {
                const filterValue = $(this).val();
                const rows = $('table tbody tr');

                rows.each(function () {
                    const winningCell = $(this).find('td:eq(5)'); // Column "Genyen"

                    if (winningCell.length === 0) return; // Skip if no cell found

                    const winningText = winningCell.text().trim();
                    const winningAmount = parseFloat(winningText.replace(/[^\d.-]/g, '')) || 0;

                    if (filterValue === 'Tout') {
                        $(this).show();
                    } else if (filterValue === 'gagnant') {
                        // Show only tickets with winning > 0
                        if (winningAmount > 0) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    } else if (filterValue === 'perdu') {
                        // Show only tickets with winning = 0
                        if (winningAmount === 0) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });

                // Close the filter popup after selection
                showFiltere();
            });
        });
    </script>

    <script type="text/javascript">
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