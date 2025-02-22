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

        .btn-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: opacity 0.3s;
        }

        .btn-gradient-primary:hover {
            opacity: 0.9;
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

        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
        }

        /* Color for Tiraj Non */
        .tiraj-newyork-soir {
            color: blue;
        }

        .tiraj-newyork-matin {
            color: #06aafd;
        }

        .tiraj-florida-matin {
            color: #53ca8c;
        }

        .tiraj-florida-soir {
            color: #30be64;
        }

        .tiraj-georgia-matin {
            color: #be3030;
        }

        .tiraj-georgia-apresmidi {
            color: #fa8e8e;
        }
    </style>

    <div class="col-lg-12 grid-margin stretch-card" style="margin: 10px;">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Lis lo ki ajoute</h4>
                <div class="form-group">
                    <label for="dateFilter">Filtrer par date:</label>
                    <input type="date" id="dateFilter" class="form-control input">
                    <button onclick="filterTable()" class="btn btn-gradient-primary me-2">Filtrer</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr>
                                <th>Tiraj non</th>
                                <th>Date</th>
                                <th>Lo Yo</th>
                                <th>Etat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @include('partials.list-items', ['list' => $list])
                        </tbody>
                    </table>
                </div>
                <div id="loader" style="display: none; text-align: center; padding: 10px;">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div id="no-more-data" style="display: none; text-align: center; padding: 10px;">
                    <p>Il n'y a plus de données à charger.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        function filterTable() {
            var inputDate = $('#dateFilter').val();
            var table = $('#dataTable');

            table.find('tr').show();

            if (inputDate) {
                table.find('tbody tr').each(function () {
                    var rowDate = $(this).find('td:eq(1)').text();
                    var formattedRowDate = formatDate(rowDate, 'DD-MM-YYYY', 'YYYY-MM-DD');
                    if (formattedRowDate !== inputDate) {
                        $(this).hide();
                    }
                });
            }
        }

        function formatDate(dateString, inputFormat, outputFormat) {
            return moment(dateString, inputFormat).format(outputFormat);
        }

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