@extends('admin-layout')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    
    <div class="card">
        
      <div class="card-body">
        
        <h4 class="card-title">Lis lo ki ajoute</h4>
       
        </p>
        <div class="form-group">
          <label for="dateFilter">Filtrer par date:</label>
          <input type="date" id="dateFilter" class="form-control input">
          <button onclick="filterTable()" class="btn btn-gradient-primary me-2">Filtrer</button>
      </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
              <thead>
                <tr>
                  <th> Tiraj non </th>
                  <th> Date </th>
                  <th> Lo Yo </th>
                  <th> Etat </th>
                  <th> Action </th>
                </tr>
              </thead>
              <tbody>
                @foreach($list as $lists)
                <tr>
                    <td>{{$lists->tirage->name}}</td>
                    <td>{{ \Carbon\Carbon::parse($lists->created_at)->format('d-m-Y') }}
                    </td>
                    <td><button type="button" class="btn btn-social-icon btn-youtube btn-rounded">{{$lists->unchiffre}}</button>  
                        <button type="button" class="btn btn-social-icon btn-facebook btn-rounded">{{$lists->premierchiffre}}</button>
                        <button type="button" class="btn btn-social-icon btn-dribbble btn-rounded">{{$lists->secondchiffre}}</button>
                        <button type="button" class="btn btn-social-icon btn-linkedin btn-rounded">{{$lists->troisiemechiffre}}</button>
                    </td>
                    <td>Tiraj 100%</td>
                    <td>Modifier</td>
                </tr>
                @endforeach
              </tbody>
            </table>
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

    // Réinitialiser le filtre
    table.find('tr').show();

    // Filtrer par date
    if (inputDate) {
        console.log('input:'+inputDate);
        table.find('tbody tr').each(function () {
            var rowDate = $(this).find('td:eq(1)').text(); // Colonne de la date
            console.log(rowDate);
            // Reformater la date de la colonne au format YYYY-MM-DD
            var formattedRowDate = formatDate(rowDate, 'DD-MM-YYYY', 'YYYY-MM-DD');
            console.log('formatted suivi de row'+formattedRowDate);
            if (formattedRowDate !== inputDate) {
                $(this).hide();
            }
        });
    }
}

// Fonction pour reformater une date
function formatDate(dateString, inputFormat, outputFormat) {
    return moment(dateString, inputFormat).format(outputFormat);
}

</script>





@stop