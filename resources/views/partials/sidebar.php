<style>
    .sidebar {
  width: 260px;
  background-color: #c7d8cb; /* hijau muda */
  height: 100vh;
  padding: 15px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.sidebar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.sidebar-header h2 {
  font-size: 18px;
  font-weight: bold;
  margin: 0;
}

.sidebar-close {
  background: #9fb8a4;
  border: none;
  padding: 8px;
  border-radius: 8px;
  cursor: pointer;
}

.sidebar-menu {
  list-style: none;
  padding: 0;
  margin: 20px 0;
  flex-grow: 1;
}

.sidebar-menu li {
  margin-bottom: 12px;
}

.sidebar-menu a, 
.sidebar-menu button {
  text-decoration: none;
  color: #000;
  font-size: 15px;
  background: none;
  border: none;
  cursor: pointer;
  width: 100%;
  text-align: left;
}

.dropdown {
  background: #9fb8a4;
  border-radius: 8px;
  padding: 5px;
}

.dropdown-content {
  display: none;
  list-style: none;
  padding: 0;
  margin-top: 8px;
}

.dropdown-content li {
  margin: 6px 0;
}

.dropdown-content a {
  color: #000;
}

.dropdown.open .dropdown-content {
  display: block;
}

.sidebar-divider {
  border: none;
  border-top: 3px solid #666;
  margin: 20px 0;
}

.sidebar-footer {
  font-size: 13px;
  color: #000;
}

.sidebar-footer a {
  color: #000;
  text-decoration: underline;
}

.sidebar-footer .copyright {
  font-size: 12px;
  margin-top: 8px;
}

 </style>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>OkGreen</h2>
        <button class="sidebar-close"> <i class="fas fa-arrow-left"></i> </button>
    </div>

    <ul class="sidebar-menu">
        <li><a href="#">Informasi pribadi</a></li>
        <li><a href="#">Tukarkan Point</a></li>
        <li><a href="#">Kontak Kami</a></li>

        <li class="dropdown">
            <button class="dropdown-btn">
                Riwayat <i class="fas fa-chevron-down"></i>
            </button>
            <ul class="dropdown-content">
                <li><a href="#">Sales Log</a></li>
                <li><a href="#">Order History</a></li>
                <li><a href="#">Watch History</a></li>
                <li><a href="#">Points Activity Log</a></li>
            </ul>
        </li>

        <li><a href="#">Keluar</a></li>
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
document.querySelector(".dropdown-btn").addEventListener("click", function(){
    this.parentElement.classList.toggle("open");
});
</script>
