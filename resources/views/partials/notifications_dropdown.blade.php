@php
  $user = auth()->user();
  $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
  $latest = $user ? $user->notifications()->latest()->limit(7)->get() : collect();
@endphp

<div class="relative">
  <!-- Bell button -->
  <button id="notifToggle" class="relative inline-flex items-center p-2 rounded hover:bg-gray-100" aria-expanded="false" aria-controls="notifPanel">
    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h11z"/></svg>
    <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 {{ $unreadCount ? '' : 'hidden' }}">{{ $unreadCount }}</span>
  </button>

  <!-- Dropdown panel -->
  <div id="notifPanel" class="hidden origin-top-left absolute left-0 mt-2 w-96 bg-white border rounded shadow-lg z-50" role="region" aria-labelledby="notifToggle">
    <div class="p-3 border-b flex justify-between items-center">
      <div class="font-medium">Notifikasi</div>
      <form id="markAllForm" action="{{ route('notifications.readAll') }}" method="POST">
        @csrf
        <button type="submit" class="text-xs text-indigo-600">Tandai semua terbaca</button>
      </form>
    </div>

    <div id="notif-list" class="max-h-80 overflow-auto">
      @forelse($latest as $n)
        <div class="p-3 border-b {{ $n->read_at ? 'bg-gray-50' : 'bg-white' }}">
          <div class="text-sm">{{ $n->data['message'] ?? $n->data['judul'] ?? 'Notifikasi baru' }}</div>
          <div class="text-xs text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</div>
          @if(!$n->read_at)
            <form class="mt-2 inline" method="POST" action="{{ route('notifications.read', $n->id) }}">
              @csrf
              <button class="text-xs text-indigo-600">Tandai terbaca</button>
            </form>
          @endif
        </div>
      @empty
        <div class="p-3 text-sm text-gray-500">Belum ada notifikasi.</div>
      @endforelse
    </div>

    <div class="p-2 text-center">
      <a href="{{ route('notifications.index') }}" class="text-sm text-indigo-600">Lihat semua</a>
    </div>
  </div>
</div>

<!-- Toast container: top-right -->
<div id="toast-container" class="fixed top-5 right-5 z-50 space-y-2"></div>

<script>
  // Toggle dropdown
  const toggle = document.getElementById('notifToggle');
  const panel = document.getElementById('notifPanel');
  const badgeEl = document.getElementById('notif-badge');

  toggle?.addEventListener('click', (e) => {
    panel.classList.toggle('hidden');
  });

  document.addEventListener('click', (e) => {
    if (!toggle.contains(e.target) && !panel.contains(e.target)) {
      panel.classList.add('hidden');
    }
  });

  // Polling & toast logic
  let seenIds = new Set();
  // populate seenIds with initial rendered notifs
  (() => {
    const initial = @json($latest->pluck('id')->toArray());
    initial.forEach(id => seenIds.add(id));
  })();

  async function fetchLatest() {
    try {
      const res = await fetch('{{ route("notifications.latest") }}', { credentials: 'same-origin' });
      if (!res.ok) return;
      const json = await res.json();
      if (!json.notifications) return;

      // update badge
      const countRes = await fetch('{{ route("notifications.unreadCount") }}', { credentials: 'same-origin' });
      if (countRes.ok) {
        const c = await countRes.json();
        if (c.unread && badgeEl) { badgeEl.classList.remove('hidden'); badgeEl.textContent = c.unread; }
        else if (badgeEl) { badgeEl.classList.add('hidden'); }
      }

      // check new notifications (id not in seenIds)
      const newNotifs = json.notifications.filter(n => !seenIds.has(n.id));
      if (newNotifs.length) {
        // render toast for each new notification (reverse so latest on top)
        newNotifs.reverse().forEach(n => {
          showToast(n);
          seenIds.add(n.id);
        });
        // also update dropdown list (simpler approach: replace list)
        updateDropdown(json.notifications);
      }
    } catch (e) {
      // ignore
    }
  }

  function updateDropdown(notifications) {
    const list = document.getElementById('notif-list');
    if (!list) return;
    list.innerHTML = '';
    if (!notifications.length) {
      list.innerHTML = '<div class="p-3 text-sm text-gray-500">Belum ada notifikasi.</div>';
      return;
    }
    notifications.forEach(n => {
      const readClass = n.read_at ? 'bg-gray-50' : 'bg-white';
      const message = n.data.message || n.data.judul || 'Notifikasi baru';
      const html = `<div class="p-3 border-b ${readClass}">
                      <div class="text-sm">${escapeHtml(message)}</div>
                      <div class="text-xs text-gray-400 mt-1">${n.time}</div>
                    </div>`;
      list.insertAdjacentHTML('beforeend', html);
    });
  }

  function showToast(n) {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const id = 'toast-' + n.id;
    const wrapper = document.createElement('div');
    wrapper.id = id;
    wrapper.className = 'max-w-sm w-full bg-white border rounded shadow-lg p-3 flex justify-between items-start';
    wrapper.style.animation = 'fadeIn .15s ease-out';

    const msg = n.data.message || n.data.judul || 'Notifikasi baru';
    wrapper.innerHTML = `
      <div class="pr-3">
        <div class="text-sm font-medium">${escapeHtml(msg)}</div>
        <div class="text-xs text-gray-400 mt-1">${n.time}</div>
      </div>
      <div class="flex flex-col items-end">
        <button class="text-gray-400 hover:text-gray-600 close-toast" aria-label="close">&times;</button>
      </div>
    `;

    container.prepend(wrapper);

    // auto-dismiss after 8s
    const timeout = setTimeout(() => removeToast(id), 8000);

    // close button
    wrapper.querySelector('.close-toast').addEventListener('click', () => {
      clearTimeout(timeout);
      removeToast(id);
    });
  }

  function removeToast(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.transition = 'opacity 0.2s';
    el.style.opacity = '0';
    setTimeout(() => el.remove(), 250);
  }

  // Utility: escape HTML to avoid injection
  function escapeHtml(unsafe) {
    return unsafe
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  // run polling every 12s
  setInterval(fetchLatest, 12000);
  // initial fetch after load
  setTimeout(fetchLatest, 1500);

</script>

<style>
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
