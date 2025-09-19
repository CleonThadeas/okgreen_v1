<div class="sidebar" style="width:220px; background:#d8e3d5; padding:20px; min-height:100vh;">
    <h4 style="margin-bottom:20px;">OkGreen</h4>

    {{-- Menu utama --}}
    <a href="{{ route('profile.edit') }}" style="display:block; margin-bottom:10px;">Informasi Pribadi</a>
    <a href="{{ route('user.points.index') }}" style="display:block; margin-bottom:10px;">Tukarkan Point</a>
    <a href="{{ route('contact') }}" style="display:block; margin-bottom:10px;">Contact Us</a>

    {{-- History dropdown --}}
    <a href="#" onclick="toggleSubmenu(event)" style="display:block; margin-bottom:10px;">History ▼</a>
    <div class="submenu" id="historySubmenu" style="margin-left:15px; display:none;">
        <a href="{{ route('history.sell') }}" style="display:block; margin-bottom:8px;">Penjualan</a>
        <a href="{{ route('history.buy') }}" style="display:block; margin-bottom:8px;">Pembelian</a>
        <a href="#" style="display:block; margin-bottom:8px;">Nonton</a>
        <a href="{{ route('history.points') }}" style="display:block; margin-bottom:8px;">Poin</a>
    </div>

    {{-- Logout --}}
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
       style="display:block; margin-top:20px; color:red;">
       Log Out
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

<script>
    function toggleSubmenu(e) {
        e.preventDefault();
        let submenu = document.getElementById('historySubmenu');
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    }
</script>
