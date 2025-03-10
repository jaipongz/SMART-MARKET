<div class="w-full sm:w-1/6 bg-gray-800 text-white h-auto sm:h-screen p-6">
    <ul>
        <li class="mb-4">
            <a href="#" class="text-lg hover:text-gray-300">ประวัติการขาย</a>
        </li>
        <li class="mb-4">
            <a href="{{route('getProducts',[Auth::user()->id])}}" class="text-lg hover:text-gray-300">สินค้าในคลัง</a>
        </li>
    </ul>
</div>