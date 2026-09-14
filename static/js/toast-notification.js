// toast-notification.js
(function() {
    // Создаем контейнер для уведомлений, если его еще нет
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed';
        document.body.appendChild(container);
    }

    // Функция для отображения уведомления
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-message toast-${type}`;
        
        const textSpan = document.createElement('span');
        textSpan.className = 'toast-text';
        textSpan.textContent = message;
        
        toast.appendChild(textSpan);
        container.appendChild(toast);
        
        // Запускаем анимацию появления
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Удаляем через 5 секунд
        setTimeout(() => {
            hideAndRemove(toast);
        }, 5000);
    };

    function hideAndRemove(element) {
        element.classList.remove('show');
        element.classList.add('hide');
        
        // Удаляем после завершения анимации
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }

    // Оборачиваем для удобства
    window.showError = function(message) {
        showToast(message, 'error');
    };
    
    window.showWarning = function(message) {
        showToast(message, 'warning');
    };
    
    window.showSuccess = function(message) {
        showToast(message, 'success');
    };
})();