@extends('admin-layout')

@section('content')
    <style>
        /* Modern color palette */
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --success-color: #00b894;
            --danger-color: #d63031;
            --warning-color: #fdcb6e;
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
            border-spacing: 0;
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%);
            color: white;
            border: none;
            padding: 12px 15px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table thead th:first-child {
            border-radius: 0;
        }
        
        .table thead th:last-child {
            border-radius: 0;
        }

        .table tbody tr {
            background-color: white;
            transition: all 0.3s ease;
            border-bottom: 2px solid #f0f2f5;
        }

        .table tbody tr:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(75, 73, 172, 0.15);
            z-index: 5;
        }

        .table tbody td {
            padding: 12px 15px;
            border: none;
            vertical-align: middle;
            font-size: 13px;
        }
        
        .table tbody td:first-child {
            border-radius: 0;
        }
        
        .table tbody td:last-child {
            border-radius: 0;
        }

        /* Row color indicators */
        .row-has-limit {
            background: linear-gradient(to right, rgba(255, 87, 87, 0.12) 0%, rgba(255, 87, 87, 0.03) 100%) !important;
            border-left: 5px solid #ff5757 !important;
            box-shadow: 0 2px 8px rgba(255, 87, 87, 0.15) !important;
        }
        
        .row-has-limit:hover {
            background: linear-gradient(to right, rgba(255, 87, 87, 0.15) 0%, rgba(255, 87, 87, 0.05) 100%) !important;
            box-shadow: 0 6px 20px rgba(255, 87, 87, 0.25) !important;
        }

        .row-no-pay-branch {
            background: linear-gradient(to right, rgba(253, 203, 110, 0.15) 0%, rgba(253, 203, 110, 0.04) 100%) !important;
            border-left: 5px solid #ffa502 !important;
            box-shadow: 0 2px 8px rgba(253, 203, 110, 0.15) !important;
        }
        
        .row-no-pay-branch:hover {
            background: linear-gradient(to right, rgba(253, 203, 110, 0.18) 0%, rgba(253, 203, 110, 0.06) 100%) !important;
            box-shadow: 0 6px 20px rgba(253, 203, 110, 0.25) !important;
        }

        /* Action buttons group */
        .action-buttons-group {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            align-items: center;
        }

        .btn-action {
            background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(75, 73, 172, 0.4);
        }

        .btn-action i {
            font-size: 16px;
        }

        .btn-action.btn-edit {
            background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%);
        }

        .btn-action.btn-prize {
            background: linear-gradient(135deg, #00b894 0%, #00d9a3 100%);
        }

        .btn-action.btn-limit {
            background: linear-gradient(135deg, #ff7675 0%, #fd79a8 100%);
        }

        /* Dropdown toggle */
        .dropdown-toggle-custom {
            background: linear-gradient(135deg, #636e72 0%, #95a5a6 100%);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .dropdown-toggle-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 110, 114, 0.4);
        }

        .dropdown-menu-custom {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            z-index: 1000;
            padding: 8px 0;
        }

        .dropdown-menu-custom.show {
            display: block;
        }

        .dropdown-item-custom {
            padding: 10px 20px;
            color: #2d3436;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 14px;
        }

        .dropdown-item-custom:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .dropdown-item-custom i {
            font-size: 18px;
        }

        .position-relative {
            position: relative;
        }

        @media (max-width: 768px) {
            .col-md-6 {
                margin-bottom: 15px;
            }
            .action-buttons-group {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Modal Styles */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 2000;
            overflow-y: auto;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .custom-modal-content {
            background-color: white;
            margin: 3% auto;
            width: 85%;
            max-width: 900px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .custom-modal-header {
            padding: 15px 25px;
            background: linear-gradient(135deg, #4361ee 0%, #6f6bb8 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .custom-close-btn {
            font-size: 28px;
            cursor: pointer;
            color: white;
            background: rgba(255, 255, 255, 0.2);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .custom-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .custom-modal-body {
            padding: 20px;
        }

        .modal-description {
            color: #6c757d;
            margin-bottom: 24px;
            font-size: 15px;
        }

        .vendor-name-display {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .vendor-name-display i {
            font-size: 24px;
            color: #4361ee;
        }

        .vendor-name-display strong {
            font-size: 18px;
            color: #2d3436;
        }

        /* Form Styles */
        .price-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .limit-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .price-section {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .price-section:hover {
            border-color: #4361ee;
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.1);
        }

        .section-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .section-header:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        }

        .section-header h4 {
            margin: 0;
            color: #2d3436;
            font-size: 16px;
            font-weight: 600;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toggle-icon {
            font-size: 14px;
            transition: transform 0.2s;
            color: #6c757d;
        }

        .section-content {
            padding: 20px;
            background-color: white;
        }

        .price-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .limit-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .price-group, .limit-group {
            display: flex;
            flex-direction: column;
        }

        .price-group label, .limit-group label {
            display: inline-block;
            margin-bottom: 8px;
            font-weight: 600;
            color: white;
            font-size: 13px;
            background: linear-gradient(135deg, #636e72 0%, #95a5a6 100%);
            padding: 6px 14px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(99, 110, 114, 0.2);
            transition: all 0.2s ease;
        }
        
        .price-group label:hover, .limit-group label:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(99, 110, 114, 0.3);
        }

        .input-group {
            display: flex;
            align-items: center;
            border: 1px solid #ced4da;
            border-radius: 6px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .input-group:focus-within {
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .input-label {
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-right: 1px solid #ced4da;
            font-size: 13px;
            font-weight: 500;
            color: #495057;
        }

        .price-select, .limit-input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            outline: none;
            background-color: white;
            font-size: 14px;
        }

        .price-select {
            color: #dc3545;
            font-weight: 600;
        }

        /* Toggle Switch for sections */
        .toggle-switch {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: relative;
            cursor: pointer;
            width: 50px;
            height: 24px;
            background-color: #ced4da;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #4361ee;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .toggle-label {
            font-size: 13px;
            color: #495057;
            font-weight: 500;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .cancel-btn {
            padding: 12px 24px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 6px;
            color: #495057;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .cancel-btn:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }
        
        .delete-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .delete-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }

        .submit-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #4361ee 0%, #6f6bb8 100%);
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.4);
        }

        /* Form switch for limit form */
        .form-check-switch {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-check-switch input[type="checkbox"] {
            width: 50px;
            height: 24px;
            cursor: pointer;
        }

        /* Dropdown Menu Styles */
        .dropdown-actions {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle-btn {
            background: linear-gradient(135deg, #636e72 0%, #95a5a6 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .dropdown-toggle-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 110, 114, 0.4);
        }

        .dropdown-toggle-btn i {
            font-size: 16px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 5px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            min-width: 180px;
            z-index: 1000;
            padding: 6px 0;
            border: 1px solid #e9ecef;
            animation: dropdownFade 0.2s ease;
        }

        .dropdown-content.show {
            display: block;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 10px 16px;
            color: #2d3436;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 13px;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        .dropdown-item i {
            font-size: 16px;
        }

        .dropdown-item.edit-item:hover {
            background: linear-gradient(135deg, rgba(75, 73, 172, 0.1) 0%, rgba(111, 107, 184, 0.1) 100%);
            color: #4B49AC;
        }

        .dropdown-item.prize-item:hover {
            background: linear-gradient(135deg, rgba(0, 184, 148, 0.1) 0%, rgba(0, 217, 163, 0.1) 100%);
            color: #00b894;
        }

        .dropdown-item.limit-item:hover {
            background: linear-gradient(135deg, rgba(255, 118, 117, 0.1) 0%, rgba(253, 121, 168, 0.1) 100%);
            color: #ff7675;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .col-md-6 {
                margin-bottom: 15px;
            }
            
            .custom-modal-content {
                width: 95%;
                margin: 5% auto;
            }

            .price-row, .limit-row {
                grid-template-columns: 1fr;
            }
            
            .dropdown-content {
                right: auto;
                left: 0;
            }
        }
    </style>

    <div class="page-header" style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); padding: 10px 15px; border-radius: 8px; color: white; box-shadow: 0 3px 10px rgba(108, 92, 231, 0.3); margin-bottom: 15px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="page-title" style="color: white; margin: 0; font-size: 1.3rem; font-weight: 700;">
                <span class="page-title-icon" style="background: rgba(255, 255, 255, 0.2); padding: 8px; border-radius: 6px; margin-right: 10px; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="mdi mdi-account-multiple" style="font-size: 18px;"></i>
                </span> 
                Lis Vandè yo
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: transparent; margin: 0; padding: 0;">
                    <li class="breadcrumb-item"><a href="admin" style="color: rgba(255, 255, 255, 0.9);">Akèy</a></li>
                    <li class="breadcrumb-item" style="color: white; font-weight: 600;">Vandè</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius: 15px; overflow: hidden; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);">
                <div style="background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%); padding: 12px 18px; color: white;">
                    <h5 style="margin: 0; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                        <i class="mdi mdi-format-list-bulleted" style="font-size: 20px;"></i>
                        Tout Vandè yo
                        <span style="background: rgba(255, 255, 255, 0.2); padding: 3px 10px; border-radius: 15px; font-size: 12px; margin-left: auto;">Total: {{ count($vendeur) }}</span>
                    </h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 10px; padding: 18px; margin-bottom: 20px; border: 2px solid #e9ecef;">
                        <h6 style="color: #4B49AC; font-weight: 600; margin-bottom: 15px; display: flex; align-items: center; gap: 6px;">
                            <i class="mdi mdi-magnify" style="font-size: 18px;"></i>
                            Filtre ak Rechèch
                        </h6>
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6">
                                <label style="font-weight: 500; color: #495057; margin-bottom: 6px; display: block; font-size: 13px;">Chèche pa non bank</label>
                                <input type="text" class="form-control search-input" id="search"
                                    placeholder="Antre non yon bank la..." style="border: 2px solid #dee2e6; padding: 8px 12px;">
                            </div>
                            <div class="col-12 col-md-6">
                                <label style="font-weight: 500; color: #495057; margin-bottom: 6px; display: block; font-size: 13px;">Chwazi branch</label>
                                <select class="form-select select-box live-search" id="branchFilter" style="border: 2px solid #dee2e6; padding: 8px 12px;">
                                    <option value="Tout">Tout branch</option>
                                    @foreach ($branch as $row)
                                        <option>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                                    <th>Pri lo branch</th>
                                    <th>Limit jeneral</th>
                                    <th class="text-end">Aksyon</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @php $i = 1; @endphp
                                @foreach ($vendeur as $row)
                                    @php
                                        $hasLimit = isset($limitsVendeur[$row->id]);
                                        $hasNoPayBranch = isset($rulesVendeur[$row->id]);
                                        $rowClass = '';
                                        if ($hasLimit) {
                                            $rowClass = 'row-has-limit';
                                        } elseif ($hasNoPayBranch) {
                                            $rowClass = 'row-no-pay-branch';
                                        }
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>
                                            <span style="font-weight: 700; color: #6c5ce7; font-size: 15px;">{{ $i }}</span>
                                        </td>
                                        <td>
                                            <span style="background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%); color: white; padding: 6px 14px; border-radius: 8px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                                                <i class="mdi mdi-barcode" style="font-size: 16px;"></i>
                                                {{ $row->code }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-weight: 600; color: #2d3436; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                                                <i class="mdi mdi-store" style="color: #6c5ce7; font-size: 18px;"></i>
                                                {{ $row->bank_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                $value = DB::table('branches')
                                                    ->where('id', $row['branch_id'])
                                                    ->value('name');
                                            ?>
                                            <span style="background: #e3f2fd; color: #1976d2; padding: 6px 12px; border-radius: 6px; font-weight: 500; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                                                <i class="mdi mdi-source-branch" style="font-size: 16px;"></i>
                                                {{ $value }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="color: #636e72; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                                                <i class="mdi mdi-account-key" style="color: #6c5ce7; font-size: 16px;"></i>
                                                {{ $row->username }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="color: #2d3436; font-size: 14px; font-weight: 500;">{{ $row->percent }} %</span>
                                        </td>
                                        <td>
                                            @if ($row->is_block == 1)
                                                <span style="color: #d63031; font-size: 14px; font-weight: 600;">Wi</span>
                                            @else
                                                <span style="color: #00b894; font-size: 14px; font-weight: 600;">Non</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $hasPriLo = isset($rulesVendeur[$row->id]);
                                            @endphp
                                            @if ($hasPriLo)
                                                <span style="color: #00b894; font-size: 14px; font-weight: 600;">Non</span>
                                            @else
                                                <span style="color: #636e72; font-size: 14px; font-weight: 500;">Wi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $hasLimit = isset($limitsVendeur[$row->id]);
                                            @endphp
                                            @if ($hasLimit)
                                                <span style="color: #d63031; font-size: 14px; font-weight: 600;">Non</span>
                                            @else
                                                <span style="color: #636e72; font-size: 14px; font-weight: 500;">Wi</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown-actions">
                                                <button class="dropdown-toggle-btn" onclick="toggleDropdown(event, this)">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-content">
                                                    <form action="editer-vendeur" style="margin: 0;">
                                                        <input type="hidden" name="id" value="{{ $row->id }}" />
                                                        <button type="submit" class="dropdown-item edit-item">
                                                            <i class="mdi mdi-pencil"></i>
                                                            Modifye
                                                        </button>
                                                    </form>
                                                    
                                                    <button type="button" class="dropdown-item prize-item" 
                                                            onclick="openPriLoModal({{ $row->id }}, '{{ $row->bank_name }}', {{ json_encode($rulesVendeur[$row->id] ?? null) }})">
                                                        <i class="mdi mdi-trophy"></i>
                                                        Pri Lo Gagnan
                                                    </button>
                                                    
                                                    <button type="button" class="dropdown-item limit-item" 
                                                            onclick="openLimitModal({{ $row->id }}, '{{ $row->bank_name }}', {{ json_encode($limitsVendeur[$row->id] ?? null) }})">
                                                        <i class="mdi mdi-gauge"></i>
                                                        Limit Pri Acha
                                                    </button>
                                                </div>
                                            </div>
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

    <!-- Pri Lo Gagnan Modal -->
    <div id="priLoModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3>
                    <i class="mdi mdi-trophy"></i>
                    Pri Gagnan Lo
                </h3>
                <span class="custom-close-btn" onclick="closePriLoModal()">&times;</span>
            </div>
            <div class="custom-modal-body">
                <div class="vendor-name-display">
                    <i class="mdi mdi-store"></i>
                    <strong id="priLoVendorName"></strong>
                </div>
                <p class="modal-description">Ajiste pri lo yo pou vandè sa a</p>

                <form class="price-form" action="{{ route('updateprilovendeur') }}" method="POST" id="priLoForm">
                    @csrf
                    <input type="hidden" name="id" id="priLoRuleId" />
                    <input type="hidden" name="user_id" id="priLoUserId" />

                    <!-- Bolet Prices Section -->
                    <div class="price-section">
                        <div class="section-header" onclick="toggleSection(this)">
                            <h4>
                                <i class="mdi mdi-ticket-confirmation"></i>
                                Pri Bolet
                            </h4>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="price-row">
                                <div class="price-group">
                                    <label>Pri 1e lo</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix" class="price-select" id="prix1" required>
                                            <option value="">null</option>
                                            <option value="60">60</option>
                                            <option value="50">50</option>
                                            <option value="55">55</option>
                                            <option value="65">65</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri 2e lo</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_second" class="price-select" id="prix2" required>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="15">15</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri 3e lo</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_third" class="price-select" id="prix3" required>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maryaj & Loto Section -->
                    <div class="price-section">
                        <div class="section-header" onclick="toggleSection(this)">
                            <h4>
                                <i class="mdi mdi-cards-heart"></i>
                                Pri Maryaj ak Loto
                            </h4>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="price-row">
                                <div class="price-group">
                                    <label>Pri maryaj</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_maryaj" class="price-select" id="prixMaryaj" required>
                                                                                        <option value="1000">1000</option>
                                            <option value="1000">1000</option>
                                            <option value="700">700</option>

                                            <option value="750">750</option>
                                            <option value="1250">1250</option>
                                            <option value="1500">1500</option>
                                            <option value="2000">2000</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri loto3</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_loto3" class="price-select" id="prixLoto3" required>
                                            <option value="">null</option>
                                            
                                            <option value="500">500</option>
                                            <option value="600">600</option>
                                            <option value="750">750</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri loto4</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_loto4" class="price-select" id="prixLoto4" required>
                                            <option value="">null</option>
                                            <option value="5000">5000</option>
                                            <option value="6000">6000</option>
                                            <option value="7500">7500</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri loto5</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_loto5" class="price-select" id="prixLoto5" required>
                                            <option value="">null</option>
                                            <option value="25000">25000</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maryaj Gratis Section -->
                    <div class="price-section">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <h4>
                                    <i class="mdi mdi-gift"></i>
                                    Pri Maryaj Gratis
                                </h4>
                                <input type="hidden" name="maryaj_statut" value="0">
                                <label class="toggle-switch">
                                    <input type="checkbox" id="toggleMaryajGratis" name="maryaj_statut" value="1">
                                    <span class="slider"></span>
                                    <span class="toggle-label">Aktive</span>
                                </label>
                            </div>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="price-row">
                                <div class="price-group">
                                    <label>Pri maryaj gratis</label>
                                    <div class="input-group">
                                        <select name="prix_maryaj_gratis" class="price-select" id="prixMaryajGratis" required>
                                            <option value="">null</option>
                                            <option value="1000">1000</option>
                                            <option value="1500">1500</option>
                                            <option value="2000">2000</option>
                                            <option value="2500">2500</option>
                                            <option value="3000">3000</option>
                                            <option value="3500">3500</option>
                                            <option value="4000">4000</option>
                                            <option value="4500">4500</option>
                                            <option value="5000">5000</option>
                                            <option value="5500">5500</option>
                                            <option value="6000">6000</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gabel Section -->
                    <div class="price-section">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <h4>
                                    <i class="mdi mdi-alpha-g-circle"></i>
                                    Pri Gabel
                                </h4>
                                <input type="hidden" name="gabel_statut" value="0">
                                <label class="toggle-switch">
                                    <input type="checkbox" id="toggleGabel" name="gabel_statut" value="1">
                                    <span class="slider"></span>
                                    <span class="toggle-label">Aktive</span>
                                </label>
                            </div>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="price-row">
                                <div class="price-group">
                                    <label>Pri gabel 1e</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_gabel1" class="price-select" id="prixGabel1">
                                            <option>20</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="price-group">
                                    <label>Pri gabel 2e</label>
                                    <div class="input-group">
                                        <span class="input-label">X fwa</span>
                                        <select name="prix_gabel2" class="price-select" id="prixGabel2">
                                            <option>10</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="cancel-btn" onclick="closePriLoModal()">
                            <i class="mdi mdi-close"></i>
                            Fèmen
                        </button>
                        <div style="display: flex; gap: 12px;">
                            <a href="#" id="deletePriLoBtn" class="delete-btn" style="display: none; text-decoration: none;">
                                <i class="mdi mdi-delete"></i>
                                Siprime
                            </a>
                            <button type="submit" class="submit-btn">
                                <i class="mdi mdi-check"></i>
                                Mete a jou
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Limit Pri Acha Modal -->
    <div id="limitModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3>
                    <i class="mdi mdi-cash-lock"></i>
                    Limit Pri Acha
                </h3>
                <span class="custom-close-btn" onclick="closeLimitModal()">&times;</span>
            </div>
            <div class="custom-modal-body">
                <div class="vendor-name-display">
                    <i class="mdi mdi-store"></i>
                    <strong id="limitVendorName"></strong>
                </div>
                <p class="modal-description">Defini limit pri acha pou vandè sa a</p>

                <div class="info-box">
                    <i class="mdi mdi-information"></i>
                    <div class="info-box-content">
                        <p><strong>Enfòmasyon sou Kwoché Limit:</strong></p>
                        <p>📌 <strong>Lè l aktive:</strong> Limit vandè sa a andan limit jeneral la. Li depann de limit jeneral.</p>
                        <p>📌 <strong>Lè l dezaktive:</strong> Limit vandè sa a pa kroche ak limit jeneral la. Li endepandan.</p>
                    </div>
                </div>

                <form class="limit-form" action="{{ route('savelimitvendeur') }}" method="POST" id="limitForm">
                    @csrf
                    <input type="hidden" name="id" id="limitId" />
                    <input type="hidden" name="user_id" id="limitUserId" />

                    <!-- Included Toggle -->
                    <div class="price-section" style="border-color: #2196f3;">
                        <div class="section-header" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                            <div class="section-title">
                                <h4 style="color: #1976d2;">
                                    <i class="mdi mdi-link-variant"></i>
                                    Kwoché ak Limit Jeneral
                                </h4>
                                <input type="hidden" name="included" value="0">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="included" value="1" id="includedActive">
                                    <span class="slider"></span>
                                    <span class="toggle-label">Kwoché</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Bolet Limit -->
                    <div class="price-section">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <h4>
                                    <i class="mdi mdi-ticket-confirmation"></i>
                                    Bolet
                                </h4>
                                <input type="hidden" name="boletetat" value="0">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="boletetat" value="1" id="boletActive">
                                    <span class="slider"></span>
                                    <span class="toggle-label">Aktive</span>
                                </label>
                            </div>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="limit-row">
                                <div class="limit-group">
                                    <label>Limit Pri (HTG)</label>
                                    <div class="input-group">
                                        <span class="input-label">
                                            <i class="mdi mdi-currency-usd"></i>
                                        </span>
                                        <input type="number" name="bolet" class="limit-input" id="limitBolet" 
                                               placeholder="0" min="0" step="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maryaj Limit -->
                    <div class="price-section">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <h4>
                                    <i class="mdi mdi-cards-heart"></i>
                                    Maryaj
                                </h4>
                                <input type="hidden" name="maryajetat" value="0">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="maryajetat" value="1" id="maryajActive">
                                    <span class="slider"></span>
                                    <span class="toggle-label">Aktive</span>
                                </label>
                            </div>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="limit-row">
                                <div class="limit-group">
                                    <label>Limit Pri (HTG)</label>
                                    <div class="input-group">
                                        <span class="input-label">
                                            <i class="mdi mdi-currency-usd"></i>
                                        </span>
                                        <input type="number" name="maryaj" class="limit-input" id="limitMaryaj" 
                                               placeholder="0" min="0" step="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loto3 Limit -->
                    <div class="price-section">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <h4>
                                    <i class="mdi mdi-dice-3"></i>
                                    Loto 3
                                </h4>
                                <input type="hidden" name="loto3etat" value="0">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="loto3etat" value="1" id="loto3Active">
                                    <span class="slider"></span>
                                    <span class="toggle-label">Aktive</span>
                                </label>
                            </div>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="limit-row">
                                <div class="limit-group">
                                    <label>Limit Pri (HTG)</label>
                                    <div class="input-group">
                                        <span class="input-label">
                                            <i class="mdi mdi-currency-usd"></i>
                                        </span>
                                        <input type="number" name="loto3" class="limit-input" id="limitLoto3" 
                                               placeholder="0" min="0" step="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loto4 Limit -->
                    <div class="price-section">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <h4>
                                    <i class="mdi mdi-dice-4"></i>
                                    Loto 4
                                </h4>
                                <input type="hidden" name="loto4etat" value="0">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="loto4etat" value="1" id="loto4Active">
                                    <span class="slider"></span>
                                    <span class="toggle-label">Aktive</span>
                                </label>
                            </div>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="limit-row">
                                <div class="limit-group">
                                    <label>Limit Pri (HTG)</label>
                                    <div class="input-group">
                                        <span class="input-label">
                                            <i class="mdi mdi-currency-usd"></i>
                                        </span>
                                        <input type="number" name="loto4" class="limit-input" id="limitLoto4" 
                                               placeholder="0" min="0" step="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loto5 Limit -->
                    <div class="price-section">
                        <div class="section-header" onclick="toggleSection(this)">
                            <div class="section-title">
                                <h4>
                                    <i class="mdi mdi-dice-5"></i>
                                    Loto 5
                                </h4>
                                <input type="hidden" name="loto5etat" value="0">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="loto5etat" value="1" id="loto5Active">
                                    <span class="slider"></span>
                                    <span class="toggle-label">Aktive</span>
                                </label>
                            </div>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="section-content">
                            <div class="limit-row">
                                <div class="limit-group">
                                    <label>Limit Pri (HTG)</label>
                                    <div class="input-group">
                                        <span class="input-label">
                                            <i class="mdi mdi-currency-usd"></i>
                                        </span>
                                        <input type="number" name="loto5" class="limit-input" id="limitLoto5" 
                                               placeholder="0" min="0" step="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="delete-btn" onclick="deleteLimitVendeur()" id="deleteLimitBtn" style="display: none;">
                            <i class="mdi mdi-delete"></i>
                            Siprime
                        </button>
                        <button type="button" class="cancel-btn" onclick="closeLimitModal()">
                            <i class="mdi mdi-close"></i>
                            Fèmen
                        </button>
                        <button type="submit" class="submit-btn">
                            <i class="mdi mdi-check"></i>
                            Anrejistre
                        </button>
                    </div>
                </form>
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

        // Pri Lo Modal Functions
        function openPriLoModal(userId, vendorName, rulesData) {
            const modal = document.getElementById('priLoModal');
            const form = document.getElementById('priLoForm');
            
            // Set vendor name
            document.getElementById('priLoVendorName').textContent = vendorName;
            document.getElementById('priLoUserId').value = userId;
            
            // Reset form
            form.reset();
            
            // If rules exist, populate form
            if (rulesData && rulesData.id) {
                document.getElementById('priLoRuleId').value = rulesData.id;
                
                // Set all price values
                if (rulesData.prix) document.getElementById('prix1').value = rulesData.prix;
                if (rulesData.prix_second) document.getElementById('prix2').value = rulesData.prix_second;
                if (rulesData.prix_third) document.getElementById('prix3').value = rulesData.prix_third;
                if (rulesData.prix_maryaj) document.getElementById('prixMaryaj').value = rulesData.prix_maryaj;
                if (rulesData.prix_loto3) document.getElementById('prixLoto3').value = rulesData.prix_loto3;
                if (rulesData.prix_loto4) document.getElementById('prixLoto4').value = rulesData.prix_loto4;
                if (rulesData.prix_loto5) document.getElementById('prixLoto5').value = rulesData.prix_loto5;
                if (rulesData.prix_maryaj_gratis) document.getElementById('prixMaryajGratis').value = rulesData.prix_maryaj_gratis;
                
                // Set toggle switches
                document.getElementById('toggleMaryajGratis').checked = rulesData.maryaj_statut == 1;
                document.getElementById('toggleGabel').checked = rulesData.gabel_statut == 1;
                
                // Show and set delete button
                const deleteBtn = document.getElementById('deletePriLoBtn');
                deleteBtn.style.display = 'flex';
                deleteBtn.href = 'deleteprilo_vendeur/' + rulesData.id;
            } else {
                document.getElementById('priLoRuleId').value = '';
                
                // Hide delete button if no rules exist
                document.getElementById('deletePriLoBtn').style.display = 'none';
            }
            
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closePriLoModal() {
            const modal = document.getElementById('priLoModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Limit Modal Functions
        function openLimitModal(userId, vendorName, limitData) {
            const modal = document.getElementById('limitModal');
            const form = document.getElementById('limitForm');
            
            // Set vendor name
            document.getElementById('limitVendorName').textContent = vendorName;
            document.getElementById('limitUserId').value = userId;
            
            // Reset form
            form.reset();
            
            // If limit exists, populate form and show delete button
            if (limitData && limitData.id) {
                document.getElementById('limitId').value = limitData.id;
                
                // Set all limit values
                if (limitData.bolet) document.getElementById('limitBolet').value = limitData.bolet;
                if (limitData.maryaj) document.getElementById('limitMaryaj').value = limitData.maryaj;
                if (limitData.loto3) document.getElementById('limitLoto3').value = limitData.loto3;
                if (limitData.loto4) document.getElementById('limitLoto4').value = limitData.loto4;
                if (limitData.loto5) document.getElementById('limitLoto5').value = limitData.loto5;
                
                // Set toggle switches
                document.getElementById('includedActive').checked = limitData.included == 1;
                document.getElementById('boletActive').checked = limitData.boletetat == 1;
                document.getElementById('maryajActive').checked = limitData.maryajetat == 1;
                document.getElementById('loto3Active').checked = limitData.loto3etat == 1;
                document.getElementById('loto4Active').checked = limitData.loto4etat == 1;
                document.getElementById('loto5Active').checked = limitData.loto5etat == 1;
                
                // Show and set delete button
                const deleteBtn = document.getElementById('deleteLimitBtn');
                deleteBtn.style.display = 'flex';
                deleteBtn.onclick = function() { deleteLimitVendeur(limitData.id); };
            } else {
                document.getElementById('limitId').value = '';
                
                // Hide delete button if no limit exists
                document.getElementById('deleteLimitBtn').style.display = 'none';
            }
            
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeLimitModal() {
            const modal = document.getElementById('limitModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Delete limit function
        function deleteLimitVendeur(limitId) {
            if (confirm('Èske ou sèten ou vle siprime limit sa a?')) {
                window.location.href = 'deletelimit_vendeur/' + limitId;
            }
        }

        // Toggle section content
        function toggleSection(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.toggle-icon');

            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.textContent = '▼';
            } else {
                content.style.display = 'none';
                icon.textContent = '▶';
            }
        }

        // Toggle dropdown menu
        function toggleDropdown(event, button) {
            event.stopPropagation();
            const dropdown = button.closest('.dropdown-actions');
            const content = dropdown.querySelector('.dropdown-content');
            const isOpen = content.classList.contains('show');
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-content.show').forEach(d => {
                d.classList.remove('show');
            });
            
            // Toggle current dropdown
            if (!isOpen) {
                content.classList.add('show');
            }
        }

        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            const priLoModal = document.getElementById('priLoModal');
            const limitModal = document.getElementById('limitModal');
            
            if (e.target === priLoModal) {
                closePriLoModal();
            }
            if (e.target === limitModal) {
                closeLimitModal();
            }
            
            // Close all dropdowns when clicking outside
            if (!e.target.closest('.dropdown-actions')) {
                document.querySelectorAll('.dropdown-content.show').forEach(d => {
                    d.classList.remove('show');
                });
            }
        });

        // Prevent toggle switch clicks from triggering section collapse
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-switch').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
@endsection