import { getApp, getApps, initializeApp } from 'firebase/app';
import { getAuth, signInWithEmailAndPassword } from 'firebase/auth';

const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
    measurementId: import.meta.env.VITE_FIREBASE_MEASUREMENT_ID,
};

const canInitialize = () => Boolean(firebaseConfig.apiKey && firebaseConfig.authDomain && firebaseConfig.projectId && firebaseConfig.appId);

export function firebaseAuth({ enabled = false, sessionUrl = '' } = {}) {
    return {
        enabled: Boolean(enabled),
        sessionUrl,
        loading: false,
        error: '',

        async submit(event) {
            if (!this.enabled) {
                HTMLFormElement.prototype.submit.call(event.currentTarget);
                return;
            }

            event.preventDefault();
            this.error = '';
            this.loading = true;

            try {
                if (!canInitialize()) {
                    throw new Error('Konfigurasi Firebase Web belum lengkap.');
                }

                const app = getApps().length ? getApp() : initializeApp(firebaseConfig);
                const auth = getAuth(app);
                const form = event.currentTarget;
                const credential = await signInWithEmailAndPassword(auth, form.email.value, form.password.value);
                const idToken = await credential.user.getIdToken();
                const response = await fetch(this.sessionUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ id_token: idToken }),
                });
                const payload = await response.json();

                if (! response.ok || ! payload.ok) {
                    throw new Error(payload.message ?? 'Sesi Firebase belum dapat diverifikasi.');
                }

                window.location.assign(payload.redirect);
            } catch (error) {
                this.error = error.message ?? 'Login Firebase gagal.';
            } finally {
                this.loading = false;
            }
        },
    };
}
