// assets/js/main.js

// File preview seblum upload
function previewFile() {
    const preview = document.getElementById('file-preview');
    const fileInput = document.querySelector('input[type=file]');
    const file = fileInput.files[0];

    if (!preview || !file) return;

    const reader = new FileReader();

    reader.onload = function(e) {
        if (file.type.startsWith('image/')) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px;">
                <p>Size: ${formatFileSize(file.size)}</p>
            `;
        } else {
            preview.innerHTML = `
                <div class="file-info">
                    <p>File Name: ${file.name}</p>
                    <p>Type: ${file.type || 'unknown'}</p>
                    <p>Size: ${formatFileSize(file.size)}</p>
                </div>
            `;
        }
    };

    reader.readAsDataURL(file);
}

// Format size dari file 
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Confirm delete
function confirmDelete(fileId, filename) {
    if (confirm(`Are you sure you want to delete "${filename}"?`)) {
        window.location.href = `/modules/files/process/delete_process.php?id=${fileId}`;
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const fileInput = form.querySelector('input[type=file]');
            
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSize = 10 * 1024 * 1024; // 10MB
                
                if (file.size > maxSize) {
                    e.preventDefault();
                    alert('File size should not exceed 10MB');
                }
            }
        });
    });

    // File input change handler
    const fileInput = document.querySelector('input[type=file]');
    if (fileInput) {
        fileInput.addEventListener('change', previewFile);
    }
});

// Auto-hide message setelah 5 detik
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.message');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });
});