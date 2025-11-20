// Modal Image Gallery
const galleryImages = document.querySelectorAll('.gallery-img');
    const modalImage = document.getElementById('modalImage');

    galleryImages.forEach(img => {
        img.addEventListener('click', () => {
            const src = img.getAttribute('data-image');
            modalImage.src = src;
        });
    });