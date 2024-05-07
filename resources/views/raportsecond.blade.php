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
                <div class="nav-item"><a role="tab" data-rr-ui-event-key="design" id="react-aria-292-tab-design"
                        aria-controls="react-aria-292-tabpane-design" aria-selected="true"
                        class="mb-sm-3 mb-md-0 nav-link active" tabindex="0" href="#">Rapo vande</a></div>
                
                <div class="nav-item">
                    <form style="display: flex; gap:10px;">

                        <div class="search-wrapper">
                            <div class="search-input">
                                <input type="text" placeholder="Type to search..." id="searchInput">
                                <div id="suggestions">
                                    @foreach ($bank as $row)
                                        <a href="rapport2?id={{ $row->id }}">{{ $row->bank_name }}</a>
                                    @endforeach
                                </div>
                                <div class="search-icon"><i class="fas fa-search"></i></div>
                                <div class="error-message" id="errorMessage">No matching suggestion found</div>
                                <div class="cancel-button" id="cancelButton"><i class="fas fa-times-circle"></i></div>
                            </div>
                        </div>

                        <input type="date"
                            style="    calc(100% - 149px);
                    border-radius: 20px;
                    outline: none;
                    border: 2px solid #ccc;
                    border-radius: 50px;" />
                    </form>
                </div>
                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary" data-mdb-modal-init
                    data-mdb-target="#exampleModal" style="margin-left:20px;margin-top:20px;">
                    Ajouter paiement
                </button>
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
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Jacob</td>
                                    <td>4000</td>
                                    <td>2000 HTG <button type="button" id="balancebtn"> <span
                                                class="badge bg-info"    >Akite</span></button>
                                    </td>

                                    <td>02/21/2023</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Larry</td>
                                    <td>the Bird</td>
                                    <td>@twitter</td>
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
                            <input type="hidden" name="id" value=""/>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="langauge">Date</label>
                                <div class="col-md-8 col-12"><input type="date" class="form-control"
                                        placeholder="date" id="edate" required="" name="date"></div>
                            </div>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="timeZone">Montant</label>
                                <div class="col-md-8 col-12"><input type="number" class="form-control"
                                        placeholder="montant Ex:5000" id="montant" required="" name="montant"></div>
                            </div>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="dateFormat">Balance</label>
                                <div class="col-md-8 col-12"> <input type="number" class="form-control"
                                        placeholder="balance" id="balance" name="balance" style="pointer-events: none;"></div>
                            </div>
                            
                            <div class="mb-3 row">
                                
                                <div class="mt-2 col-md-8 col-12 offset-md-4"><button type="submit"
                                        class="btn btn-primary">Save Changes</button></div>
                            </div>
                        </form>

                        <form class="" id="paiement_balance" style="display: none;">
                            <input type="hidden" name="id" value=""/>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="langauge">Date</label>
                                <div class="col-md-8 col-12"><input type="date" class="form-control"
                                        placeholder="date" id="edate" required="" name="date" style="pointer-events: none;"></div>
                            </div>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="timeZone">balance</label>
                                <div class="col-md-8 col-12"><input type="number" class="form-control"
                                        placeholder="5000" id="balance" required="" name="balance" style="pointer-events: none;"></div>
                            </div>
                            <div class="mb-3 row"><label class="col-md-4 form-label" for="dateFormat">Montant</label>
                                <div class="col-md-8 col-12"> <input type="number" class="form-control"
                                        placeholder="montant" id="montant" name="montant" ></div>
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDGtiDZklfDzbrjRizqgyj/21zZ+OwSY9OCn" crossorigin="anonymous"></script>

    <!-- Initialisation de la modale Bootstrap -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupération du bouton de déclenchement de la modale
            var button = document.querySelector('[data-mdb-target="#exampleModal"]');
            var formulaire=document.getElementById('paiement_balance');
            var formulairepaiement=document.getElementById('paiement');
            var texte=document.getElementById('texte');
            // Ajout d'un écouteur d'événements au clic sur le bouton
            button.addEventListener('click', function() {
                // Récupération de la modale cible
                var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
                formulaire.style.display='none';
                formulairepaiement.style.display="block";
                texte.innerHTML="Paiement";
                // Affichage de la modale
                modal.show();
                
            });

            var buttonbalance =document.getElementById('balancebtn');
           
            buttonbalance.addEventListener('click', function() {
                // Récupération de la modale cible
                var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
                formulaire.style.display='block';
                formulairepaiement.style.display="none";
                texte.innerHTML="Balance Paiment";
                // Affichage de la modale
                modal.show();
            });



            var closeButton = document.querySelector('[data-mdb-dismiss="modal"]');

    // Ajout d'un écouteur d'événements au clic sur le bouton de fermeture
    closeButton.addEventListener('click', function () {
      // Récupération de la modale parente du bouton
      var modal = closeButton.closest('.modal');
      
      // Fermeture de la modale
      var modalInstance = bootstrap.Modal.getInstance(modal);
      modalInstance.hide();
    });
        });
    </script>


    <script id="rendered-js">
        const searchInput = document.getElementById('searchInput');
        const suggestionsList = document.getElementById('suggestions');
        const errorMessage = document.getElementById('errorMessage');
        const cancelButton = document.getElementById('cancelButton');
        searchInput.addEventListener('input', function() {
            const inputValue = this.value.trim().toLowerCase();
            const suggestionItems = suggestionsList.querySelectorAll('a');
            let hasMatches = false;
            suggestionItems.forEach(function(listItem) {
                const textValue = listItem.textContent.toLowerCase();
                const displayStyle = textValue.includes(inputValue) ? 'block' : 'none';
                listItem.style.display = displayStyle;
                hasMatches = hasMatches || displayStyle === 'block';
            });
            suggestionsList.style.display = hasMatches ? 'block' : 'none';
            errorMessage.style.display = hasMatches || inputValue.length === 0 ? 'none' : 'block';
            cancelButton.style.display = inputValue.length > 0 ? 'block' : 'none';
        });
        cancelButton.addEventListener('click', function() {
            searchInput.value = '';
            suggestionsList.style.display = 'none';
            errorMessage.style.display = 'none';
            cancelButton.style.display = 'none';
        });
        //# sourceURL=pen.js
    </script>
@stop
