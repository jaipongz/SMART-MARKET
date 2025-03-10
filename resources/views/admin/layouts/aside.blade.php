<div class="w-64 bg-gray-800 h-screen md:h-screen p-4 fixed md:static transform -translate-x-full md:translate-x-0 transition-transform"
    id="sidebar">
    <h2 class="admin-heading">Admin</h2>

    <ul id="menu" class="menu">
        <li class="mb-2"><a href="{{ route('admin.dashboard') }}"
                class="menu-item block py-2 px-4 p-2 rounded-md hover:bg-gray-700">Dashboard</a></li>
        <li class="mb-2"><a href="{{ route('admin.getUser') }}"
                class="menu-item block py-2 px-4 p-2 rounded-md hover:bg-gray-700">Users</a></li>
        <li class="mb-2"><a href="{{ route('admin.getAdmin') }}"
                class="menu-item block py-2 px-4 p-2 rounded-md hover:bg-gray-700">Admins</a></li>
        <li class="mb-2"><a href="#"
                class="menu-item block py-2 px-4 p-2 rounded-md hover:bg-gray-700">Reports</a></li>
        <li class="mb-2"><a href="#"
                class="menu-item block py-2 px-4 p-2 rounded-md hover:bg-gray-700">Settings</a></li>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <li class="menu-item block py-2 px-4 p-2 rounded-md hover:bg-gray-700">
                <button type="submit">Logout</button>
            </li>
        </form>
    </ul>
</div>

<style>
    /* Ensure full height on mobile devices, including iPhones */
    html,
    body {
        height: 100%;
        margin: 0;
    }

    #sidebar {
        height: calc(var(--vh, 1vh) * 100);
        /* Use custom vh for mobile quirks */
    }

    .menu-item.active {
        background-color: #4B5563;
        /* Same as bg-gray-600 */
        color: white;
    }

    /* Ensure full height on mobile devices, including iPhones */
    html,
    body {
        height: 100%;
        margin: 0;
    }

    /* Sticky Sidebar */
    #sidebar {
        position: sticky;
        /* Makes the sidebar sticky */
        top: 0;
        /* Sticks to the top of the viewport */
        height: 100vh;
        /* Full viewport height */
        z-index: 100;
        /* Ensure it stays on top */
        overflow-y: auto;
        /* Allow scrolling within the sidebar if needed */
    }

    /* Ensure the sidebar remains full height on all screen sizes */
    @media (max-width: 1024px) {
        #sidebar {
            position: fixed;
            /* Sidebar should be fixed on smaller screens */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
    }

    /* Styling active menu item */
    .menu-item.active {
        background-color: #4B5563;
        /* Same as bg-gray-600 */
        color: white;
    }
    .admin-heading {
    text-align: center; /* Aligns text to the center */
    font-weight: bold; /* Makes text bold */
    font-size: 2rem; /* Adjusts the font size */
}
</style>

<script>
    // Fix for mobile devices' inconsistent 100vh behavior
function setVh() {
    const vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
}

window.addEventListener('resize', setVh);
window.addEventListener('load', setVh);

// Get all menu items
const menuItems = document.querySelectorAll('.menu-item');

// Set active class based on the current URL or route
menuItems.forEach(item => {
    if (window.location.href === item.href) {
        item.classList.add('active');
    } else {
        item.classList.remove('active');
    }

    // Add event listener for click to toggle the active class
    item.addEventListener('click', () => {
        // Remove active class from other menu items
        menuItems.forEach(i => i.classList.remove('active'));

        // Add active class to the clicked menu item
        item.classList.add('active');
    });
});

</script>
