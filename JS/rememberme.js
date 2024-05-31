document.addEventListener('DOMContentLoaded', function() {
    var loginForm = document.getElementById('loginForm');
    var rememberMeCheckbox = document.getElementById('rememberMe');
  
    // Cuando se envía el formulario, guarda la información de inicio de sesión si se seleccionó "Remember Me"
    loginForm.addEventListener('submit', function(event) {
      if (rememberMeCheckbox.checked) {
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
  
        localStorage.setItem('rememberedEmail', email);
        localStorage.setItem('rememberedPassword', password);
      } else {
        // Si "Remember Me" no está marcado, elimina cualquier información almacenada previamente
        localStorage.removeItem('rememberedEmail');
        localStorage.removeItem('rememberedPassword');
      }
    });
  
    // Al cargar la página, verifica si hay información de inicio de sesión almacenada localmente y completa automáticamente los campos del formulario
    var rememberedEmail = localStorage.getItem('rememberedEmail');
    var rememberedPassword = localStorage.getItem('rememberedPassword');
    if (rememberedEmail && rememberedPassword) {
      document.getElementById('email').value = rememberedEmail;
      document.getElementById('password').value = rememberedPassword;
    }
  });
  