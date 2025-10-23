@extends('layouts.app')

@section('title', 'Upload & Analyse Image')

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Upload Image to Cloudinary</h2>
        <p class="text-muted">Analyse automatique de l’image et affichage des maladies détectées.</p>
    </div>

    {{-- SUCCESS MESSAGE + IMAGE --}}
    @if (session('success'))
        <div class="alert alert-success text-center shadow-sm">
            {{ session('success') }}
        </div>
        <div class="text-center mb-4">
            <img src="{{ session('image') }}" 
                 alt="Uploaded Image" 
                 class="rounded shadow img-fluid border"
                 style="max-width: 350px;">
        </div>
    @endif

    {{-- AI ANALYSIS SECTION --}}
    @if (session('analysis'))
        <div id="ai-analysis-card" class="card border-0 shadow-lg mb-5">
            <div class="card-header bg-primary text-white fw-semibold">
                Résultats de l’analyse IA
            </div>
            <div class="card-body">
                <div id="ai-analysis-root"></div>
                <div class="mt-4 d-flex justify-content-end">
                    <button id="ai-copy-btn" class="btn btn-outline-primary me-2">
                        <i class="bi bi-clipboard"></i> Copier sélection (JSON)
                    </button>
                    <button id="ai-clear-btn" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Effacer
                    </button>
                </div>
            </div>
        </div>

        {{-- Embed JSON for JavaScript --}}
        <script>
            window.__aiAnalysis = {!! json_encode(session('analysis'), JSON_UNESCAPED_UNICODE) !!};
        </script>

        {{-- ANALYSIS TABLE SCRIPT --}}
        <script>
            (function(){
                const root = document.getElementById('ai-analysis-root');
                const analysis = window.__aiAnalysis;

                function safe(obj){
                    try { return JSON.parse(JSON.stringify(obj)); } catch(e){ return obj; }
                }

                function createProgress(pct){
                    const container = document.createElement('div');
                    container.className = 'd-flex align-items-center';
                    const wrap = document.createElement('div');
                    wrap.className = 'progress flex-grow-1 me-2';
                    wrap.style.height = '8px';
                    const bar = document.createElement('div');
                    bar.className = 'progress-bar bg-info';
                    bar.style.width = (isNaN(pct) ? 0 : pct) + '%';
                    wrap.appendChild(bar);
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-info text-dark fw-semibold';
                    badge.textContent = (isNaN(pct) ? '0' : pct) + '%';
                    container.appendChild(wrap);
                    container.appendChild(badge);
                    return container;
                }

                function renderTable(maladies){
                    const form = document.createElement('form');
                    form.id = 'ai-results-form';
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'ai_selected_json';
                    hidden.id = 'ai_selected_json';
                    form.appendChild(hidden);

                    const table = document.createElement('table');
                    table.className = 'table table-hover align-middle mt-3';
                    const thead = document.createElement('thead');
                    thead.className = 'table-primary';
                    thead.innerHTML = '<tr><th>Maladie</th><th>Correspondance</th><th>Sélection</th></tr>';
                    table.appendChild(thead);
                    const tbody = document.createElement('tbody');

                    maladies.forEach((m, idx) => {
                        const nom = m.nom || m.name || 'Inconnu';
                        const desc = m.description || '';
                        const confiance = parseInt(m.confiance ?? m.confidence ?? 0, 10);

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-virus text-danger me-3 fs-5"></i>
                                    <div>
                                        <h6 class="fw-semibold mb-0">${nom}</h6>
                                        <small class="text-muted">${desc.substring(0, 80)}</small>
                                    </div>
                                </div>
                            </td>
                            <td>${createProgress(confiance).outerHTML}</td>
                            <td>
                                <input type="radio" class="form-check-input ai-select-radio" 
                                       name="ai_maladie" value="${idx}">
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });

                    table.appendChild(tbody);
                    form.appendChild(table);
                    root.appendChild(form);

                    // selection event
                    form.addEventListener('change', () => {
                        const sel = form.querySelector('input[name="ai_maladie"]:checked');
                        if(sel){
                            document.getElementById('ai_selected_json').value = JSON.stringify(maladies[sel.value]);
                        }
                    });
                }

                function renderAnalysis(data){
                    root.innerHTML = '';
                    if(!data) return root.textContent = 'Aucune donnée reçue.';

                    let maladies = null;
                    if(Array.isArray(data)) maladies = data;
                    else if(data.maladies && Array.isArray(data.maladies)) maladies = data.maladies;
                    else if(data.content && Array.isArray(data.content)) maladies = data.content;

                    if(maladies) renderTable(maladies);
                    else root.textContent = JSON.stringify(data, null, 2);
                }

                renderAnalysis(safe(analysis));

                document.getElementById('ai-copy-btn').addEventListener('click', () => {
                    const val = document.getElementById('ai_selected_json').value;
                    if(!val) return alert('Sélectionnez une maladie avant de copier.');
                    navigator.clipboard.writeText(val).then(() => alert('JSON copié !'));
                });

                document.getElementById('ai-clear-btn').addEventListener('click', () => {
                    root.innerHTML = '';
                    document.getElementById('ai-analysis-card').style.display = 'none';
                });
            })();
        </script>
    @endif

    {{-- UPLOAD FORM --}}
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Choisir une image</label>
                    <input type="file" name="image" id="image" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-cloud-upload"></i> Envoyer et Analyser
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
