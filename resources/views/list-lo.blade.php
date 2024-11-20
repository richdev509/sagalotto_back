@extends('admin-layout')
@section('content')

<style>
  .spinner-border {
      width: 3rem;
      height: 3rem;
      border-width: 0.3rem;
  }
  </style>
 
  <div class="col-lg-12 grid-margin stretch-card" style="margin: 10px;border-style:ridge; border-width:1px; border-color:rgb(209, 163, 252);">
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
                              <th> Tiraj non </th>
                              <th> Date </th>
                              <th> Lo Yo </th>
                              <th> Etat </th>
                              <th> Action </th>
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
  @stop
  