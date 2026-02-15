<nav class="top-navbar" style="
    background: #ffffff; 
    border-bottom: 1px solid #e0e0e0; 
    position: fixed; 
    top: 0;            /* MENGUNCI DI TITIK NOL ATAS */
    left: 0;           /* MENGUNCI DI TITIK NOL KIRI */
    width: 100%; 
    height: 60px; 
    z-index: 2000;     /* Angka tinggi agar tidak tertutup apapun */
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    padding: 0 20px;
    box-sizing: border-box;
">
    <div class="navbar-left">
        <strong style="font-size: 20px; color: #1a1a1a; letter-spacing: 1px;">
            PONDASIKITA <span style="font-weight: 300;">SELLER</span>
        </strong>
    </div>
    <div class="navbar-right" style="display: flex; align-items: center; gap: 20px;">
        <i class="mdi mdi-bell-outline" style="font-size: 20px; cursor: pointer; color: #666;"></i>
        <div class="profile" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
            <span style="font-weight: 500; color: #333;">{{ Auth::user()->nama }}</span>
            <div style="width: 35px; height: 35px; background: #1a1a1a; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
            </div>
        </div>
    </div>
</nav>