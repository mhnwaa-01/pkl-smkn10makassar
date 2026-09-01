@extends('layouts.app')

@section('title', 'Presensi Harian Siswa')

@section('content')
<div class="space-y-8" x-data="{
    currentTime: '',
    latitude: '-5.147665',
    longitude: '119.432731',
    locationCoords: '-5.147665, 119.432731',
    locationStatus: 'Mendeteksi lokasi GPS otomatis...',
    locationSuccess: true,
    initData() {
        this.updateTime();
        setInterval(() => this.updateTime(), 1000);
        this.detectLocation();
    },
    updateTime() {
        const now = new Date();
        this.currentTime = now.toLocaleTimeString('id-ID', { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', second: '2-digit' });
    },
    detectLocation() {
        this.locationStatus = 'Mengakses koordinat GPS...';
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.latitude = pos.coords.latitude.toFixed(6);
                    this.longitude = pos.coords.longitude.toFixed(6);
                    this.locationCoords = `${this.latitude}, ${this.longitude}`;
                    this.locationStatus = `GPS Terdeteksi: ${this.locationCoords}`;
                    this.locationSuccess = true;
                },
                (err) => {
                    console.warn('Geolocation fallback:', err);
                    this.latitude = '-5.147665';
                    this.longitude = '119.432731';
                    this.locationCoords = `${this.latitude}, ${this.longitude}`;
                    this.locationStatus = `Lokasi Otomatis (Makassar): ${this.locationCoords}`;
                    this.locationSuccess = true;
                },
                { enableHighAccuracy: true, timeout: 6000, maximumAge: 0 }
            );
        } else {
            this.latitude = '-5.147665';
            this.longitude = '119.432731';
            this.locationCoords = `${this.latitude}, ${this.longitude}`;
            this.locationStatus = `Lokasi Otomatis (Makassar): ${this.locationCoords}`;
            this.locationSuccess = true;
        }
    }
}" x-init="initData()">

    <!-- Page Header & Rules -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Presensi Kehadiran Siswa</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                        Batas Jam Masuk: <strong class="text-slate-900 dark:text-white">{{ substr($setting->check_in_late_time, 0, 5) }} WITA</strong> • Buka Jam Pulang: <strong class="text-slate-900 dark:text-white">{{ substr($allowedOutTime, 0, 5) }} WITA</strong> (Normal: {{ substr($setting->check_out_time, 0, 5) }} WITA)
                    </p>
                </div>
            </div>
            <!-- Live Digital Clock (Asia/Makassar) -->
            <div class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full px-6 py-2.5 text-center flex-shrink-0">
                <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">WAKTU MAKASSAR (WITA)</span>
                <span x-text="currentTime" class="text-xl font-bold text-slate-900 dark:text-white font-mono tracking-wider">--:--:--</span>
            </div>
        </div>
    </div>

    <!-- Check-In / Check-Out Actions Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- PRESENSI DATANG CARD -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span>Presensi Datang (Pagi)</span>
                    </h3>
                    <span class="px-3 py-1 text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 rounded-full uppercase tracking-wider">Check-In</span>
                </div>

                @if($todayAttendance && $todayAttendance->check_in_time)
                    <div class="p-6 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/80 rounded-2xl text-center space-y-3">
                        <div class="w-14 h-14 bg-emerald-600 text-white rounded-full flex items-center justify-center mx-auto shadow-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h4 class="text-base font-bold text-emerald-900 dark:text-emerald-300">Presensi Datang Tercatat!</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">Jam Masuk: <strong class="font-mono text-slate-900 dark:text-white">{{ $todayAttendance->check_in_time }} WITA</strong></p>
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase
                                @if($todayAttendance->check_in_status === 'Tepat Waktu') bg-emerald-200 text-emerald-900 dark:bg-emerald-900/60 dark:text-emerald-200
                                @else bg-rose-200 text-rose-900 dark:bg-rose-900/60 dark:text-rose-200 @endif">
                                Status: {{ $todayAttendance->check_in_status }}
                            </span>
                        </div>
                        
                        @if($todayAttendance->location)
                            <div class="pt-1">
                                <a href="{{ $todayAttendance->check_in_map_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/60 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 rounded-full text-xs font-semibold transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Lokasi Datang: {{ $todayAttendance->location }}</span>
                                    <span class="text-[10px] text-blue-500 font-mono underline">(Buka Google Maps ↗)</span>
                                </a>
                            </div>
                        @endif

                        @if($todayAttendance->check_in_notes)
                            <p class="text-xs text-slate-500 dark:text-slate-400 italic mt-1">"{{ $todayAttendance->check_in_notes }}"</p>
                        @endif
                    </div>
                @else
                    <form action="{{ route('attendance.checkIn') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="cameraWidget('photo_in')">
                        @csrf
                        <input type="hidden" name="location" :value="locationCoords">
                        
                        <!-- Location GPS Status Box (Auto Detected) -->
                        <div class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 flex items-center justify-between gap-2 text-xs">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 bg-emerald-500 animate-pulse"></span>
                                <span class="text-slate-700 dark:text-slate-300 font-semibold truncate" x-text="locationStatus"></span>
                            </div>
                            <button type="button" @click="detectLocation()" class="px-3 py-1 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-full text-[10px] font-bold flex-shrink-0 transition-colors">
                                🔄 Refresh GPS
                            </button>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Catatan / Keterangan (Opsional)</label>
                            <input type="text" name="notes" placeholder="Contoh: Sudah tiba di lokasi industri"
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Foto Wajah Presensi Datang</label>
                            <!-- Hidden File Input that supports native camera capture on all devices -->
                            <input type="file" name="photo" id="photo_in" accept="image/*" capture="user" @change="handleFileInput($event)" required class="hidden">
                            
                            <div class="p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl space-y-3">
                                <div class="space-y-3">
                                    <div @click="if (!capturedPhoto) startCamera()"
                                         class="relative bg-slate-950 rounded-xl overflow-hidden aspect-video flex items-center justify-center cursor-pointer border border-slate-800 hover:border-slate-700 transition-all">
                                        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover" x-show="streamActive" style="transform: scaleX(-1);"></video>
                                        <canvas x-ref="canvas" class="hidden"></canvas>
                                        <img :src="capturedPhoto" class="w-full h-full object-cover" x-show="capturedPhoto" alt="Pratinjau Foto">
                                        <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center text-slate-400" x-show="!streamActive && !capturedPhoto">
                                            <div class="w-12 h-12 rounded-full bg-slate-900 text-slate-500 flex items-center justify-center mb-1.5 shadow-sm">
                                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </div>
                                            <span class="text-xs font-semibold text-slate-300">Klik di sini untuk mengaktifkan kamera</span>
                                            <span class="text-[10px] text-slate-500 mt-0.5">Mendukung kamera HP & webcam otomatis</span>
                                        </div>
                                        <div class="absolute inset-0 flex items-center justify-center bg-slate-950/80 text-white text-xs font-bold" x-show="loading">
                                            <span class="animate-pulse">Mengaktifkan kamera...</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" @click="startCamera()" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full text-xs font-semibold flex items-center justify-center gap-2 transition-colors shadow-sm" x-show="!streamActive && !capturedPhoto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <span>Buka Kamera / Ambil Foto</span>
                                        </button>
                                        <button type="button" @click="capturePhoto()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-full text-xs font-semibold flex items-center justify-center gap-2 transition-colors shadow-sm" x-show="streamActive">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span>Ambil Foto Wajah</span>
                                        </button>
                                        <button type="button" @click="resetPhoto()" class="py-2.5 px-5 bg-rose-100 hover:bg-rose-200 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 rounded-full text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors" x-show="capturedPhoto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            <span>Ambil Ulang</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-full shadow-md transition-all inline-flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Presensi Datang</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- PRESENSI PULANG CARD -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                        <span>Presensi Pulang (Sore)</span>
                    </h3>
                    <span class="px-3 py-1 text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300 rounded-full uppercase tracking-wider">Check-Out</span>
                </div>

                @if(!$todayAttendance || !$todayAttendance->check_in_time)
                    <div class="p-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 flex items-center justify-center mx-auto mb-2 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">Lakukan presensi datang terlebih dahulu untuk membuka formulir presensi pulang.</p>
                    </div>
                @elseif($todayAttendance->check_out_time)
                    <div class="p-6 bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800/80 rounded-2xl text-center space-y-3">
                        <div class="w-14 h-14 bg-purple-600 text-white rounded-full flex items-center justify-center mx-auto shadow-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h4 class="text-base font-bold text-purple-900 dark:text-purple-300">Presensi Pulang Tercatat!</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">Jam Pulang: <strong class="font-mono text-slate-900 dark:text-white">{{ $todayAttendance->check_out_time }} WITA</strong></p>
                        <div class="flex flex-wrap items-center justify-center gap-2">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase
                                @if($todayAttendance->check_out_status === 'Tepat Waktu') bg-purple-200 text-purple-900 dark:bg-purple-900/60 dark:text-purple-200
                                @else bg-amber-200 text-amber-900 dark:bg-amber-900/60 dark:text-amber-200 @endif">
                                Status: {{ $todayAttendance->check_out_status }}
                            </span>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                                Lama Kerja: {{ $todayAttendance->work_duration }}
                            </span>
                        </div>

                        @if($todayAttendance->location_out ?: $todayAttendance->location)
                            <div class="pt-1">
                                <a href="{{ $todayAttendance->check_out_map_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/60 dark:hover:bg-purple-900/60 text-purple-700 dark:text-purple-300 rounded-full text-xs font-semibold transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Lokasi Pulang: {{ $todayAttendance->location_out ?: $todayAttendance->location }}</span>
                                    <span class="text-[10px] text-purple-500 font-mono underline">(Buka Google Maps ↗)</span>
                                </a>
                            </div>
                        @endif

                        @if($todayAttendance->check_out_notes)
                            <p class="text-xs text-slate-500 dark:text-slate-400 italic mt-1">"{{ $todayAttendance->check_out_notes }}"</p>
                        @endif
                    </div>
                @elseif($isCheckOutLocked)
                    <!-- LOCKED STATE UNTIL ALLOWED TIME ARRIVES -->
                    <div class="p-6 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-center space-y-3">
                        <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-full flex items-center justify-center mx-auto shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">Presensi Pulang Terkunci</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-xs mx-auto">
                                Belum memasuki waktu presensi pulang. Formulir dan kamera presensi pulang akan dibuka mulai pukul <strong class="text-slate-900 dark:text-white">{{ substr($allowedOutTime, 0, 5) }} WITA</strong>.
                            </p>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 rounded-full text-xs font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Jam Pulang Normal: {{ substr($setting->check_out_time, 0, 5) }} WITA</span>
                        </div>
                    </div>
                @else
                    <!-- UNLOCKED FORM -->
                    <form action="{{ route('attendance.checkOut') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="cameraWidget('photo_out')">
                        @csrf
                        <input type="hidden" name="location" :value="locationCoords">

                        <!-- Location GPS Status Box (Auto Detected) -->
                        <div class="p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 flex items-center justify-between gap-2 text-xs">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 bg-purple-500 animate-pulse"></span>
                                <span class="text-slate-700 dark:text-slate-300 font-semibold truncate" x-text="locationStatus"></span>
                            </div>
                            <button type="button" @click="detectLocation()" class="px-3 py-1 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-full text-[10px] font-bold flex-shrink-0 transition-colors">
                                🔄 Refresh GPS
                            </button>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Catatan Pulang (Opsional)</label>
                            <input type="text" name="notes" placeholder="Contoh: Jam kerja magang hari ini selesai"
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-full px-4 py-2.5 text-xs text-slate-900 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-purple-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Foto Bukti Pulang</label>
                            <!-- Hidden File Input that supports native camera capture on all devices -->
                            <input type="file" name="photo" id="photo_out" accept="image/*" capture="user" @change="handleFileInput($event)" required class="hidden">
                            
                            <div class="p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl space-y-3">
                                <div class="space-y-3">
                                    <div @click="if (!capturedPhoto) startCamera()"
                                         class="relative bg-slate-950 rounded-xl overflow-hidden aspect-video flex items-center justify-center cursor-pointer border border-slate-800 hover:border-slate-700 transition-all">
                                        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover" x-show="streamActive" style="transform: scaleX(-1);"></video>
                                        <canvas x-ref="canvas" class="hidden"></canvas>
                                        <img :src="capturedPhoto" class="w-full h-full object-cover" x-show="capturedPhoto" alt="Pratinjau Foto">
                                        <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center text-slate-400" x-show="!streamActive && !capturedPhoto">
                                            <div class="w-12 h-12 rounded-full bg-slate-900 text-slate-500 flex items-center justify-center mb-1.5 shadow-sm">
                                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </div>
                                            <span class="text-xs font-semibold text-slate-300">Klik di sini untuk mengaktifkan kamera</span>
                                            <span class="text-[10px] text-slate-500 mt-0.5">Mendukung kamera HP & webcam otomatis</span>
                                        </div>
                                        <div class="absolute inset-0 flex items-center justify-center bg-slate-950/80 text-white text-xs font-bold" x-show="loading">
                                            <span class="animate-pulse">Mengaktifkan kamera...</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" @click="startCamera()" class="flex-1 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-full text-xs font-semibold flex items-center justify-center gap-2 transition-colors shadow-sm" x-show="!streamActive && !capturedPhoto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <span>Buka Kamera / Ambil Foto</span>
                                        </button>
                                        <button type="button" @click="capturePhoto()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-full text-xs font-semibold flex items-center justify-center gap-2 transition-colors shadow-sm" x-show="streamActive">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span>Ambil Foto Pulang</span>
                                        </button>
                                        <button type="button" @click="resetPhoto()" class="py-2.5 px-5 bg-rose-100 hover:bg-rose-200 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 rounded-full text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors" x-show="capturedPhoto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            <span>Ambil Ulang</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-full shadow-md transition-all inline-flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Presensi Pulang</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>

    <!-- Attendance History Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Riwayat Presensi Anda</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-bold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Presensi Datang</th>
                        <th class="p-3">Foto Datang</th>
                        <th class="p-3">Presensi Pulang</th>
                        <th class="p-3">Foto Pulang</th>
                        <th class="p-3">Lama Kerja</th>
                        <th class="p-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($attendances as $att)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <!-- Tanggal -->
                            <td class="p-3 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d F Y') }}
                            </td>
                            
                            <!-- Presensi Datang -->
                            <td class="p-3 whitespace-nowrap">
                                @if($att->check_in_time)
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $att->check_in_time }} WITA</span>
                                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full
                                                @if($att->check_in_status === 'Tepat Waktu') bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300
                                                @else bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 @endif">
                                                {{ $att->check_in_status }}
                                            </span>
                                        </div>
                                        @if($att->location)
                                            <a href="{{ $att->check_in_map_url }}" target="_blank" class="inline-flex items-center gap-1 font-mono text-[11px] text-blue-600 dark:text-blue-400 hover:underline">
                                                <svg class="w-3.5 h-3.5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <span>{{ $att->location }}</span>
                                                <span class="text-[9px] text-blue-500 font-sans">↗</span>
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Foto Datang -->
                            <td class="p-3">
                                @if($att->check_in_photo_url)
                                    <button type="button" @click="openImagePreview('{{ $att->check_in_photo_url }}')" class="group relative block w-10 h-10 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm hover:scale-105 transition-transform">
                                        <img src="{{ $att->check_in_photo_url }}" class="w-full h-full object-cover" alt="Foto Datang">
                                    </button>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Presensi Pulang -->
                            <td class="p-3 whitespace-nowrap">
                                @if($att->check_out_time)
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono font-bold text-purple-600 dark:text-purple-400">{{ $att->check_out_time }} WITA</span>
                                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full
                                                @if($att->check_out_status === 'Tepat Waktu') bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300
                                                @else bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 @endif">
                                                {{ $att->check_out_status }}
                                            </span>
                                        </div>
                                        @if($att->location_out ?: $att->location)
                                            <a href="{{ $att->check_out_map_url }}" target="_blank" class="inline-flex items-center gap-1 font-mono text-[11px] text-purple-600 dark:text-purple-400 hover:underline">
                                                <svg class="w-3.5 h-3.5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <span>{{ $att->location_out ?: $att->location }}</span>
                                                <span class="text-[9px] text-purple-500 font-sans">↗</span>
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Foto Pulang -->
                            <td class="p-3">
                                @if($att->check_out_photo_url)
                                    <button type="button" @click="openImagePreview('{{ $att->check_out_photo_url }}')" class="group relative block w-10 h-10 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm hover:scale-105 transition-transform">
                                        <img src="{{ $att->check_out_photo_url }}" class="w-full h-full object-cover" alt="Foto Pulang">
                                    </button>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Lama Kerja -->
                            <td class="p-3 font-semibold text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold
                                    @if($att->work_duration === '-') bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400
                                    @elseif($att->work_duration === 'Sedang Berlangsung') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 animate-pulse
                                    @else bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 @endif">
                                    {{ $att->work_duration ?? '-' }}
                                </span>
                            </td>

                            <!-- Catatan -->
                            <td class="p-3 text-slate-500 dark:text-slate-400 text-xs max-w-xs">
                                @if($att->check_in_notes || $att->check_out_notes)
                                    <div class="space-y-0.5">
                                        @if($att->check_in_notes)
                                            <p class="truncate"><strong class="text-slate-700 dark:text-slate-300">Datang:</strong> "{{ $att->check_in_notes }}"</p>
                                        @endif
                                        @if($att->check_out_notes)
                                            <p class="truncate"><strong class="text-slate-700 dark:text-slate-300">Pulang:</strong> "{{ $att->check_out_notes }}"</p>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 dark:text-slate-500">
                                Belum ada riwayat presensi yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
