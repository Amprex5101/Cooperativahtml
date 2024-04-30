document.addEventListener("DOMContentLoaded", function() {
  const menuContainer = document.getElementById("menu-container");

  // Cargar el contenido del menú hamburguesa desde 'Navegacion.html'
  fetch("Navegacion.html")
    .then(response => response.text())
    .then(data => {
      // Insertar el menú en el contenedor
      menuContainer.innerHTML = data;

      // Agregar los eventos de apertura y cierre del menú después de que se haya cargado
      agregarEventosNavegacion();
    })
    .catch(error => console.error("Error al cargar el menú:", error));
});

function agregarEventosNavegacion() {
  const columnas = document.querySelector("#columnas");
  const abrir = document.querySelector("#abrir");
  const cerrar = document.querySelector("#cerrar");

  if (abrir && cerrar && columnas) {
    abrir.addEventListener("click", () => {
      columnas.classList.add("visible");
    });

    cerrar.addEventListener("click", () => {
      columnas.classList.remove("visible");
    });
  } else {
    console.error("No se encontraron los elementos del menú");
  }
}