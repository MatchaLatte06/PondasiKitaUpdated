<aside class="sidebar" style="
    background: #1a1a1a; 
    width: 250px; 
    height: calc(100vh - 60px); 
    position: fixed; 
    top: 60px;        /* Mulai tepat di bawah navbar */
    left: 0;
    z-index: 1000;
    overflow-y: auto; /* Agar menu bisa di-scroll jika panjang */
">

    <ul style="list-style: none; padding: 0; margin: 0;">
        <li style="padding: 15px 25px; border-left: 4px solid #ffffff; background: #262626;">
            <a href="{{ route('seller.dashboard') }}" style="color: #fff; text-decoration: none; display: flex; align-items: center; gap: 12px; font-weight: 500;">
                <i class="mdi mdi-view-dashboard" style="font-size: 20px;"></i> Dashboard
            </a>
        </li>
        <li style="padding: 15px 25px;">
            <a href="#" style="color: #bbb; text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.3s;">
                <i class="mdi mdi-archive" style="font-size: 20px;"></i> Produk Saya
            </a>
        </li>
        <li style="padding: 15px 25px;">
            <a href="#" style="color: #bbb; text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.3s;">
                <i class="mdi mdi-cart" style="font-size: 20px;"></i> Pesanan
            </a>
        </li>
        <li style="padding: 15px 25px;">
            <a href="#" style="color: #bbb; text-decoration: none; display: flex; align-items: center; gap: 12px; transition: 0.3s;">
                <i class="mdi mdi-chart-bar" style="font-size: 20px;"></i> Statistik
            </a>
        </li>
        
        <li style="padding: 15px 25px; margin-top: 30px; border-top: 1px solid #333;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: none; border: none; color: #ff5252; cursor: pointer; display: flex; align-items: center; gap: 12px; padding: 0; font-size: 15px; font-weight: 500;">
                    <i class="mdi mdi-logout" style="font-size: 20px;"></i> Keluar
                </button>
            </form>
        </li>
    </ul>
</aside>