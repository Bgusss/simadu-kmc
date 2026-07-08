@forelse($notifications as $notif)
    @php

        $originalMessage = $notif->comment_message ?? ($notif->message ?? '');

        $cleanMessage = preg_replace('/@?Simadu\s*KMC[:,]?\s*/i', '', $originalMessage);

        $cleanMessage = trim($cleanMessage);

    @endphp

    <div class="notification-card p-4 mb-4">

        <div class="d-flex justify-content-between">

            <div class="d-flex gap-3">

                @if ($notif->title == 'Instagram DM')
                    <div class="instagram-icon" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%,#d6249f 60%,#285AEB 90%); color: white; font-size: 1.2rem;">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                @else
                    <div class="facebook-icon">
                        f
                    </div>
                @endif

                <div>

                    <h3 class="fw-bold mb-1">

                        {{ $notif->sender_name }}

                    </h3>

                    <span class="platform-badge">

                        @if ($notif->title == 'Facebook Comment Mention')
                            Komentar Facebook
                        @elseif ($notif->title == 'Instagram DM')
                            DM Instagram
                        @else
                            Postingan Facebook
                        @endif

                    </span>

                </div>

            </div>

            <div class="text-end">

                {{-- Prioritas + Waktu --}}
                <div class="d-flex align-items-center justify-content-end gap-2 mb-3">

                    @if ($notif->ai?->priority === 'Tinggi')
                        <span class="priority-badge">

                            Prioritas Tinggi

                        </span>
                    @endif

                    <small class="text-muted">

                        <i class="fa-regular fa-clock me-1"></i>

                        {{ $notif->created_at->diffForHumans() }}

                    </small>

                </div>

                {{-- Status --}}
                <div class="d-flex justify-content-end gap-2">

                    @if (!$notif->is_read)
                        <span class="status-new">

                            <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i>

                            Baru

                        </span>

                        <span class="status-unread">

                            Belum Dibaca

                        </span>
                    @else
                        <span class="status-read">

                            Sudah Dibaca

                        </span>
                    @endif

                </div>

            </div>

        </div>

        <div class="message-box mt-4">

            {{ $cleanMessage }}

        </div>

        {{-- ── DUPLIKASI TERDETEKSI ────────────────────────────── --}}
        @if ($notif->duplicate_status === 'terdeteksi')
            <div class="mt-3 p-3 rounded-4" style="background: linear-gradient(135deg, #FFF8E1, #FFE0B2); border: 1px solid #FFB74D;">
                <div class="d-flex align-items-start gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 36px; height: 36px; background: #E65100;">
                        <i class="fa-solid fa-triangle-exclamation text-white" style="font-size: 0.9rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold mb-1" style="color: #E65100; font-size: 0.95rem;">
                            Duplikat Terdeteksi
                            <span class="badge rounded-pill ms-2" style="background: #E65100; font-size: 0.72rem; vertical-align: middle;">
                                {{ round($notif->duplicate_similarity) }}% Mirip
                            </span>
                        </div>
                        @if ($notif->duplicateOf)
                            @php
                                $origMsg = $notif->duplicateOf->comment_message ?? $notif->duplicateOf->message ?? '';
                                $origClean = preg_replace('/@?Simadu\s*KMC[:,]?\s*/i', '', $origMsg);
                                $origClean = trim($origClean);
                            @endphp
                            <div class="small mb-2" style="color: #795548;">
                                <i class="fa-solid fa-arrow-right-arrow-left me-1" style="font-size: 0.7rem;"></i>
                                Mirip dengan aduan dari
                                <strong>{{ $notif->duplicateOf->sender_name }}</strong>:
                                <em class="text-muted">"{{ Str::limit($origClean, 80) }}"</em>
                            </div>
                        @endif
                        <div class="d-flex gap-2 mt-2 flex-wrap">
                            <form method="POST" action="{{ route('notifications.not-duplicate', $notif->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm"
                                    onclick="return confirm('Yakin bukan duplikat? Tiket akan langsung dibuat.')">
                                    <i class="fa-solid fa-check me-1"></i> Bukan Duplikat — Buat Tiket
                                </button>
                            </form>
                            <form method="POST" action="{{ route('notifications.is-duplicate', $notif->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                    onclick="return confirm('Konfirmasi bahwa ini memang duplikat?')">
                                    <i class="fa-solid fa-ban me-1"></i> Konfirmasi Duplikat
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($notif->duplicate_status === 'bukan_duplikat')
            <div class="mt-3 px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2" style="background: #E8F5E9; border: 1px solid #A5D6A7;">
                <i class="fa-solid fa-circle-check" style="color: #2E7D32;"></i>
                <span class="small fw-semibold" style="color: #2E7D32;">Diverifikasi Bukan Duplikat oleh Admin</span>
            </div>
        @elseif ($notif->duplicate_status === 'dikonfirmasi_duplikat')
            <div class="mt-3 px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2" style="background: #F5F5F5; border: 1px solid #E0E0E0;">
                <i class="fa-solid fa-ban" style="color: #9E9E9E;"></i>
                <span class="small fw-semibold" style="color: #757575;">Dikonfirmasi Duplikat — Tiket Tidak Dibuat</span>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">

            <div class="d-flex gap-2 flex-wrap">

                @if ($notif->ai?->suggested_category)
                    <span class="category-badge">
                        <i class="fa-solid fa-building me-1" style="font-size: 0.7rem;"></i>{{ $notif->ai->suggested_category }}
                    </span>
                @endif

                @if ($notif->ai?->suggested_sub_category)
                    <span class="subcategory-badge">
                        <i class="fa-solid fa-tag me-1" style="font-size: 0.7rem;"></i>{{ $notif->ai->suggested_sub_category }}
                    </span>
                @endif

            </div>

            <div class="d-flex gap-2">

                @if ($notif->permalink)
                    <a href="/notification/{{ $notif->id }}/detail?url={{ urlencode($notif->permalink) }}"
                        target="_blank" class="btn btn-outline-primary rounded-3">

                        Lihat Postingan

                    </a>
                @endif

            </div>

        </div>

    </div>

@empty

    <div class="alert alert-info">

        Belum ada notifikasi.

    </div>
@endforelse

