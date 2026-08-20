{{-- resources/views/components/topbar.blade.php --}}
@auth
<div class="flex items-center justify-between bg-white px-6 py-4 rounded-2xl shadow-sm border border-slate-100 mb-8 mt-2">
    <!-- Left Side: Welcome / Title Context -->
    <div class="flex items-center gap-4">
        <!-- Sidebar Toggle Button -->
        <button type="button" id="toggle-sidebar-btn" class="p-2.5 text-slate-400 hover:text-[#C41E3A] hover:bg-red-50 rounded-xl transition-all duration-300 focus:outline-none" title="Toggle Sidebar">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-slate-500">Selamat datang,</span>
            <span class="text-sm font-bold text-[#C41E3A]">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</span>
        </div>
    </div>

    <!-- Right Side: Notification Icon & Details -->
    <div class="flex items-center gap-4">
        <!-- Date Display -->
        <div class="hidden md:flex items-center gap-2 text-slate-500 text-xs font-medium">
            <i class="fa-solid fa-calendar-days text-[#C41E3A]"></i>
            <span id="current-date-span-topbar"></span>
        </div>

        <div class="h-6 w-px bg-slate-200 hidden md:block"></div>

        <!-- Notification Bell Dropdown -->
        <div class="relative" id="notification-bell-container">
            <button type="button" id="notification-bell-btn" class="relative p-2 text-slate-400 hover:text-[#C41E3A] hover:bg-red-50 rounded-xl transition-all duration-300 focus:outline-none">
                <i class="fa-solid fa-bell text-lg"></i>
                <span id="notification-badge" class="hidden absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-600 rounded-full ring-2 ring-white animate-pulse"></span>
            </button>

            <!-- Dropdown Menu -->
            <div id="notification-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 animate-fade-in-down">
                <div class="px-4 py-2 border-b border-slate-50 flex items-center justify-between gap-2">
                    <span class="font-bold text-slate-800 text-sm">Notifikasi</span>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="markAllNotificationsAsRead()" class="text-[10px] font-bold text-[#C41E3A] hover:underline focus:outline-none">
                            Dibaca
                        </button>
                        <span class="text-slate-300 text-[10px]">|</span>
                        <button type="button" onclick="deleteAllNotifications()" class="text-[10px] font-bold text-slate-500 hover:text-red-600 hover:underline focus:outline-none">
                            Hapus Semua
                        </button>
                    </div>
                </div>

                <!-- Notification List Container -->
                <div id="notification-list" class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                    <!-- Notifications will be loaded here dynamically -->
                    <div class="px-4 py-8 text-center text-slate-400 text-xs">
                        <i class="fa-solid fa-bell-slash text-xl mb-2 block text-slate-300"></i>
                        Tidak ada notifikasi baru
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sidebar Toggle
        const toggleBtn = document.getElementById('toggle-sidebar-btn');
        const sidebar = document.getElementById('main-sidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function () {
                const isCollapsed = sidebar.classList.contains('collapsed');
                if (isCollapsed) {
                    sidebar.classList.remove('collapsed');
                    localStorage.setItem('sidebar-collapsed', 'false');
                } else {
                    sidebar.classList.add('collapsed');
                    localStorage.setItem('sidebar-collapsed', 'true');
                }
            });
        }

        // Render date in topbar
        const dateSpan = document.getElementById('current-date-span-topbar');
        if (dateSpan) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateSpan.textContent = new Date().toLocaleDateString('id-ID', options);
        }

        // Bell click toggle
        const bellBtn = document.getElementById('notification-bell-btn');
        const dropdown = document.getElementById('notification-dropdown');

        if (bellBtn && dropdown) {
            bellBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
                loadNotifications();
            });

            document.addEventListener('click', function (e) {
                const container = document.getElementById('notification-bell-container');
                if (container && !container.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }

        // Initial check for unread count
        loadNotifications();
        
        // Poll notifications every 15 seconds for live updates
        setInterval(loadNotifications, 15000);
    });

    function loadNotifications() {
        fetch('{{ route('notifications.get') }}')
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('notification-badge');
                if (badge) {
                    if (data.unread_count > 0) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }

                const list = document.getElementById('notification-list');
                if (!list) return;

                if (!data.notifications || data.notifications.length === 0) {
                    list.innerHTML = `
                        <div class="px-4 py-8 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-bell-slash text-xl mb-2 block text-slate-300"></i>
                            Tidak ada notifikasi baru
                        </div>
                    `;
                    return;
                }

                let html = '';
                data.notifications.forEach(n => {
                    const isUnread = !n.is_read;
                    const bgClass = isUnread ? 'bg-red-50/20 hover:bg-red-50/40' : 'hover:bg-slate-50';
                    const titleClass = isUnread ? 'font-bold text-slate-800' : 'font-medium text-slate-600';
                    
                    let iconClass = 'fa-circle-exclamation';
                    let iconColor = 'text-amber-500';
                    
                    if (n.title.toLowerCase().includes('baru')) {
                        iconClass = 'fa-circle-plus';
                        iconColor = 'text-green-500';
                    } else if (n.title.toLowerCase().includes('hapus')) {
                        iconClass = 'fa-circle-xmark';
                        iconColor = 'text-red-500';
                    }

                    html += `
                        <div onclick="markAsRead(${n.id}, '${n.link || ''}')" class="px-4 py-3 text-xs cursor-pointer transition-all duration-200 flex gap-3 items-start relative group ${bgClass}">
                            <div class="w-7 h-7 rounded-xl bg-slate-50 flex items-center justify-center flex-shrink-0 mt-0.5 border border-slate-100 shadow-xs">
                                <i class="fa-solid ${iconClass} ${iconColor} text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0 pr-4">
                                <p class="text-[9px] text-slate-400 font-bold mb-0.5 uppercase tracking-wider">${n.title}</p>
                                <p class="${titleClass} leading-relaxed truncate-2-lines mb-1">${n.message}</p>
                                <p class="text-[9px] text-slate-400">${formatTime(n.created_at)}</p>
                            </div>
                            <div class="flex flex-col items-center justify-between h-full flex-shrink-0">
                                ${isUnread ? '<span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mb-2"></span>' : ''}
                                <button type="button" onclick="deleteNotification(event, ${n.id})" class="text-slate-300 hover:text-red-600 focus:outline-none p-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                list.innerHTML = html;
            });
    }

    function markAsRead(id, link) {
        fetch(`/notifications/mark-read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(() => {
            if (link) {
                window.location.href = link;
            } else {
                loadNotifications();
            }
        });
    }

    function deleteNotification(e, id) {
        if (e) e.stopPropagation();
        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(() => {
            loadNotifications();
        });
    }

    function deleteAllNotifications() {
        const executeDeleteAll = () => {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Semua notifikasi Anda akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C41E3A',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/notifications', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        loadNotifications();
                        Swal.fire({
                            title: 'Terhapus!',
                            text: 'Semua notifikasi Anda berhasil dihapus.',
                            icon: 'success',
                            confirmButtonColor: '#C41E3A',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl px-5 py-2.5 font-bold'
                            }
                        });
                    });
                }
            });
        };

        if (typeof Swal === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
            script.onload = executeDeleteAll;
            document.head.appendChild(script);
        } else {
            executeDeleteAll();
        }
    }

    function markAllNotificationsAsRead() {
        fetch('{{ route('notifications.mark-all-read') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(() => {
            loadNotifications();
        });
    }

    function formatTime(dateTimeStr) {
        try {
            const date = new Date(dateTimeStr);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMins / 60000);

            if (diffMins < 1) return 'Baru saja';
            if (diffMins < 60) return `${diffMins} menit yang lalu`;
            if (diffHours < 24) return `${diffHours} jam yang lalu`;
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) + ' ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        } catch (e) {
            return '';
        }
    }
</script>
<style>
    .truncate-2-lines {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translate3d(0, -10px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.2s ease-out forwards;
    }
</style>
@else
<div class="mb-6"></div>
@endauth
