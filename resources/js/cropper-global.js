// cropper-global.js
import Cropper from 'cropperjs';

/**
 * Inisialisasi cropper pada input file
 */
export function initCropper() {
    const fileInput = document.getElementById('fileInput');
    const dropArea = document.getElementById('drop-area');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview');
    const croppedInput = document.getElementById('croppedImageInput');
    const removeImageBtn = document.getElementById('removeImage');

    let cropper;

    const handleFile = (file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');

            if (cropper) cropper.destroy();

            cropper = new Cropper(previewImage, {
                aspectRatio: 3 / 4,
                viewMode: 1,
                autoCropArea: 1,
                cropend() {
                    const canvas = cropper.getCroppedCanvas();
                    croppedInput.value = canvas.toDataURL('image/jpeg');
                }
            });

            // Set awal cropped image
            cropper.ready = () => {
                const canvas = cropper.getCroppedCanvas();
                croppedInput.value = canvas.toDataURL('image/jpeg');
            };
        };
        reader.readAsDataURL(file);
    };

    // Handle drag & drop
    dropArea.addEventListener('click', () => fileInput.click());
    dropArea.addEventListener('dragover', (e) => e.preventDefault());
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    // Handle manual input
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) handleFile(file);
    });

    // Remove image
    removeImageBtn.addEventListener('click', () => {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        previewImage.src = '#';
        croppedInput.value = '';
        previewContainer.classList.add('hidden');
    });
}
