

<header class="d-flex align-items-center gap-2 p-3" style="background-color: #4A90E2; color: white;">
    <span class="fs-4 menu-toggle">&#9776;</span>
    <h5 class="m-0 fw-bold">ClearWave</h5>
</header>

<div id="sidebar">
    <span id="closeSidebar" class="fs-4" style="cursor:pointer; top:1rem; right:1rem; color:black;">&#8592;</span>
    <h3>Sidebar Menu</h3>
    <ul>
    <li><a href="index.php?c=DonasiController&m=pilihanDonasi" style="color:rgb(67, 127, 195);">Dashboard</a></li>
    <li><a href="index.php?c=DonasiController&m=tahunRekap" style="color:rgb(67, 127, 195);">Rekapitulasi Donasi</a></li>
    <li><a href="index.php?c=DonasiController&m=index" style="color:rgb(67, 127, 195);">Kampanye</a></li>
    <li><a href="index.php?c=DonasiController&m=loginForm" style="color:rgb(67, 127, 195);">Log Out</a></li>
    </ul>
</div>
<script>
    const toggleBtn = document.querySelector('.menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const closeBtn = document.getElementById('closeSidebar');
    
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
    closeBtn.addEventListener('click', () => {
        sidebar.classList.remove('active');
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
