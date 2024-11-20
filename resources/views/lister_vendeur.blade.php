@extends('admin-layout')


@section('content')
    <div class="page-header" style="">
        <h3 class="page-title">Lis vandè </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                <li class="breadcrumb-item active" aria-current="page">Vandè</li>
            </ol>
        </nav>
    </div>
    <div class="row" style="margin: 10px;border-style:ridge; border-width:1px; border-color:rgb(209, 163, 252);">
        <div class="card" style="margin: 1px;padding:1px;">
            <div class="card-body">
                <div class="row align-items-center">

                    <div class="col-12 col-md-12">
                        <h6 class="header-title">Chache</h6>
                    </div>
                    <!-- 3 Select Inputs with Live Search -->


                    <div class="col-12 col-md-6">
                        <input type="text" style="height: 35px;" class="form-control search-input" id="search"
                            placeholder="Antre nom yon bank la...">
                    </div>

                    <div class="col-12 col-md-6">
                        <select class="form-select select-box live-search" id="branchFilter" style="width: 100%;">
                            <option value="Tout">Tout branch</option>
                            @foreach ($branch as $row)
                                <option>{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kod</th>
                                <th>Bank</th>
                                <th>Branch</th>
                                <th>Itilizatè</th>
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

                                    @if ($row->is_block == 1)
                                        <td style="color:red;">Wi</td>
                                    @else
                                        <td style="color:green;">Non</td>
                                    @endif

                                    <td class="text-end">
                                        <form action="editer-vendeur">
                                            <input type="hidden" name="id" value="{{ $row->id }}" />
                                            <button type="submit"><i class="mdi mdi-table-edit"></i></button>

                                        </form>


                                    </td>
                                </tr>
                                @php $i =$i +1 ; @endphp
                            @endforeach



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
