import { Html5Qrcode } from 'html5-qrcode';

export function attendanceScanner({ scanUrl = '', demoToken = '' } = {}) {
    return {
        scanUrl,
        qrToken: '',
        demoToken,
        locationState: 'idle',
        locationMessage: 'Aktifkan GPS untuk melanjutkan absensi.',
        latitude: null,
        longitude: null,
        accuracy: null,
        scannerState: 'idle',
        result: 'success',
        responseMessage: 'Pilih state untuk melihat respons demo.',
        steps: {
            qr: { label: 'QR valid', detail: 'Menunggu pemeriksaan', status: 'waiting' },
            location: { label: 'Lokasi sesuai', detail: 'Menunggu pemeriksaan', status: 'waiting' },
            attendance: { label: 'Status kehadiran', detail: 'Menunggu pemeriksaan', status: 'waiting' },
            recorded: { label: 'Pencatatan', detail: 'Menunggu proses', status: 'waiting' },
        },
        camera: null,
        cameraRunning: false,

        locate() {
            return new Promise((resolve) => {
                if (! navigator.geolocation) {
                    this.locationState = 'error';
                    this.locationMessage = 'Browser ini belum mendukung pembacaan lokasi.';
                    resolve(false); return;
                }
                this.locationState = 'loading';
                this.locationMessage = 'Mencari koordinat perangkat…';
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.latitude = position.coords.latitude;
                        this.longitude = position.coords.longitude;
                        this.accuracy = position.coords.accuracy;
                        this.locationState = 'ready';
                        this.locationMessage = 'Lokasi sekolah terdeteksi';
                        resolve(true);
                    },
                    (error) => {
                        this.locationState = 'error';
                        this.locationMessage = error.code === 1 ? 'Izin lokasi ditolak. Aktifkan GPS lalu coba lagi.' : 'Lokasi belum dapat dibaca. Periksa GPS dan coba lagi.';
                        resolve(false);
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 },
                );
            });
        },

        speakWelcome(name, identifier, timeStr) {
            if (!('speechSynthesis' in window)) return;
            window.speechSynthesis.cancel();
            const text = `Selamat Datang ${name}, NISN ${identifier}, pukul ${timeStr}, Berhasil`;
            const u = new SpeechSynthesisUtterance(text);
            u.lang = 'id-ID'; u.rate = 0.95; u.volume = 1;
            window.speechSynthesis.speak(u);
        },

        useDemoToken() {
            this.qrToken = this.demoToken;
            this.responseMessage = 'Token demo siap diuji melalui validasi server.';
        },

        async startCamera() {
            if (this.locationState !== 'ready') {
                await this.locate();
            }

            if (this.locationState !== 'ready' || this.cameraRunning) {
                return;
            }

            this.scannerState = 'scanning';
            this.camera = new Html5Qrcode('qr-reader');

            try {
                await this.camera.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 220, height: 220 } },
                    async (decodedText) => {
                        this.qrToken = decodedText;
                        await this.stopCamera();
                        await this.submit();
                    },
                    () => {},
                );
                this.cameraRunning = true;
            } catch (error) {
                this.scannerState = 'error';
                this.responseMessage = 'Kamera belum dapat dibuka. Gunakan token demo untuk menguji endpoint.';
                this.camera = null;
            }
        },

        async stopCamera() {
            if (this.camera && this.cameraRunning) {
                await this.camera.stop();
                this.camera.clear();
            }

            this.cameraRunning = false;
            this.camera = null;
        },

        async submit() {
            if (! this.qrToken || this.latitude === null || this.longitude === null || ! this.scanUrl) {
                this.responseMessage = 'Lokasi dan token QR wajib tersedia sebelum dikirim.';
                return;
            }

            this.scannerState = 'submitting';

            try {
                const response = await fetch(this.scanUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        qr_token: this.qrToken,
                        latitude: this.latitude,
                        longitude: this.longitude,
                        accuracy: this.accuracy,
                    }),
                });

                const payload = await response.json();
                const resultMap = { outside_area: 'outside', duplicate: 'duplicate', expired: 'expired', success: 'success' };
                this.result = resultMap[payload.result] ?? 'expired';
                this.steps = payload.steps ?? this.steps;
                this.responseMessage = payload.message ?? 'Respons server diterima.';
                this.scannerState = 'result';
                if (payload.result === 'success' && payload.attendance) {
                    const a = payload.attendance;
                    const t = a.scanned_at ? new Date(a.scanned_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}) : new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
                    this.speakWelcome(a.user_name || 'Siswa', a.identifier || '-', t);
                    try { localStorage.setItem('last_scan', JSON.stringify({name:a.user_name, identifier:a.identifier, time:t, at: Date.now()})); } catch(e){}
                    // auto-reset after 2.5s to allow next scan
                    setTimeout(()=>this.resetForScan(), 2500);
                }
            } catch (error) {
                this.scannerState = 'error';
                this.responseMessage = 'Hasil belum dapat dikirim. Sambungkan kembali lalu coba sekali lagi.';
            }
        },

        resetForScan() {
            this.qrToken = '';
            this.scannerState = 'idle';
            this.responseMessage = 'Pindai QR terbaru untuk memulai pemeriksaan.';
            this.steps = {
                qr: { label: 'QR valid', detail: 'Menunggu pemeriksaan', status: 'waiting' },
                location: { label: 'Lokasi sesuai', detail: 'Menunggu pemeriksaan', status: 'waiting' },
                attendance: { label: 'Status kehadiran', detail: 'Menunggu pemeriksaan', status: 'waiting' },
                recorded: { label: 'Pencatatan', detail: 'Menunggu proses', status: 'waiting' },
            };
        },

        selectDemo(result) {
            this.result = result;
            this.scannerState = 'result';
            const demoSteps = {
                success: {
                    qr: { label: 'QR valid', detail: 'Token masih aktif', status: 'passed' },
                    location: { label: 'Lokasi sesuai', detail: 'Dalam radius 80 m', status: 'passed' },
                    attendance: { label: 'Status kehadiran', detail: 'Belum tercatat', status: 'passed' },
                    recorded: { label: 'Pencatatan', detail: 'Data tersimpan di server', status: 'passed' },
                },
                expired: {
                    qr: { label: 'QR valid', detail: 'Token sudah kedaluwarsa', status: 'failed' },
                    location: { label: 'Lokasi sesuai', detail: 'Belum diperiksa', status: 'waiting' },
                    attendance: { label: 'Status kehadiran', detail: 'Belum diperiksa', status: 'waiting' },
                    recorded: { label: 'Pencatatan', detail: 'Tidak diproses', status: 'waiting' },
                },
                outside: {
                    qr: { label: 'QR valid', detail: 'Token masih aktif', status: 'passed' },
                    location: { label: 'Lokasi sesuai', detail: 'Di luar radius sekolah', status: 'failed' },
                    attendance: { label: 'Status kehadiran', detail: 'Belum diperiksa', status: 'waiting' },
                    recorded: { label: 'Pencatatan', detail: 'Tidak diproses', status: 'waiting' },
                },
                duplicate: {
                    qr: { label: 'QR valid', detail: 'Token masih aktif', status: 'passed' },
                    location: { label: 'Lokasi sesuai', detail: 'Dalam radius 80 m', status: 'passed' },
                    attendance: { label: 'Status kehadiran', detail: 'Sudah tercatat hari ini', status: 'failed' },
                    recorded: { label: 'Pencatatan', detail: 'Tidak membuat duplikasi', status: 'waiting' },
                },
            };

            this.steps = demoSteps[result] ?? demoSteps.success;
            this.responseMessage = {
                success: 'Kehadiran berhasil dicatat. Waktu kehadiran Anda telah tersimpan.',
                expired: 'QR sudah kedaluwarsa. Pindai kode terbaru yang sedang tampil di monitor.',
                outside: 'Anda berada di luar area sekolah. Absensi hanya dapat dilakukan di dalam radius lokasi sekolah.',
                duplicate: 'Kehadiran sudah tercatat. Anda tidak perlu melakukan pemindaian ulang hari ini.',
            }[result];
        },
    };
}
