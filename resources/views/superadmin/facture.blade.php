@extends('superadmin.admin-layout')
@section('content')

    <div class="card shadow-sm mb-3">
        <div class="card-body py-2">
            <ul class="nav nav-pills nav-fill gap-2 small" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#gen-section" class="nav-link" id="tab-gen">Générer</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#hist-section" class="nav-link active" id="tab-hist">Historique factures</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Générer section -->
    <div id="gen-section" class="card mb-4 box-shadow d-none">
        <div class="card-header" style="background:#0e75b3;color:white;">
            <h4 class="my-0 font-weight-normal">Générer une facture</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('genererfacture') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company">Compagnie</label>
                            <select name="company" id="company" class="form-control" data-live-search="true" style="height: 50px;">
                                @foreach ($data as $row)
                                    @php $displayName = $row->fullname ?? $row->name; @endphp
                                    <option value="{{$row->id}}">{{$row->code}}({{$displayName}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date">Date d'expiration</label>
                            <input type="date" class="form-control" name="date" required />
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary w-100 w-md-auto">Générer</button>
                </div>

                @if(isset($facture) && $facture == 1)
                    <div class="mt-4">
                        <h4 class="mb-3">Sagaloto.com</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2">Facture du {{$date}}</th>
                                </tr>
                            </thead>
                            <tbody style="border: 1px solid #ac32cb;">
                                <tr>
                                    @php $compDisp = $compagnie->fullname ?? $compagnie->name; @endphp
                                    <td colspan="2" class="text-center">{{$compDisp}}, Tel {{$compagnie->phone}}</td>
                                </tr>
                                <tr>
                                    <td>POS Actifs pour ce mois:</td>
                                    <td>{{ $vendeur }}</td>
                                </tr>
                                <tr>
                                    <td>Montant par POS:</td>
                                    <td>{{ $compagnie->plan }}$</td>
                                </tr>
                                <tr>
                                    <td>Total:</td>
                                    <td>{{ $vendeur * $compagnie->plan }}$</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="mt-2 mb-0 small text-muted">NB: Ou gen yon delai de 5 jou pouw peye. Apresa system nan ap bloke ou otomatik. pou tout difikilte kontakte administrasyon an.</p>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Historique section -->
    <div id="hist-section" class="card shadow">
        <div class="card-header" style="background:#5a91c6;color:white;">
            <h5 class="mb-0">Historique des factures</h5>
        </div>
        <div class="card-body p-0">
            @php
                $factures = \App\Models\facture::orderBy('due_date','desc')->orderBy('created_at','desc')->get();
            @endphp
            <div class="p-3 border-bottom bg-light">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span>
                            <input type="text" id="filter-company" class="form-control" placeholder="Filtrer par compagnie">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="month" id="filter-month" class="form-control form-control-sm" />
                    </div>
                    <div class="col-md-3">
                        <select id="filter-status" class="form-select form-select-sm">
                            <option value="all">Tous</option>
                            <option value="paid">Payée</option>
                            <option value="unpaid">Non payée</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" id="clear-filters" class="btn btn-sm btn-outline-secondary w-100">Réinitialiser</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Compagnie</th>
                            <th>Nbr POS</th>
                            <th>Date d'échéance</th>
                            <th>A payer</th>
                            <th>Payer</th>
                            <th>Statut</th>
                            <th>Facture</th>
                            <th>Mois ajouté</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="hist-body">
                        @forelse($factures as $f)
                            @php 
                                $c = \App\Models\company::find($f->compagnie_id);
                                $cName = ($c->name) ?? ('#'.$f->compagnie_id);
                                $dueYm = \Carbon\Carbon::parse($f->due_date)->format('Y-m');
                                $dueYmd = \Carbon\Carbon::parse($f->due_date)->format('Y-m-d');
                            @endphp
                            <tr data-company="{{ strtolower($cName) }}" data-ym="{{ $dueYm }}" data-status="{{ $f->is_paid ? 'paid' : 'unpaid' }}">
                                <td>{{ $f->id }}</td>
                                <td>{{ $cName }}</td>
                                <td>{{ $f->number_pos ?? '-' }}</td>

                                <td>{{ $dueYmd }}</td>
                                <td>{{ $f->amount }} $</td>
                                <td>{{ $f->paid_amount ?? 0 }} $</td>
                                <td>
                                    @if($f->is_paid)
                                        <span class="badge bg-success">Payée</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Non payée</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($f->facture_image))
                                        <a href="{{ route('regenerate_facture_show', $f->id) }}" class="btn btn-sm btn-outline-secondary">Voir</a>
                                    @else
                                        <a href="{{ route('regenerate_facture_show', $f->id) }}" class="btn btn-sm btn-warning">Regénérer & Voir</a>
                                    @endif
                                </td>
                                <td>
                                    @php
                                    if($f->month_added==0){
                                        $addedMonth = 'Non';
                                    }else{
                                        $addedMonth = 'Oui';
                                    }
                                    @endphp
                                    {{ $addedMonth }}

                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#voirFactureModal"
                                                   data-id="{{ $f->id }}"
                                                   data-company="{{ $cName }}"
                                                   data-duedate="{{ $dueYmd }}"
                                                   data-amount="{{ $f->amount }}"
                                                   data-paid="{{ $f->paid_amount ?? 0 }}"
                                                   data-status="{{ $f->is_paid ? 'Payée' : 'Non payée' }}"
                                                   data-method="{{ $f->payment_method ?? '' }}"
                                                   data-paymentid="{{ $f->payment_id ?? '' }}"
                                                   data-description="{{ $f->description ?? '' }}"
                                                   data-image="{{ !empty($f->facture_image) ? asset($f->facture_image) : '' }}"
                                                >Voir</a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#payerFactureModal"
                                                   data-id="{{ $f->id }}"
                                                   data-company="{{ $cName }}"
                                                   data-duedate="{{ $dueYmd }}"
                                                   data-amount="{{ $f->amount }}"
                                                   data-paid="{{ $f->paid_amount ?? 0 }}"
                                                   data-method="{{ $f->payment_method ?? '' }}"
                                                   data-paymentid="{{ $f->payment_id ?? '' }}"
                                                   data-description="{{ $f->description ?? '' }}"
                                                >Payer</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Aucune facture enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const gen = document.getElementById('gen-section');
            const hist = document.getElementById('hist-section');
            const tabGen = document.getElementById('tab-gen');
            const tabHist = document.getElementById('tab-hist');
            const filterCompany = document.getElementById('filter-company');
            const filterMonth = document.getElementById('filter-month');
            const clearFilters = document.getElementById('clear-filters');
            const filterStatus = document.getElementById('filter-status');
            const histBody = document.getElementById('hist-body');

            function activate(target) {
                if (target === '#hist-section') {
                    gen.classList.add('d-none');
                    hist.classList.remove('d-none');
                    tabGen.classList.remove('active');
                    tabHist.classList.add('active');
                    try { history.replaceState(null, '', '#hist-section'); } catch (e) {}
                } else {
                    gen.classList.remove('d-none');
                    hist.classList.add('d-none');
                    tabGen.classList.add('active');
                    tabHist.classList.remove('active');
                    try { history.replaceState(null, '', '#gen-section'); } catch (e) {}
                }
            }

            tabGen.addEventListener('click', function(e){ e.preventDefault(); activate('#gen-section'); });
            tabHist.addEventListener('click', function(e){ e.preventDefault(); activate('#hist-section'); });

            function applyFilters() {
                const q = (filterCompany?.value || '').toLowerCase().trim();
                const ym = (filterMonth?.value || '').trim();
                const status = (filterStatus?.value || 'all');
                if (!histBody) return;
                for (const tr of histBody.rows) {
                    const c = (tr.dataset.company || '').toLowerCase();
                    const d = tr.dataset.ym || '';
                    const st = tr.dataset.status || '';
                    const okCompany = !q || c.indexOf(q) !== -1;
                    const okMonth = !ym || d === ym;
                    const okStatus = status === 'all' || st === status;
                    tr.style.display = (okCompany && okMonth && okStatus) ? '' : 'none';
                }
            }

            filterCompany?.addEventListener('input', applyFilters);
            filterMonth?.addEventListener('change', applyFilters);
            filterStatus?.addEventListener('change', applyFilters);
            clearFilters?.addEventListener('click', () => {
                if (filterCompany) filterCompany.value = '';
                if (filterMonth) filterMonth.value = '';
                if (filterStatus) filterStatus.value = 'all';
                applyFilters();
            });

            if (window.location.hash === '#gen-section') {
                activate('#gen-section');
            } else {
                // Default to Historique first
                activate('#hist-section');
            }

            // Initial filter application
            applyFilters();

            // Modals wiring (Bootstrap 5)
            const voirModal = document.getElementById('voirFactureModal');
            if (voirModal) {
                voirModal.addEventListener('show.bs.modal', function (event) {
                    const btn = event.relatedTarget;
                    if (!btn) return;
                    const set = (id, val) => { const el = voirModal.querySelector(id); if (el) el.textContent = val ?? ''; };
                    set('#v-id', btn.getAttribute('data-id'));
                    set('#v-company', btn.getAttribute('data-company'));
                    set('#v-duedate', btn.getAttribute('data-duedate'));
                    set('#v-amount', btn.getAttribute('data-amount'));
                    set('#v-paid', btn.getAttribute('data-paid'));
                    set('#v-status', btn.getAttribute('data-status'));
                    set('#v-method', btn.getAttribute('data-method'));
                    set('#v-paymentid', btn.getAttribute('data-paymentid'));
                    set('#v-description', btn.getAttribute('data-description'));
                    const imgLink = voirModal.querySelector('#v-image');
                    if (imgLink) {
                        const href = btn.getAttribute('data-image') || '#';
                        imgLink.href = href;
                        imgLink.classList.toggle('disabled', href === '#');
                    }
                });
            }

            const payerModal = document.getElementById('payerFactureModal');
            if (payerModal) {
                payerModal.addEventListener('show.bs.modal', function (event) {
                    const btn = event.relatedTarget;
                    if (!btn) return;
                    const id = btn.getAttribute('data-id');
                    const company = btn.getAttribute('data-company');
                    const duedate = btn.getAttribute('data-duedate');
                    const amount = parseFloat(btn.getAttribute('data-amount') || '0');
                    const paid = parseFloat(btn.getAttribute('data-paid') || '0');
                    const remaining = Math.max(0, amount - paid);
                    payerModal.querySelector('#p-facture-id').value = id;
                    payerModal.querySelector('#p-company').textContent = company || '';
                    payerModal.querySelector('#p-duedate').textContent = duedate || '';
                    payerModal.querySelector('#p-amount').textContent = amount.toFixed(2);
                    payerModal.querySelector('#p-paid').textContent = paid.toFixed(2);
                    payerModal.querySelector('#p-remaining').textContent = remaining.toFixed(2);
                });
            }
        });
    </script>

    <!-- Voir Facture Modal -->
    <div class="modal fade" id="voirFactureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails de la facture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><strong>ID:</strong> <span id="v-id"></span></div>
                        <div class="col-md-6"><strong>Compagnie:</strong> <span id="v-company"></span></div>
                        <div class="col-md-6"><strong>Date d'échéance:</strong> <span id="v-duedate"></span></div>
                        <div class="col-md-6"><strong>Montant:</strong> <span id="v-amount"></span> $</div>
                        <div class="col-md-6"><strong>Déjà payé:</strong> <span id="v-paid"></span> $</div>
                        <div class="col-md-6"><strong>Statut:</strong> <span id="v-status"></span></div>
                        <div class="col-md-6"><strong>Méthode de paiement:</strong> <span id="v-method"></span></div>
                        <div class="col-md-6"><strong>Payment ID:</strong> <span id="v-paymentid"></span></div>
                        <div class="col-12"><strong>Description:</strong>
                            <div id="v-description" class="border rounded p-2 bg-light" style="white-space: pre-line;"></div>
                        </div>
                        <div class="col-12">
                            <a id="v-image" href="#" target="_blank" class="btn btn-sm btn-outline-secondary">Voir l'image</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payer Facture Modal -->
    <div class="modal fade" id="payerFactureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payer la facture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('payerfacture') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="facture_id" id="p-facture-id" />
                        <div class="mb-2"><strong>Compagnie:</strong> <span id="p-company"></span></div>
                        <div class="mb-2"><strong>Date d'échéance:</strong> <span id="p-duedate"></span></div>
                        <div class="row g-2 mb-2">
                            <div class="col-4"><small class="text-muted">Montant</small><div id="p-amount">0.00</div></div>
                            <div class="col-4"><small class="text-muted">Payé</small><div id="p-paid">0.00</div></div>
                            <div class="col-4"><small class="text-muted">Reste</small><div id="p-remaining">0.00</div></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant à payer</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="paid_amount" required />
                        </div>
                        <div class="mb-2 text-muted small">
                            Le statut sera défini automatiquement selon le total payé.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Méthode de paiement</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="moncash">Moncash</option>
                                <option value="natcash">Natcash</option>
                                <option value="unibank_usd">Unibank Dollar</option>
                                <option value="unibank_htg">Unibank Gdes</option>
                                <option value="zelle">Zelle</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment ID</label>
                            <input type="text" class="form-control" name="payment_id" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer le paiement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
