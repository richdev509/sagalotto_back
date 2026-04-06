@extends('admin-layout')


@section('content')
        <!-- partial -->
      <style>
            .card-description{
                font-weight: bold;
                color: #333;
                font-size: 16px;
                margin-top: 20px;
                margin-bottom: 15px;
                padding-bottom: 8px;
                border-bottom: 1px solid #e0e0e0;
            }
            
            .form-card {
                background: white;
                border-radius: 10px;
                padding: 0;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            
            .form-content {
                background: white;
                border-radius: 10px;
                padding: 25px;
            }
            
            .form-group label {
                font-weight: 500;
                color: #495057;
                margin-bottom: 8px;
                font-size: 14px;
            }
            
            .form-control {
                border: 1px solid #ced4da;
                border-radius: 5px;
                padding: 10px 15px;
                transition: all 0.2s ease;
                font-size: 14px;
            }
            
            .form-control:focus {
                border-color: #4B49AC;
                box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.15);
            }
            
            .radio-card {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 12px;
                margin-bottom: 10px;
                transition: all 0.2s ease;
                cursor: pointer;
            }
            
            .radio-card:hover {
                border-color: #4B49AC;
                background: #f3f4f6;
                box-shadow: 0 2px 8px rgba(75, 73, 172, 0.1);
            }
            
            .radio-card input[type="radio"]:checked + label {
                color: #4B49AC;
                font-weight: 500;
            }
            
            .radio-card.active {
                border-color: #4B49AC;
                background: #f8f9ff;
                box-shadow: 0 2px 8px rgba(75, 73, 172, 0.15);
            }
            
            .btn-submit {
                background: #4B49AC;
                border: none;
                padding: 10px 30px;
                font-size: 14px;
                font-weight: 500;
                border-radius: 5px;
                color: white;
                transition: all 0.2s ease;
            }
            
            .btn-submit:hover {
                background: #3d3a8c;
                box-shadow: 0 2px 8px rgba(75, 73, 172, 0.3);
            }
            
            .icon-input {
                position: relative;
            }
            
            .icon-input i {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: #4B49AC;
                font-size: 16px;
            }
            
            .icon-input .form-control {
                padding-left: 45px;
            }
            
            .section-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                background: #4B49AC;
                border-radius: 5px;
                margin-right: 8px;
                color: white;
                font-size: 14px;
            }
            
            .page-header h3 {
                color: #333;
                font-weight: 600;
                font-size: 1.5rem;
            }
            
            .error {
                color: #e74c3c;
                font-size: 13px;
                margin-top: 5px;
                display: block;
            }
            
            .percent-input-box {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 15px;
                border: 1px dashed #ced4da;
            }
            
            .branch-info-header {
                background: linear-gradient(135deg, #4B49AC 0%, #6f6bb8 100%);
                padding: 15px 25px;
                margin: -30px -30px 25px -30px;
                border-radius: 10px 10px 0 0;
            }
            
            .branch-code-display {
                color: white;
                font-size: 16px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .branch-code-display i {
                font-size: 24px;
            }
            
            .branch-code-display strong {
                background: rgba(255, 255, 255, 0.2);
                padding: 6px 15px;
                border-radius: 6px;
                font-weight: 600;
                letter-spacing: 1px;
            }
      </style>
          
              <div class="page-header">
                <h3 class="page-title">Modifye Branch</h3>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin">Akèy</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifye Branch</li>
                  </ol>
                </nav>
              </div>
              <div class="row">          
                <div class="col-12">
                  <div class="card form-card">
                    <div class="card-body form-content">
                      
                      <div class="branch-info-header">
                        <div class="branch-code-display">
                          <i class="mdi mdi-barcode"></i>
                          <span>Kòd Branch:</span>
                          <strong>{{$branch->code}}</strong>
                        </div>
                      </div>
                      
                      <form class="form-sample" method="post" action="editerBranch">
                        @csrf
                        <p class="card-description">
                          <span class="section-icon">
                            <i class="mdi mdi-information"></i>
                          </span>
                          Info Sou Branch Lan
                        </p>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Non Branch Lan</label>
                              <div class="col-sm-9 icon-input">
                                <input type="hidden" name="id" value="{{$branch->id}}" />
                                <i class="mdi mdi-store"></i>
                                <input type="text" name="name" value="{{$branch->name}}" class="form-control" placeholder="Ba li yon non" />
                                <span class="error">@error('name') 
                                   {{$message}}
                                  @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Adrès Branch Lan</label>
                              <div class="col-sm-9 icon-input">
                                <i class="mdi mdi-map-marker"></i>
                                <input type="text" name="address" value="{{$branch->address}}" class="form-control" placeholder="Adrès branch la" />
                                <span class="error">@error('address') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-3 col-form-label">Telefòn Branch La</label>
                                  <div class="col-sm-9 icon-input">
                                    <i class="mdi mdi-phone"></i>
                                    <input type="text" class="form-control" value="{{$branch->phone}}"  placeholder="Nimewo telefòn" name="phone" />
                                    <span class="error">@error('phone') 
                                      {{$message}}
                                     @enderror</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-3 col-form-label">Deskripsyon Branch La</label>
                                  <div class="col-sm-9 icon-input">
                                    <i class="mdi mdi-text"></i>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Ekri yon ti deskripsyon...">{{$branch->description}}</textarea>

                                    <span class="error">@error('description') 
                                      {{$message}}
                                     @enderror</span>
                                  </div>
                                </div>
                              </div>
                       
                        </div>
                      
                        <p class="card-description">
                          <span class="section-icon">
                            <i class="mdi mdi-account-tie"></i>
                          </span>
                          Info Sipèvizè
                        </p>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Non Konplè</label>
                              <div class="col-sm-9 icon-input">
                                <i class="mdi mdi-account"></i>
                                <input type="text" name="agent_fullname" value="{{$branch->agent_fullname}}" class="form-control" placeholder="Tout non sipèvizè a" />
                                <span class="error">@error('agent_name')
                                    {{$message}}
                                @enderror</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Non Itilizatè</label>
                              <div class="col-sm-9 icon-input">
                                <i class="mdi mdi-account-key"></i>
                                <input type="text" class="form-control" value="{{$branch->agent_username}}" disabled style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;"/>
                                <input type="hidden" class="form-control" name="agent_username" value="{{$branch->agent_username}}" />
                                <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 5px;">
                                  <i class="mdi mdi-information-outline"></i> Non itilizatè pa ka chanje
                                </small>
                                <span class="error">@error('agent_username') 
                                  {{$message}}
                                 @enderror</span>
                              </div>
                              
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Modpas</label>
                              <div class="col-sm-9 icon-input">
                                <i class="mdi mdi-lock"></i>
                                <input type="password" name="agent_password" value="" class="form-control" placeholder="Kite vid si w pa vle chanje"/>
                              </div>
                            </div>
                          </div>
                         
                          
                        </div>
                        
                        <p class="card-description">
                          <span class="section-icon">
                            <i class="mdi mdi-percent"></i>
                          </span>
                          Konfigirasyon Pousantaj
                        </p>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label style="font-weight: 500; color: #495057; margin-bottom: 10px; font-size: 14px;">Tip Pousantaj</label>
                              <div class="radio-card" onclick="selectRadio('percent_both')">
                                <input class="form-check-input" type="radio" name="percentage_type" id="percent_both" value="both" {{ $branch->percent_agent_only == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="percent_both" style="cursor: pointer; margin-left: 10px; font-size: 14px;">
                                  <i class="mdi mdi-account-multiple" style="color: #4B49AC;"></i>
                                  <strong>% Vandè + % Sipèvizè</strong>
                                  <br>
                                  <small style="color: #6c757d;">Pousantaj separe pou vandè ak sipèvizè</small>
                                </label>
                              </div>
                              <div class="radio-card" onclick="selectRadio('percent_agent_only')">
                                <input class="form-check-input" type="radio" name="percentage_type" id="percent_agent_only" value="agent_only" {{ $branch->percent_agent_only == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="percent_agent_only" style="cursor: pointer; margin-left: 10px; font-size: 14px;">
                                  <i class="mdi mdi-account-tie" style="color: #4B49AC;"></i>
                                  <strong>% Sipèvizè Sèlman</strong>
                                  <br>
                                  <small style="color: #6c757d;">Pousantaj pou sipèvizè sèlman</small>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label style="font-weight: 500; color: #495057; margin-bottom: 10px; font-size: 14px;">Pousantaj</label>
                              <div class="percent-input-box">
                                <div class="icon-input">
                                  <i class="mdi mdi-percent"></i>
                                  <input type="number" id="percent_value" name="percent_value" class="form-control" placeholder="Antre pousantaj pou sipèvizè" min="0" max="100" step="0.01" value="{{ $branch->percent ?? 0 }}"/>
                                </div>
                                <small style="color: #6c757d; display: block; margin-top: 8px; font-size: 13px;">
                                  <i class="mdi mdi-information"></i> Antre yon valè ant 0 ak 100
                                </small>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <script>
                          function selectRadio(id) {
                            document.getElementById(id).checked = true;
                            updateRadioCards();
                            updatePercentValue();
                          }
                          
                          function updateRadioCards() {
                            const cards = document.querySelectorAll('.radio-card');
                            cards.forEach(card => {
                              const radio = card.querySelector('input[type="radio"]');
                              if (radio.checked) {
                                card.classList.add('active');
                              } else {
                                card.classList.remove('active');
                              }
                            });
                          }
                          
                          function updatePercentValue() {
                            const percentBothRadio = document.getElementById('percent_both');
                            const percentInput = document.getElementById('percent_value');
                            
                            if (percentBothRadio.checked) {
                              // When "% vandè + % sipèvizè" is selected, set to 0
                              percentInput.value = 0;
                            }
                          }
                          
                          document.addEventListener('DOMContentLoaded', function() {
                            updateRadioCards();
                            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                              radio.addEventListener('change', function() {
                                updateRadioCards();
                                updatePercentValue();
                              });
                            });
                          });
                        </script>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Estati Branch</label>
                              <div class="col-sm-9">
                                <div class="radio-card" style="display:inline-block; margin-right:20px;">
                                  <input type="radio" id="block_branch" name="is_block" value="1" {{ $branch->is_block == 1 ? 'checked' : '' }}>
                                  <label for="block_branch" style="margin-left:8px; cursor:pointer;">Bloke</label>
                                </div>
                                <div class="radio-card" style="display:inline-block;">
                                  <input type="radio" id="unblock_branch" name="is_block" value="0" {{ $branch->is_block == 0 ? 'checked' : '' }}>
                                  <label for="unblock_branch" style="margin-left:8px; cursor:pointer;">Debloke</label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 text-center" style="margin-top: 30px;">
                            <button type="submit" class="btn-submit">
                              <i class="mdi mdi-check-circle"></i>
                              Modifye Branch Lan
                            </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            
          
        
          <!-- main-panel ends -->
    
@endsection