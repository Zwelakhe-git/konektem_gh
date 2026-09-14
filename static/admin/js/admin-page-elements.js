const customCheckbox = document.querySelector('.checkbox');
if(customCheckbox && customCheckbox.dataset.sz){
    customCheckbox.style.setProperty('--checkkox-sz', `${this.dataset.sz}`);
}
customCheckbox?.addEventListener('click', function(){
    this.classList.toggle('checked');
    document.querySelector('.checkbox ~ #publish-inp').value = Number(this.classList.contains('checked'));
});