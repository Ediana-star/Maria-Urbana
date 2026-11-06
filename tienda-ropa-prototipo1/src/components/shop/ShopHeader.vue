<script setup>
import { RouterLink } from 'vue-router'
import { useTiendaStore } from '../../stores/useTiendaStore'

const store = useTiendaStore()
</script>

<template>
  <header class="shop-header">
    <!-- Logo de la tienda -->
    <div class="logo">
      <RouterLink to="/">María Urbana</RouterLink>
    </div>

    <!-- Menú de navegación central con separadores -->
    <nav class="menu">
      <RouterLink to="/" active-class="activo">Inicio</RouterLink>
      <span class="separador">|</span>
      
      <RouterLink to="/catalogo" active-class="activo">Catálogo</RouterLink>
      <span class="separador">|</span>
      
      <RouterLink to="/tabla-de-talles" active-class="activo">Tabla de talles</RouterLink>
    </nav>

    <!-- Ícono del carrito con Badge estilo App -->
    <div class="cart-icon">
      <RouterLink to="/carrito" class="btn-carrito">
        <!-- Ícono SVG de Carrito -->
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        <span class="texto-carrito">Carrito</span>
        
        <span v-if="store.totalArticulos > 0" class="contador-badge">
          {{ store.totalArticulos }}
        </span>
      </RouterLink>
    </div>
  </header>
</template>

<style scoped>
.shop-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.2rem 5%;
  background-color: #ead3b7;
  border-bottom: 1px solid #D6C2AA; /* Unificado con el borde del footer */
  position: sticky;
  top: 0;
  z-index: 100;
}

.logo a {
  font-size: 1.6rem; /* Aumentado para coincidir con el footer */
  font-weight: bold;
  color: #8C7355;
  text-decoration: none;
  font-family: 'Playfair Display', serif;
}

.menu {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.menu a {
  text-decoration: none;
  color: #554A46; /* Tonalidad igual al texto del footer */
  font-weight: 500;
  transition: color 0.3s;
  padding-bottom: 3px;
  font-size: 1rem; /* Aumentado a 1rem */
}

.menu a:hover {
  color: #8C7355;
}

/* 🌟 Estilo para la pestaña activa */
.menu a.activo {
  color: #8C7355;
  font-weight: 600;
  border-bottom: 2px solid #8C7355;
}

/* 🌟 Separadores de la barra de navegación */
.separador {
  color: #c9b195;
  font-size: 1rem;
  user-select: none;
}

/* 🛒 Carrito y Contador Badge */
.btn-carrito {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  text-decoration: none;
  color: #554A46; /* Unificado con el menú */
  font-weight: 500;
  font-size: 1rem; /* Aumentado */
  transition: color 0.3s;
  position: relative;
}

.btn-carrito:hover {
  color: #8C7355;
}

.texto-carrito {
  font-size: 1rem;
}

.contador-badge {
  background-color: #c0392b;
  color: #ffffff;
  font-size: 0.75rem;
  font-weight: bold;
  padding: 0.15rem 0.45rem;
  border-radius: 10px;
  line-height: 1;
}

/* ========================================================
   📱 ADAPTACIÓN RESPONSIVA PARA CELULARES Y TABLETS
   ======================================================== */
@media (max-width: 768px) {
  .shop-header {
    flex-wrap: wrap;
    padding: 0.8rem 1rem;
    row-gap: 0.6rem;
  }

  .logo a {
    font-size: 1.4rem; /* Un poco más grande en móviles también */
  }

  .menu {
    order: 3;
    width: 100%;
    justify-content: center;
    gap: 1.2rem;
    padding-top: 0.5rem;
    border-top: 1px solid rgba(140, 115, 85, 0.2);
    overflow-x: auto;
    white-space: nowrap;
  }

  /* Ocultar separadores en móvil para ganar espacio */
  .separador {
    display: none;
  }

  .menu a {
    font-size: 0.95rem; /* Mejor lectura en móvil */
  }

  .texto-carrito {
    font-size: 0.95rem;
  }
}

@media (max-width: 480px) {
  .menu {
    gap: 0.9rem;
  }

  .menu a {
    font-size: 0.9rem;
  }
}
</style>