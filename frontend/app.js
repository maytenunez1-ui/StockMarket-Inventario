document.getElementById('loginForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const mensajeDiv = document.getElementById('mensaje');

  mensajeDiv.className = 'mensaje';
  mensajeDiv.innerText = 'Autenticando...';

  try {
    const response = await fetch('/api/auth/usuarios/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ email, password })
    });

    const data = await response.json();

    if (response.ok) {
      mensajeDiv.className = 'mensaje exito';
      mensajeDiv.innerText = `¡Bienvenido, ${data.usuario.nombre}! Redirigiendo...`;

      // 1. Guardamos Token y datos del Usuario
      localStorage.setItem('token', data.token);
      localStorage.setItem('usuario', JSON.stringify(data.usuario));

      // 2. Redirigimos al Dashboard después de 1 segundo
      setTimeout(() => {
        window.location.href = 'dashboard.html';
      }, 1000);

    } else {
      mensajeDiv.className = 'mensaje error';
      mensajeDiv.innerText = data.error || 'Credenciales incorrectas';
    }

  } catch (error) {
    mensajeDiv.className = 'mensaje error';
    mensajeDiv.innerText = 'Error al conectar con ms-auth (Puerto 3001)';
  }
});
