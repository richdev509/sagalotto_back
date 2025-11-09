@extends('superadmin.admin-layout')

@section('content')
 

@php
    $compagnies = \App\Models\company::all();
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- In-page menu to navigate between sections -->
            <div class="card shadow-sm mb-3">
                <div class="card-body py-2">
                    <ul class="nav nav-pills nav-fill gap-2 small" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#block-form" class="nav-link active" id="tab-form">Bloquer une compagnie</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#block-list" class="nav-link" id="tab-list">Liste des compagnies bloquées</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="block-form" class="card shadow mb-4">
                <div class="card-header  text-white" style="background-color: rgb(40, 40, 242)">
                    <h4 class="mb-0">Bloquer une compagnie</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="bloquer">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="compagnie">Compagnie</label>
                            <select name="compagnie_id" id="compagnie" class="form-control" required>
                                <option value="">-- Sélectionner une compagnie --</option>
                                @foreach($compagnies as $compagnie)
                                    <option value="{{ $compagnie->id }}">{{ $compagnie->nom ?? $compagnie->name ?? $compagnie->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="4" style="white-space: pre-line; word-break: break-word;" required>Nou wè ke ou poko peye frè sèvis la, malgre nou te fè ou konprann sa deja.

Selon règleman nou yo, nou oblije sispann sèvis la otomatikman pou ou. Si ou peye nan delai sa, nap reaktive l touswit ou fin peye.

Nou vrèman regrèt sa a epi nou mande ou padon paske se yon sistèm otomatik ki fè sa, se pa yon moun ki deside bloke ou pèsonèlman.

Si ou gen nenpòt kesyon oswa ou bezwen èd, pa ezite kontakte nou nan 31071890.

Mèsi pou konpwann ou.

Avèk respè,
Ekip sagacetech (sagaloto.com)</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="blocked_at">Date et heure de blocage</label>
                            <input type="datetime-local" name="blocked_at" id="blocked_at" class="form-control" required>
                        </div>
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="btn btn-primary w-100">Envoyer</button>
                        
                    </form>
                </div>
            </div>
            @if(isset($block_list) && $block_list->count())
                <div id="block-list" class="card shadow">
                    <div class="card-header bg-gradient- text-white" style="background-color: rgb(100, 100, 240)">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                            <h5 class="mb-0">Compagnies bloquées</h5>
                            <div class="input-group input-group-sm" style="max-width: 320px;">
                                <span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span>
                                <input type="text" id="search-blocks" class="form-control" placeholder="Rechercher (compagnie, message, date)">
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <style>
                                /* Ensure long words wrap nicely; mobile gets a short version */
                                td.message-cell { white-space: normal; word-break: break-word; overflow-wrap: anywhere; }
                                /* Optional: keep desktop full text pre-line formatting */
                                @media (min-width: 576px) {
                                    td.message-cell .msg-full { white-space: pre-line; }
                                }
                            </style>
                            <table id="blocked-table" class="table table-bordered mb-0 ">
                                <thead class="table-light">
                                    <tr>
                                        <th>Compagnie</th>
                                        <th style="width:40%">Message</th>
                                        <th>Date blocage</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($block_list as $block)
                                        <tr>
                                            @php
                                             $comp = \app\Models\company::find($block->compagnie_id);
                                            @endphp
                                            <td class="col-company">{{ $comp->name ??  $block->compagnie_id }}</td>
                                            <td class="message-cell">
                                                <!-- Desktop and larger: show full message, wrapped -->
                                                <span class="msg-full d-none d-sm-inline">{{ $block->message }}</span>
                                                <!-- Mobile (sm and below): show a truncated version (50 chars, no ellipsis) -->
                                                <span class="msg-short d-inline d-sm-none" title="{{ $block->message }}">{{ \Illuminate\Support\Str::limit($block->message, 50, '') }}</span>
                                            </td>
                                            <td class="col-date">{{ $block->blocked_at }}</td>
                                            <td>
                                                <form method="POST" action="bloquer" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $block->id }}">
                                                    <input type="hidden" name="action" value="delete">
                                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formCard = document.getElementById('block-form');
        const listCard = document.getElementById('block-list');
        const tabForm = document.getElementById('tab-form');
        const tabList = document.getElementById('tab-list');

        function activate(target) {
            if (target === '#block-list' && listCard) {
                if (formCard) formCard.classList.add('d-none');
                listCard.classList.remove('d-none');
                if (tabForm) tabForm.classList.remove('active');
                if (tabList) tabList.classList.add('active');
                try { history.replaceState(null, '', '#block-list'); } catch (e) {}
                listCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                if (formCard) formCard.classList.remove('d-none');
                if (listCard) listCard.classList.add('d-none');
                if (tabForm) tabForm.classList.add('active');
                if (tabList) tabList.classList.remove('active');
                try { history.replaceState(null, '', '#block-form'); } catch (e) {}
                if (formCard) formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Click handlers to toggle sections
        if (tabForm) {
            tabForm.addEventListener('click', function (e) {
                e.preventDefault();
                activate('#block-form');
            });
        }
        if (tabList) {
            tabList.addEventListener('click', function (e) {
                e.preventDefault();
                activate('#block-list');
            });
        }

        // Initialize based on hash (or default to form)
        if (window.location.hash === '#block-list' && listCard) {
            activate('#block-list');
        } else {
            activate('#block-form');
        }

        // Simple search filter for blocked companies table
        const input = document.getElementById('search-blocks');
        const table = document.getElementById('blocked-table');
        if (input && table) {
            const tbody = table.querySelector('tbody');
            input.addEventListener('input', function (e) {
                const q = e.target.value.toLowerCase();
                for (const tr of tbody.rows) {
                    const text = tr.innerText.toLowerCase();
                    tr.style.display = text.indexOf(q) > -1 ? '' : 'none';
                }
            });
        }
    });
</script>

@endsection