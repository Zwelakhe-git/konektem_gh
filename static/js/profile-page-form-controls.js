// Автозакрытие алертов через 5 секунд
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    // кнопки отмена на страницах создания и редактирования
    document.querySelector('.btn.cancel')?.addEventListener('click', (e)=>{
        if(!confirm("Are you sure you want to discard changes?")){
            e.preventDefault();
            return;
        }
    });

    // for editors
    (function(){
        const tinymceEditor = document.querySelector('.tinymce-editor');
        const submitBtn = document.getElementById('submitBtn');

        submitBtn.addEventListener('click', function(){
            if(tinymceEditor){
                const editorId = tinymceEditor.id;
                const inpElementId = tinymceEditor.dataset.inputId;
                const inpElement = document.getElementById(inpElementId);
                if(inpElement){
                    inpElement.value = tinymce ? tinymce.get(editorId).getContent() : tinymceEditor.value;

                    if(inpElement.value.trim().length === 0){
                        showError("Please fill all required fields");
                        return;
                    }
                }
            }
            
            (document.querySelector('.create-form') ?? document.querySelector('.edit-form')).requestSubmit();
        });
        
    })();

    // Инициализация превью для всех file input
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.getAttribute('data-preview');
            if (previewId) {
                previewImage(this, previewId);
            }
        });
    });

    // Динамическое обновление счетчиков символов для textarea
    document.querySelectorAll('textarea[data-max-length]').forEach(textarea => {
        const maxLength = textarea.getAttribute('data-max-length');
        const counterId = textarea.getAttribute('data-counter');
        
        if (counterId) {
            const counter = document.getElementById(counterId);
            if (counter) {
                textarea.addEventListener('input', function() {
                    const remaining = maxLength - this.value.length;
                    counter.textContent = remaining + ' символов осталось';
                    counter.className = remaining < 50 ? 'form-text text-danger' : 'form-text text-muted';
                });
            }
        }
    });
});

// Превью изображений перед загрузкой
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const noPreviewFallback = document.getElementById(input.dataset.noPreviewFallback ?? '');
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.addEventListener('load', function() {
            preview.src = reader.result;
            preview.style.display = 'block';
            preview.classList.remove('d-none');
            noPreviewFallback?.classList.add('d-none');
        });
        
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('d-none');
        noPreviewFallback?.classList.remove('d-none');
    }
}