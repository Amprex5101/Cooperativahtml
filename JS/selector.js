document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.combo_tamaño').forEach(select => {
        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const priceElement = this.closest('.texto-menu').querySelector('.precio');
            priceElement.textContent = '$' + selectedOption.getAttribute('data-precio');
        });
    });
});