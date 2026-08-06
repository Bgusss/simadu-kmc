@forelse($notifications as $notif)
    @php
        $cleanMessage = $notif->display_message;
        $isUnread = !$notif->is_read;

        if ($notif->title == 'Instagram DM') {
            $platform = 'DM Instagram';
            $platformBg = '#FDF0F5';
            $platformColor = '#D6249F';
            $avatarClass = 'instagram';
            $avatarIcon = 'fa-instagram';
        } elseif ($notif->title == 'Facebook Comment Mention') {
            $platform = 'Komentar Facebook';
            $platformBg = '#E7F0FF';
            $platformColor = '#1877F2';
            $avatarClass = 'facebook';
            $avatarIcon = 'fa-facebook-f';
        } elseif ($notif->title == 'WhatsApp' || str_contains($notif->title ?? '', 'WhatsApp')) {
            $platform = 'WhatsApp';
            $platformBg = '#dcfce7';
            $platformColor = '#166534';
            $avatarClass = 'whatsapp';
            $avatarIcon = 'fa-whatsapp';
        } elseif ($notif->title == 'Laporan Web SIMADU' || str_contains($notif->title ?? '', 'Laporan Web')) {
            $platform = 'Laporan Web';
            $platformBg = '#dbeafe';
            $platformColor = '#1e40af';
            $avatarClass = 'web';
            $avatarIcon = 'fa-globe';
        } else {
            $platform = 'Postingan Facebook';
            $platformBg = '#E7F0FF';
            $platformColor = '#1877F2';
            $avatarClass = 'facebook';
            $avatarIcon = 'fa-facebook-f';
        }
    @endphp

    <div class="notif-row {{ $isUnread ? 'unread' : '' }}">
        
        {{-- Kolom Kiri: Icon --}}
        <div class="notif-left-col">
            <div class="platform-avatar {{ $avatarClass }}">
                <i class="fa-brands {{ $avatarIcon }}"></i>
            </div>
            @if ($isUnread)
                <span class="unread-dot"></span>
            @endif
        </div>

        {{-- Kolom Tengah: Info & Message --}}
        <div class="notif-mid-col">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="sender-name">{{ $notif->sender_name }}</span>
                <span class="platform-badge" style="background-color: {{ $platformBg }}; color: {{ $platformColor }};">
                    {{ $platform }}
                </span>
                @if ($notif->ai?->priority === 'Tinggi')
                    <span class="priority-badge-pill">
                        Prioritas Tinggi
                    </span>
                @endif
            </div>

            <div class="message-text">
                {{ Str::limit($cleanMessage, 150) }}
            </div>

            {{-- Kategori tag --}}
            <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                @if ($notif->ai?->suggested_category)
                    <span class="category-tag">
                        <i class="fa-solid fa-building me-1"></i>{{ $notif->ai->suggested_category }}
                    </span>
                @endif
                @if ($notif->ai?->suggested_sub_category)
                    <span class="subcategory-tag">
                        <i class="fa-solid fa-tag me-1"></i>{{ $notif->ai->suggested_sub_category }}
                    </span>
                @endif
            </div>

            {{-- Duplikasi inline --}}
            @if ($notif->duplicate_status === 'terdeteksi')
                <div class="dup-inline-bar mt-2">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    <span>Duplikat {{ round($notif->duplicate_similarity) }}% mirip</span>
                    @if ($notif->duplicateOf)
                        <span class="text-muted"> — mirip dengan <strong>{{ $notif->duplicateOf->sender_name }}</strong></span>
                    @endif
                    <div class="d-inline-flex gap-1 ms-2">
                        <form method="POST" action="{{ route('notifications.not-duplicate', $notif->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-dup-action btn-dup-ok" onclick="return confirm('Yakin bukan duplikat? Tiket akan langsung dibuat.')">
                                Bukan Duplikat
                            </button>
                        </form>
                        <form method="POST" action="{{ route('notifications.is-duplicate', $notif->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-dup-action btn-dup-no" onclick="return confirm('Konfirmasi bahwa ini memang duplikat?')">
                                Duplikat
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan: Meta & View Button --}}
        <div class="notif-right-col">
            <span class="notif-time-text">
                <i class="fa-regular fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
            </span>

            @if ($isUnread)
                <span class="status-badge status-badge-new">Baru</span>
            @else
                <span class="status-badge status-badge-read">Terbaca</span>
            @endif

            {{-- Tombol Detail internal HANYA untuk WhatsApp / Web --}}
            @if (in_array($notif->title, ['WhatsApp', 'Laporan Web SIMADU']) || str_contains($notif->title ?? '', 'WhatsApp') || str_contains($notif->title ?? '', 'Laporan Web'))
                <a href="{{ route('notifications.show', $notif->id) }}"
                    class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center justify-content-center gap-2"
                    style="font-size: 0.78rem; border-width: 2px; white-space: nowrap;">
                    <span class="d-inline-flex align-items-center justify-content-center" style="line-height: 1; height: 1em;"><i class="fa-solid fa-eye" style="font-size: 0.76rem; line-height: 1;"></i></span>
                    <span style="line-height: 1;">Detail</span>
                </a>
            @endif

            {{-- Tombol Lihat untuk notif media sosial (FB/IG) --}}
            @if ($notif->permalink && !in_array($notif->title, ['WhatsApp', 'Laporan Web SIMADU']))
                <a href="/notification/{{ $notif->id }}/detail?url={{ urlencode($notif->permalink) }}"
                    target="_blank"
                    class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center justify-content-center gap-2"
                    style="font-size: 0.78rem; border-width: 2px; white-space: nowrap;">
                    <span class="d-inline-flex align-items-center justify-content-center" style="line-height: 1; height: 1em;"><i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.72rem; line-height: 1;"></i></span>
                    <span style="line-height: 1;">Lihat</span>
                </a>
            @endif
        </div>

    </div>
@empty
    <div class="text-center py-4 text-muted">
        <i class="fa-regular fa-bell-slash fa-2x mb-2 d-block"></i>
        Belum ada notifikasi.
    </div>
@endforelse