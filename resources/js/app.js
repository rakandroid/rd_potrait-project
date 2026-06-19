import './bootstrap';

const menuToggle = document.querySelector('[data-menu-toggle]');
const mobileMenu = document.querySelector('#mobile-menu');
const siteHeader = document.querySelector('[data-site-header]');
let closeMobileMenu = () => {};

if (menuToggle && mobileMenu) {
    closeMobileMenu = () => {
        menuToggle.setAttribute('aria-expanded', 'false');
        mobileMenu.classList.add('hidden');
    };

    menuToggle.addEventListener('click', () => {
        const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!isOpen));
        mobileMenu.classList.toggle('hidden', isOpen);
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeMobileMenu);
    });
}

if (siteHeader) {
    let lastScrollY = window.scrollY;
    let ticking = false;

    const updateHeaderVisibility = () => {
        const currentScrollY = window.scrollY;
        const menuIsOpen = menuToggle?.getAttribute('aria-expanded') === 'true';
        const scrollingDown = currentScrollY > lastScrollY;
        const passedHeader = currentScrollY > siteHeader.offsetHeight + 24;

        siteHeader.classList.toggle('is-hidden', scrollingDown && passedHeader && !menuIsOpen);
        lastScrollY = Math.max(currentScrollY, 0);
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(updateHeaderVisibility);
            ticking = true;
        }
    }, { passive: true });
}

const adminModal = document.querySelector('[data-admin-modal]');
const adminModalOpeners = document.querySelectorAll('[data-admin-modal-open]');
const adminModalClosers = document.querySelectorAll('[data-admin-modal-close]');

if (adminModal && adminModalOpeners.length > 0) {
    const usernameInput = adminModal.querySelector('input[name="email"]');

    const openAdminModal = () => {
        adminModal.classList.remove('hidden');
        adminModal.classList.add('is-open');
        adminModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        window.setTimeout(() => usernameInput?.focus(), 120);
    };

    const closeAdminModal = () => {
        adminModal.classList.remove('is-open');
        adminModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        window.setTimeout(() => adminModal.classList.add('hidden'), 180);
    };

    adminModalOpeners.forEach((opener) => {
        opener.addEventListener('click', (event) => {
            event.preventDefault();
            closeMobileMenu();
            openAdminModal();
        });
    });

    adminModalClosers.forEach((closer) => {
        closer.addEventListener('click', closeAdminModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && adminModal.classList.contains('is-open')) {
            closeAdminModal();
        }
    });

    if (document.body.dataset.openAdminModal === 'true') {
        openAdminModal();
    }
}

document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
    const field = toggle.closest('.password-field')?.querySelector('input');

    if (!field) {
        return;
    }

    toggle.addEventListener('click', () => {
        const shouldShow = field.type === 'password';

        field.type = shouldShow ? 'text' : 'password';
        toggle.textContent = shouldShow ? 'Tutup' : 'Lihat';
        toggle.setAttribute('aria-label', shouldShow ? 'Sembunyikan password' : 'Tampilkan password');
        toggle.setAttribute('aria-pressed', String(shouldShow));
    });
});

const readImage = (file) => new Promise((resolve, reject) => {
    const image = new Image();
    const url = URL.createObjectURL(file);

    image.onload = () => {
        URL.revokeObjectURL(url);
        resolve(image);
    };

    image.onerror = () => {
        URL.revokeObjectURL(url);
        reject(new Error('Image failed to load'));
    };

    image.src = url;
});

const canvasToBlob = (canvas, quality) => new Promise((resolve) => {
    canvas.toBlob(resolve, 'image/jpeg', quality);
});

const compressImageFile = async (file, maxBytes) => {
    const image = await readImage(file);
    const maxSide = 1800;
    const scale = Math.min(1, maxSide / Math.max(image.naturalWidth, image.naturalHeight));
    const canvas = document.createElement('canvas');

    canvas.width = Math.max(1, Math.round(image.naturalWidth * scale));
    canvas.height = Math.max(1, Math.round(image.naturalHeight * scale));
    canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);

    for (const quality of [0.82, 0.72, 0.62, 0.52, 0.42]) {
        const blob = await canvasToBlob(canvas, quality);

        if (blob && blob.size <= maxBytes) {
            return new File([blob], file.name.replace(/\.[^.]+$/, '.jpg'), {
                type: 'image/jpeg',
                lastModified: Date.now(),
            });
        }
    }

    return null;
};

