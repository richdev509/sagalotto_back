@extends('admin-layout')


@section('content')

    <style>
        /* Add your styles here */
        .search-wrapper {
            margin: 5px;
        }

        .search-input {
            position: relative;
            max-width: 300px;
            margin: auto;
        }

        input[type="text"] {
            width: calc(100% - -9px);
            padding: 8px;
            padding-left: 35px;
            outline: none;
            border: 2px solid #ccc;
            border-radius: 50px;
        }

        input[type="text"]:focus {
            border: 2px solid rgb(146, 146, 146) !important;
        }

        #suggestions {
            list-style: none;
            padding: 0;
            display: none;
            position: absolute;
            background-color: white;
            width: 100%;
            border: 1px solid #ccc;
            z-index: 1;
        }

        #suggestions a {
            display: block;
            padding: 8px;
            border-bottom: 1px solid #eee;
            text-decoration: none;
            color: #333;
        }

        #suggestions a:last-child {
            border-bottom: none;
        }

        input::placeholder {
            color: rgb(146, 146, 146);
        }

        .error-message {
            display: none;
            color: red;
            margin-top: 5px;
        }

        .cancel-button {
            position: absolute;
            top: 19px;
            right: 0px;
            transform: translateY(-50%);
            cursor: pointer;
            display: none;
        }

        .search-icon {
            position: absolute;
            top: 8px;
            left: 10px;
        }
    </style>
    <div class="card">
        <div class="border-bottom-0 p-0 card-header">
            <div class="nav-lb-tab nav card-header-undefined" role="tablist">

                <div class="row">

                    <form id="rapport_form">
                       @csrf
                        <div class="form-group" style="display:inline-flex;border: 1px solid #dc61e7;padding: 0px;">
                            <div>
                                <select class="form-control selectpicker" data-live-search="true" name="user">
                                    @foreach ($bank as $row)
                                        <option value="{{ $row->id }}">{{ $row->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input class="form-control" type="date" name="date" required>
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
        <div class="p-0 card-body">
            <div class="tab-content">
                <div role="tabpanel" id="react-aria-292-tabpane-design" aria-labelledby="react-aria-292-tab-design"
                    class="fade pb-4 p-4 tab-pane active show">
                    <div class="table-responsive">
                        <table class="text table">
                            <thead>
                                <tr>
                                    <th scope="col">CODE</th>
                                    <th scope="col">Vande</th>
                                    <th scope="col">Montant</th>
                                    <th scope="col">Balance</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>2000 HTG<div style="display: flex;gap:10px;"> <span
                                                class="badge bg-success">Paye</span></div>
                                    </td>
                                    <td>0 HTG </td>
                                    <td>02/21/2023</td>
                                </tr>
                               

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 15px;">
        <div class="border-bottom-0 p-0 card-header">
            <div class="nav-lb-tab nav card-header-undefined" role="tablist">
                <div class="nav-item"><a role="tab" data-rr-ui-event-key="design" id="react-aria-292-tab-design"
                        aria-controls="react-aria-292-tabpane-design" aria-selected="true"
                        class="mb-sm-3 mb-md-0 nav-link active" tabindex="0" href="#">Historique Transaction</a>
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
                                    <th scope="col">CODE</th>
                                    <th scope="col">ID_RAPO</th>
                                    <th scope="col">Montant</th>

                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>1</td>
                                    <td>2000 HTG</td>
                                    <td>02/21/2023</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulaire paiement vendeur</h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="mb-6">
                            <h4 class="mb-1" id="texte"></h4>
                        </div>
                        <form class="" id="paiement" style="">
                            <input type="hidden" name="id" value="" />
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="langauge">Date</label>
                                <div class="col-md-8 col-12"><input type="date" class="form-control" placeholder="date"
                                        id="edate" required="" name="date"></div>
                            </div>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="timeZone">Montant</label>
                                <div class="col-md-8 col-12"><input type="number" class="form-control"
                                        placeholder="montant Ex:5000" id="montant" required="" name="montant">
                                </div>
                            </div>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="dateFormat">Balance</label>
                                <div class="col-md-8 col-12"> <input type="number" class="form-control"
                                        placeholder="balance" id="balance" name="balance"
                                        style="pointer-events: none;"></div>
                            </div>

                            <div class="mb-3 row">

                                <div class="mt-2 col-md-8 col-12 offset-md-4"><button type="submit"
                                        class="btn btn-primary">Save Changes</button></div>
                            </div>
                        </form>

                        <form class="" id="paiement_balance" style="display: none;">
                            <input type="hidden" name="id" value="" />
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="langauge">Date</label>
                                <div class="col-md-8 col-12"><input type="date" class="form-control"
                                        placeholder="date" id="edate" required="" name="date"
                                        style="pointer-events: none;"></div>
                            </div>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="timeZone">balance</label>
                                <div class="col-md-8 col-12"><input type="number" class="form-control"
                                        placeholder="5000" id="balance" required="" name="balance"
                                        style="pointer-events: none;"></div>
                            </div>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="dateFormat">Montant</label>
                                <div class="col-md-8 col-12"> <input type="number" class="form-control"
                                        placeholder="montant" id="montant" name="montant"></div>
                            </div>

                            <div class="mb-3 row">

                                <div class="mt-2 col-md-8 col-12 offset-md-4"><button type="submit"
                                        class="btn btn-primary">Save Changes</button></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-secondary"
                        data-mdb-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JavaScript -->
   
    
    <!-- Initialisation de la modale Bootstrap -->
    <script>
        $(document).ready(function() {
            $("#rapport_form").submit(function(event) {
                // Prevent the default form submission
                event.preventDefault();

                // Get the form data
                var formData = $(this).serialize();

                // AJAX request
                $.ajax({
                    type: "POST",
                    url: "raport2_get_amount",
                    data: formData,
                    success: function(response) {
                        // Update the result div with the response
                        alert(response.montant);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@stop
