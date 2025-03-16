<div class="sm:hidden bg-gray-800 text-white p-4 flex items-center justify-between">
    <button id="menuToggle" class="text-white text-2xl">☰</button>
    <h1 class="text-lg font-semibold">Dashboard</h1>
</div>

<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden sm:hidden"></div>

<div id="sidebar"
    class="fixed sm:relative top-0 left-0 w-64 sm:w-1/6 bg-gray-800 text-white min-h-screen sm:h-auto p-6 transform -translate-x-full sm:translate-x-0 transition-transform duration-300 z-50">
    <ul class="flex flex-col space-y-4">
        
        <li>
            <a href="{{ route('getProducts', [Auth::user()->id]) }}"
                class="text-lg hover:text-gray-300">สินค้าในคลัง</a>
        </li>
        <li>
            <a href="{{route('merchant.order',[Auth::user()->id])}}" class="text-lg hover:text-gray-300">คำสั่งซื้อ</a>
        </li>
        {{-- <li>
            <a href="{{route('merchant.order',[Auth::user()->id])}}" class="text-lg hover:text-gray-300">ประวัติการขาย</a>
        </li> --}}
    </ul>
</div>