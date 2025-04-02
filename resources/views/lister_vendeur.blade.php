@extends('admin-layout')

@section('content')
    <style>
        /* Modern color palette */
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --success-color: #00b894;
            --danger-color: #d63031;
            --background-color: #f8f9fa;
            --text-color: #2d3436;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 20px;
        }

        .header-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(108, 92, 231, 0.2);
        }

        .select-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .select-box:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(108, 92, 231, 0.2);
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table tbody tr {
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .table tbody td {
            padding: 15px;
            border: none;
        }

        .btn-edit {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 18px;
            transition: color 0.3s;
        }

        .btn-edit:hover {
            color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .col-md-6 {
                margin-bottom: 15px;
            }
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-multiple"></i>
            </span> Lis vandè
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Vandè</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-12">
                            <h6 class="header-title">Chache</h6>
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="text" class="form-control search-input" id="search"
                                placeholder="Antre nom yon bank la...">
                        </div>
                        <div class="col-12 col-md-6">
                            <select class="form-select select-box live-search" id="branchFilter">
                                <option value="Tout">Tout branch</option>
                                @foreach ($branch as $row)
                                    <option>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kod</th>
                                    <th>Bank</th>
                                    <th>Branch</th>
                                    <th>Itilizatè</th>
                                    <th>Komisyon</th>
                                    <th>Bloke</th>
                                    <th class="text-end">Aksyon</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @php $i = 1; @endphp
                                @foreach ($vendeur as $row)
                                                            <tr>
                                                                <td>{{ $i }}</td>
                                                                <td>{{ $row->code }}</td>
                                                                <td>{{ $row->bank_name }}</td>
                                                                <td>
                                                                    <?php
                                    $value = DB::table('branches')
                                        ->where('id', $row['branch_id'])
                                        ->value('name');
                                                                                                            ?>
                                                                    {{ $value }}
                                                                </td>
                                                                <td>{{ $row->username }}</td>

                                                                <td>{{ $row->percent }} %</td>

                                                                <td>
                                                                    @if ($row->is_block == 1)
                                                                        <span class="badge bg-danger">Wi</span>
                                                                    @else
                                                                        <span class="badge bg-success">Non</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-end">
                                                                    <form action="editer-vendeur">
                                                                        <input type="hidden" name="id" value="{{ $row->id }}" />
                                                                        <button type="submit" class="btn-edit">
                                                                            <i class="mdi mdi-table-edit"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            @php $i = $i + 1; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const branchFilter = document.getElementById('branchFilter');
            const tableBody = document.getElementById('tableBody');
            const rows = tableBody.getElementsByTagName('tr');

            // Filter function
            function filterTable() {
                const searchText = searchInput.value.toLowerCase();
                const selectedBranch = branchFilter.value.toLowerCase();

                for (let i = 0; i < rows.length; i++) {
                    let bankName = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
                    let branchName = rows[i].getElementsByTagName('td')[3].textContent.toLowerCase();

                    // Check if both the bank name and branch match the filter
                    if ((bankName.includes(searchText) || searchText === '') &&
                        (branchName.includes(selectedBranch) || selectedBranch === 'tout')) {
                        rows[i].style.display = ''; // Show row
                    } else {
                        rows[i].style.display = 'none'; // Hide row
                    }
                }
            }

            // Attach event listeners to the inputs
            searchInput.addEventListener('input', filterTable);
            branchFilter.addEventListener('change', filterTable);
        });
    </script>
@endsection