@extends('superviseur.admin-layout')

@section('content')
    <style>
        .page-header {
            margin-bottom: 25px;
        }
        
        .page-header h3 {
            color: #333;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .modern-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%);
            padding: 12px 20px;
            border: none;
        }
        
        .card-title-custom {
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .vendor-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .search-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .search-box label {
            font-weight: 500;
            color: #495057;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: #4B49AC;
            box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.15);
        }
        
        .table-modern {
            margin-bottom: 0;
        }
        
        .table-modern thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 2px solid #dee2e6;
            padding: 15px 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-modern tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .table-modern tbody tr:hover {
            background: #f8f9ff;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(75, 73, 172, 0.1);
        }
        
        .table-modern tbody td {
            padding: 15px 20px;
            vertical-align: middle;
        }
        
        .badge-code {
            background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
        }
        
        .bank-name {
            font-weight: 600;
            color: #333;
            font-size: 15px;
        }
        
        .username-text {
            color: #6c757d;
            font-size: 14px;
        }
        
        .username-text i {
            color: #4B49AC;
            margin-right: 5px;
        }
        
        .badge-percent {
            background: linear-gradient(135deg, #00b894 0%, #00d9a3 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
        }
        
        .badge-blocked {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-active {
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .btn-edit {
            background: #4B49AC;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-edit:hover {
            background: #3d3a8c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(75, 73, 172, 0.3);
        }
        
        .btn-edit i {
            font-size: 16px;
        }
        
        .row-number {
            font-weight: 600;
            color: #4B49AC;
            font-size: 14px;
        }
        
        .table-responsive {
            border-radius: 0 0 10px 10px;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-account-multiple"></i>
                    </span> Lis Vendè yo
                </h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-header-custom">
                    <div class="card-title-custom">
                        <span>
                            <i class="mdi mdi-format-list-bulleted"></i>
                            Tout Vendè yo
                        </span>
                        <span class="vendor-count">
                            Total: {{ count($vendeur) }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="search-box">
                        <label>
                            <i class="mdi mdi-magnify"></i>
                            Chèche yon vandè
                        </label>
                        <input type="text" class="form-control" id="search" 
                               placeholder="Tape non osinon kòd pou chèche..." />
                    </div>

                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kòd</th>
                                    <th>Non</th>
                                    <th>Username</th>
                                    <th>Pousan</th>
                                    <th>Estati</th>
                                    <th class="text-end">Aksyon</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @php $i = 1; @endphp
                                @foreach ($vendeur as $row)
                                    <tr>
                                        <td>
                                            <span class="row-number">{{ $i }}</span>
                                        </td>
                                        <td>
                                            <span class="badge-code">
                                                <i class="mdi mdi-barcode"></i>
                                                {{ $row->code }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="bank-name">{{ $row->bank_name }}</span>
                                        </td>
                                        <td>
                                            <span class="username-text">
                                                <i class="mdi mdi-account-key"></i>
                                                {{ $row->username }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-percent">
                                                <i class="mdi mdi-percent"></i>
                                                {{ $row->percent ?? 0 }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if ($row->is_block == 1)
                                                <span class="badge-blocked">
                                                    <i class="mdi mdi-lock"></i> Bloke
                                                </span>
                                            @else
                                                <span class="badge-active">
                                                    <i class="mdi mdi-check-circle"></i> Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="sup_edit-vendeur" style="display: inline;">
                                                <input type="hidden" name="id" value="{{ $row->id }}" />
                                                <button type="submit" class="btn-edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                    Modifye
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
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('tableBody');
            const rows = tableBody.getElementsByTagName('tr');

            // Filter function
            function filterTable() {
                const searchText = searchInput.value.toLowerCase();

                for (let i = 0; i < rows.length; i++) {
                    let bankName = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();

                    // Check if both the bank name and branch match the filter
                    if ((bankName.includes(searchText) || searchText === '')) {
                        rows[i].style.display = ''; // Show row
                    } else {
                        rows[i].style.display = 'none'; // Hide row
                    }
                }
            }

            // Attach event listeners to the inputs
            searchInput.addEventListener('input', filterTable);
        });
    </script>
@endsection
