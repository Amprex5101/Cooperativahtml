document.addEventListener('DOMContentLoaded', () => {
    const botonesAñadir = document.querySelectorAll('.btn-añadir');

    botonesAñadir.forEach(boton => {
        boton.addEventListener('click', (event) => {
            const producto = event.target.closest('.texto-menu');
            const nombre = producto.querySelector('.nombre').textContent;
            const precio = parseFloat(producto.querySelector('.precio').textContent.replace('$', ''));
            const cantidad = parseInt(producto.querySelector('.input-cantidad').value);
            const tamaño = producto.querySelector('.combo_tamaño').value;
            const imagenSrc = producto.closest('.image_chavindeca').querySelector('img').src;

            const productoCarrito = {
                nombre,
                precio,
                cantidad,
                tamaño,
                imagenSrc
            };

            añadirAlCarrito(productoCarrito);
        });
    });

    const añadirAlCarrito = (producto) => {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        carrito.push(producto);
        localStorage.setItem('carrito', JSON.stringify(carrito));
    };
});