document.querySelectorAll('input[type="file"][data-max-upload-bytes]').forEach((input) => {
    const maxBytes = Number(input.dataset.maxUploadBytes);
    const maxMegabytes = (maxBytes / 1024 / 1024).toFixed(1);

    input.addEventListener('change', async () => {
        const file = input.files?.[0];

        input.setCustomValidity('');

        if (!file || !maxBytes || file.size <= maxBytes) {
            input.dataset.compressingUpload = 'false';
            return;
        }

        if (!file.type.startsWith('image/')) {
            input.value = '';
            input.setCustomValidity(`Ukuran file maksimal ${maxMegabytes}MB untuk upload di Vercel.`);
            input.reportValidity();
            return;
        }

        input.dataset.compressingUpload = 'true';

        try {
            const compressedFile = await compressImageFile(file, maxBytes);

            if (!compressedFile) {
                input.value = '';
                input.setCustomValidity(`Foto belum bisa dikompres di bawah ${maxMegabytes}MB. Coba crop atau kecilkan dulu.`);
                input.reportValidity();
                return;
            }

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(compressedFile);
            input.files = dataTransfer.files;
            input.setCustomValidity('');
        } catch {
            input.value = '';
            input.setCustomValidity('Foto belum bisa diproses. Coba gunakan file JPG atau PNG lain.');
            input.reportValidity();
        } finally {
            input.dataset.compressingUpload = 'false';
        }
    });

    input.form?.addEventListener('submit', (event) => {
        if (input.dataset.compressingUpload === 'true') {
            event.preventDefault();
            input.setCustomValidity('Tunggu sebentar, foto sedang dikompres.');
            input.reportValidity();
        }
    });
});

const backgroundAudio = document.querySelector('[data-background-audio]');
const audioToggle = document.querySelector('[data-audio-toggle]');
const focusVideos = Array.from(document.querySelectorAll('[data-focus-video]'));

if (backgroundAudio && audioToggle) {
    let audioWanted = true;
    let pausedByVideo = false;

    const updateAudioButton = () => {
        const isPlaying = !backgroundAudio.paused;

        audioToggle.textContent = isPlaying ? 'Jeda lagu' : 'Nyalakan lagu';
        audioToggle.setAttribute('aria-pressed', String(isPlaying));
        audioToggle.classList.toggle('is-playing', isPlaying);
    };

    const playBackgroundAudio = async () => {
        if (!audioWanted || pausedByVideo || document.hidden) {
            updateAudioButton();
            return;
        }

        backgroundAudio.volume = 0.42;

        try {
            await backgroundAudio.play();
        } catch {
            backgroundAudio.pause();
        } finally {
            updateAudioButton();
        }
    };

    const pauseBackgroundAudio = () => {
        backgroundAudio.pause();
        updateAudioButton();
    };

    audioToggle.addEventListener('click', () => {
        audioWanted = backgroundAudio.paused;
        pausedByVideo = false;

        if (audioWanted) {
            playBackgroundAudio();
            return;
        }

        pauseBackgroundAudio();
    });

    focusVideos.forEach((video) => {
        video.addEventListener('loadeddata', () => {
            video.classList.add('is-video-ready');
            video.classList.remove('is-video-error');
        }, { once: true });

        video.addEventListener('error', () => {
            video.classList.add('is-video-error');
            video.classList.remove('is-video-ready');
        });

        video.addEventListener('play', () => {
            pausedByVideo = true;
            pauseBackgroundAudio();

            focusVideos
                .filter((otherVideo) => otherVideo !== video)
                .forEach((otherVideo) => otherVideo.pause());
        });

        ['pause', 'ended'].forEach((eventName) => {
            video.addEventListener(eventName, () => {
                const hasPlayingVideo = focusVideos.some((item) => !item.paused && !item.ended);

                if (!hasPlayingVideo) {
                    pausedByVideo = false;
                    playBackgroundAudio();
                }
            });
        });
    });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            pauseBackgroundAudio();
            return;
        }

        playBackgroundAudio();
    });

    ['click', 'touchstart', 'keydown'].forEach((eventName) => {
        document.addEventListener(eventName, playBackgroundAudio, { once: true, passive: true });
    });

    playBackgroundAudio();
}

