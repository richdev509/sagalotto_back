@extends('admin-layout')


@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Lis vandè yo</h4>
       
        </p>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Bank</th>
              <th>Kod</th>
              <th>Itilizatè</th>
              <th>Aktif</th>
              <th>Bloke</th>
              <th class="text-end">Aksyon</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Dave</td>
              <td>53275535</td>
              <td>53275535</td>
            
              <td><label class="badge badge-success">Wi</label></td>
              <td><label class="badge badge-warning">Wi</label></td>
              <td class="text-end">
                  <i class="mdi mdi-eye
                  "> Afiche</i>

              </td>
            </tr>
            <tr>
                <td>Dave</td>
                <td>53275535</td>
                <td>53275535</td>
              
                <td><label class="badge badge-warning">Non</label></td>
                <td><label class="badge badge-warning">Non</label></td>
                <td class="text-end">
                    <form action="afficherVendeur">
                        
                        <button type="submit"><i class="mdi mdi-eye"> Afiche</i></button>

                    </form>
                    
  
                </td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>




@endsection