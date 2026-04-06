@extends('admin-layout')


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
        
        .branch-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 500;
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
            padding: 18px 20px;
            vertical-align: middle;
            color: #495057;
            font-size: 14px;
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
        
        .branch-name {
            font-weight: 600;
            color: #333;
            font-size: 15px;
        }
        
        .supervisor-name {
            color: #6c757d;
            font-size: 14px;
        }
        
        .supervisor-name i {
            color: #4B49AC;
            margin-right: 5px;
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
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 64px;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        .table-responsive {
            border-radius: 0 0 10px 10px;
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <i class="mdi mdi-source-branch" style="color: #4B49AC; margin-right: 10px;"></i>
            Lis Branch Yo
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branch</li>
            </ol>
        </nav>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-header-custom">
                    <h4 class="card-title-custom">
                        <span>
                            <i class="mdi mdi-store"></i>
                            Tout Branch Yo
                        </span>
                        <span class="branch-count">
                            <i class="mdi mdi-counter"></i>
                            {{$branch->count()}} Branch
                        </span>
                    </h4>
                </div>
                
                <div class="card-body p-0">
                    @if($branch->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">#</th>
                                        <th style="width: 150px;">Kòd Branch</th>
                                        <th>Non Branch</th>
                                        <th>Sipèvizè</th>
                                        <th style="width: 120px;" class="text-end">Aksyon</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($branch as $row)
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
                                                <span class="branch-name">{{ $row->name }}</span>
                                            </td>
                                            <td>
                                                <span class="supervisor-name">
                                                    <i class="mdi mdi-account-tie"></i>
                                                    {{ $row->agent_fullname ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <form action="editer_branch" style="display: inline;">
                                                    <input type="hidden" name="id" value="{{$row->id}}" />
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
                    @else
                        <div class="empty-state">
                            <i class="mdi mdi-store-off"></i>
                            <h5>Pa gen branch ankò</h5>
                            <p>Klike sou "Ajoute Branch" pou kreye premye branch ou</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
