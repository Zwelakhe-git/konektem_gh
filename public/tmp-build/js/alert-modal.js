// Fonction pour afficher les alertes
function showAlert(message, type) {
    // Supprimer les anciennes alertes
    const oldAlert = document.querySelector('.alert-dynamic');
    if (oldAlert) oldAlert.remove();
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-dynamic`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insérer l'alerte en haut du formulaire
    const form = document.querySelector('.form');
    if (form) {
        form.insertBefore(alertDiv, form.firstChild);
    }
    
    // Auto fermeture après 5 secondes
    setTimeout(() => {
        if (alertDiv) alertDiv.remove();
    }, 5000);
}

export default showAlert;