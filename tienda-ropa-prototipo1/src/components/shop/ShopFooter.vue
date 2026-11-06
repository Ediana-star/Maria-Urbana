<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
  <footer class="shop-footer">
    <!-- Grilla principal de 3 columnas -->
    <div class="footer-grid">
      
      <!-- Columna 1: Marca y descripción -->
      <div class="col-footer brand-col">
        <h3 class="brand-title">María Urbana</h3>
        <p class="brand-desc">
          Tienda de ropa femenina con lo mejor de las tendencias y la comodidad.
        </p>
      </div>

      <!-- Columna 2: Atención al cliente -->
      <div class="col-footer">
        <h4>Atención al cliente</h4>
        <ul class="lista-footer">
          <li>
            <a href="https://wa.me/59898630403" target="_blank" class="icono-link">
              <!-- Ícono SVG WhatsApp -->
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
              </svg>
              WhatsApp
            </a>
          </li>
        </ul>
      </div>

      <!-- Columna 3: Seguinos -->
      <div class="col-footer">
        <h4>Seguinos</h4>
        <ul class="lista-footer">
          <li>
            <a href="https://www.instagram.com/m.urbana1991?igsi=MWJrZXp2eGIxcjE1dg==" target="_blank" class="icono-link">
              <!-- Ícono SVG Instagram -->
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
              </svg>
              Instagram
            </a>
          </li>
        </ul>
      </div>

    </div>

    <!-- Parte inferior: Acceso Admin + Copyright -->
    <div class="footer-bottom">
      <RouterLink to="/admin/login" class="link-admin">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>
        Acceso Administrador
      </RouterLink>
      <p class="copyright">
        © 2026 María Urbana - Todos los derechos reservados.
      </p>
    </div>
  </footer>
</template>

<style scoped>
.shop-footer {
  background-color: #ead3b7; 
  border-top: 1px solid #EAE5DF;
  padding: 4rem 6% 2rem 6%;
  margin-top: 4rem;
  color: #554A46;
}

/* Grilla responsive de 3 columnas */
.footer-grid {
  display: grid;
  /* Le damos 1.5 de espacio a la marca (por el texto) y 1 a las redes */
  grid-template-columns: 1.5fr 1fr 1fr;
  gap: 3rem;
  max-width: 1200px;
  margin: 0 auto 3rem auto;
}

/* Columna de la marca un poco más ancha si hay espacio */
.brand-col {
  padding-right: 2rem;
}

.brand-title {
  font-family: 'Playfair Display', serif; /* Igual al header */
  font-size: 1.6rem;
  font-weight: bold;
  color: #8C7355; /* Mismo color del logo del header */
  margin-bottom: 0.8rem;
}

.brand-desc {
  color: #665B55;
  font-size: 1rem;
  line-height: 1.6;
}

.col-footer h4 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #2C2623;
  margin-bottom: 1.2rem;
}

.lista-footer {
  list-style: none;
  padding: 0;
  margin: 0;
}

.lista-footer li {
  margin-bottom: 0.8rem;
}

.lista-footer a {
  color: #665B55;
  text-decoration: none;
  font-size: 1rem;
  transition: color 0.3s;
}

/* Alineación para los íconos */
.icono-link {
  display: flex;
  align-items: center;
  gap: 0.5rem; /* Separación entre ícono y texto */
}

.lista-footer a:hover {
  color: #8C7355;
}

/* Sección inferior central */
.footer-bottom {
  border-top: 1px solid #D6C2AA; /* Borde un poco más visible */
  padding-top: 1.8rem;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.8rem;
}

.link-admin {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  color: #8C7355;
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  transition: opacity 0.2s;
}

.link-admin:hover {
  opacity: 0.8;
  text-decoration: underline;
}

.copyright {
  font-size: 0.9rem;
  color: #8A7E77;
}

/* Adaptación responsive para celulares y tablets pequeñas */
@media (max-width: 768px) {
  .footer-grid {
    grid-template-columns: 1fr; /* Una sola columna en celulares */
    gap: 2.5rem;
    text-align: center; /* Centramos el texto en móviles */
  }

  .brand-col {
    padding-right: 0;
  }

  .icono-link {
    justify-content: center; /* Centrar ícono y texto en móviles */
  }
}
</style>