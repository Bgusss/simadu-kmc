@extends('layouts.app')

@section('title', 'Edit Tiket ' . ($ticket->tracking_number ?? $ticket->ticket_number))
@section('page-title')
    <a href="{{ route('tickets.index') }}" class="btn-back me-2"><i class="fas fa-arrow-left"></i></a> 
    Edit Tiket: {{ $ticket->tracking_number ?? $ticket->ticket_number }}
@endsection

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                <strong>Terdapat kesalahan pada form:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- Nomor Tiket --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor Tiket</label>
                    <input type="text" class="form-control bg-light" value="{{ $ticket->ticket_number }}" readonly>
                </div>

                {{-- Platform --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Platform</label>
                    <input type="text" class="form-control bg-light" value="{{ $ticket->platform }}" readonly>
                </div>

                {{-- Kategori --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="category" id="category_select" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->name }}" {{ old('category', $ticket->category) == $cat->name ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sub Kategori --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Sub Kategori</label>
                    <select name="sub_category" id="subcategory_select" class="form-select" required>
                        <option value="">-- Pilih Sub Kategori --</option>
                    </select>
                </div>

                {{-- OPD Terkait --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">OPD Terkait (Tujuan)</label>
                    <select name="opd_related" class="form-select" required>
                        <option value="">-- Pilih OPD --</option>
                        @foreach ($opds as $opd)
                            <option value="{{ $opd->name }}" {{ old('opd_related', $ticket->opd_related) == $opd->name ? 'selected' : '' }}>
                                {{ $opd->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Prioritas --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Prioritas</label>
                    <select name="priority" class="form-select" required>
                        <option value="rendah" {{ old('priority', $ticket->priority) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="sedang" {{ old('priority', $ticket->priority) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="tinggi" {{ old('priority', $ticket->priority) == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                    </select>
                </div>

                {{-- Pertanyaan / Permasalahan --}}
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Isi Aduan</label>
                    <textarea class="form-control bg-light" rows="5" readonly>{{ $ticket->complaint }}</textarea>
                </div>

                {{-- Tombol Aksi --}}
                <div class="col-12 d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                        <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const categories = @json($categories);
    const categorySelect = document.getElementById('category_select');
    const subcategorySelect = document.getElementById('subcategory_select');
    
    const initialCategory = "{{ old('category', $ticket->category) }}";
    const initialSubcategory = "{{ old('sub_category', $ticket->sub_category) }}";
    
    if (initialCategory) {
        const catExists = categories.some(cat => cat.name === initialCategory);
        if (!catExists) {
            const tempOpt = document.createElement('option');
            tempOpt.value = initialCategory;
            tempOpt.textContent = initialCategory;
            tempOpt.selected = true;
            categorySelect.appendChild(tempOpt);
        }
    }
    
    function populateSubcategories(selectedCategoryName, selectedSubcategoryName = '') {
        subcategorySelect.innerHTML = '<option value="">-- Pilih Sub Kategori --</option>';
        
        if (!selectedCategoryName) return;
        
        const categoryObj = categories.find(cat => cat.name === selectedCategoryName);
        
        let subcategories = [];
        if (categoryObj && categoryObj.sub_categories) {
            subcategories = categoryObj.sub_categories;
        }
        
        subcategories.forEach(sub => {
            const opt = document.createElement('option');
            opt.value = sub.name;
            opt.textContent = sub.name;
            if (selectedSubcategoryName && sub.name === selectedSubcategoryName) {
                opt.selected = true;
            }
            subcategorySelect.appendChild(opt);
        });
        
        if (subcategories.length === 0) {
            const opt = document.createElement('option');
            opt.value = selectedCategoryName;
            opt.textContent = selectedCategoryName;
            opt.selected = true;
            subcategorySelect.appendChild(opt);
        } else if (selectedSubcategoryName) {
            const subExists = subcategories.some(sub => sub.name === selectedSubcategoryName);
            if (!subExists) {
                const tempOpt = document.createElement('option');
                tempOpt.value = selectedSubcategoryName;
                tempOpt.textContent = selectedSubcategoryName;
                tempOpt.selected = true;
                subcategorySelect.appendChild(tempOpt);
            }
        }
    }
    
    if (categorySelect.value) {
        populateSubcategories(categorySelect.value, initialSubcategory);
    }
    
    categorySelect.addEventListener('change', function () {
        populateSubcategories(this.value);
    });
});
</script>
@endsection
