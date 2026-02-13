<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Futsal Booking Dashboard">
    <title>Futsal Admin Dashboard</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4/dist/css/adminlte.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper d-flex flex-column" style="min-height: 100vh;">
        <!-- Header -->
        @include('layouts.header')

        <div class="d-flex flex-grow-1">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Content Wrapper -->
            <div class="content-wrapper flex-grow-1 d-flex flex-column">
                <!-- Content -->
                <div class="content flex-grow-1">
                    @yield('content')
                </div>

                <!-- Footer -->
                <div class="mt-auto">
                    
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4/dist/js/adminlte.min.js"></script>

    @stack('scripts')

    <style>
        /* Responsive Footer Fix */
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .wrapper > div:nth-child(2) {
            display: flex;
            flex: 1;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .content {
            flex: 1;
        }

        .main-footer {
            margin-top: auto;
            background-color: #f3f4f6;
            border-top: 1px solid #dee2e6;
            padding: 1rem;
        }

        /* Sidebar menu toggle styling */
        .sidebar-menu .nav-item {
            position: relative;
        }

        .sidebar-menu .nav-treeview {
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu .nav-treeview.show {
            display: block;
        }

        .sidebar-menu .nav-treeview .nav-item .nav-link {
            padding-left: 3rem;
            font-size: 0.875rem;
        }

        .sidebar-menu .nav-link.menu-is-opening {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 3px solid #007bff;
            padding-left: calc(1rem - 3px);
        }

        .nav-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-menu .nav-link.menu-is-opening .nav-arrow {
            transform: rotate(90deg);
        }

        /* Responsive Sidebar */
        @media (max-width: 576px) {
            .app-sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                width: 100%;
                max-width: 300px;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease-in-out;
                overflow-y: auto;
            }

            .app-sidebar.show,
            .sidebar-mini.sidebar-open .app-sidebar {
                left: 0;
            }

            .sidebar-mini.sidebar-open .content-wrapper {
                margin-left: 0;
            }

            .navbar-expand {
                padding: 0.5rem 1rem;
            }
        }

        @media (min-width: 577px) and (max-width: 768px) {
            .app-sidebar {
                width: 250px;
            }

            .sidebar-brand {
                padding: 0.75rem !important;
            }

            .sidebar-brand .brand-text {
                font-size: 0.9rem;
            }

            .nav-link p {
                display: none;
            }

            .nav-link svg,
            .nav-link i {
                margin-right: 0;
            }
        }

        @media (max-width: 768px) {
            .nav-link {
                padding: 0.5rem 0.75rem;
            }

            .sidebar-wrapper {
                overflow-y: auto;
                max-height: calc(100vh - 100px);
            }
        }

        /* Mobile hamburger menu enhancement */
        .bi-list {
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Prevent content overflow */
        .content {
            padding: 1rem;
            word-wrap: break-word;
            overflow-x: hidden;
        }

        /* Footer responsiveness */
        @media (max-width: 576px) {
            .main-footer {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }

            .main-footer strong {
                display: block;
                margin-bottom: 0.5rem;
            }

            .main-footer .float-right {
                float: none !important;
                display: block !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup sidebar menu toggle functionality
            const sidebarMenu = document.querySelector('.sidebar-menu');
            
            if (sidebarMenu) {
                // Handle menu item clicks with children
                sidebarMenu.querySelectorAll('.nav-item > .nav-link').forEach(navLink => {
                    const navItem = navLink.parentElement;
                    const subMenu = navItem.querySelector('.nav-treeview');
                    
                    if (subMenu) {
                        navLink.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Toggle the submenu
                            subMenu.classList.toggle('show');
                            navLink.classList.toggle('menu-is-opening');
                            
                            // Close other open menus at same level
                            navItem.parentElement.querySelectorAll('.nav-treeview.show').forEach(menu => {
                                if (menu !== subMenu) {
                                    menu.classList.remove('show');
                                    menu.previousElementSibling?.classList.remove('menu-is-opening');
                                }
                            });
                        });
                    }
                });
                
                // Highlight active menu item
                const currentUrl = window.location.pathname;
                sidebarMenu.querySelectorAll('.nav-link[href]').forEach(link => {
                    const href = link.getAttribute('href');
                    
                    if (href && href !== '#' && href !== 'javascript:void(0);') {
                        if (currentUrl.includes(href) || currentUrl === href) {
                            link.classList.add('active');
                            
                            // Expand parent if current link is inside a submenu
                            const parentMenu = link.closest('.nav-treeview');
                            if (parentMenu && !parentMenu.classList.contains('show')) {
                                parentMenu.classList.add('show');
                                const parentLink = parentMenu.previousElementSibling;
                                if (parentLink) {
                                    parentLink.classList.add('menu-is-opening');
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>