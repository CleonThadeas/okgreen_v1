<div class="sidebar">
    <div class="sidebar-header">
        <h2>OkGreen</h2>
        <button class="sidebar-close" onclick="toggleSidebar()">
            <i class="fas fa-arrow-left"></i>
        </button>
    </div>

    <ul class="sidebar-menu">
        <a href="{{ route('profile.edit') }}" style="display:block; margin-bottom:10px;">Informasi Pribadi</a>
    <a href="{{ route('user.points.index') }}" style="display:block; margin-bottom:10px;">Tukarkan Point</a>
    <a href="{{ route('contact') }}" style="display:block; margin-bottom:10px;">Contact Us</a>

   <div class="menu-item">
  <button class="dropdown-btn" onclick="toggleDropdown(this)">
    Riwayat <span class="arrow">▼</span>
  </button>
  <div class="dropdown-container">
    <a href="{{ route('history.sell') }}">Penjualan</a>
    <a href="{{ route('history.buy') }}">Pembelian</a>
    <a href="#">Nonton</a>
    <a href="{{ route('history.points') }}">Point</a>
  </div>
</div>

        {{-- Logout --}}
<a href="{{ route('logout') }}"
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
   style="display:block; margin-top:20px; color:red;">
   Log Out
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>
    </ul>
    <hr class="sidebar-divider">

    <div class="sidebar-footer">
        <p>+0123-456-789</p>
        <p><a href="mailto:example@gmail.com">example@gmail.com</a></p>
        <p>Jalbud, Jabar. 1234</p>
        <p class="copyright">Copyright © 2024 Furniture. All Rights Reserved.</p>
    </div>
</div>
<script>
function toggleDropdown(btn) {
  btn.classList.toggle("active");
  let container = btn.nextElementSibling;
  container.style.display = container.style.display === "block" ? "none" : "block";
}
</script>