document.querySelectorAll('[data-rating-picker]').forEach((picker) => {
    const ratingText = picker.parentElement?.querySelector('[data-rating-text]');
    const inputs = picker.querySelectorAll('input[name="rating"]');

    inputs.forEach((input) => {
        input.addEventListener('change', () => {
            if (ratingText) {
                ratingText.textContent = `${input.value} dari 5 bintang`;
            }
        });
    });
});

const dateInput = document.querySelector('#booking-date');
const datePreview = document.querySelector('[data-date-preview]');

const formatIndonesianDate = (value) => {
    if (!value) {
        return 'Pilih tanggal acara';
    }

    const [year, month, day] = value.split('-').map(Number);
    const date = new Date(year, month - 1, day);

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(date);
};

if (dateInput && datePreview) {
    datePreview.textContent = formatIndonesianDate(dateInput.value);
    dateInput.addEventListener('change', () => {
        datePreview.textContent = formatIndonesianDate(dateInput.value);
    });
}

const locationButton = document.querySelector('[data-location-button]');
const locationInput = document.querySelector('#booking-location');
const locationStatus = document.querySelector('[data-location-status]');

if (locationButton && locationInput) {
    locationButton.addEventListener('click', () => {
        if (!navigator.geolocation) {
            if (locationStatus) {
                locationStatus.textContent = 'Browser ini belum mendukung deteksi lokasi otomatis.';
            }
            return;
        }

        locationButton.disabled = true;
        locationButton.textContent = 'Mengambil lokasi...';

        navigator.geolocation.getCurrentPosition((position) => {
            const latitude = position.coords.latitude.toFixed(6);
            const longitude = position.coords.longitude.toFixed(6);

            locationInput.value = `https://www.google.com/maps?q=${latitude},${longitude}`;
            locationButton.disabled = false;
            locationButton.textContent = 'Pakai Lokasi Saya';

            if (locationStatus) {
                locationStatus.textContent = 'Lokasi berhasil diisi sebagai link Google Maps.';
            }
        }, () => {
            locationButton.disabled = false;
            locationButton.textContent = 'Pakai Lokasi Saya';

            if (locationStatus) {
                locationStatus.textContent = 'Izin lokasi ditolak atau lokasi belum bisa dibaca. Kamu tetap bisa isi alamat manual.';
            }
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000,
        });
    });
}

const priceModal = document.querySelector('[data-price-modal]');
const priceModalPanel = document.querySelector('[data-price-modal-panel]');
const priceOpeners = document.querySelectorAll('[data-service-open]');
const priceClosers = document.querySelectorAll('[data-price-modal-close]');
const priceTitle = document.querySelector('[data-price-title]');
const priceDescription = document.querySelector('[data-price-description]');
const priceEyebrow = document.querySelector('[data-price-eyebrow]');
const pricePackages = document.querySelector('[data-price-packages]');
const priceWa = document.querySelector('[data-price-wa]');

const waNumber = '628551250670';
const albumNote = 'Album: 1 roll Rp150rb, 2 roll Rp230rb';

