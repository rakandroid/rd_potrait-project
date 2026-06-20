import './bootstrap';

const joyGate = document.querySelector('[data-joy-gate]');
const joyGateOpen = document.querySelector('[data-joy-gate-open]');
const menuToggle = document.querySelector('[data-menu-toggle]');
const mobileMenu = document.querySelector('#mobile-menu');
const siteHeader = document.querySelector('[data-site-header]');
let closeMobileMenu = () => {};

if (joyGate) {
    document.body.style.overflow = 'hidden';
}

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

const cropModal = document.querySelector('[data-crop-modal]');
const cropCanvas = document.querySelector('[data-crop-canvas]');
const cropStage = document.querySelector('[data-crop-stage]');
const cropZoom = document.querySelector('[data-crop-zoom]');
const cropApply = document.querySelector('[data-crop-apply]');
const cropCancel = document.querySelector('[data-crop-cancel]');
const cropZoomIn = document.querySelector('[data-crop-zoom-in]');
const cropZoomOut = document.querySelector('[data-crop-zoom-out]');

if (cropModal && cropCanvas && cropStage && cropZoom && cropApply && cropCancel) {
    const context = cropCanvas.getContext('2d');
    let activeInput = null;
    let activeImage = null;
    let activeFileName = 'cropped.jpg';
    let scale = 1;
    let offsetX = 0;
    let offsetY = 0;
    let dragging = false;
    let lastX = 0;
    let lastY = 0;

    const drawCrop = () => {
        const rect = cropStage.getBoundingClientRect();
        const size = Math.min(rect.width, rect.height);
        const dpr = window.devicePixelRatio || 1;

        cropCanvas.width = Math.round(size * dpr);
        cropCanvas.height = Math.round(size * dpr);
        cropCanvas.style.width = `${size}px`;
        cropCanvas.style.height = `${size}px`;

        context.setTransform(dpr, 0, 0, dpr, 0, 0);
        context.clearRect(0, 0, size, size);
        context.fillStyle = '#050505';
        context.fillRect(0, 0, size, size);

        if (!activeImage) {
            return;
        }

        const baseScale = Math.max(size / activeImage.naturalWidth, size / activeImage.naturalHeight);
        const imageWidth = activeImage.naturalWidth * baseScale * scale;
        const imageHeight = activeImage.naturalHeight * baseScale * scale;
        const minOffsetX = Math.min(0, size - imageWidth);
        const minOffsetY = Math.min(0, size - imageHeight);

        offsetX = Math.min(0, Math.max(minOffsetX, offsetX));
        offsetY = Math.min(0, Math.max(minOffsetY, offsetY));

        context.drawImage(activeImage, offsetX, offsetY, imageWidth, imageHeight);
    };

    const openCropModal = async (input, file) => {
        activeInput = input;
        activeImage = await readImage(file);
        activeFileName = file.name.replace(/\.[^.]+$/, '.jpg');
        scale = 1;
        offsetX = 0;
        offsetY = 0;
        cropZoom.value = '1';
        cropModal.classList.remove('hidden');
        cropModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        window.setTimeout(drawCrop, 40);
    };

    document.querySelectorAll('input[type="file"][data-crop-upload]').forEach((input) => {
        input.addEventListener('change', async () => {
            const file = input.files?.[0];

            if (!file || input.dataset.cropApplied === 'true' || !file.type.startsWith('image/')) {
                input.dataset.cropApplied = 'false';
                return;
            }

            input.dataset.cropPending = 'true';

            try {
                await openCropModal(input, file);
            } catch {
                input.dataset.cropPending = 'false';
                input.setCustomValidity('Foto belum bisa dibuka untuk crop. Coba file lain.');
                input.reportValidity();
            }
        });
    });

    const closeCropModal = () => {
        cropModal.classList.add('hidden');
        cropModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';

        if (activeInput) {
            activeInput.dataset.cropPending = 'false';
        }
    };

    cropStage.addEventListener('pointerdown', (event) => {
        dragging = true;
        lastX = event.clientX;
        lastY = event.clientY;
        cropStage.setPointerCapture(event.pointerId);
    });

    cropStage.addEventListener('pointermove', (event) => {
        if (!dragging) {
            return;
        }

        offsetX += event.clientX - lastX;
        offsetY += event.clientY - lastY;
        lastX = event.clientX;
        lastY = event.clientY;
        drawCrop();
    });

    cropStage.addEventListener('pointerup', () => {
        dragging = false;
    });

    cropZoom.addEventListener('input', () => {
        scale = Number(cropZoom.value);
        drawCrop();
    });

    cropZoomIn?.addEventListener('click', () => {
        cropZoom.value = String(Math.min(3, Number(cropZoom.value) + 0.1));
        cropZoom.dispatchEvent(new Event('input'));
    });

    cropZoomOut?.addEventListener('click', () => {
        cropZoom.value = String(Math.max(1, Number(cropZoom.value) - 0.1));
        cropZoom.dispatchEvent(new Event('input'));
    });

    cropCancel.addEventListener('click', () => {
        if (activeInput) {
            activeInput.value = '';
        }

        closeCropModal();
    });

    cropApply.addEventListener('click', async () => {
        if (!activeInput) {
            closeCropModal();
            return;
        }

        const output = document.createElement('canvas');
        output.width = 1600;
        output.height = 1600;
        output.getContext('2d').drawImage(cropCanvas, 0, 0, output.width, output.height);
        const blob = await canvasToBlob(output, 0.86);

        if (!blob) {
            closeCropModal();
            return;
        }

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(new File([blob], activeFileName, {
            type: 'image/jpeg',
            lastModified: Date.now(),
        }));

        activeInput.dataset.cropApplied = 'true';
        activeInput.dataset.cropPending = 'false';
        activeInput.files = dataTransfer.files;
        activeInput.dispatchEvent(new Event('change'));
        closeCropModal();
    });

    window.addEventListener('resize', drawCrop);
}

