@extends('admin-layout')

@section('content')
   <style>
        :root {
            --primary-color: #4e73df;
            --primary-light: #7e9eff;
            --secondary-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --text-color: #212529;
            --border-radius: 0.5rem;
            --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        body {
            background-color: var(--light-color);
            color: var(--text-color);
            font-family: 'Nunito', sans-serif;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title-icon {
            background-color: var(--primary-color);
            padding: 0.75rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
        }

        .breadcrumb {
            background: none;
            padding: 0;
        }

        .card {
            background-color: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }

        .card-header {
            background-color: var(--light-color);
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            font-size: 1.1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            padding: 0.5rem 1rem;
            font-size: 1rem;
            transition: 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn {
            border-radius: var(--border-radius);
            font-weight: 600;
            padding: 0.6rem 1.2rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .table {
            width: 100%;
            margin-top: 1rem;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: var(--dark-color);
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.75rem;
            border-radius: 0.35rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .search-form {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        dialog {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            z-index: 1050;
            animation: fadeIn 0.3s ease-out;
        }

        dialog::backdrop {
            background: rgba(0, 0, 0, 0.5);
        }

        dialog .x {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-color);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        @media (max-width: 768px) {
            .form-control, .btn {
                width: 100%;
                margin-bottom: 0.75rem;
            }

            .card-header {
                text-align: center;
            }
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span
                class="page-title-icon bg-gradient-primary text-white me-2 rounded-circle d-inline-flex align-items-center justify-content-center"
                style="width: 36px; height: 36px;">
                <i class="mdi mdi-account-multiple"></i>
            </span>
            Jèsyon Rapò Vandè
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin" class="text-decoration-none">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Rapò Vandè</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Search Rapport Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Chèche Rapò Vandè</h5>
                </div>
                <div class="card-body">
                    <form id="rapport_form" class="search-form">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Chwazi Bank</label>
                                <select class="form-control selectpicker" data-live-search="true" name="user" id="user"
                                    required>
                                    <option value="" disabled selected>Chwazi yon bank</option>
                                    @foreach ($bank as $row)
                                        <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Dat komanse</label>
                                <input class="form-control dateInput" type="date" name="date11" id="date11" required
                                    disabled>
                                <input class="form-control dateInput" type="hidden" name="date1" id="date1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Dat fini</label>
                                <input class="form-control dateInput" type="date" id="date2" name="date2" required>
                            </div>
                        </div>
                        <div class="row">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-magnify mr-1"></i> Chache Rapò
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover" id="rapport_table">
                            <thead>
                                <tr>
                                    <th>Kòd Bank</th>
                                    <th>Bank</th>
                                    <th>Montan</th>
                                    <th>Dat Komanse</th>
                                    <th>Dat Fini</th>
                                    <th>Aksyon</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">Pa gen done pou montre</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment History Card -->
            <div class="card">
                <div class="card-header">
                    <h5>Istorik Pèyman Rapò Yo</h5>
                </div>
                <div class="card-body">
                    <form id="history_form" class="search-form">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-6 mb-6">
                                <label class="form-label">Chwazi Bank</label>
                                <select class="form-control selectpicker" data-live-search="true" name="user" required>
                                    @foreach ($bank as $row)
                                        <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                           
                            <div class="col-md-6 mb-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="mdi mdi-magnify mr-1"></i> Chache Istorik
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kòd</th>
                                    <th>Bank</th>
                                    <th>Montan</th>
                                    <th>Balans</th>
                                    <th>Dat komanse</th>
                                    <th>Dat fini</th>
                                    <th>Aksyon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($control as $row)
                                  @if($row->montant > 0)
                                    <tr>
                                        <td>{{ $row->id_user }}</td>
                                        <td>{{ $row->bank_name }}</td>
                                        <td class="{{ $row->balance == 0 ? 'status-paid' : 'status-unpaid' }}">
                                            {{ $row->montant }} {{ Session('devise') }}
                                        </td>
                                        <td>
                                            @if ($row->balance != 0)
                                                <span class="badge badge-danger">{{ $row->balance  }}
                                                    {{ Session('devise') }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $row->balance }}
                                                    {{ Session('devise') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $row->date_rapport }}</td>
                                        <td>{{ $row->date_fin }}</td>

                                        <td>
                                            @if ($row->balance != 0)
                                                <button class="btn btn-sm btn-success btn_finpeye" data-id="{{ $row->id }}">
                                                    <i class="mdi mdi-check-circle mr-1"></i> Aquittement
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="mdi mdi-check-all mr-1"></i> Peye
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @else
                                    <tr style="color: #c22733;">
                                        <td>{{ $row->id_user }}</td>
                                        <td>{{ $row->bank_name }}</td>
                                        <td>
                                            {{ $row->montant }} {{ Session('devise') }}
                                        </td>
                                        <td>
                                            @if ($row->balance != 0)
                                                <span class="badge badge-danger">{{ $row->balance  }}
                                                    {{ Session('devise') }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $row->balance }}
                                                    {{ Session('devise') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $row->date_rapport }}</td>
                                        <td>{{ $row->date_fin }}</td>

                                        <td>
                                            @if ($row->balance != 0)
                                                <button class="btn btn-sm btn-success btn_finpeye" data-id="{{ $row->id }}">
                                                    <i class="mdi mdi-check-circle mr-1"></i> Aquittement
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="mdi mdi-check-all mr-1"></i> Peye
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Payment Modal/Dialog -->
    <dialog id="paymentDialog">
        <button onclick="window.paymentDialog.close();" aria-label="close" class="x">×</button>
        
        <div class="d-flex align-items-center mb-4">
            <i class="mdi mdi-cash-multiple mr-2" style="font-size: 1.5rem;"></i>
            <h5 class="mb-0">Pèyman Rapò</h5>
        </div>

        <div id="error_m" class="alert alert-danger d-none"></div>
        <div id="success_m" class="alert alert-success d-none"></div>

        <form method="POST" action="save_reglement" id="save_reglement">
            @csrf
            <div class="form-group">
                <label class="form-label">Vandè</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="mdi mdi-bank"></i></span>
                    <input class="form-control" type="hidden" name="vendeur" id="bank" required>
                    <input class="form-control" type="text" id="bank_v" disabled>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Dat komanse</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-calendar-start"></i></span>
                            <input class="form-control" type="hidden" name="ddate" id="ddate" required>
                            <input class="form-control" type="date" id="ddate_v" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Dat fini</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-calendar-end"></i></span>
                            <input class="form-control" type="hidden" name="edate" id="edate" required>
                            <input class="form-control" type="date" id="edate_v" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Montan Total</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="mdi mdi-currency-usd"></i></span>
                    <input class="form-control" type="hidden" name="amount" id="amount" required>
                    <input class="form-control" type="text" id="amount_v" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Montan Pèyman</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="mdi mdi-cash"></i></span>
                    <input class="form-control" type="number" name="amount_" required placeholder="Antre kantite lajan an">
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 gap-2">
                <button type="button" onclick="window.paymentDialog.close();" class="btn btn-outline-secondary">
                    <i class="mdi mdi-close mr-1"></i> Fèmen
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save mr-1"></i> Anrejistre
                </button>
            </div>
        </form>
    </dialog>

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.3.2/dist/html2canvas.min.js"></script>
    <script>
        $(document).ready(function () {
            // Set max date to today for all date inputs
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('.dateInput').forEach(input => {
                input.setAttribute('max', today);
            });

            // Rapport form submission
            $("#rapport_form").submit(function (event) {
                event.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "raport2_get_amount",
                    data: formData,
                    beforeSend: function () {
                        // Show loading indicator
                        $('#rapport_table tbody').html('<tr><td colspan="6" class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
                    },
                    success: function (response) {
                        if (response.control == 0) {
                            if (response.montant > 0) {
                                $('#rapport_table tbody').html(`
                                    <tr class="content-clear">
                                        <td>${response.bank_code}</td>
                                        <td>${response.bank}</td>
                                        <td class="font-weight-bold">${response.montant} ${response.devise}</td>
                                        <td>${response.date}</td>
                                        <td>${response.date1}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary reglement" onclick="showPaymentDialog(this)">
                                                <i class="mdi mdi-cash mr-1"></i> Encaissement
                                            </button>
                                        </td>
                                    </tr>
                                `);
                            }
                            if (response.montant < 0) {
                                $('#rapport_table tbody').html(`
                                    <tr class="content-clear">
                                        <td>${response.bank_code}</td>
                                        <td>${response.bank}</td>
                                        <td class="font-weight-bold">${response.montant} ${response.devise}</td>
                                        <td>${response.date}</td>
                                        <td>${response.date1}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary reglement btn-danger" onclick="showPaymentDialog(this)">
                                                <i class="mdi mdi-cash mr-1"></i> Decaissement
                                            </button>
                                        </td>
                                    </tr>
                                `);
                            }
                            if (response.montant == 0) {
                                $('#rapport_table tbody').html('<tr><td colspan="6" class="text-center">Pa gen rapò pou dat sa a balance lan zero</td></tr>');
                            }
                        } else {
                            $('#rapport_table tbody').html('<tr><td colspan="6" class="text-center">Pa gen rapò pou dat sa a</td></tr>');
                        }
                    },
                    error: function (xhr) {
                        $('#rapport_table tbody').html('<tr><td colspan="6" class="text-center text-danger">Erè sou sèvè a</td></tr>');
                        console.error(xhr.responseText);
                    }
                });
            });

            $("#user").on('change', function (event) {
                event.preventDefault();
                var userId = $(this).val();

                $.ajax({
                    type: "POST",
                    url: "get_control_date",
                    data: {
                        user: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        // Show loading indicator
                        $('#rapport_table tbody').html('<tr><td colspan="6" class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
                    },
                    success: function (response) {
                        if (response.status == 'true') {
                            // Enable the date input and set its value
                            $('#date11').val(response.date).prop('disabled', false);
                            $('#date11').prop('disabled', true);
                            $('#date1').val(response.date);
                            $("#date2").attr('min', response.date);
                            $('#rapport_table tbody').html('<tr><td colspan="6" class="text-center">Chache rapo</td></tr>');
                        } else {
                            $('#date1').prop('disabled', true).val('');
                            $('#rapport_table tbody').html('<tr><td colspan="6" class="text-center">Bank sa poko aktif</td></tr>');
                        }
                    },
                    error: function (xhr) {
                        $('#date1').prop('disabled', true).val('');
                        $('#rapport_table tbody').html('<tr><td colspan="6" class="text-center text-danger">Erè sou sèvè a</td></tr>');
                        console.log(xhr.responseText);
                    }
                });
            });

            // Payment form submission
            $("#save_reglement").submit(function (event) {
                event.preventDefault();
                var formData = $(this).serialize();

                // Clear previous messages
                $('#error_m').addClass('d-none').text('');
                $('#success_m').addClass('d-none').text('');

                $.ajax({
                    type: "POST",
                    url: "save_reglement",
                    data: formData,
                    success: function (response) {
                        if (response.save == 1) {
                            $('#success_m').removeClass('d-none').text(response.message);
                            setTimeout(() => {
                                window.paymentDialog.close();
                                location.reload();
                            }, 1500);
                        } else {
                            $('#error_m').removeClass('d-none').text(response.message);
                        }
                    },
                    error: function (xhr) {
                        $('#error_m').removeClass('d-none').text('Erè sou sèvè a');
                        console.error(xhr.responseText);
                    }
                });
            });

            // Handle payment button clicks from history table
            $('.btn_finpeye').click(function () {
                const row = $(this).closest('tr');
                const id = row.find('td:eq(0)').text().trim();
                const bank = row.find('td:eq(1)').text().trim();
                const amount = row.find('td:eq(3)').text().trim().split(' ')[0];
                const dateee = row.find('td:eq(4)').text().trim();
                const dateee1 = row.find('td:eq(5)').text().trim();

                // Set values in the payment dialog
                $('#bank').val(id);
                $('#bank_v').val(bank);
                $('#amount').val(amount);
                $('#amount_v').val(amount);
                $('#ddate').val(dateee);
                $('#ddate_v').val(dateee);
                $('#edate').val(dateee1);
                $('#edate_v').val(dateee1);

                // Show the dialog
                window.paymentDialog.showModal();
            });
        });

        // Show payment dialog for rapport table rows
        function showPaymentDialog(button) {
            const row = $(button).closest('tr');
            const bankCode = row.find('td:eq(0)').text().trim();
            const bank = row.find('td:eq(1)').text().trim();
            const amount = row.find('td:eq(2)').text().trim().split(' ')[0];
            const date = row.find('td:eq(3)').text().trim();
            const date1 = row.find('td:eq(4)').text().trim();

            // Set values in the payment dialog
            $('#bank').val(bankCode);
            $('#bank_v').val(bank);
            $('#amount').val(amount);
            $('#amount_v').val(amount);
            $('#ddate').val(date);
            $('#ddate_v').val(date);
            $('#edate').val(date1);
            $('#edate_v').val(date1);

            // Clear any previous messages
            $('#error_m').addClass('d-none').text('');
            $('#success_m').addClass('d-none').text('');

            // Show the dialog with animation
            const dialog = document.getElementById('paymentDialog');
            dialog.showModal();
            
            // Focus on the payment amount field
            setTimeout(() => {
                $('input[name="amount_"]').focus();
            }, 100);
        }

        // Make the function available globally
        window.showPaymentDialog = showPaymentDialog;
    </script>
@endsection