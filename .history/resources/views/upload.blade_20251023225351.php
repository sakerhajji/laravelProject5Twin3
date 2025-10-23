@extends('layouts.front')

@section('title', 'Upload & Analyse Image')

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Upload Image to Cloudinary</h2>
        <p class="text-muted">Analyse automatique de l'image et affichage des maladies détectées.</p>
    </div>

    {{-- SUCCESS MESSAGE + IMAGE --}}
    @if (session('success'))
        <div class="alert alert-success text-center shadow-sm">
            {{ session('success') }}
        </div>
        <div class="text-center mb-4">
            <img src="{{ session('image') }}" alt="Uploaded Image" class="rounded shadow img-fluid border" style="max-width: 350px;">
        </div>
    @endif

    {{-- AI ANALYSIS SECTION --}}
    @if (session('analysis'))
        @php
            $analysis = session('analysis');
            $hasDiseases = isset($analysis['maladies']) && is_array($analysis['maladies']) && count($analysis['maladies']) > 0;
            $hasError = isset($analysis['error']);
        @endphp

        @if ($hasError)
            <div class="alert alert-danger">
                <h5><i class="bi bi-exclamation-triangle"></i> Analyse Error</h5>
                <p>{{ $analysis['error'] }}</p>
                @if (isset($analysis['raw']))
                    <details class="mt-2">
                        <summary>Raw Response</summary>
                        <pre class="bg-light p-2 rounded"><code>{{ json_encode($analysis['raw'], JSON_PRETTY_PRINT) }}</code></pre>
                    </details>
                @endif
            </div>
        @elseif ($hasDiseases)
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

            {{-- Embed JSON --}}
            <script>
                window.__aiAnalysis = {!! json_encode($analysis, JSON_UNESCAPED_UNICODE) !!};
            </script>

            <script>
                (function(){
                    const root = document.getElementById('ai-analysis-root');
                    const analysis = window.__aiAnalysis;

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
                        badge.textContent = (isNaN(pct) ? 0 : pct) + '%';
                        container.appendChild(wrap);
                        container.appendChild(badge);
                        return container;
                    }

                    function renderSymptomes(symptomes){
                        if(!symptomes || !Array.isArray(symptomes) || symptomes.length === 0) {
                            return document.createTextNode('Aucun symptôme détecté');
                        }
                        const table = document.createElement('table');
                        table.className = 'table table-sm table-bordered mt-2';
                        const thead = document.createElement('thead');
                        thead.className = 'table-light';
                        thead.innerHTML = `<tr>
                            <th>Symptôme</th>
                            <th>Description</th>
                            <th>Gravité</th>
                            <th>Status</th>
                        </tr>`;
                        table.appendChild(thead);
                        const tbody = document.createElement('tbody');

                        symptomes.forEach(s => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${s.nom || 'N/A'}</td>
                                <td>${s.description || 'N/A'}</td>
                                <td><span class="badge ${
                                    (s.gravite || '').trim() === 'Forte' ? 'bg-danger' : 
                                    (s.gravite || '').trim() === 'Moyenne' ? 'bg-warning text-dark' : 
                                    'bg-success'
                                }">${s.gravite || 'N/A'}</span></td>
                                <td><span class="badge ${
                                    (s.status || '').trim() === 'Activé' || (s.status || '').trim() === 'Actif' ? 'bg-success' : 'bg-secondary'
                                }">${s.status || 'N/A'}</span></td>
                            `;
                            tbody.appendChild(tr);
                        });

                        table.appendChild(tbody);
                        return table;
                    }

                    function renderTable(maladies){
                        root.innerHTML = '';
                        maladies.forEach((m, idx) => {
                            const card = document.createElement('div');
                            card.className = 'card mb-4 shadow-sm';
                            card.innerHTML = `
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="fw-bold text-dark">${m.nom || 'N/A'}</h5>
                                            <p class="text-muted mb-1">${m.description || 'N/A'}</p>
                                            <p class="mb-1"><strong>Type:</strong> ${m.type || 'N/A'}</p>
                                            <p class="mb-1"><strong>Prévention:</strong> ${m.prevention || 'N/A'}</p>
                                            <p class="mb-2"><strong>Traitement:</strong> ${m.traitement || 'N/A'}</p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-${
                                                (m.status || '').trim() === 'Actif' || (m.status || '').trim() === 'En cours' ? 'success' : 'secondary'
                                            } mb-2">${m.status || 'N/A'}</span>
                                            ${createProgress(m.confiance ?? 0).outerHTML}
                                        </div>
                                    </div>
                                    <h6>Symptômes détectés :</h6>
                                </div>
                            `;
                            card.querySelector('.card-body').appendChild(renderSymptomes(m.symptomes || []));
                            root.appendChild(card);
                        });
                    }

                    if(analysis && analysis.maladies && Array.isArray(analysis.maladies) && analysis.maladies.length > 0){
                        renderTable(analysis.maladies);
                    } else {
                        root.innerHTML = '<p class="text-muted">Aucun résultat trouvé.</p>';
                    }

                    // Copy button
                    document.getElementById('ai-copy-btn').addEventListener('click', () => {
                        navigator.clipboard.writeText(JSON.stringify(analysis, null, 2))
                            .then(() => alert('JSON copié dans le presse-papiers'))
                            .catch(err => console.error('Failed to copy: ', err));
                    });

                    // Clear button
                    document.getElementById('ai-clear-btn').addEventListener('click', () => {
                        root.innerHTML = '';
                        document.getElementById('ai-analysis-card').style.display = 'none';
                    });

                })();
            </script>
        @else
            <div class="alert alert-info">
                <h5><i class="bi bi-info-circle"></i> Aucune maladie détectée</h5>
                <p>L'analyse n'a pas identifié de maladies dans l'image fournie.</p>
            </div>
        @endif
    @endif

    {{-- UPLOAD FORM --}}
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Choisir une image</label>
                    <input type="file" name="image" id="image" class="form-control" required accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-cloud-upload"></i> Envoyer et Analyser
                </button>
            </form>
        </div>
    </div>

</div>  
@endsection