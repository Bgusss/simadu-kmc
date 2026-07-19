@extends('layouts.app')

@section('title', 'Buat Tiket')
@section('page-title', 'Buat Tiket')

@section('content')

    @php
        $platformRaw = $notification ? ($notification->title ?? 'Facebook') : '';
        $platformName = $notification ? trim(explode(' ', $platformRaw)[0] ?? 'Facebook') : 'Lainnya';
        if ($platformName === '') {
            $platformName = 'Facebook';
        }

        $aiOpds = $notification && $notification->ai ? $notification->ai->suggested_opds ?? [] : [];

        $selectedOpd = old('opd_related', $aiOpds[0] ?? '');

        $originalMessage = $notification ? ($notification->comment_message ?? ($notification->message ?? '')) : '';
        $cleanMessage = preg_replace('/@?Simadu\s*KMC[:,]?\s*/i', '', $originalMessage);
        $cleanMessage = trim($cleanMessage);
    @endphp

    <style>
        .readonly-field {
            background-color: #f8f9fa !important;
            cursor: not-allowed;
        }

        .sheet-combobox-wrapper {
            position: relative;
        }

        .sheet-combobox-input {
            width: 100%;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #fff;
            transition: .15s;
        }

        .sheet-combobox-input:focus {
            outline: none;
            border-color: #86b7fe;
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15);
        }

        .sheet-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            z-index: 2000;
            background: #fff;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            max-height: 260px;
            overflow-y: auto;
            display: none;
        }

        .sheet-dropdown.open {
            display: block;
        }

        .sheet-dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
            line-height: 1.4;
        }

        .sheet-dropdown-item:hover,
        .sheet-dropdown-item.active {
            background: #e8f0fe;
        }

        .sheet-hint {
            font-size: 12px;
            color: #6c757d;
        }

        .ai-badge {
            background: #eef2ff;
            color: #4f46e5;
            border-radius: 999px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
    </style>

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

            <form method="POST" action="{{ route('tickets.store') }}">
                @csrf

                <input type="hidden" name="notification_id" value="{{ $notification->id ?? '' }}">

                <div class="row g-4">
                    {{-- Waktu --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Waktu</label>
                        <input type="text" class="form-control readonly-field"
                            value="{{ $ticketTime->format('Y-m-d H:i:s') }}" readonly tabindex="-1">
                    </div>

                    {{-- Nomor Tiket --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Tiket</label>
                        <input type="text" class="form-control readonly-field" value="{{ $ticketNumber }}" readonly
                            tabindex="-1">
                    </div>

                    {{-- Platform --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Platform</label>
                        @if ($notification)
                            <input type="text" class="form-control readonly-field" value="{{ $platformName }}" readonly tabindex="-1">
                            <input type="hidden" name="platform" value="{{ $platformName }}">
                        @else
                            <select name="platform" class="form-select" required>
                                <option value="WhatsApp" {{ old('platform') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="Telepon" {{ old('platform') == 'Telepon' ? 'selected' : '' }}>Telepon</option>
                                <option value="Tatap Muka" {{ old('platform') == 'Tatap Muka' ? 'selected' : '' }}>Tatap Muka</option>
                                <option value="Facebook" {{ old('platform') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                <option value="Instagram" {{ old('platform') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                <option value="Email" {{ old('platform') == 'Email' ? 'selected' : '' }}>Email</option>
                                <option value="Lainnya" {{ old('platform') == 'Lainnya' ? 'selected' : 'selected' }}>Lainnya</option>
                            </select>
                        @endif
                    </div>

                    {{-- Nama Pelapor --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Pelapor / ID / Identitas</label>
                        @if ($notification)
                            <input type="text" class="form-control readonly-field"
                                value="{{ old('reporter_name', $notification->sender_name ?? ($notification->sender ?? '')) }}"
                                readonly tabindex="-1">
                            <input type="hidden" name="reporter_name"
                                value="{{ old('reporter_name', $notification->sender_name ?? ($notification->sender ?? '')) }}">
                        @else
                            <input type="text" name="reporter_name" class="form-control" value="{{ old('reporter_name') }}" required placeholder="Nama pelapor...">
                        @endif
                    </div>

                    {{-- Nomor HP / Link --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor HP / Link Pelapor</label>
                        @if ($notification)
                            <input type="text" class="form-control readonly-field"
                                value="{{ old('reporter_link', $notification->permalink ?? '') }}" readonly tabindex="-1">
                            <input type="hidden" name="reporter_link"
                                value="{{ old('reporter_link', $notification->permalink ?? '') }}">
                        @else
                            <input type="text" name="reporter_link" class="form-control" value="{{ old('reporter_link') }}" placeholder="Nomor HP atau link profil (opsional)...">
                        @endif
                    </div>

                    {{-- Kategori --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori</label>
                        @if ($notification)
                            <input type="text" class="form-control readonly-field"
                                value="{{ old('category', $notification->ai->suggested_category ?? 'Belum ada') }}" readonly
                                tabindex="-1">
                            <input type="hidden" name="category"
                                value="{{ old('category', $notification->ai->suggested_category ?? '') }}">
                        @else
                            <select name="category" id="category_select" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->name }}" {{ old('category') == $cat->name ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    {{-- Sub Kategori --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Sub Kategori</label>
                        @if ($notification)
                            <input type="text" class="form-control readonly-field"
                                value="{{ old('sub_category', $notification->ai->suggested_sub_category ?? 'Belum ada') }}"
                                readonly tabindex="-1">
                            <input type="hidden" name="sub_category"
                                value="{{ old('sub_category', $notification->ai->suggested_sub_category ?? '') }}">
                        @else
                            <select name="sub_category" id="subcategory_select" class="form-select" required>
                                <option value="">-- Pilih Sub Kategori --</option>
                            </select>
                        @endif
                    </div>

                    @if (!$notification)
                        {{-- Prioritas --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prioritas</label>
                            <select name="priority" class="form-select" required>
                                <option value="rendah" {{ old('priority') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="sedang" {{ old('priority') == 'sedang' ? 'selected' : 'selected' }}>Sedang</option>
                                <option value="tinggi" {{ old('priority') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            </select>
                        </div>
                    @endif

                    {{-- OPD Terkait --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">OPD Terkait</label>

                        <div class="sheet-combobox-wrapper">
                            <input type="text" id="opd_search"
                                class="sheet-combobox-input @error('opd_related') is-invalid @enderror"
                                {{-- value="{{ $selectedOpd }}" --}} placeholder="Ketik untuk mencari OPD..." autocomplete="off" required>

                            <input type="hidden" name="opd_related" id="opd_related" value="{{ $selectedOpd }}">

                            <div id="opd_dropdown" class="sheet-dropdown">
                                @foreach ($opds as $opd)
                                    <div class="sheet-dropdown-item" data-value="{{ $opd->name }}">
                                        {{ $opd->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if (count($aiOpds) > 0)
                            <div class="mt-2">
                                <span class="ai-badge">
                                    <i class="fa-solid fa-robot"></i>
                                    Rekomendasi AI: {{ implode(', ', $aiOpds) }}
                                </span>
                            </div>
                        @endif

                        @error('opd_related')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Pertanyaan / Permasalahan --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Pertanyaan / Permasalahan (Isi Aduan)</label>
                        @if ($notification)
                            <textarea class="form-control readonly-field" rows="5" readonly tabindex="-1">{{ old('complaint', $cleanMessage) }}</textarea>
                            <input type="hidden" name="complaint" value="{{ old('complaint', $cleanMessage) }}">
                        @else
                            <textarea name="complaint" class="form-control" rows="5" required placeholder="Tulis isi aduan atau laporan di sini...">{{ old('complaint') }}</textarea>
                        @endif
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="col-12 d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                            <i class="fa-solid fa-save me-1"></i> Simpan & Kirim
                        </button>
                        <a href="{{ route('notifications.index') }}"
                            class="btn btn-outline-secondary px-4 py-2 rounded-3">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('opd_search');
            const hidden = document.getElementById('opd_related');
            const dropdown = document.getElementById('opd_dropdown');
            const items = Array.from(dropdown.querySelectorAll('.sheet-dropdown-item'));

            let activeIndex = -1;

            function openDropdown() {
                dropdown.classList.add('open');
            }

            function closeDropdown() {
                dropdown.classList.remove('open');
                activeIndex = -1;
                updateActive();
            }

            function updateActive() {
                items.forEach((item, index) => {
                    item.classList.toggle('active', index === activeIndex);
                });
            }

            function filterItems(query) {
                const q = query.trim().toLowerCase();

                items.forEach(item => {
                    const text = item.dataset.value.toLowerCase();
                    const match = !q || text.includes(q);
                    item.style.display = match ? 'block' : 'none';
                });

                const visibleItems = items.filter(item => item.style.display !== 'none');
                activeIndex = visibleItems.length > 0 ? 0 : -1;

                items.forEach(item => item.classList.remove('active'));
                if (activeIndex >= 0) {
                    visibleItems[activeIndex].classList.add('active');
                }
            }

            function selectItem(value) {
                input.value = value;
                hidden.value = value;
                closeDropdown();
            }

            input.addEventListener('focus', function() {
                openDropdown();
                filterItems(input.value);
            });

            input.addEventListener('click', function() {
                openDropdown();
                filterItems(input.value);
            });

            input.addEventListener('input', function() {
                hidden.value = '';
                openDropdown();
                filterItems(input.value);
            });

            input.addEventListener('keydown', function(e) {
                const visibleItems = items.filter(item => item.style.display !== 'none');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!dropdown.classList.contains('open')) {
                        openDropdown();
                        filterItems(input.value);
                    }
                    if (visibleItems.length > 0) {
                        activeIndex = Math.min(activeIndex + 1, visibleItems.length - 1);
                        items.forEach(item => item.classList.remove('active'));
                        visibleItems[activeIndex].classList.add('active');
                    }
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (visibleItems.length > 0) {
                        activeIndex = Math.max(activeIndex - 1, 0);
                        items.forEach(item => item.classList.remove('active'));
                        visibleItems[activeIndex].classList.add('active');
                    }
                }

                if (e.key === 'Enter') {
                    const activeItem = visibleItems[activeIndex];
                    if (activeItem) {
                        e.preventDefault();
                        selectItem(activeItem.dataset.value);
                    }
                }

                if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            dropdown.addEventListener('click', function(e) {
                const item = e.target.closest('.sheet-dropdown-item');
                if (item) {
                    selectItem(item.dataset.value);
                }
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.sheet-combobox-wrapper')) {
                    closeDropdown();
                }
            });

            // Logika dependent dropdown Kategori -> Sub Kategori (jika form manual)
            const categorySelect = document.getElementById('category_select');
            const subcategorySelect = document.getElementById('subcategory_select');
            
            if (categorySelect && subcategorySelect) {
                const categories = @json($categories ?? []);
                const initialCategory = "{{ old('category') }}";
                const initialSubcategory = "{{ old('sub_category') }}";
                
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
                    }
                }
                
                if (categorySelect.value) {
                    populateSubcategories(categorySelect.value, initialSubcategory);
                }
                
                categorySelect.addEventListener('change', function () {
                    populateSubcategories(this.value);
                });
            }
        });
    </script>

@endsection
