@extends('admin-layout')
@section('content')

    <!-- resources/views/votre_vue.blade.php -->

    <div class="card">
        <div class="border-bottom-0 p-0 card-header">
            <div class="nav-lb-tab nav card-header-undefined" role="tablist">
                <div class="row">
                    <form id="rapport_form">
                        @csrf
                        <div class="form-group" style="display:inline-flex;border: 1px solid #dc61e7;padding: 0px;">
                            <div>
                                <select class="form-control selectpicker" data-live-search="true" name="user">
                                    <option disabled></option>
                                    @foreach ($list as $liste)
                                        <option value="{{ $liste->id }}">{{ $liste->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input class="form-control" type="number" name="boul" max="99999" required>
                            </div>
                            <div>
                                <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary">
                                    Chache
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 15px;">
        <div class="border-bottom-0 p-0 card-header">
            <div class="nav-lb-tab nav card-header-undefined" role="tablist">
                <div class="nav-item"><a role="tab" data-rr-ui-event-key="design" id="react-aria-292-tab-design"
                        aria-controls="react-aria-292-tabpane-design" aria-selected="true"
                        class="mb-sm-3 mb-md-0 nav-link active" tabindex="0" href="#">Statistik</a>
                </div>
                <div class="nav-item"></div>
            </div>
        </div>

        <div class="p-0 card-body">
            <div class="tab-content">
                <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                    class="fade pb-4 p-4 tab-pane active show">
                    <div class="table-responsive">
                        <table class="text table">
                            <thead>
                                <tr>
                                    <th scope="col">Type</th>
                                    <th scope="col">Boul</th>
                                    <th scope="col">Nfois</th>
                                    <th scope="col">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>
                        <div style="width: 100%;display:flex;justify-content: center;">
                            <p style="display:none;margin-top: 20px;font-weight: bolder;" id="message">
                                Pa gen statistik pou rechech ou an, pou jounen an</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- resources/views/statistique/statistiqueunique.blade.php -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#rapport_form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'getstatistiqueSimple',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $(this).serialize(),
                    success: function(data) {
                        console.log(data);
                        var tbody = $('table tbody');
                        tbody
                            .empty(); // Vider le tableau avant d'ajouter les nouveaux résultats
                        if (data && data.length > 0) {
                            //

                            data.forEach(function(jeu) {
                                var row = '<tr>' +
                                    '<td>' + jeu.type + '</td>' +
                                    '<td>' + jeu.boul + '</td>' +
                                    '<td>' + jeu.nf + '</td>' +
                                    '<td>' + jeu.montant + '</td>' +
                                    '</tr>';
                                tbody.append(row);
                            });
                            document.getElementById("message").style.display = "none";
                        } else {
                            document.getElementById("message").style.display = "block";
                        }
                    }
                });
            });
        });
    </script>


@stop
