@extends('admin-layout')

@section('content')
    <style>
        /* Modern Enhanced Styles */
        .list-lo-container {
            padding: 0;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 16px 20px;
            border-radius: 12px 12px 0 0;
            margin-bottom: 0;
            box-shadow: 0 2px 12px rgba(102, 126, 234, 0.25);
        }

        .page-header h4 {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header h4 i {
            font-size: 24px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-body {
            padding: 20px;
            background: #fff;
        }

        /* Filter Section */
        .filter-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-section label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 14px;
            display: block;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        /* Button Styles */
        .btn-action-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn i {
            font-size: 18px;
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-gradient-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
        }

        .btn-gradient-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.5);
        }

        .btn-gradient-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);
        }

        .btn-gradient-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(250, 112, 154, 0.5);
        }

        /* Table Styles */
        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .table-modern thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .table-modern thead th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 700;
            color: white;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: none;
        }

        .table-modern thead th:first-child {
            padding-left: 16px;
        }

        .table-modern thead th:last-child {
            padding-right: 16px;
        }

        .table-modern tbody tr {
            background: white;
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-modern tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #fef5ff 100%);
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
        }

        .table-modern tbody td {
            padding: 10px 10px;
            vertical-align: middle;
            border: none;
            font-size: 13px;
            color: #4a5568;
        }

        .table-modern tbody td:first-child {
            padding-left: 16px;
            font-weight: 600;
            color: #2d3748;
        }

        .table-modern tbody td:last-child {
            padding-right: 16px;
        }

        /* Number Badges */
        .number-badges {
            display: inline-flex;
            gap: 6px;
            flex-wrap: nowrap;
            align-items: center;
        }

        .badge-number {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .badge-number:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        }

        .badge-red {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .badge-blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .badge-purple {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #764ba2;
        }

        .badge-green {
            background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        }

        /* Status Badge */
        .status-badge {
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            display: inline-block;
            box-shadow: 0 1px 4px rgba(17, 153, 142, 0.3);
        }

        /* Action Buttons */
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
        }

        .action-btn:hover {
            transform: translateY(-2px) rotate(3deg);
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
        }

        .action-btn i {
            font-size: 16px;
        }

        /* Loading Spinner */
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
            border-color: #667eea;
            border-right-color: transparent;
        }

        .loader-container, .no-data-container {
            text-align: center;
            padding: 30px;
        }

        .no-data-container p {
            color: #718096;
            font-style: italic;
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 12px 15px;
                border-radius: 10px 10px 0 0;
            }

            .page-header h4 {
                font-size: 16px;
                gap: 8px;
            }

            .page-header h4 i {
                font-size: 20px;
            }

            .card-body {
                padding: 15px;
            }

            .filter-section {
                padding: 15px;
                margin-bottom: 15px;
            }

            .btn-action-group {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
                padding: 10px 18px;
                font-size: 13px;
            }

            .number-badges {
                gap: 4px;
                flex-wrap: nowrap;
            }

            .badge-number {
                width: 30px;
                height: 30px;
                font-size: 11px;
                flex-shrink: 0;
            }

            .table-modern thead th {
                font-size: 10px;
                padding: 10px 8px;
            }

            .table-modern tbody td {
                font-size: 11px;
                padding: 8px 6px;
            }

            .table-modern tbody td:first-child,
            .table-modern thead th:first-child {
                padding-left: 10px;
            }

            .table-modern tbody td:last-child,
            .table-modern thead th:last-child {
                padding-right: 10px;
            }

            .action-btn {
                width: 30px;
                height: 30px;
            }

            .action-btn i {
                font-size: 14px;
            }

            .status-badge {
                font-size: 10px;
                padding: 3px 10px;
            }
        }
    </style>

    <div class="col-lg-12 grid-margin stretch-card list-lo-container">
        <div class="card">
            <div class="page-header">
                <h4>
                    <i class="mdi mdi-trophy-award"></i>
                    Lis Lo Ki Ajoute
                </h4>
            </div>
            
            <div class="card-body">
                <!-- Date Range Filter Form -->
                <form id="filterForm" method="GET" action="{{ route('listlo') }}" class="filter-section">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="date_debut">
                                <i class="mdi mdi-calendar-start"></i> Date Début
                            </label>
                            <input type="date" name="date_debut" id="date_debut" 
                                   class="form-control" 
                                   value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="date_fin">
                                <i class="mdi mdi-calendar-end"></i> Date Fin
                            </label>
                            <input type="date" name="date_fin" id="date_fin" 
                                   class="form-control" 
                                   value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="opacity: 0;">Actions</label>
                            <div class="btn-action-group">
                                <button type="submit" class="btn btn-gradient-primary">
                                    <i class="mdi mdi-filter-variant"></i>
                                    Filtrer
                                </button>
                                <a href="{{ route('listlo') }}" class="btn btn-gradient-secondary">
                                    <i class="mdi mdi-refresh"></i>
                                    Réinitialiser
                                </a>
                                <button type="button" id="exportPdfBtn" class="btn btn-gradient-danger">
                                    <i class="mdi mdi-file-pdf-box"></i>
                                    Exporter PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table-modern" id="dataTable">
                        <thead>
                            <tr>
                                <th>
                                    <i class="mdi mdi-trophy"></i> Tirage
                                </th>
                                <th>
                                    <i class="mdi mdi-calendar-clock"></i> Date
                                </th>
                                <th>
                                    <i class="mdi mdi-numeric"></i> Numéros Gagnants
                                </th>
                                <th>
                                    <i class="mdi mdi-check-circle"></i> Statut
                                </th>
                                <th style="text-align: center;">
                                    <i class="mdi mdi-cog"></i> Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @include('partials.list-items', ['list' => $list])
                        </tbody>
                    </table>
                </div>
                
                <div id="loader" class="loader-container" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p style="margin-top: 15px; color: #667eea; font-weight: 600;">Chargement des données...</p>
                </div>
                
                <div id="no-more-data" class="no-data-container" style="display: none;">
                    <i class="mdi mdi-information-outline" style="font-size: 48px; color: #cbd5e0;"></i>
                    <p>Il n'y a plus de données à charger.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        // PDF Export functionality
        $('#exportPdfBtn').on('click', function() {
            var dateDebut = $('#date_debut').val();
            var dateFin = $('#date_fin').val();
            
            var url = '{{ route("listlo.export.pdf") }}';
            
            if (dateDebut && dateFin) {
                url += '?date_debut=' + dateDebut + '&date_fin=' + dateFin;
            } else if (dateDebut) {
                url += '?date_debut=' + dateDebut;
            } else if (dateFin) {
                url += '?date_fin=' + dateFin;
            }
            
            window.location.href = url;
        });

        let page = 1;
        let hasMore = true;

        $(window).scroll(function() {
            if (hasMore && $(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                page++;
                loadMoreData(page);
            }
        });

        function loadMoreData(page) {
            $.ajax({
                url: '{{ route("load-more") }}?page=' + page,
                type: 'get',
                beforeSend: function() {
                    $('#loader').show();
                }
            })
            .done(function(data) {
                $('#loader').hide();
                if (data.html === "") {
                    $('#no-more-data').show();
                    hasMore = false;
                    return;
                }
                $("#table-body").append(data.html);
                if (!data.hasMore) {
                    $('#no-more-data').show();
                    hasMore = false;
                }
            })
            .fail(function(jqXHR, ajaxOptions, thrownError) {
                $('#loader').hide();
                console.log('Server error occurred');
            });
        }
    </script>
@endsection