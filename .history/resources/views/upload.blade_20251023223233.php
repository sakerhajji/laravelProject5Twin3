<!DOCTYPE html>
<html>
<head>
    <title>Upload to Cloudinary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">Upload Image to Cloudinary</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        <img src="{{ session('image') }}" alt="Uploaded Image" class="img-fluid mt-3" width="300">
    @endif

    @if (session('analysis'))
        <div id="ai-analysis-card" class="mt-4">
            <div class="card shadow-sm">
                <div class="card-header">Analyse du modèle (AI)</div>
                <div class="card-body">
                    <div id="ai-analysis-root"></div>
                    <div class="mt-3 d-flex justify-content-end">
                        <button id="ai-copy-btn" class="btn btn-outline-secondary me-2">Copier sélection (JSON)</button>
                        <button id="ai-clear-btn" class="btn btn-secondary">Effacer</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Embed analysis JSON for JS renderer --}}
        <script>
            window.__aiAnalysis = {!! json_encode(session('analysis'), JSON_UNESCAPED_UNICODE) !!};
        </script>

        <script>
            (function(){
                const root = document.getElementById('ai-analysis-root');
                const analysis = window.__aiAnalysis;

                function safe(obj){
                    try { return JSON.parse(JSON.stringify(obj)); } catch(e){ return obj; }
                }

                function renderString(str){
                    const pre = document.createElement('pre');
                    pre.style.whiteSpace = 'pre-wrap';
                    pre.textContent = str;
                    root.appendChild(pre);
                }

                function createProgress(pct){
                    const container = document.createElement('div');
                    container.className = 'd-flex align-items-center';
                    const progWrap = document.createElement('div');
                    progWrap.className = 'progress flex-grow-1 me-2';
                    progWrap.style.height = '8px';
                    const prog = document.createElement('div');
                    prog.className = 'progress-bar bg-info';
                    prog.setAttribute('role','progressbar');
                    prog.style.width = (isNaN(pct) ? 0 : pct + '%');
                    prog.setAttribute('aria-valuenow', (isNaN(pct) ? 0 : pct));
                    prog.setAttribute('aria-valuemin','0');
                    prog.setAttribute('aria-valuemax','100');
                    progWrap.appendChild(prog);
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-info text-dark fw-semibold';
                    badge.textContent = (isNaN(pct) ? '0' : pct) + '%';
                    container.appendChild(progWrap);
                    container.appendChild(badge);
                    return container;
                }

                function renderTable(maladies){
                    const form = document.createElement('form');
                    form.id = 'ai-results-form';
                    // hidden input to store selected JSON when copying
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'ai_selected_json';
                    hidden.id = 'ai_selected_json';
                    form.appendChild(hidden);

                    const table = document.createElement('table');
                    table.className = 'table table-hover align-middle mt-3';
                    const thead = document.createElement('thead');
                    thead.className = 'table-light';
                    thead.innerHTML = '<tr><th style="width:60%">Maladie</th><th style="width:25%">Correspondance</th><th style="width:15%">Sélection</th></tr>';
                    table.appendChild(thead);
                    const tbody = document.createElement('tbody');

                    maladies.forEach((m, idx) => {
                        const nom = m.nom || m.name || m.nom_maladie || 'N / A';
                        const desc = m.description || m.desc || m.description_maladie || '';
                        const confiance = parseInt(m.confiance ?? m.confidence ?? m.percentage ?? 0, 10);

                        const tr = document.createElement('tr');
                        tr.className = 'hover-row';

                        // Maladie cell
                        const td1 = document.createElement('td');
                        td1.innerHTML = `
                            <div class="d-flex align-items-center">
                                <i class="bi bi-virus me-3 text-danger" style="font-size:1.2rem;"></i>
                                <div>
                                    <h6 class="mb-0 fw-semibold">${nom}</h6>
                                    <small class="text-muted">${(desc || '').substring(0,120)}</small>
                                </div>
                            </div>`;
                        tr.appendChild(td1);

                        // Correspondance cell
                        const td2 = document.createElement('td');
                        td2.appendChild(createProgress(confiance));
                        tr.appendChild(td2);

                        // Selection cell
                        const td3 = document.createElement('td');
                        td3.innerHTML = `<div class="form-check mb-0"><input type="radio" class="form-check-input ai-select-radio" name="ai_maladie" value="${idx}"></div>`;
                        tr.appendChild(td3);

                        tbody.appendChild(tr);
                    });

                    table.appendChild(tbody);
                    form.appendChild(table);
                    root.appendChild(form);

                    // wire selection to hidden input
                    form.addEventListener('change', function(){
                        const sel = form.querySelector('input[name="ai_maladie"]:checked');
                        if(sel){
                            const idx = parseInt(sel.value,10);
                            document.getElementById('ai_selected_json').value = JSON.stringify(maladies[idx]);
                        } else {
                            document.getElementById('ai_selected_json').value = '';
                        }
                    });
                }

                function renderFromAnalysis(data){
                    root.innerHTML = '';
                    if (!data) { root.textContent = 'Aucune analyse disponible.'; return; }

                    // If it's a string, display as raw
                    if (typeof data === 'string'){
                        renderString(data);
                        return;
                    }

                    // If it's an object that contains 'maladies'
                    let maladies = null;
                    if (Array.isArray(data)) {
                        // maybe direct array of maladies
                        maladies = data;
                    } else if (data.maladies && Array.isArray(data.maladies)){
                        maladies = data.maladies;
                    } else if (data.content && Array.isArray(data.content)){
                        maladies = data.content;
                    }

                    if (maladies && maladies.length > 0){
                        renderTable(maladies);
                        return;
                    }

                    // Fallback: pretty-print the object
                    const pre = document.createElement('pre');
                    pre.style.whiteSpace = 'pre-wrap';
                    try { pre.textContent = JSON.stringify(data, null, 2); } catch(e){ pre.textContent = String(data); }
                    root.appendChild(pre);
                }

                // initial render
                renderFromAnalysis(safe(analysis));

                // copy button
                document.getElementById('ai-copy-btn').addEventListener('click', function(){
                    const val = document.getElementById('ai_selected_json').value;
                    if(!val){ alert('Sélectionnez d\'abord une maladie à copier.'); return; }
                    navigator.clipboard.writeText(val).then(()=>{ alert('JSON copié dans le presse-papiers'); }).catch(()=>{ alert('Échec de la copie'); });
                });

                // clear button
                document.getElementById('ai-clear-btn').addEventListener('click', function(){
                    // clear session is server-side; just clear UI
                    root.innerHTML = '';
                    document.getElementById('ai-analysis-card').style.display = 'none';
                });
            })();
        </script>
    @endif

    <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="image" class="form-label">Select image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

</body>
</html>
