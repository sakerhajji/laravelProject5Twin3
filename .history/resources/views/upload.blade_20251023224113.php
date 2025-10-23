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
            // Debug log the analysis data
            console.log('Raw analysis data:', window.__aiAnalysis);
        </script>

        <script>
            (function(){
                const root = document.getElementById('ai-analysis-root');
                const data = window.__aiAnalysis;

                function getMaladies(data) {
                    if (!data) return [];
                    // Try to extract maladies array from various possible structures
                    if (Array.isArray(data)) return data;
                    if (data.maladies && Array.isArray(data.maladies)) return data.maladies;
                    if (data.content && Array.isArray(data.content)) return data.content;
                    if (typeof data === 'string') {
                        try {
                            const parsed = JSON.parse(data);
                            return getMaladies(parsed);
                        } catch (e) {
                            return [];
                        }
                    }
                    return [];
                }

                function createProgress(pct) {
                    const div = document.createElement('div');
                    div.className = 'progress my-2';
                    const value = parseInt(pct) || 0;
                    div.innerHTML = `
                        <div class="progress-bar bg-success fw-semibold" role="progressbar"
                             style="width: ${value}%" aria-valuenow="${value}" aria-valuemin="0" aria-valuemax="100">
                             ${value}%
                        </div>`;
                    return div;
                }

                function renderSymptomes(symptomes = []) {
                    if (!Array.isArray(symptomes) || symptomes.length === 0) {
                        return '<p class="text-muted fst-italic">Aucun symptôme détecté.</p>';
                    }

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
                                    <td><i class="bi bi-activity text-danger me-2"></i> ${s.nom || s.name || 'N/A'}</td>
                                    <td>${s.description || 'N/A'}</td>
                                    <td>
                                        <span class="badge ${(s.gravite || '').trim() === 'Forte' ? 'bg-danger' :
                                                             (s.gravite || '').trim() === 'Moyenne' ? 'bg-warning text-dark' :
                                                             'bg-success'}">
                                            ${s.gravite || 'N/A'}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-${s.status === 'Actif' ? 'success' : 'secondary'}">${s.status || 'N/A'}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>`;
                    return table;
                }

                function renderMaladie(m) {
                    const card = document.createElement('div');
                    card.className = 'card border-0 shadow-sm mb-4';
                    card.innerHTML = `
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold text-dark mb-1">
                                        <i class="bi bi-virus text-danger me-2"></i> ${m.nom || m.name || 'Maladie'}
                                    </h5>
                                    <p class="text-muted mb-1">${m.description || 'Aucune description'}</p>
                                    <p class="mb-1"><strong>Type:</strong> ${m.type || 'N/A'}</p>
                                    <p class="mb-2"><strong>Prévention:</strong> ${m.prevention || 'N/A'}</p>
                                    <p><strong>Traitement:</strong> ${m.traitement || 'N/A'}</p>
                                </div>
                                <div class="text-end ms-3">
                                    <span class="badge bg-${m.status === 'Actif' ? 'success' : 'secondary'} mb-2">
                                        ${m.status || 'N/A'}
                                    </span>
                                    ${createProgress(m.confiance || m.confidence || 0).outerHTML}
                                </div>
                            </div>
                            <h6 class="fw-semibold mt-4 mb-3">Symptômes détectés :</h6>
                            ${typeof renderSymptomes(m.symptomes) === 'string' 
                              ? renderSymptomes(m.symptomes)
                              : renderSymptomes(m.symptomes).outerHTML}
                        </div>
                    `;
                    return card;
                }

                function renderData() {
                    root.innerHTML = '';
                    const maladies = getMaladies(data);
                    
                    if (maladies.length === 0) {
                        root.innerHTML = `
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Aucune maladie n'a été détectée dans cette image.
                            </div>`;
                        return;
                    }

                    maladies.forEach(m => root.appendChild(renderMaladie(m)));
                }

                // Initial render
                renderData();

                // Wire up buttons
                document.getElementById('ai-copy-btn').addEventListener('click', () => {
                    const maladies = getMaladies(data);
                    if (maladies.length === 0) {
                        alert('Aucune donnée à copier.');
                        return;
                    }
                    navigator.clipboard.writeText(JSON.stringify(maladies, null, 2))
                        .then(() => alert('Données copiées dans le presse-papiers'));
                });

                document.getElementById('ai-clear-btn').addEventListener('click', () => {
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
