<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pirate Town</title>
    <link rel="icon" type="image/x-icon" href="{{url('/resource/poll.jpg')}}">
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CDN -->
    <style>
        .slider-container {
            overflow: hidden;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .pagination-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #d1d5db;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .pagination-dot.active {
            background-color: #1f2937;
        }
        
        .test{
            background-image: {{url('/resource/poll.JPG')}};
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <!-- Hero Section -->
    <section class="test text-white pt-8 pb-16">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-white text-lg font-semibold">
                <a href="/">
                    <img src="{{ url('/resource/poll.JPG') }}" alt="" style="width: 70px;">
                </a>
            </div>
            <!--<img src="{{ url('/resource/poll.JPG') }}" />-->

            <div class="space-x-4">
                @if (Route::has('login'))
                    <nav class="-mx-3 flex flex-1 justify-end">
                        <!-- Check if user is authenticated as 'web' (regular user) -->
                        @auth('web')
                            <a href="{{ url('/dashboard') }}" class="text-white text-lg font-semibold">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                    Register
                                </a>
                            @endif
                        @endauth

                        <!-- Check if user is authenticated as 'admin' -->
                        @auth('admin')
                            <a href="{{ url('/admin/dashboard') }}"
                                class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('admin.login') }}"
                                class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Admin Log in
                            </a>

                            @if (Route::has('admin.register'))
                                <a href="{{ route('admin.register') }}"
                                    class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                    Admin Register
                                </a>
                            @endif
                        @endauth
                    </nav>

                @endif

            </div>
        </div>
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold">Welcome to Quickslip</h1>
            <p class="mt-4 text-lg">จะหาผู้ใดเก่งเสมือนแม่ท่านไม่มี</p>
            <a href="#services"
                class="mt-8 inline-block bg-white text-blue-600 px-6 py-3 rounded-full text-lg hover:bg-gray-100 transition duration-300">Learn
                More</a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-16 bg-white">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold text-gray-800">บริการของเรา</h2>
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div class="bg-gray-100 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-700">Web Development</h3>
                    <p class="mt-4 text-gray-600">Build responsive and user-friendly websites that grow your business.
                    </p>
                </div>
                <div class="bg-gray-100 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-700">Mobile App Development</h3>
                    <p class="mt-4 text-gray-600">Develop high-performance mobile apps for both Android and iOS.</p>
                </div>
                <div class="bg-gray-100 p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-700">SEO Optimization</h3>
                    <p class="mt-4 text-gray-600">Optimize your website to rank higher on search engines.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="container mx-auto text-center">
            <h2 class="text-2xl font-bold mb-6">Promotions</h2>

            <!-- Slider Container -->
            <div class="slider-container relative">
                <div id="slider" class="slider">
                    <!-- Page 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full shrink-0">
                        <!-- Promotion Card 1 -->
                        <div class="bg-white shadow-md rounded-lg p-4">
                            <img src="https://thunder.in.th/wp-content/uploads/2024/06/BEGINNER.webp"
                                class="w-full rounded-md mb-4" alt="Beginner">
                            <h4 class="text-md font-bold">Beginner</h4>
                            <p class="text-lg text-red-500 font-bold my-2 border-b-2 border-red-500">999 บาท</p>
                            <ul class="text-sm text-gray-600 space-y-1 mb-3">
                                <li>✅ จำนวนสลิปที่ใช้งานได้ 4,500 สลิป</li>
                                <li>✅ ระยะเวลาการใช้งาน 30 วัน</li>
                                <li>✅ ราคาต่อสลิป 0.133 บาท</li>
                                <li>✅ เชื่อมต่อได้ 10 สาขา</li>
                            </ul>
                            <a href="{{ route('register') }}"
                                class="block text-center bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                                ซื้อเลย (599฿)
                            </a>
                        </div>

                        <div class="bg-white shadow-md rounded-lg p-4">
                            <img src="https://thunder.in.th/wp-content/uploads/2024/06/BEGINNER.webp"
                                class="w-full rounded-md mb-4" alt="Intermediate">
                            <h4 class="text-md font-bold">Intermediate</h4>
                            <p class="text-lg text-red-500 font-bold my-2 border-b-2 border-red-500">999 บาท</p>
                            <ul class="text-sm text-gray-600 space-y-1 mb-3">
                                <li>✅ จำนวนสลิปที่ใช้งานได้ 10,000 สลิป</li>
                                <li>✅ ระยะเวลาการใช้งาน 60 วัน</li>
                                <li>✅ ราคาต่อสลิป 0.10 บาท</li>
                                <li>✅ เชื่อมต่อได้ 20 สาขา</li>
                            </ul>
                            <a href="{{ route('register') }}"
                                class="block text-center bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                                ซื้อเลย (999฿)
                            </a>
                        </div>

                        <div class="bg-white shadow-md rounded-lg p-4">
                            <img src="https://thunder.in.th/wp-content/uploads/2024/06/BEGINNER.webp"
                                class="w-full rounded-md mb-4" alt="Advanced">
                            <h4 class="text-md font-bold">Advanced</h4>
                            <p class="text-lg text-red-500 font-bold my-2 border-b-2 border-red-500">999 บาท</p>
                            <ul class="text-sm text-gray-600 space-y-1 mb-3">
                                <li>✅ จำนวนสลิปที่ใช้งานได้ 15,000 สลิป</li>
                                <li>✅ ระยะเวลาการใช้งาน 90 วัน</li>
                                <li>✅ ราคาต่อสลิป 0.099 บาท</li>
                                <li>✅ เชื่อมต่อได้ 30 สาขา</li>
                            </ul>
                            <a href="{{ route('register') }}"
                                class="block text-center bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                                ซื้อเลย (1,499฿)
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full shrink-0">
                        <div class="bg-gray-200 shadow-md rounded-lg p-4">Page 2 Content Here</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full shrink-0">
                        <div class="bg-gray-300 shadow-md rounded-lg p-4">Page 3 Content Here</div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center gap-2 mt-6">
                <button class="pagination-dot" onclick="goToSlide(0)"></button>
                <button class="pagination-dot" onclick="goToSlide(1)"></button>
                <button class="pagination-dot" onclick="goToSlide(2)"></button>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 MyWebsite. All rights reserved.</p>
        </div>
    </footer>
    <script>
        const slider = document.getElementById('slider');
        const dots = document.querySelectorAll('.pagination-dot');
        const totalSlides = slider.children.length;
        let currentSlide = 0;

        function goToSlide(index) {
            const width = slider.children[0].offsetWidth;
            slider.style.transform = `translateX(-${index * width}px)`;
            updateDots(index);
        }

        function updateDots(activeIndex) {
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === activeIndex);
            });
        }

        // Initialize first dot as active
        updateDots(0);
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides; // Loop back to the first slide after the last one
            goToSlide(currentSlide);
        }, 5000); // Change slide every 5 seconds
    </script>
</body>

</html>