const servicePrices = {
    wedding: {
        eyebrow: 'Wedding Package',
        title: 'Wedding Photography',
        description: 'Package dokumentasi wedding dengan delivery file mentah, editing pilihan, private link, dan opsi album.',
        animation: 'price-modal--shutter',
        packages: [
            {
                name: 'Silver Package',
                price: 'Rate via WhatsApp',
                details: [
                    'Durasi 1/2 hari',
                    'Unlimited shoot',
                    'Full file mentah dikirim JPEG/RAW',
                    '50 foto editing',
                    'Private link foto 15 hari',
                    albumNote,
                ],
            },
            {
                name: 'Gold Package',
                price: 'Rp1.000.000',
                details: [
                    'Durasi full time',
                    'Full file mentah dikirim',
                    '100 foto editing',
                    'Private link 1 bulan',
                    albumNote,
                ],
            },
            {
                name: 'Platinum Package',
                price: 'Rp1.700.000',
                details: [
                    'Durasi full time sampai malam',
                    '2 fotografer',
                    '150 foto editing',
                    'Private link 2 bulan',
                    'Full file mentah dikirim',
                    'Free gift/aksesoris',
                    'Premium album',
                    'Free SD card/flashdisk',
                ],
            },
            {
                name: 'Premium Package',
                price: 'Rp2.700.000',
                details: [
                    'Durasi full time sampai malam',
                    '2 fotografer',
                    '150 foto editing',
                    'Private link 2 bulan',
                    'Full file mentah dikirim',
                    'Video cinematic',
                    'Free gift/aksesoris',
                    'Premium album',
                    'Free SD card/flashdisk',
                ],
            },
        ],
    },
    prewedding: {
        eyebrow: 'Pre-Wedding Package',
        title: 'Pre-Wedding',
        description: 'Paket pre-wedding untuk konsep personal dengan arahan pose, moodboard, dan hasil yang siap dibagikan.',
        animation: 'price-modal--float',
        packages: [
            {
                name: 'Gold Package',
                price: 'Rp500.000',
                details: [
                    'Konsep personal',
                    'Arahan pose',
                    'Moodboard sesi',
                    'File hasil dikirim digital',
                ],
            },
            {
                name: 'Platinum Package',
                price: 'Rp1.100.000',
                details: [
                    'Konsep personal lebih detail',
                    'Arahan pose dan moodboard',
                    'Editing pilihan',
                    'File hasil dikirim digital',
                ],
            },
        ],
    },
    cinematic: {
        eyebrow: 'Video Package',
        title: 'Cinematic Video',
        description: 'Video highlight dengan rasa sinematik, cocok untuk wedding, pre-wedding, atau momen spesial lain.',
        animation: 'price-modal--cinema',
        packages: [
            {
                name: 'Cinematic Standar',
                price: 'Rp1.000.000',
                details: [
                    'Video cinematic highlight',
                    'Color grading',
                    'File final dikirim digital',
                ],
            },
            {
                name: 'Semi Dokumenter',
                price: 'Rp1.500.000',
                details: [
                    'Format cerita semi dokumenter',
                    'Cinematic highlight',
                    'Color grading',
                    'File final dikirim digital',
                ],
            },
        ],
    },
    event: {
        eyebrow: 'Event Booking',
        title: 'Portrait & Event',
        description: 'Untuk event, detail kebutuhan biasanya berbeda tiap acara. Klik WhatsApp untuk konsultasi tanggal, lokasi, rundown, dan output.',
        animation: 'price-modal--event',
        packages: [
            {
                name: 'Event Custom',
                price: 'Direct WhatsApp',
                details: [
                    'Wisuda, ulang tahun, corporate, dan acara khusus',
                    'Durasi dan kebutuhan output menyesuaikan rundown',
                    'Konsultasi langsung via WhatsApp',
                ],
            },
        ],
    },
};

const createWaLink = (serviceName) => {
    const message = `Halo R&D Photography, saya mau tanya pricelist untuk ${serviceName}.`;
    return `https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`;
};

const renderPackage = (item, index) => {
    const details = item.details.map((detail) => `<li>${detail}</li>`).join('');

    return `
        <article class="price-package" style="--package-index: ${index}">
            <div>
                <h3>${item.name}</h3>
                <p>${item.price}</p>
            </div>
            <ul>${details}</ul>
        </article>
    `;
};

if (priceModal && priceOpeners.length > 0) {
    const openPriceModal = (serviceKey) => {
        const service = servicePrices[serviceKey];

        if (!service || !priceTitle || !priceDescription || !pricePackages || !priceWa || !priceModalPanel || !priceEyebrow) {
            return;
        }

        priceEyebrow.textContent = service.eyebrow;
        priceTitle.textContent = service.title;
        priceDescription.textContent = service.description;
        pricePackages.innerHTML = service.packages.map(renderPackage).join('');
        priceWa.href = createWaLink(service.title);
        priceModalPanel.className = `price-modal-panel relative w-full max-w-5xl border border-white/10 bg-[#151515] shadow-2xl shadow-black/60 ${service.animation}`;
        priceModal.classList.remove('hidden');
        priceModal.classList.add('is-open');
        priceModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };

    const closePriceModal = () => {
        priceModal.classList.remove('is-open');
        priceModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        window.setTimeout(() => priceModal.classList.add('hidden'), 220);
    };

    priceOpeners.forEach((opener) => {
        opener.addEventListener('click', () => {
            openPriceModal(opener.dataset.serviceOpen);
        });
    });

    priceClosers.forEach((closer) => {
        closer.addEventListener('click', closePriceModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && priceModal.classList.contains('is-open')) {
            closePriceModal();
        }
    });
}

const revealItems = document.querySelectorAll('.reveal');

if (revealItems.length > 0) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.14,
    });

    revealItems.forEach((item) => observer.observe(item));
}
