@extends('layouts.app')

@section('title', 'Analyse Médicale IA')

@section('content')
<div class="container py-5">

    <h2 class="text-center mb-4 fw-bold text-primary">Analyse IA des Maladies</h2>

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="alert alert-success text-center shadow-sm">
            {{ session('success') }}
        </div>
        <div class="text-center mb-4">
            <img src="{{ session('image') }}" alt="Uploaded Image"
                 class="rounded shadow img-fluid border"
                 style="max-width: 350px;">
        </div>
    @endif

    {{-- ANALYSIS CARD --}}
    @if (session('analysis'))
        <div id="ai-analysis-card" class="card border-0 shadow-lg mb-5">
            <div class="card-header bg-primary text-white fw-semibold">
                Résultats d’analyse IA
            </div>
            <div class="card-body" id="ai-analysis-root"></div>

            <div class="card-footer d-flex justify-content-end">
                <button id="ai-copy-btn" class="btn btn-outline-primary me-2">
                    <i class="bi bi-clipboard"></i> Copier sélection (JSON)
                </button>
                <button id="ai-clear-btn" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Effacer
                </button>
            </div>
        </div>

        {{-- Inject JSON from session --}}
        <script>
            window.__aiAnalysis = {!! json_encode(session('analysis'), JSON_UNESCAPED_UNICODE) !!};
        </script>

        <script>
            (function(){
                const root = document.getElementById('ai-analysis-root');
                const data = window.__aiAnalysis;

                function createProgress(pct){
                    const div = document.createElement('div');
                    div.className = 'progress my-2';
                    div.innerHTML = `
                        <div class="progress-bar bg-success fw-semibold" role="progressbar"
                             style="width: ${pct}%" aria-valuenow="${pct}" aria-valuemin="0" aria-valuemax="100">
                             ${pct}%
                        </div>`;
                    return div;
                }

                function renderSymptomes(symptomes){
                    const table = document.createElement('table');
                    table.className = 'table table-bordered table-hover align-middle mt-3';
                    table.innerHTML = `
                        <thead class="table-light">
                            <tr>
                                <th>Symptôme</th>
                                <th>Description</th>
                                <th>Gravité</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${symptomes.map(s => `
                                <tr>
                                    <td><i class="bi bi-activity text-danger me-2"></i> ${s.nom}</td>
                                    <td>${s.description}</td>
                                    <td>
                                        <span class="badge ${s.gravite.trim() === 'Forte' ? 'bg-danger' :
                                                             s.gravite.trim() === 'Moyenne' ? 'bg-warning text-dark' :
                                                             'bg-success'}">
                                            ${s.gravite}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-${s.status === 'Actif' ? 'success' : 'secondary'}">${s.status}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>`;
                    return table;
                }

                function renderMaladie(m){
                    const card = document.createElement('div');
                    card.className = 'card border-0 shadow-sm mb-4';
                    card.innerHTML = `
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">
                                        <i class="bi bi-virus text-danger me-2"></i> ${m.nom}
                                    </h5>
                                    <p class="text-muted mb-1">${m.description}</p>
                                    <p class="mb-1"><strong>Type:</strong> ${m.type}</p>
                                    <p class="mb-2"><strong>Prévention:</strong> ${m.prevention}</p>
                                    <p><strong>Traitement:</strong> ${m.traitement}</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-${m.status === 'Actif' ? 'success' : 'secondary'} mb-2">${m.status}</span>
                                    ${createProgress(m.confiance ?? 0).outerHTML}
                                </div>
                            </div>
                            <h6 class="fw-semibold mt-4">Symptômes détectés :</h6>
                        </div>
                    `;
                    card.querySelector('.card-body').appendChild(renderSymptomes(m.symptomes || []));
                    return card;
                }

                function renderData(){
                    root.innerHTML = '';
                    if(!data || !data.maladies){ 
                        root.innerHTML = '<p class="text-muted">Aucune donnée disponible.</p>';
                        return;
                    }

                    data.maladies.forEach(m => root.appendChild(renderMaladie(m)));
                }

                renderData();

                document.getElementById('ai-copy-btn').addEventListener('click', () => {
                    navigator.clipboard.writeText(JSON.stringify(data, null, 2))
                        .then(() => alert('JSON copié dans le presse-papiers'));
                });

                document.getElementById('ai-clear-btn').addEventListener('click', () => {
                    root.innerHTML = '';
                    document.getElementById('ai-analysis-card').style.display = 'none';
                });
            })();
        </script>
    @endif

    {{-- UPLOAD FORM --}}
    <div class="card border-0 shadow-lg">
        <div class="card-body">
            <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Sélectionner une image</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                    <i class="bi bi-cloud-upload"></i> Envoyer et Analyser
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
