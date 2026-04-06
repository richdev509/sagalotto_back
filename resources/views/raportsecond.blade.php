@extends('admin-layout')
@section('content')
<style>
    :root { --vs-primary: 29 92 255; }
    .card-page { border:1px solid #eae6fb; border-radius:16px; background:#fff; box-shadow:0 6px 18px rgba(13,42,149,.06); margin:12px 10px; }
    .card-page .card-body { padding:20px 16px; }
  .card-title { letter-spacing:.5px; font-weight:800; color:#0d2a95; font-size:22px; }
  @media (max-width: 576px) {
    .card-title { font-size:16px; }
  }
    .form-label { font-weight:600; font-size:13px; color:#36435a; }
    input[type=date], select.form-control { width:100%; padding:8px 12px; height:42px; font-size:14px; border:1px solid #d9dde7; border-radius:10px; outline:none; }
    input[type=date]:focus, select.form-control:focus { border-color:#9aa7ff !important; box-shadow:0 0 0 3px rgba(154,167,255,.2); }
    .btn.primary { font-size:14px !important; padding:10px 18px !important; border-radius:10px !important; min-width:140px; color:#fff !important; background:rgb(var(--vs-primary)); box-shadow:0 10px 20px -10px rgba(29,92,255,.5); border:0; }
    .btn.primary:focus { box-shadow:0 0 0 .2rem rgba(29,92,255,.25); }
    .table thead th { font-weight:700; font-size:14px; }
    .table tbody td { font-size:13px; vertical-align:middle; }
    .table-hover tbody tr:hover { background:#f8fafc; }
    .badge-pos { color:#17803d; font-weight:700; }
    .badge-neg { color:#c62828; font-weight:700; }
    .table tbody td { color:#333; }
    .table tbody td.text-end.badge-pos { color:#17803d !important; }
    .table tbody td.text-end.badge-neg { color:#c62828 !important; }
    .table-wrap { margin-top:18px; }
  /* Keep native table layout on mobile; rely on .table-responsive to scroll horizontally */
</style>

<div class="card card-page">
  <div class="card-body">
  <h4 class="card-title text-center w-100" style="margin-bottom:6px;">Rapo general pou chak bank</h4>
  <form method="get" action="raport2" id="search" class="row g-2 align-items-end">
        @csrf
    <div class="col-4">
            <label class="form-label">Komanse</label>
            <input type="date" class="form-control dateInput" name="date_debut" value="{{ $date_debut }}" required />
        </div>
    <div class="col-4">
            <label class="form-label">Fini</label>
            <input type="date" class="form-control dateInput" name="date_fin" value="{{ $date_fin }}" required />
        </div>
    <div class="col-4">
            <label class="form-label">Branch</label>
            <select class="form-control" name="branch">
                <option value="tout">Tout</option>
                @foreach ($branch as $row)
                  <option value="{{ $row->id }}">{{ $row->name }}</option>
                @endforeach
            </select>
        </div>
    <div class="col-12 text-center mt-1">
            <button type="submit" class="btn primary"><i class="mdi mdi-chart-bar"></i> Fe rapo</button>
        </div>
    </form>

    <div class="table-wrap table-responsive">
      @php $total=0;$sum_vente=0;$sum_commission=0;$sum_perte=0;$sum_branch_commission=0; @endphp
      <table class="table table-striped table-hover table-sm align-middle responsive-table" id="myRapport">
        <thead style="background:#0d2a95;color:#fff;">
          <tr>
            <th>Bank <i class="mdi mdi-cash-register mdi-16px"></i></th>
            <th>Dat <i class="mdi mdi-calendar mdi-16px"></i></th>
            <th>Branch <i class="mdi mdi-source-branch mdi-16px"></i></th>
            <th class="text-end">Vant <i class="mdi mdi-tag mdi-16px"></i></th>
            <th class="text-end">Pedi <i class="mdi mdi-arrow-down-bold mdi-16px"></i></th>
            <th class="text-end">Komisyon Vandè <i class="mdi mdi-percent mdi-16px"></i></th>
            @if(isset($show_branch_commission) && $show_branch_commission)
            <th class="text-end">Komisyon Sipèvizè <i class="mdi mdi-account-supervisor mdi-16px"></i></th>
            @endif
            <th class="text-end">Balans <i class="mdi mdi-wallet mdi-16px"></i></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($vendeur as $row)
            @php
              $value = DB::table('users')->where('id',$row['bank_name'])->first(['bank_name','branch_id']);
              $branchName = DB::table('branches')->where('id',$value->branch_id)->value('name');
              $branchCommissionValue = isset($row['branch_commission']) ? $row['branch_commission'] : 0;
              $balanceCalc = round($row['vente'] - ($row['commission'] + $row['perte'] + $branchCommissionValue),2);
              $sum_vente += $row['vente'];
              $sum_commission += $row['commission'];
              $sum_perte += $row['perte'];
              $sum_branch_commission += $branchCommissionValue;
              $total += $balanceCalc;
            @endphp
            <tr>
              <td data-label="Bank">{{ $value->bank_name }}</td>
              <td data-label="Dat">{{ $date_debut }} => {{ $date_fin }}</td>
              <td data-label="Branch">{{ $branchName }}</td>
              <td data-label="Vant" class="text-end">{{ round($row['vente'],2) }} {{ Session('devise') }}</td>
              <td data-label="Pedi" class="text-end">{{ round($row['perte'],2) }} {{ Session('devise') }}</td>
              <td data-label="Komisyon Vandè" class="text-end">{{ round($row['commission'],2) }} {{ Session('devise') }}</td>
              @if(isset($show_branch_commission) && $show_branch_commission)
              <td data-label="Komisyon Sipèvizè" class="text-end">{{ round($branchCommissionValue,2) }} {{ Session('devise') }}</td>
              @endif
              <td data-label="Balans" class="text-end {{ $balanceCalc < 0 ? 'badge-neg':'badge-pos' }}">{{ $balanceCalc }} {{ Session('devise') }}</td>
            </tr>
          @endforeach
          @if($vendeur->isEmpty())
            <tr><td colspan="{{ isset($show_branch_commission) && $show_branch_commission ? '8' : '7' }}" class="text-center">Aucune donnée disponible</td></tr>
          @endif
        </tbody>
        <tfoot style="background:#0d2a95;color:#fff;">
          <tr>
            <td class="text-end fw-bold">Total</td>
            <td></td><td></td>
            <td class="text-end">{{ round($sum_vente,2) }} {{ Session('devise') }}</td>
            <td class="text-end">{{ round($sum_perte,2) }} {{ Session('devise') }}</td>
            <td class="text-end">{{ round($sum_commission,2) }} {{ Session('devise') }}</td>
            @if(isset($show_branch_commission) && $show_branch_commission)
            <td class="text-end">{{ round($sum_branch_commission,2) }} {{ Session('devise') }}</td>
            @endif
            <td class="text-end">@if($total>0)<span class="badge-pos">{{ round($total,2) }} {{ Session('devise') }}</span>@else<span class="badge-neg">{{ round($total,2) }} {{ Session('devise') }}</span>@endif</td>
          </tr>
        </tfoot>
      </table>
      <div class="mt-2 d-grid d-sm-flex justify-content-sm-end">
        <button id="exportJPG" class="btn primary"><i class="mdi mdi-download"></i> Telechaje</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.3.2/dist/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
  const today = new Date().toISOString().split('T')[0];
  document.querySelectorAll('.dateInput').forEach(el=>el.setAttribute('max',today));
  document.getElementById('exportJPG').addEventListener('click',()=>{
    html2canvas(document.getElementById('myRapport')).then(canvas=>{ const link=document.createElement('a'); link.href=canvas.toDataURL('image/jpeg'); link.download='rapport_vendeur.jpg'; link.click(); });
  });
});
</script>
@stop