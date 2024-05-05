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
    border: 2px solid  rgb(146, 146, 146) !important;
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
    .search-icon{
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
                <div class="nav-item" ><form style="display: flex; gap:10px;">
                    
                    <div class="search-wrapper">
                        <div class="search-input">
                        <input type="text" placeholder="Type to search..." id="searchInput">
                        <div id="suggestions">
                        @foreach($bank as $row)
                        <a href="rapport2?id={{$row->id}}">{{ $row->bank_name}}</a>
                       
                        @endforeach
                        </div>
                        <div class="search-icon"><i class="fas fa-search"></i></div>
                        <div class="error-message" id="errorMessage">No matching suggestion found</div>
                        <div class="cancel-button" id="cancelButton"><i class="fas fa-times-circle"></i></div>
                        </div>
                        </div>
                    
                    <input type="date" style="    calc(100% - 149px);
                    border-radius: 20px;
                    outline: none;
                    border: 2px solid #ccc;
                    border-radius: 50px;"/>
                </form></div>
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
                                <td>Otto</td>
                                <td>@mdo</td>
                                <td>02/21/2023</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
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
    <script id="rendered-js" >
        const searchInput = document.getElementById('searchInput');
        const suggestionsList = document.getElementById('suggestions');
        const errorMessage = document.getElementById('errorMessage');
        const cancelButton = document.getElementById('cancelButton');
        searchInput.addEventListener('input', function () {
        const inputValue = this.value.trim().toLowerCase();
        const suggestionItems = suggestionsList.querySelectorAll('a');
        let hasMatches = false;
        suggestionItems.forEach(function (listItem) {
        const textValue = listItem.textContent.toLowerCase();
        const displayStyle = textValue.includes(inputValue) ? 'block' : 'none';
        listItem.style.display = displayStyle;
        hasMatches = hasMatches || displayStyle === 'block';
        });
        suggestionsList.style.display = hasMatches ? 'block' : 'none';
        errorMessage.style.display = hasMatches || inputValue.length === 0 ? 'none' : 'block';
        cancelButton.style.display = inputValue.length > 0 ? 'block' : 'none';
        });
        cancelButton.addEventListener('click', function () {
        searchInput.value = '';
        suggestionsList.style.display = 'none';
        errorMessage.style.display = 'none';
        cancelButton.style.display = 'none';
        });
        //# sourceURL=pen.js
        </script>
@stop
