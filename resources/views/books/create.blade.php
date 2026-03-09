@extends('layouts.app')
@section('title', __('messages.publish_book'))

@push('styles')
<style>
/* ── UPLOAD PAGE ──────────────────────────────────────────────────── */
.upload-hero {
    background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #0f2027 100%);
    position: relative;
    overflow: hidden;
    padding: 3.5rem 1rem 2.5rem;
    text-align: center;
}
.upload-hero::before {
    content:'';
    position:absolute; inset:0;
    background: radial-gradient(ellipse at 60% 20%, rgba(16,185,129,.18) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 80%, rgba(99,102,241,.14) 0%, transparent 55%);
    pointer-events:none;
}
.upload-hero h1 {
    font-size: clamp(1.6rem, 4vw, 2.4rem);
    font-weight: 800;
    color: #fff;
    margin-bottom: .5rem;
}
.upload-hero p { color: rgba(255,255,255,.7); font-size:1rem; }

/* Progress bar */
.step-track {
    display: flex;
    justify-content: center;
    gap: 0;
    margin: 2rem auto 0;
    max-width: 560px;
}
.step-item {
    display: flex; align-items: center; flex: 1;
}
.step-dot {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .8rem;
    transition: all .3s;
    background: rgba(255,255,255,.15);
    color: rgba(255,255,255,.6);
    border: 2px solid rgba(255,255,255,.2);
    flex-shrink: 0;
}
.step-dot.active { background:#10b981; color:#fff; border-color:#10b981; box-shadow:0 0 0 4px rgba(16,185,129,.25); }
.step-dot.done   { background:#6366f1; color:#fff; border-color:#6366f1; }
.step-label { font-size:.72rem; color:rgba(255,255,255,.5); margin-top:.3rem; white-space:nowrap; }
.step-bar { flex:1; height:2px; background:rgba(255,255,255,.15); margin:0 4px; }
.step-bar.done { background:#6366f1; }

/* Form card */
.upload-card {
    background: var(--bg-secondary, #fff);
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,.08);
    border: 1px solid var(--border-color, #e2e8f0);
    overflow: hidden;
}

/* Step panels */
.step-panel { display: none; }
.step-panel.active { display: block; }

.step-header {
    padding: 1.5rem 2rem 1rem;
    border-bottom: 1px solid var(--border-color, #f0f0f0);
    display: flex; align-items: center; gap: .75rem;
}
.step-icon {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
}
.icon-info  { background: rgba(99,102,241,.12); }
.icon-files { background: rgba(16,185,129,.12); }
.icon-opts  { background: rgba(245,158,11,.12); }
.step-header h2 { font-size: 1rem; font-weight: 700; margin: 0; }
.step-header p  { font-size: .8rem; color: var(--text-muted, #64748b); margin: 0; }

.step-body { padding: 1.5rem 2rem; }

/* Field group */
.field-group { margin-bottom: 1.25rem; }
.field-group label {
    display: block; margin-bottom: .4rem;
    font-weight: 600; font-size: .875rem;
    color: var(--text-primary, #1e293b);
}
.field-group label .req { color: #ef4444; margin-left: 2px; }
.field-group .hint { font-size: .75rem; color: var(--text-muted, #94a3b8); margin-top: .25rem; }

/* Inputs */
.form-control {
    width: 100%; padding: .75rem 1rem;
    border: 2px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    font-size: .9rem;
    background: var(--bg-primary, #fff);
    color: var(--text-primary, #1e293b);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.15);
}
.form-control.error { border-color: #ef4444; }

textarea.form-control { resize: vertical; min-height: 110px; }
select.form-control { cursor: pointer; }

/* Grid cols */
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
@media (max-width: 640px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } }

/* Dropzone */
.dropzone {
    border: 2px dashed var(--border-color, #cbd5e1);
    border-radius: 16px;
    padding: 2rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: all .25s;
    position: relative;
}
.dropzone:hover, .dropzone.drag-over {
    border-color: #6366f1;
    background: rgba(99,102,241,.04);
}
.dropzone input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.dropzone .dz-icon { font-size: 2rem; margin-bottom: .5rem; }
.dropzone .dz-text { font-weight: 600; font-size: .9rem; color: var(--text-primary, #1e293b); }
.dropzone .dz-sub  { font-size: .78rem; color: var(--text-muted, #94a3b8); margin-top: .2rem; }
.dropzone .dz-file-name {
    display: none; margin-top: .75rem;
    background: rgba(16,185,129,.1); border-radius: 8px;
    padding: .4rem .75rem; font-size: .8rem; color: #059669; font-weight: 600;
}
.dropzone.has-file .dz-file-name { display: inline-block; }

/* Cover preview */
.cover-preview-wrap { position: relative; }
.cover-preview {
    width: 100%; aspect-ratio: 2/3; max-height: 220px;
    border-radius: 12px; overflow: hidden;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    display: flex; align-items: center; justify-content: center;
    position: relative;
}
.cover-preview img {
    width: 100%; height: 100%; object-fit: cover;
    display: none;
}
.cover-placeholder { font-size: 2.5rem; color: #cbd5e1; }
.cover-preview.has-image img { display: block; }
.cover-preview.has-image .cover-placeholder { display: none; }

/* Toggle */
.toggle-card {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.25rem;
    background: var(--bg-secondary, #f8fafc);
    border-radius: 12px;
    border: 1.5px solid var(--border-color, #e2e8f0);
    cursor: pointer;
    transition: border-color .2s;
}
.toggle-card:hover { border-color: #6366f1; }
.toggle-card input { display: none; }
.toggle-switch {
    width: 44px; height: 24px; background: #cbd5e1;
    border-radius: 99px; position: relative; transition: background .2s;
    flex-shrink: 0;
}
.toggle-switch::after {
    content: ''; position: absolute; top: 2px; left: 2px;
    width: 20px; height: 20px; border-radius: 50%; background: #fff;
    transition: transform .2s; box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.toggle-card input:checked + .toggle-info + .toggle-switch { background: #6366f1; }
.toggle-card input:checked + .toggle-info + .toggle-switch::after { transform: translateX(20px); }
.toggle-info strong { font-size: .875rem; font-weight: 700; display: block; color: var(--text-primary, #1e293b); }
.toggle-info span   { font-size: .75rem; color: var(--text-muted, #64748b); }

/* Nav buttons */
.step-nav {
    display: flex; justify-content: space-between; align-items: center;
    padding: 1.25rem 2rem;
    border-top: 1px solid var(--border-color, #f0f0f0);
    gap: .75rem;
}
.btn-nav-prev {
    padding: .65rem 1.5rem; border-radius: 10px; font-weight: 600; font-size: .875rem;
    background: var(--bg-secondary, #f1f5f9);
    color: var(--text-secondary, #475569);
    border: none; cursor: pointer; transition: background .2s;
}
.btn-nav-prev:hover { background: var(--border-color, #e2e8f0); }
.btn-nav-next {
    padding: .65rem 1.75rem; border-radius: 10px; font-weight: 700; font-size: .875rem;
    background: linear-gradient(135deg, #6366f1, #818cf8);
    color: #fff; border: none; cursor: pointer;
    display: flex; align-items: center; gap: .5rem;
    transition: opacity .2s, transform .2s;
}
.btn-nav-next:hover { opacity: .9; transform: translateY(-1px); }
.btn-submit {
    background: linear-gradient(135deg, #10b981, #059669) !important;
}

/* Tags input */
.tags-wrap {
    display: flex; flex-wrap: wrap; gap: .4rem;
    padding: .5rem .75rem; min-height: 46px;
    border: 2px solid var(--border-color, #e2e8f0); border-radius: 12px;
    cursor: text; transition: border-color .2s;
}
.tags-wrap:focus-within {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.15);
}
.tag-chip {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    color: #4338ca; border-radius: 6px;
    padding: .2rem .6rem; font-size: .78rem; font-weight: 600;
    display: flex; align-items: center; gap: .3rem;
}
.tag-chip button { background: none; border: none; cursor: pointer; font-size: .9rem; line-height: 1; color: #6366f1; padding: 0; }
.tag-input {
    border: none; outline: none; font-size: .875rem; min-width: 100px;
    background: transparent; color: var(--text-primary, #1e293b); flex: 1;
}

/* Error */
.field-error { font-size: .78rem; color: #ef4444; margin-top: .3rem; display: block; }

/* Upload progress overlay */
.upload-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(15,32,39,.7); backdrop-filter: blur(4px);
    z-index: 9999; align-items: center; justify-content: center; flex-direction: column;
    gap: 1rem;
}
.upload-overlay.active { display: flex; }
.upload-spinner {
    width: 60px; height: 60px; border-radius: 50%;
    border: 4px solid rgba(255,255,255,.2);
    border-top-color: #10b981;
    animation: spin .8s linear infinite;
}
.upload-overlay p { color: #fff; font-weight: 600; font-size: 1.05rem; }
.upload-overlay small { color: rgba(255,255,255,.6); font-size: .85rem; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Status badge */
.status-pending { background:#fef3c7; color:#92400e; padding:.2rem .6rem; border-radius:6px; font-size:.75rem; font-weight:700; }
</style>
@endpush

@section('content')

{{-- Upload Overlay --}}
<div class="upload-overlay" id="uploadOverlay">
    <div class="upload-spinner"></div>
    <p>{{ __('messages.uploading_book') }}</p>
    <small>{{ __('messages.upload_wait') }}</small>
</div>

{{-- Hero --}}
<div class="upload-hero">
    <div class="upload-hero-content">
        <h1>📚 {{ __('messages.publish_book') }}</h1>
        <p>{{ __('messages.publish_book_desc') }}</p>
    </div>

    {{-- Step Tracker --}}
    <div class="step-track" id="stepTrack">
        <div class="step-item">
            <div style="display:flex;flex-direction:column;align-items:center">
                <div class="step-dot active" id="dot-1">1</div>
                <span class="step-label">{{ __('messages.step_info') }}</span>
            </div>
        </div>
        <div class="step-bar" id="bar-1"></div>
        <div class="step-item">
            <div style="display:flex;flex-direction:column;align-items:center">
                <div class="step-dot" id="dot-2">2</div>
                <span class="step-label">{{ __('messages.step_files') }}</span>
            </div>
        </div>
        <div class="step-bar" id="bar-2"></div>
        <div class="step-item">
            <div style="display:flex;flex-direction:column;align-items:center">
                <div class="step-dot" id="dot-3">3</div>
                <span class="step-label">{{ __('messages.step_options') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Form --}}
<div class="section" style="padding-top: 2rem;">
    <div class="container" style="max-width: 720px;">

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom:1rem;">{{ session('error') }}</div>
        @endif

        <div class="upload-card">
            <form id="uploadForm" action="{{ route('author.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ════ STEP 1: Basic Info ════ --}}
                <div class="step-panel active" id="panel-1">
                    <div class="step-header">
                        <div class="step-icon icon-info">📝</div>
                        <div>
                            <h2>{{ __('messages.step_info') }}</h2>
                            <p>{{ __('messages.step_info_desc') }}</p>
                        </div>
                    </div>
                    <div class="step-body">

                        <div class="field-group">
                            <label>{{ __('messages.title') }} <span class="req">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                class="form-control @error('title') error @enderror"
                                placeholder="{{ __('messages.title_placeholder') }}">
                            @error('title') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="field-group">
                            <label>{{ __('messages.description') }} <span class="req">*</span></label>
                            <textarea name="description" required
                                class="form-control @error('description') error @enderror"
                                placeholder="{{ __('messages.description_placeholder') }}">{{ old('description') }}</textarea>
                            @error('description') <span class="field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid-2">
                            <div class="field-group">
                                <label>{{ __('messages.category') }} <span class="req">*</span></label>
                                <select name="category_id" required class="form-control @error('category_id') error @enderror">
                                    <option value="">{{ __('messages.select_category') }}</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->localized_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="field-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="field-group">
                                <label>{{ __('messages.type') }} <span class="req">*</span></label>
                                <select name="type" required class="form-control">
                                    <option value="book" {{ old('type','book') === 'book' ? 'selected' : '' }}>
                                        📖 {{ __('messages.type_book') }}
                                    </option>
                                    <option value="scientific_review" {{ old('type') === 'scientific_review' ? 'selected' : '' }}>
                                        🔬 {{ __('messages.type_scientific_review') }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="field-group">
                                <label>{{ __('messages.language') }} <span class="req">*</span></label>
                                <select name="language" required class="form-control">
                                    <option value="fr" {{ old('language','fr') === 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                                    <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                                </select>
                            </div>
                            <div class="field-group">
                                <label>{{ __('messages.publisher') ?? 'Maison d\'édition' }}</label>
                                <input type="text" name="publisher" value="{{ old('publisher') }}" class="form-control" placeholder="Maison d'édition...">
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="field-group">
                                <label>ISBN</label>
                                <input type="text" name="isbn" value="{{ old('isbn') }}" class="form-control" placeholder="978-…">
                            </div>
                            <div class="field-group">
                                <label>DOI</label>
                                <input type="text" name="doi" value="{{ old('doi') }}" class="form-control" placeholder="10.xxxx/…">
                            </div>
                        </div>

                        <div class="field-group">
                            <label>{{ __('messages.keywords') }}</label>
                            <div class="tags-wrap" id="tagsWrap" onclick="document.getElementById('tagInput').focus()">
                                <input type="hidden" name="keywords" id="keywordsHidden" value="{{ old('keywords') }}">
                                <input class="tag-input" id="tagInput" placeholder="{{ __('messages.keywords_hint') }}"
                                    autocomplete="off">
                            </div>
                            <p class="hint">{{ __('messages.keywords_help') }}</p>
                        </div>
                    </div>
                    <div class="step-nav">
                        <span></span>
                        <button type="button" class="btn-nav-next" onclick="goStep(2)">
                            {{ __('messages.next') }} →
                        </button>
                    </div>
                </div>

                {{-- ════ STEP 2: Files ════ --}}
                <div class="step-panel" id="panel-2">
                    <div class="step-header">
                        <div class="step-icon icon-files">📁</div>
                        <div>
                            <h2>{{ __('messages.step_files') }}</h2>
                            <p>{{ __('messages.step_files_desc') }}</p>
                        </div>
                    </div>
                    <div class="step-body">
                        <div class="grid-2" style="align-items:start;">

                            {{-- Cover --}}
                            <div class="field-group">
                                <label>{{ __('messages.cover_image') }} <span class="req">*</span></label>
                                <div class="cover-preview" id="coverPreview">
                                    <span class="cover-placeholder">🖼️</span>
                                    <img id="coverImg" alt="Cover preview">
                                </div>
                                <div class="dropzone" id="coverDrop" style="margin-top:.75rem; padding: 1rem;">
                                    <input type="file" name="cover_image" accept="image/*" required
                                        id="coverInput"
                                        onchange="previewCover(this)">
                                    <div class="dz-icon">🎨</div>
                                    <div class="dz-text">{{ __('messages.choose_cover') }}</div>
                                    <div class="dz-sub">JPEG, PNG, WEBP — max 5 Mo</div>
                                    <div class="dz-file-name" id="coverFileName"></div>
                                </div>
                                @error('cover_image') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            {{-- Book file --}}
                            <div class="field-group">
                                <label>{{ __('messages.book_file') }} <span class="req">*</span></label>
                                <div class="dropzone" id="fileDrop" style="min-height: 200px; display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                    <input type="file" name="book_file" accept=".pdf,.epub" required
                                        id="fileInput"
                                        onchange="handleBookFile(this)">
                                    <div class="dz-icon" id="fileIcon">📄</div>
                                    <div class="dz-text">{{ __('messages.drag_drop_book') }}</div>
                                    <div class="dz-sub">PDF ou EPUB — max 50 Mo</div>
                                    <div class="dz-file-name" id="bookFileName"></div>
                                </div>
                                @error('book_file') <span class="field-error">{{ $message }}</span> @enderror
                                <p class="hint" style="margin-top:.5rem;">
                                    {{ __('messages.book_file_hint') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="step-nav">
                        <button type="button" class="btn-nav-prev" onclick="goStep(1)">← {{ __('messages.previous') }}</button>
                        <button type="button" class="btn-nav-next" onclick="goStep(3)">{{ __('messages.next') }} →</button>
                    </div>
                </div>

                {{-- ════ STEP 3: Options & Submit ════ --}}
                <div class="step-panel" id="panel-3">
                    <div class="step-header">
                        <div class="step-icon icon-opts">⚙️</div>
                        <div>
                            <h2>{{ __('messages.step_options') }}</h2>
                            <p>{{ __('messages.step_options_desc') }}</p>
                        </div>
                    </div>
                    <div class="step-body">

                        <label class="toggle-card" style="margin-bottom:1rem;">
                            <input type="checkbox" name="is_premium" value="1" id="isPremium" {{ old('is_premium') ? 'checked' : '' }}>
                            <div class="toggle-info">
                                <strong>💎 {{ __('messages.premium_book') }}</strong>
                                <span>{{ __('messages.premium_book_hint') }}</span>
                            </div>
                            <div class="toggle-switch"></div>
                        </label>

                        {{-- Summary card --}}
                        <div id="summarySect" style="
                            padding: 1.25rem; border-radius: 14px;
                            background: linear-gradient(135deg, rgba(99,102,241,.06), rgba(16,185,129,.05));
                            border: 1.5px solid rgba(99,102,241,.15);
                        ">
                            <p style="font-weight:700; margin-bottom:.75rem; font-size:.9rem;">📋 {{ __('messages.summary') }}</p>
                            <div style="display:grid; grid-template-columns: auto 1fr; gap:.35rem .75rem; font-size:.82rem; color:var(--text-secondary, #64748b);">
                                <span style="font-weight:600;">{{ __('messages.title') }}:</span>
                                <span id="sumTitle">—</span>
                                <span style="font-weight:600;">{{ __('messages.type') }}:</span>
                                <span id="sumType">—</span>
                                <span style="font-weight:600;">{{ __('messages.category') }}:</span>
                                <span id="sumCat">—</span>
                                <span style="font-weight:600;">{{ __('messages.language') }}:</span>
                                <span id="sumLang">—</span>
                                <span style="font-weight:600;">{{ __('messages.book_file') }}:</span>
                                <span id="sumFile">—</span>
                                <span style="font-weight:600;">Statut:</span>
                                <span><span class="status-pending">⏳ En attente de révision</span></span>
                            </div>
                        </div>

                        <div style="background:rgba(245,158,11,.08); border-radius:12px; padding:1rem; margin-top:1rem;
                            border-left: 3px solid #f59e0b; font-size:.82rem; color:#92400e;">
                            ⚠️ <strong>{{ __('messages.review_notice_title') }}</strong>
                            {{ __('messages.review_notice') }}
                        </div>
                    </div>
                    <div class="step-nav">
                        <button type="button" class="btn-nav-prev" onclick="goStep(2)">← {{ __('messages.previous') }}</button>
                        <button type="submit" class="btn-nav-next btn-submit" id="submitBtn"
                            onclick="showUploadOverlay()">
                            🚀 {{ __('messages.submit_for_review') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>{{-- /upload-card --}}
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentStep = 1;
const totalSteps = 3;

function goStep(step) {
    if (step < 1 || step > totalSteps) return;

    // Validate step 1 before advancing
    if (currentStep === 1 && step > 1) {
        const title = document.querySelector('[name="title"]').value.trim();
        const desc  = document.querySelector('[name="description"]').value.trim();
        const cat   = document.querySelector('[name="category_id"]').value;
        if (!title || !desc || !cat) {
            highlightEmpty(['[name="title"]', '[name="description"]', '[name="category_id"]']);
            return;
        }
    }

    // Validate step 2 before advancing
    if (currentStep === 2 && step > 2) {
        const cover = document.getElementById('coverInput').files.length;
        const file  = document.getElementById('fileInput').files.length;
        if (!cover || !file) {
            if (!cover) document.getElementById('coverDrop').style.borderColor = '#ef4444';
            if (!file)  document.getElementById('fileDrop').style.borderColor  = '#ef4444';
            return;
        }
    }

    document.getElementById('panel-' + currentStep).classList.remove('active');
    document.getElementById('panel-' + step).classList.add('active');

    // Dots & bars
    for (let i = 1; i <= totalSteps; i++) {
        const dot = document.getElementById('dot-' + i);
        dot.classList.remove('active','done');
        if (i < step) dot.classList.add('done');
        if (i === step) dot.classList.add('active');
    }
    for (let i = 1; i < totalSteps; i++) {
        const bar = document.getElementById('bar-' + i);
        if (bar) bar.classList.toggle('done', i < step);
    }

    if (step === 3) updateSummary();
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function highlightEmpty(selectors) {
    selectors.forEach(s => {
        const el = document.querySelector(s);
        if (el && !el.value.trim()) {
            el.classList.add('error');
            el.addEventListener('input', () => el.classList.remove('error'), { once: true });
        }
    });
}

function updateSummary() {
    document.getElementById('sumTitle').textContent = document.querySelector('[name="title"]').value || '—';
    const typeEl = document.querySelector('[name="type"]');
    document.getElementById('sumType').textContent = typeEl?.options[typeEl.selectedIndex]?.text || '—';
    const catEl = document.querySelector('[name="category_id"]');
    document.getElementById('sumCat').textContent = catEl?.options[catEl.selectedIndex]?.text || '—';
    const langEl = document.querySelector('[name="language"]');
    document.getElementById('sumLang').textContent = langEl?.options[langEl.selectedIndex]?.text || '—';
    const f = document.getElementById('fileInput').files[0];
    document.getElementById('sumFile').textContent = f ? f.name + ' (' + (f.size/1024/1024).toFixed(1) + ' Mo)' : '—';
}

function previewCover(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('coverImg');
        img.src = e.target.result;
        document.getElementById('coverPreview').classList.add('has-image');
        document.getElementById('coverFileName').textContent = '✅ ' + file.name;
        document.getElementById('coverDrop').classList.add('has-file');
        document.getElementById('coverDrop').style.borderColor = '#10b981';
    };
    reader.readAsDataURL(file);
}

function handleBookFile(input) {
    const file = input.files[0];
    if (!file) return;
    const ext = file.name.split('.').pop().toLowerCase();
    document.getElementById('fileIcon').textContent = ext === 'epub' ? '📗' : '📕';
    document.getElementById('bookFileName').textContent = '✅ ' + file.name;
    document.getElementById('fileDrop').classList.add('has-file');
    document.getElementById('fileDrop').style.borderColor = '#10b981';
}

// Dynamic drag-and-drop
['coverDrop', 'fileDrop'].forEach(id => {
    const el = document.getElementById(id);
    el.addEventListener('dragover', e => { e.preventDefault(); el.classList.add('drag-over'); });
    el.addEventListener('dragleave', () => el.classList.remove('drag-over'));
    el.addEventListener('drop', e => { e.preventDefault(); el.classList.remove('drag-over'); });
});

// Tag input behaviour
const tagInput     = document.getElementById('tagInput');
const keywordsHidden = document.getElementById('keywordsHidden');
const tagsWrap     = document.getElementById('tagsWrap');
let tags = keywordsHidden.value ? keywordsHidden.value.split(',').filter(Boolean) : [];

function renderTags() {
    Array.from(tagsWrap.querySelectorAll('.tag-chip')).forEach(c => c.remove());
    tags.forEach((tag, idx) => {
        const chip = document.createElement('span');
        chip.className = 'tag-chip';
        chip.innerHTML = `${tag}<button type="button" onclick="removeTag(${idx})">×</button>`;
        tagsWrap.insertBefore(chip, tagInput);
    });
    keywordsHidden.value = tags.join(',');
}

function removeTag(idx) { tags.splice(idx, 1); renderTags(); }

tagInput.addEventListener('keydown', e => {
    if (['Enter', ',', 'Tab'].includes(e.key)) {
        e.preventDefault();
        const val = tagInput.value.trim().replace(/,/g,'');
        if (val && !tags.includes(val)) { tags.push(val); renderTags(); }
        tagInput.value = '';
    } else if (e.key === 'Backspace' && !tagInput.value && tags.length) {
        tags.pop(); renderTags();
    }
});

renderTags();

function showUploadOverlay() {
    const f = document.getElementById('fileInput').files[0];
    const c = document.getElementById('coverInput').files[0];
    if (f && c) {
        document.getElementById('uploadOverlay').classList.add('active');
    }
}

// If validation errors from server — jump to right step
@if($errors->hasAny(['title','description','category_id','type','language','isbn','doi']))
    goStep(1);
@elseif($errors->hasAny(['cover_image','book_file']))
    goStep(2);
@endif
</script>
@endpush
