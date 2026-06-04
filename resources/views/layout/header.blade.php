{{-- <nav class="navbar navbar-expand navbar-theme">
    <a class="sidebar-toggle d-flex me-2">
        <i class="hamburger align-self-center"></i>
    </a>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown ms-lg-2">
                <a class="nav-link dropdown-toggle position-relative" href="#" id="userDropdown"
                    data-bs-toggle="dropdown">
                    <i class="align-middle fas fa-cog"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <a class="dropdown-item"><i class="align-middle me-1 fas fa-fw fa-user"></i>
                        {{ Auth::user()->name }}</a> --}}

{{-- <a class="dropdown-item" href="#"><i class="align-middle me-1 fas fa-fw fa-cogs"></i>
                        Settings</a> --}}

{{-- <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"><i
                            class="align-middle me-1 fas fa-fw fa-arrow-alt-circle-right"></i> Sign out</a>
                </div>
            </li>
        </ul>
    </div>
</nav> --}}


<nav class="navbar navbar-expand navbar-theme">
    <a class="sidebar-toggle d-flex me-2" id="sidebarToggleBtn">
        <i id="sidebarToggleIcon" class="fas fa-bars align-self-center transition-icon"></i>
    </a>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
             <li class="nav-item dropdown ms-lg-2">
                <a class="nav-link dropdown-toggle position-relative" href="#" id="alertsDropdown"
                    data-bs-toggle="dropdown">
                    @php
                        $unreadNotifications = auth()->user()->unreadNotifications;
                        $notificationCount = $unreadNotifications->count();
                    @endphp
                    <i class="align-middle fas fa-bell"></i>
                    <span class="badge bg-danger" id="notificationCount">{{ $notificationCount }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                    <div class="dropdown-menu-header">
                        @if ($notificationCount == 1)
                            Notification
                        @else
                            Notifications
                        @endif
                    </div>
                    <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                        @foreach ($unreadNotifications as $notification)
                            <a href="{{ $notification->url ?? '#' }}" class="list-group-item notification-item"
                                data-is-read="false" data-id="{{ $notification->id }}">
                                <div class="row g-0 align-items-center">
                                    <div class="col-2">
                                        <i class="ms-1 text-success fas fa-fw fa-bell-slash"></i>
                                    </div>
                                    <div class="col-10">
                                        <div class="text-dark">{{ $notification->subject }}</div>
                                        <div class="text-muted small mt-1">{{ $notification->message }}</div>
                                        <div class="text-muted small mt-1">
                                            {{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="dropdown-menu-footer">
                        <a href="{{ route('notifications.index') }}" class="text-muted">Show all notifications</a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown ms-lg-2">
                <a class="nav-link dropdown-toggle position-relative" href="#" id="userDropdown"
                    data-bs-toggle="dropdown">
                    <i class="align-middle fas fa-cog"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <a class="dropdown-item">
                        <i class="align-middle me-1 fas fa-fw fa-user"></i> {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="align-middle me-1 fas fa-fw fa-arrow-alt-circle-right"></i> Sign out
                    </a>
                    {{-- <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="align-middle me-1 fas fa-fw fa-arrow-alt-circle-right"></i> Sign out
                    </a> --}}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>

<script>
    const notificationItem = document.querySelectorAll('.notification-item');
    const notificationCount = document.getElementById('notificationCount');
    notificationCount.textContent = notificationItem.length;
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.target.dataset.isRead == "false") {
                $.ajax({
                    url: "{{ route('notifications.markRead') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: entry.target.dataset.id
                    },
                    success: function() {
                        entry.target.dataset.isRead = true;
                        notificationCount.textContent = Number(notificationCount
                            .textContent) - 1;
                    }
                });
            }
        });
    }, {
        root: null,
        threshold: 0.9,
    });

    notificationItem.forEach(item => {
        observer.observe(item);
    });
</script>
