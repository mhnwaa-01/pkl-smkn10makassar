<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SMKN 10 Makassar PKL</title>
    
    <!-- Preload Logo to Prevent Flickering -->
    <link rel="preload" as="image" href="{{ asset('logo-sekolah.png') }}">

    <!-- Inline Script to set Dark Mode instantly before page paint -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Tailwind CSS with Forms Plugin -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Camera and Geolocation Helper Functions -->
    <script>
        function dataURLtoFile(dataurl, filename) {
            var arr = dataurl.split(','), mime = (arr[0].match(/:(.*?);/) || ['', 'image/jpeg'])[1],
                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
            while(n--){
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, {type:mime});
        }

        function cameraWidget(targetInputId) {
            return {
                streamActive: false,
                capturedPhoto: null,
                stream: null,
                loading: false,
                fallbackMode: false,
                async startCamera() {
                    this.loading = true;
                    // Check if WebRTC getUserMedia is supported and protocol is secure
                    if (navigator.mediaDevices && typeof navigator.mediaDevices.getUserMedia === 'function') {
                        try {
                            this.stream = await navigator.mediaDevices.getUserMedia({
                                video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                                audio: false
                            });
                            if (this.$refs.video) {
                                this.$refs.video.srcObject = this.stream;
                            }
                            this.streamActive = true;
                            this.fallbackMode = false;
                            this.loading = false;
                            return;
                        } catch (err) {
                            console.warn('WebRTC getUserMedia unavailable or denied. Opening native camera fallback:', err);
                        }
                    }

                    // Fallback: Trigger native device camera
                    this.loading = false;
                    this.fallbackMode = true;
                    this.triggerNativeCamera();
                },
                triggerNativeCamera() {
                    const fileInput = document.getElementById(targetInputId);
                    if (fileInput) {
                        fileInput.click();
                    }
                },
                handleFileInput(e) {
                    const file = e.target.files && e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            this.capturedPhoto = event.target.result;
                            this.stopCamera();
                        };
                        reader.readAsDataURL(file);
                    }
                },
                stopCamera() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                        this.stream = null;
                    }
                    this.streamActive = false;
                },
                capturePhoto() {
                    if (!this.$refs.video) return;
                    const canvas = this.$refs.canvas;
                    const video = this.$refs.video;
                    canvas.width = video.videoWidth || 640;
                    canvas.height = video.videoHeight || 480;
                    const context = canvas.getContext('2d');
                    
                    // Mirror flip for front camera
                    context.translate(canvas.width, 0);
                    context.scale(-1, 1);
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
                    this.capturedPhoto = dataUrl;
                    this.stopCamera();

                    // Synchronously create File and assign to input
                    try {
                        const file = dataURLtoFile(dataUrl, 'camera_capture_' + Date.now() + '.jpg');
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        const fileInput = document.getElementById(targetInputId);
                        if (fileInput) {
                            fileInput.files = dataTransfer.files;
                        }
                    } catch (e) {
                        console.warn('DataTransfer fallback:', e);
                    }
                },
                resetPhoto() {
                    this.capturedPhoto = null;
                    const fileInput = document.getElementById(targetInputId);
                    if (fileInput) {
                        fileInput.value = '';
                    }
                    this.startCamera();
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen flex" x-data="{
    sidebarOpen: false,
    headerRoleDropdown: false,
    showCheckInModal: {{ session('show_checkin_popup') ? 'true' : 'false' }},
    darkMode: document.documentElement.classList.contains('dark'),
    toggleTheme() {
        this.darkMode = !this.darkMode;
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    },
    journalModalOpen: false,
    selectedJournal: { title: '', date: '', student: '', class: '', description: '', photo: '', status: '', verification_notes: '' },
    openJournalModal(title, date, student, className, description, photoUrl, status, verificationNotes) {
        this.selectedJournal = {
            title: title,
            date: date,
            student: student,
            class: className,
            description: description,
            photo: photoUrl,
            status: status,
            verification_notes: verificationNotes
        };
        this.journalModalOpen = true;
    },
    imagePreviewOpen: false,
    previewImageUrl: '',
    openImagePreview(url) {
        this.previewImageUrl = url;
        this.imagePreviewOpen = true;
    }
}">

    <!-- SweetAlert2 Flash Notifications (1.5 - 2 detik) -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: @json(session('success')),
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800'
                }
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: @json(session('error')),
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800'
                }
            });
        });
    </script>
    @endif

    @if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: @json(session('warning')),
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800'
                }
            });
        });
    </script>
    @endif

    <!-- Sidebar Backdrop for Mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
        class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-40 lg:hidden" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:sticky top-0 inset-y-0 left-0 z-50 w-72 h-screen max-h-screen bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col transition-transform duration-300 ease-in-out shadow-lg lg:shadow-none flex-shrink-0">
        
        <!-- Sidebar Brand with Circular Logo Container -->
        <div class="h-20 px-5 flex items-center justify-between border-b border-slate-200 dark:border-slate-800 flex-shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center p-1.5 shadow-sm flex-shrink-0">
                    <img src="{{ asset('logo-sekolah.png') }}" class="w-full h-full object-contain" width="48" height="48" loading="eager" decoding="sync" alt="Logo SMKN 10">
                </div>
                <div>
                    <h2 class="font-extrabold text-slate-900 dark:text-white text-base tracking-tight leading-tight flex items-center gap-1.5">
                        SMKN 10
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    </h2>
                    <span class="text-[10px] text-blue-600 dark:text-blue-400 font-bold tracking-wider uppercase block">Makassar PKL System</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation Links (Scrollable) -->
        <div class="flex-1 overflow-y-auto py-4 px-3.5 space-y-1 min-h-0">

            <!-- Shared: Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <span>Dashboard</span>
                </div>
            </a>

            <!-- Menu: Presensi Harian -->
            <a href="{{ route('attendance.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('attendance.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('attendance.*') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span>Presensi Harian</span>
                </div>
            </a>

            <!-- Menu: Jurnal Harian -->
            <a href="{{ route('journals.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('journals.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('journals.*') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span>Jurnal Harian</span>
                </div>
            </a>

            <!-- Menu: Monitoring PKL -->
            <a href="{{ route('monitoring.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('monitoring.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('monitoring.*') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <span>Monitoring PKL</span>
                </div>
            </a>

            <!-- Menu: Penilaian PKL -->
            <a href="{{ route('evaluations.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('evaluations.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('evaluations.*') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <span>Penilaian PKL</span>
                </div>
            </a>

            <!-- Section Admin Only -->
            @if(Auth::user()->isAdmin())
                <div class="pt-4 pb-1.5 px-3">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                        Master Data Admin
                    </p>
                </div>

                <a href="{{ route('admin.students.index') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('admin.students.*') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span>Data Siswa</span>
                    </div>
                </a>

                <a href="{{ route('admin.majors.index') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('admin.majors.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('admin.majors.*') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <span>Data Jurusan</span>
                    </div>
                </a>

                <a href="{{ route('admin.industries.index') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('admin.industries.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('admin.industries.*') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01"/></svg>
                        </div>
                        <span>Data Industri</span>
                    </div>
                </a>

                <a href="{{ route('admin.teachers.index') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('admin.teachers.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('admin.teachers.*') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span>Data Guru</span>
                    </div>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-full font-semibold text-xs sm:text-sm transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center {{ request()->routeIs('admin.settings.*') ? 'bg-blue-700 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span>Pengaturan Jam</span>
                    </div>
                </a>
            @endif

        </div>

        <!-- Sidebar User Footer (Fixed at Bottom) -->
        <div class="p-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 flex-shrink-0">
            <!-- Profile Info & Logout Button -->
            <div class="flex items-center justify-between gap-2 p-2 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm">
                <div class="flex items-center gap-2.5 overflow-hidden min-w-0">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center flex-shrink-0 text-xs shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                        <span class="inline-block px-2 py-0.5 text-[9px] font-bold rounded-full uppercase
                            @if(Auth::user()->isAdmin()) bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300
                            @elseif(Auth::user()->isGuru()) bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300
                            @elseif(Auth::user()->isIndustri()) bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300
                            @else bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 @endif">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="Keluar" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-100 dark:bg-slate-950">
        
        <!-- Header Top Navbar (Responsive, Clean, Never Overflowing) -->
        <header class="min-h-[4.5rem] py-3 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm gap-3">
            <!-- Left: Mobile Menu & Page Title -->
            <div class="flex items-center gap-3 min-w-0 flex-1 sm:flex-initial">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 dark:text-slate-300 hover:text-blue-600 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="min-w-0 truncate">
                    <h1 class="text-sm sm:text-base lg:text-lg font-bold text-slate-900 dark:text-white tracking-tight truncate">@yield('title')</h1>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium truncate hidden md:block">SMK Negeri 10 Makassar • Portal Praktik Kerja Lapangan</span>
                </div>
            </div>

            <!-- Right: Utilities (Theme Toggle & Date) -->
            <div class="flex items-center gap-2.5 text-xs flex-shrink-0">
                <!-- Theme Toggle Button -->
                <button @click="toggleTheme()" class="p-2.5 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-amber-400 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 transition-colors flex items-center justify-center shadow-sm" title="Ubah Tema">
                    <!-- Sun Icon (Dark Mode active) -->
                    <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <!-- Moon Icon (Light Mode active) -->
                    <svg x-show="!darkMode" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <!-- Date Badge -->
                <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 px-3.5 py-2 rounded-full border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold shadow-sm">
                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>@formatdate(now())</span>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <!-- GLOBAL JOURNAL DETAIL MODAL (z-50) -->
    <div x-show="journalModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="journalModalOpen = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-lg w-full z-10 shadow-2xl relative text-left">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800 mb-4">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold rounded-full" x-text="selectedJournal.date"></span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                        :class="selectedJournal.status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : (selectedJournal.status === 'rejected' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300')"
                        x-text="selectedJournal.status"></span>
                </div>
                <button type="button" @click="journalModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="selectedJournal.title"></h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-semibold">
                        Siswa: <span class="text-slate-900 dark:text-white" x-text="selectedJournal.student"></span> (<span x-text="selectedJournal.class"></span>)
                    </p>
                </div>

                <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 text-xs leading-relaxed text-slate-700 dark:text-slate-200 whitespace-pre-line" x-text="selectedJournal.description"></div>

                <template x-if="selectedJournal.photo">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Foto Dokumentasi Kegiatan (Klik Foto Untuk Memperbesar):</span>
                        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 aspect-video bg-slate-950 cursor-zoom-in group relative" @click="openImagePreview(selectedJournal.photo)">
                            <img :src="selectedJournal.photo" class="w-full h-full object-cover group-hover:scale-105 transition-transform" alt="Foto Jurnal">
                            <div class="absolute bottom-2 right-2 px-2.5 py-1 bg-slate-900/80 backdrop-blur-sm rounded-full text-[10px] text-white font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Perbesar Foto</span>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="selectedJournal.verification_notes">
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 text-xs">
                        <span class="text-slate-500 dark:text-slate-400 font-bold block mb-1">Catatan Pembimbing:</span>
                        <p class="text-slate-700 dark:text-slate-200 italic font-medium" x-text="'\"' + selectedJournal.verification_notes + '\"'"></p>
                    </div>
                </template>
            </div>

            <div class="mt-5 pt-3 border-t border-slate-200 dark:border-slate-800 text-right">
                <button type="button" @click="journalModalOpen = false" class="px-5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-full transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- POPUP CHECK-IN REMINDER FOR SISWA (z-50) -->
    <div x-show="showCheckInModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click="showCheckInModal = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-md w-full z-10 shadow-2xl relative text-center">
            <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1.5">Pengingat Presensi Datang</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-5">
                Anda belum melakukan presensi datang hari ini. Pastikan Anda mengaktifkan kamera dan mencatat kehadiran Anda sebelum batas toleransi jam masuk!
            </p>
            <div class="flex items-center gap-2">
                <a href="{{ route('attendance.index') }}" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-sm transition-colors">
                    Buka Kamera Presensi
                </a>
                <button type="button" @click="showCheckInModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-full transition-colors">
                    Nanti
                </button>
            </div>
        </div>
    </div>

    <!-- GLOBAL HIGH-LAYER IMAGE PREVIEW LIGHTBOX (z-[80] ON TOP OF ALL MODALS) -->
    <div x-show="imagePreviewOpen" class="fixed inset-0 z-[80] flex items-center justify-center p-3 sm:p-6" style="display: none;">
        <!-- Backdrop with high blur & darker opacity -->
        <div @click="imagePreviewOpen = false" class="fixed inset-0 bg-slate-950/90 backdrop-blur-md"></div>
        
        <!-- Lightbox Content Box -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-4 sm:p-5 max-w-4xl w-full z-10 shadow-2xl relative flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800 mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pratinjau Foto Kegiatan</h3>
                </div>
                <button type="button" @click="imagePreviewOpen = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-full transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 flex items-center justify-center overflow-hidden rounded-2xl bg-slate-950/80 p-2 min-h-[300px]">
                <img :src="previewImageUrl" class="max-w-full max-h-[72vh] object-contain rounded-xl shadow-lg" alt="Pratinjau Foto Bukti">
            </div>
            <div class="mt-3 text-right">
                <button type="button" @click="imagePreviewOpen = false" class="px-6 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-full transition-colors">
                    Tutup Pratinjau
                </button>
            </div>
        </div>
    </div>

</body>
</html>
