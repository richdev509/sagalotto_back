@extends('admin-layout')
@section('content')

    <div class="p-6 container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="border-bottom pb-4 mb-4 ">
                    <h3 class="mb-0 fw-bold">Billing</h3>
                </div>
            </div>
        </div>
        <div class="mt-6 row">
            <div class="col-xl-8 col-lg-10 col-md-12 col-12 offset-xl-2 offset-lg-1">
                <div class="row">
                    <div class="mb-6 col-12">
                        <div class="card">
                            <div class="p-4 bg-white card-header">
                                <h4 class="mb-0">Plan ki aktive</h4>
                            </div>
                            <div class="card-body">
                                <div class="row row">
                                    <div class="col-xl-8 col-lg-6 col-md-12 col-12">
                                        <div class="mb-2">
                                            <p class="text-muted mb-0">Plan aktive</p>
                                            <h3 class="mt-2 mb-3 fw-bold">Demare le - {{ \Carbon\Carbon::parse($data->dateplan)->format('j M Y') }} </h3>
                                            <p>Sa se plan kew genyen ki aktive li debite nan dat kew te peye e lap kanpe 5 Jou apre dat ekspirasyon an
                                            <p>

                                                <i class="fe fe-info fs-4 me-2 text-muted icon-xs"></i>Wap gen pou peye nan lel expire:
                                                <span class="text-primary">$10.00 USD</span><span
                                                    class="text-dark fw-bold"> {{ \Carbon\Carbon::parse($data->dateexpiration)->format('j M Y') }}
                                                </span>
                                            </p>
                                            <p>
                                                <?php
    use Carbon\Carbon;
    // Date de plan
    $datePlan = Carbon::parse($data->dateplan);
    // Date d'expiration
    $dateExpiration = Carbon::parse($data->dateexpiration);
    // Calculer le nombre de jours restants
    $dateActuelle = \Carbon\Carbon::now();
    $joursRestants = $datePlan->diffInDays($dateExpiration);
   
    
    $pourcentageJoursRestants =($joursEcoules * 100) / 31 ;
?>

                                                Ou rete {{ $joursRestants }} Jour(s)<div class="progress">
                                                    <div class="progress-bar bg-gradient-danger" role="progressbar" style="width:{{ $pourcentageJoursRestants }}%" aria-valuenow="{{ $pourcentageJoursRestants }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                  </div>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-6 col-md-12 col-12">
                                        <div><small class="text-muted">Peye pa Mwa</small>
                                            <h1 class="fw-bold text-primary">$ {{$data->plan}} USD</h1>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white card-footer">
                                <div class="d-md-flex justify-content-between align-items-center">
                                    <div class="mb-3 mb-lg-0 text-center text-sm-start">
                                        <h5 class="text-uppercase mb-0">Metod pou peye Moncash</h5>
                                        <div class="mt-2"><img src="/images/creditcard/mastercard.svg" alt=""
                                                class=""> <span class="fw-bold">40302231</span></div>
                                    </div>
                                    
                            </div>
                        </div>
                    </div>
                    <div class="mb-6 col-12">
                        <div class="card">
                            <div class="p-4 bg-white card-header">
                                
                            </div>
                            <div class="card-body">
                                <div class="align-items-center row">
                                    <div class="mb-4 mb-lg-0 col-lg-6 col-md-12 col-12">
                                        <div class="mb-3 mb-lg-0">
                                            <div class="form-check"><label
                                                    for="shippingBillingAddress1" class="form-check-label"><span
                                                        class="d-block mb-3 text-dark fw-bold">Enfomasyon Konpani
                                                        Non</span><span class="d-block text-dark fw-medium fs-4">{{$data->name}}</span>
                                                    <span class="d-block mb-4">{{$data->address}}, {{$data->city}}
                                                       </span>
                                                    </label></div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-lg-end col-lg-6 col-md-12 col-12">
                                        <div class="mb-2">
                                            <p class="mb-1">E-mail: <a
                                                    >{{$data->email}}</a></p>
                                            <p>Phone:{{$data->phone}}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <hr class="my-6">
                                    </div>
                                    
                                    
                                    <div class="col-12">
                                        <hr class="mt-6 mb-4">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
