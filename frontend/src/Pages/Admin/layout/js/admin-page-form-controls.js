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
    handleCheckBox();
});
// Подтверждение удаления
function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return confirm(message);
}

// Превью изображений перед загрузкой
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.addEventListener('load', function() {
            preview.src = reader.result;
            preview.style.display = 'block';
            preview.classList.remove('d-none');
        });
        
        reader.readAsDataURL(file);
    }
}

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
function handleCheckBox(){
    let check_label = document.querySelector('label.check');
    if(check_label){
        let check_inp = check_label.querySelector("input");
        if(check_inp){
            const selectContainer = check_label.parentElement.querySelector('div');
            const selectField = selectContainer.querySelector('select');
            if(!selectContainer || !selectField) return;

            check_inp.addEventListener("change", ()=>{
                if(check_inp && check_inp.checked){
                    selectContainer.style.display = 'block';
                    selectField.disabled = false;
                } else {
                    selectContainer.style.display = 'none';
                    selectField.disabled = true;
                }
            })
        } else {
            console.log('')
        }
    } else {
        console.log('')
    }
}
