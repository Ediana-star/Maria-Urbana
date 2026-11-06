<script setup>
import { RouterLink } from 'vue-router'

// Recibimos si el menú debe estar abierto (en móviles)
defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

// Emitimos un evento para avisarle a la vista padre que cierre el menú
const emit = defineEmits(['close'])

const cerrarMenu = () => {
  emit('close')
}
</script>

<template>
  <!-- La clase .sidebar-abierta se agrega cuando en móvil se toca el menú -->
  <aside class="admin-sidebar" :class="{ 'sidebar-abierta': isOpen }">
    
    <!-- Encabezado del Panel -->
    <div class="sidebar-header">
      <h2>María Urbana</h2>
      <span class="rol-tag">Panel de Control</span>

      <!-- Botón X para cerrar en móvil -->
      <button class="btn-cerrar-sidebar" @click="cerrarMenu">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>

    <!-- Menú de Navegación Interno -->
    <nav class="sidebar-menu">
      <!-- Al hacer clic en un link, cerramos el menú automáticamente en móviles -->
      <RouterLink to="/admin/pedidos" class="menu-item" active-class="activo" @click="cerrarMenu">
        📋 Gestión de Pedidos
      </RouterLink>

      <RouterLink to="/admin/catalogo" class="menu-item" active-class="activo" @click="cerrarMenu">
        📦 Catálogo de Productos
      </RouterLink>
    </nav>

    <!-- Botón de Salida al pie de la barra -->
    <div class="sidebar-footer">
      <RouterLink to="/" class="btn-volver-tienda">
        🚪 Volver a la Tienda
      </RouterLink>
    </div>
  </aside>
</template>

<style scoped>
.admin-sidebar {
  width: 260px;
  height: 100vh;
  background-color: #4A3B2C;
  color: #F7F5F0;
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 0;
  left: 0;
  box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
  z-index: 50; /* Z-index alto para quedar arriba de todo en móvil */
  transition: transform 0.3s ease; /* Animación de apertura */
}

.sidebar-header {
  padding: 2rem 1.5rem;
  border-bottom: 1px solid #5C4A37;
  text-align: center;
  position: relative;
}

.sidebar-header h2 {
  font-size: 1.4rem;
  margin: 0;
  color: #FFFFFF;
  letter-spacing: 1px;
}

.rol-tag {
  display: inline-block;
  font-size: 0.75rem;
  background-color: #8C7355;
  color: #FFFFFF;
  padding: 0.2rem 0.6rem;
  border-radius: 10px;
  margin-top: 0.5rem;
  font-weight: bold;
}

/* Botón cerrar X oculto en Desktop */
.btn-cerrar-sidebar {
  display: none;
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: none;
  border: none;
  color: #FFFFFF;
  cursor: pointer;
}

.sidebar-menu {
  flex-grow: 1;
  padding: 2rem 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  overflow-y: auto; /* Si hay muchos ítems, se puede scrollear */
}

.menu-item {
  display: flex;
  align-items: center;
  color: #D1C7BD;
  text-decoration: none;
  padding: 0.8rem 1rem;
  border-radius: 6px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.menu-item:hover {
  background-color: #5C4A37;
  color: #FFFFFF;
}

.menu-item.activo {
  background-color: #8C7355;
  color: #FFFFFF;
  font-weight: bold;
}

.sidebar-footer {
  padding: 1.5rem;
  border-top: 1px solid #5C4A37;
}

.btn-volver-tienda {
  display: block;
  text-align: center;
  background-color: transparent;
  border: 1px solid #8C7355;
  color: #FFFFFF;
  text-decoration: none;
  padding: 0.7rem;
  border-radius: 4px;
  font-size: 0.9rem;
  font-weight: bold;
  transition: all 0.3s;
}

.btn-volver-tienda:hover {
  background-color: #8C7355;
}

/* =========================================
   📱 RESPONSIVE (Tablets y Celulares)
   ========================================= */
@media (max-width: 992px) {
  .admin-sidebar {
    /* Escondemos la barra hacia la izquierda fuera de la pantalla */
    transform: translateX(-100%); 
  }

  /* Cuando Vue le aplica esta clase (porque isOpen es true), la barra entra */
  .admin-sidebar.sidebar-abierta {
    transform: translateX(0);
  }

  .btn-cerrar-sidebar {
    display: block; /* Mostramos la X en celulares */
  }
}
</style>