document.querySelectorAll('input[type="file"][data-max-upload-bytes]').forEach((input) => {
    const maxBytes = Number(input.dataset.maxUploadBytes);
    const maxMegabytes = (maxBytes / 1024 / 1024).toFixed(1);

    input.addEventListener('change', async () => {
        const file = input.files?.[0];

        input.setCustomValidity('');

        if (input.dataset.cropPending === 'true') {
            return;
        }

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

document.querySelectorAll('img').forEach((image) => {
    image.addEventListener('error', () => {
        if (image.dataset.fallbackApplied === 'true') {
            return;
        }

        image.dataset.fallbackApplied = 'true';
        image.classList.add('is-image-error');
        image.src = image.dataset.fallbackImage || '/images/portfolio/beni-profile-camera.jpeg';
    });
});

const backgroundAudio = document.querySelector('[data-background-audio]');
const audioToggle = document.querySelector('[data-audio-toggle]');
const audioMenuToggle = document.querySelector('[data-audio-menu-toggle]');
const audioMenu = document.querySelector('[data-audio-menu]');
const audioToggleLabel = document.querySelector('[data-audio-menu-label]');
const focusVideos = Array.from(document.querySelectorAll('[data-focus-video]'));

if (backgroundAudio && audioToggle) {
    let audioWanted = false;
    let pausedByVideo = false;

    const updateAudioButton = () => {
        const isPlaying = !backgroundAudio.paused;
        const label = isPlaying ? 'Jeda lagu' : 'Nyalakan lagu';

        if (audioToggleLabel) {
            audioToggleLabel.textContent = label;
        }

        audioToggle.setAttribute('aria-label', label);
        audioToggle.setAttribute('aria-pressed', String(isPlaying));
        audioMenuToggle?.classList.toggle('is-playing', isPlaying);
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

    joyGateOpen?.addEventListener('click', () => {
        audioWanted = true;
        pausedByVideo = false;
        playBackgroundAudio();
    });

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

    audioMenuToggle?.addEventListener('click', () => {
        const willOpen = audioMenu?.hasAttribute('hidden') ?? false;

        audioMenu?.toggleAttribute('hidden', !willOpen);
        audioMenuToggle.setAttribute('aria-expanded', String(willOpen));
    });

    document.addEventListener('click', (event) => {
        if (!audioMenu || !audioMenuToggle || audioMenu.hasAttribute('hidden')) {
            return;
        }

        const target = event.target;

        if (target instanceof Node && (audioMenu.contains(target) || audioMenuToggle.contains(target))) {
            return;
        }

        audioMenu.setAttribute('hidden', '');
        audioMenuToggle.setAttribute('aria-expanded', 'false');
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

    const startAudioAfterLoad = () => {
        window.setTimeout(playBackgroundAudio, 1200);
    };

    if (document.readyState === 'complete') {
        startAudioAfterLoad();
    } else {
        window.addEventListener('load', startAudioAfterLoad, { once: true });
    }

    updateAudioButton();
}

document.querySelectorAll('[data-custom-video-player]').forEach((player) => {
    const video = player.querySelector('video');
    const playButton = player.querySelector('[data-video-play]');
    const muteButton = player.querySelector('[data-video-mute]');

    if (!video || !playButton || !muteButton) {
        return;
    }

    const updateVideoControls = () => {
        playButton.textContent = video.paused ? 'Play' : 'Pause';
        playButton.setAttribute('aria-label', video.paused ? 'Putar video' : 'Jeda video');
        muteButton.textContent = video.muted ? 'Muted' : 'Sound';
        muteButton.setAttribute('aria-label', video.muted ? 'Nyalakan suara' : 'Matikan suara');
    };

    playButton.addEventListener('click', async () => {
        if (video.paused) {
            await video.play().catch(() => {});
        } else {
            video.pause();
        }

        updateVideoControls();
    });

    muteButton.addEventListener('click', () => {
        video.muted = !video.muted;
        updateVideoControls();
    });

    ['play', 'pause', 'ended', 'volumechange'].forEach((eventName) => {
        video.addEventListener(eventName, updateVideoControls);
    });

    updateVideoControls();
});

if (joyGate && joyGateOpen) {
    joyGateOpen.addEventListener('click', () => {
        joyGate.classList.add('is-opening');

        window.setTimeout(() => {
            joyGate.classList.add('is-hidden');
            joyGate.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }, 1900);
    }, { once: true });
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
                price: '500 rb',
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
                    'Website fotografy pribadi',
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
                price: 'negotiable',
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
    const message = `Halo RD Potrait, saya mau tanya pricelist untuk ${serviceName}.`;
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

document.querySelectorAll('[data-profile-carousel]').forEach((carousel) => {
    const track = carousel.querySelector('[data-profile-track]');
    const previousButton = carousel.querySelector('[data-profile-prev]');
    const nextButton = carousel.querySelector('[data-profile-next]');

    if (!track || !previousButton || !nextButton) {
        return;
    }

    const scrollProfile = (direction) => {
        const firstCard = track.querySelector('.portfolio-character');
        const gap = Number.parseFloat(window.getComputedStyle(track).columnGap || '0');
        const distance = firstCard ? firstCard.getBoundingClientRect().width + gap : track.clientWidth * 0.82;

        track.scrollBy({
            left: direction * distance,
            behavior: 'smooth',
        });
    };

    previousButton.addEventListener('click', () => scrollProfile(-1));
    nextButton.addEventListener('click', () => scrollProfile(1));
});

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
