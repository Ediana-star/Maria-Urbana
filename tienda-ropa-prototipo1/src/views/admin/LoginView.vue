<script setup>
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'

const router = useRouter()

// MAGIA APLICADA: Detecta automáticamente el servidor
const urlServidor = `http://${window.location.hostname}:8000`

// Variables para el Login Normal
const usuario = ref('')
const clave = ref('')
const errorMsg = ref('')

// Variables para la Recuperación
const mostrarRecuperacion = ref(false)
const emailRecuperacion = ref('')
const palabraSecreta = ref('')
const nuevaClave = ref('')
const msjRecuperacion = ref('')
const tipoMsj = ref('error')

const iniciarSesion = async () => {
  errorMsg.value = ''
  if (!usuario.value.trim() || !clave.value.trim()) {
    errorMsg.value = 'Por favor, completá todos los campos.'
    return
  }

  try {
    // Reemplazamos localhost por nuestra variable dinámica
    const respuesta = await fetch(`${urlServidor}/api/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: usuario.value.trim(),
        password: clave.value.trim()
      })
    })

    const datos = await respuesta.json()

    if (respuesta.ok) {
      localStorage.setItem('adminToken', datos.token)
      localStorage.setItem('sesionIniciada', 'true')
      router.push('/admin/pedidos')
    } else {
      errorMsg.value = datos.message || 'Error en el servidor.'
    }
  } catch (error) {
    errorMsg.value = 'No se pudo conectar con el servidor de Laravel.'
  }
}

// Nueva función de recuperación
const ejecutarRecuperacion = async () => {
  if (!emailRecuperacion.value || !palabraSecreta.value || !nuevaClave.value) {
    msjRecuperacion.value = 'Completá todos los campos por favor.'
    tipoMsj.value = 'error'
    return
  }

  if (nuevaClave.value.length < 6) {
    msjRecuperacion.value = 'La nueva contraseña debe tener al menos 6 caracteres.'
    tipoMsj.value = 'error'
    return
  }

  try {
    // Reemplazamos localhost por nuestra variable dinámica
    const respuesta = await fetch(`${urlServidor}/api/recover`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: emailRecuperacion.value.trim(),
        secret_word: palabraSecreta.value.trim(),
        new_password: nuevaClave.value.trim()
      })
    })

    const datos = await respuesta.json()

    if (respuesta.ok) {
      msjRecuperacion.value = datos.message
      tipoMsj.value = 'exito'
      // Esperamos 3 segundos y volvemos al login normal
      setTimeout(() => {
        mostrarRecuperacion.value = false
        usuario.value = emailRecuperacion.value 
        msjRecuperacion.value = ''
      }, 3000)
    } else {
      msjRecuperacion.value = datos.message || 'Error al restablecer.'
      tipoMsj.value = 'error'
    }
  } catch (error) {
    msjRecuperacion.value = 'Fallo de conexión con el servidor.'
    tipoMsj.value = 'error'
  }
}
</script>

<template>
  <div class="login-wrapper">
    <div class="login-card">
      <div class="brand-header">
        <h1 class="logo-text">María Urbana</h1>
        <span class="badge-admin">🔒 Panel de Control</span>
      </div>
      
      <!-- ================= VISTA DE LOGIN NORMAL ================= -->
      <div v-if="!mostrarRecuperacion">
        <p class="instrucciones">
          Ingresá tus credenciales de administrador para gestionar el catálogo.
        </p>

        <form @submit.prevent="iniciarSesion" class="form-admin">
          <div class="form-group">
            <label for="usuario">Usuario (Correo)</label>
            <input id="usuario" type="text" v-model="usuario" placeholder="ejemplo@correo.com" autocomplete="username" />
          </div>

          <div class="form-group">
            <label for="clave">Contraseña</label>
            <input id="clave" type="password" v-model="clave" placeholder="........" autocomplete="current-password" />
          </div>

          <div v-if="errorMsg" class="error-box">
            {{ errorMsg }}
          </div>

          <button type="submit" class="btn-ingresar">Ingresar al Panel</button>
        </form>

        <div class="recuperar-link-box">
          <button type="button" class="btn-text" @click="mostrarRecuperacion = true">
            ¿Olvidaste tu contraseña?
          </button>
        </div>
      </div>

      <!-- ================= VISTA DE RECUPERACIÓN ================= -->
      <div v-else>
        <p class="instrucciones">
          Recuperación segura. Ingresá tu correo y la palabra de seguridad que elegiste al instalar el sistema.
        </p>

        <form @submit.prevent="ejecutarRecuperacion" class="form-admin">
          <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" v-model="emailRecuperacion" placeholder="Tu correo administrador" />
          </div>

          <div class="form-group">
            <label>Palabra de Seguridad</label>
            <input type="text" v-model="palabraSecreta" placeholder="Tu palabra secreta" />
          </div>

          <div class="form-group">
            <label>Nueva Contraseña</label>
            <input type="password" v-model="nuevaClave" placeholder="Mínimo 6 caracteres" />
          </div>

          <div v-if="msjRecuperacion" :class="['error-box', tipoMsj]">
            {{ msjRecuperacion }}
          </div>

          <button type="submit" class="btn-ingresar">Cambiar Contraseña</button>
        </form>

        <div class="recuperar-link-box">
          <button type="button" class="btn-text" @click="mostrarRecuperacion = false">
            ← Volver al inicio de sesión
          </button>
        </div>
      </div>

      <div class="footer-card">
        <RouterLink to="/" class="btn-volver">Volver a la Tienda</RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.login-wrapper { min-height: 100vh; display: flex; justify-content: center; align-items: center; background-color: #FAF8F5; padding: 1.5rem; }
.login-card { background-color: #FFFFFF; border: 1px solid #EAE5DF; border-radius: 12px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03); }
.brand-header { text-align: center; margin-bottom: 0.8rem; }
.logo-text { font-family: 'Playfair Display', Georgia, serif; font-size: 1.8rem; color: #2C2623; margin-bottom: 0.4rem; }
.badge-admin { display: inline-block; background-color: #F3EFEA; color: #8C7355; font-size: 0.75rem; font-weight: 700; padding: 0.3rem 0.8rem; border-radius: 20px; letter-spacing: 0.5px; text-transform: uppercase; }
.instrucciones { text-align: center; color: #7A6E65; font-size: 0.88rem; line-height: 1.4; margin-bottom: 2rem; }
.form-admin { display: flex; flex-direction: column; gap: 1.2rem; }
.form-group { display: flex; flex-direction: column; gap: 0.4rem; }
.form-group label { font-size: 0.85rem; font-weight: 600; color: #4A3E3D; }
.form-group input { padding: 0.8rem 1rem; border: 1px solid #DDD7D0; border-radius: 6px; font-size: 0.95rem; color: #2C2623; background-color: #FAFAFA; outline: none; transition: all 0.2s ease; }
.form-group input:focus { border-color: #8C7355; background-color: #FFFFFF; box-shadow: 0 0 0 3px rgba(140, 115, 85, 0.1); }

/* Cajas de mensajes */
.error-box { padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.85rem; }
.error-box.error { background-color: #FDF2F2; border: 1px solid #F8B4B4; color: #9B1C1C; }
.error-box.exito { background-color: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; }

.btn-ingresar { background-color: #8C7355; color: #FFFFFF; border: none; padding: 0.9rem; border-radius: 6px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: background-color 0.2s ease; margin-top: 0.5rem; }
.btn-ingresar:hover { background-color: #6E5941; }

.recuperar-link-box { text-align: center; margin-top: 1rem; }
.btn-text { background: none; border: none; color: #8C7355; font-size: 0.85rem; font-weight: 600; cursor: pointer; text-decoration: underline; }
.btn-text:hover { color: #6E5941; }

.footer-card { text-align: center; margin-top: 2rem; padding-top: 1.2rem; border-top: 1px solid #F3EFEA; }
.btn-volver { color: #8A7E77; text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: color 0.2s ease; }
.btn-volver:hover { color: #2C2623; }
</style>