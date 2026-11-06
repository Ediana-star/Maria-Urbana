<script setup>
import { RouterView, useRouter } from 'vue-router'
import AdminSidebar from '../../components/admin/AdminSidebar.vue'
import { ref, onMounted } from 'vue'

const router = useRouter()
const nombreAdmin = ref('Administrador')

// Estado para controlar el menú móvil
const menuMovilAbierto = ref(false)

onMounted(() => {
  const correoGuardado = localStorage.getItem('adminEmail')
  if (correoGuardado) {
    nombreAdmin.value = correoGuardado.split('@')[0]
  }
})

// Función para abrir/cerrar el menú en móviles
const toggleMenu = () => {
  menuMovilAbierto.value = !menuMovilAbierto.value
}

const cerrarSesion = () => {
  localStorage.removeItem('sesionIniciada')
  router.push('/admin/login')
}
</script>

<template>
  <div class="admin-layout">
    
    <!-- Pasamos el estado de apertura y la función para cerrarlo -->
    <AdminSidebar :is-open="menuMovilAbierto" @close="menuMovilAbierto = false" />

    <!-- Fondo oscuro cuando el menú está abierto en móvil -->
    <div 
      v-if="menuMovilAbierto" 
      class="overlay-movil" 
      @click="toggleMenu"
    ></div>

    <div class="admin-bloque-derecho">
      
      <header class="admin-topbar">
        <!-- Botón Hamburguesa (solo visible en móviles) -->
        <button class="btn-menu-movil" @click="toggleMenu">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>

        <div class="topbar-usuario">
          <div class="usuario-perfil">
            <span class="avatar-placeholder">👤</span>
            <span class="usuario-nombre">Hola, {{ nombreAdmin }}</span>
          </div>

          <button class="btn-cerrar-sesion" @click="cerrarSesion">
            Cerrar sesión
          </button>
        </div>
      </header>

      <main class="admin-contenido-dinamico">
        <RouterView />
      </main>

    </div>
  </div>
</template>

<style scoped>
.admin-layout {
  display: flex;
  background-color: #F4EFEA;
  min-height: 100vh;
  position: relative;
}

.admin-bloque-derecho {
  flex-grow: 1;
  padding-left: 260px; /* Margen para la sidebar en desktop */
  display: flex;
  flex-direction: column;
  transition: padding-left 0.3s ease;
}

.admin-topbar {
  background-color: #FFFFFF;
  height: 70px;
  display: flex;
  justify-content: flex-end; /* Por defecto a la derecha */
  align-items: center;
  padding: 0 2rem;
  border-bottom: 1px solid #EAE5DF;
}

/* Botón Hamburguesa Oculto por defecto */
.btn-menu-movil {
  display: none;
  background: none;
  border: none;
  color: #4A3E3D;
  cursor: pointer;
  padding: 0.5rem;
}

.topbar-usuario {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.usuario-perfil {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.avatar-placeholder {
  font-size: 1.2rem;
  background-color: #EFECE8;
  padding: 0.3rem;
  border-radius: 50%;
}

.usuario-nombre {
  font-size: 0.9rem;
  font-weight: 500;
  color: #4A3E3D;
}

.btn-cerrar-sesion {
  background-color: transparent;
  border: 1px solid #DDD7D0;
  color: #6E5941;
  padding: 0.55rem 0.9rem;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-cerrar-sesion:hover {
  background-color: #F3EFEA;
  border-color: #8C7355;
}

.admin-contenido-dinamico {
  padding: 2.5rem;
  flex-grow: 1;
}

/* Oscurece el fondo cuando el menú está abierto en móvil */
.overlay-movil {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 40;
}

/* =========================================
   📱 RESPONSIVE (Tablets y Celulares)
   ========================================= */
@media (max-width: 992px) {
  .admin-bloque-derecho {
    padding-left: 0; /* Quitamos el margen, la sidebar flota arriba */
  }

  .admin-topbar {
    justify-content: space-between; /* Espacio entre hamburguesa y perfil */
    padding: 0 1rem;
  }

  .btn-menu-movil {
    display: block; /* Mostramos el botón hamburguesa */
  }
}

@media (max-width: 480px) {
  .usuario-nombre {
    display: none; /* Ocultamos el nombre para ganar espacio */
  }

  .admin-contenido-dinamico {
    padding: 1.5rem 1rem; /* Menos padding lateral en celulares */
  }

  .topbar-usuario {
    gap: 0.8rem;
  }
}
</style>