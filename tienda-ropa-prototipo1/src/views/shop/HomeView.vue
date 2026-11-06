<script setup>
import { RouterLink } from 'vue-router'
import ShopHeader from '../../components/shop/ShopHeader.vue'
import ShopFooter from '../../components/shop/ShopFooter.vue'
import { useTiendaStore } from '../../stores/useTiendaStore'

// --- Importaciones para el Carrusel Swiper ---
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, EffectFade } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/effect-fade'

// --- Importación de imágenes del banner ---
import img1 from '@/assets/images/lentes.jpeg'
import img2 from '@/assets/images/vestido.jpeg'
import img3 from '@/assets/images/unias.jpeg'

const store = useTiendaStore()

const swiperModules = [Autoplay, EffectFade]

// Categorías destacadas con sus nombres EXACTOS y una foto representativa
const categoriasDestacadas = [
  { nombre: 'Remeras y Camisas', imagen: '/imagenes/camisa-hombre.jpeg' },
  { nombre: 'Camperas', imagen: '/imagenes/campera-mujer.jpeg' },
  { nombre: 'Pantalones', imagen: '/imagenes/jean-mujer.jpeg' },
  { nombre: 'Vestidos y Enteritos', imagen: '/imagenes/vestido.jpeg' },
  { nombre: 'Accesorios', imagen: '/imagenes/reloj-negro.jpeg' }
]
</script>

<template>
  <ShopHeader />

  <main class="home-container">
    
    <!-- 🌟 1. BANNER PRINCIPAL -->
    <section class="hero-banner-slider">
      <swiper
        :modules="swiperModules"
        :slides-per-view="1"
        effect="fade"
        :fade-effect="{ crossFade: true }"
        :speed="300"
        :loop="true"
        :autoplay="{ delay: 4500, disableOnInteraction: false }"
        class="mySwiper"
      >
        <swiper-slide>
          <div class="slide-content" :style="{ backgroundImage: `url(${img1})` }">
            <div class="hero-contenido">
              <span class="hero-subtitulo">Nueva Temporada 2026</span>
              <h1 class="hero-titulo">Sencillez, tendencia y comodidad.</h1>
              <p class="hero-descripcion">
                Diseños pensados para acompañar tu día a día con estilo propio. Descubrí prendas exclusivas hechas para vos.
              </p>
              <RouterLink to="/catalogo" class="btn-hero-cta">
                Explorar Colección
              </RouterLink>
            </div>
          </div>
        </swiper-slide>

        <swiper-slide>
          <div class="slide-content" :style="{ backgroundImage: `url(${img2})` }">
            <div class="hero-contenido">
              <span class="hero-subtitulo">Colección Urbana</span>
              <h1 class="hero-titulo">Elegancia en cada paso.</h1>
              <p class="hero-descripcion">
                Renová tu armario con tonos cálidos y texturas suaves que marcan la diferencia.
              </p>
              <RouterLink to="/catalogo" class="btn-hero-cta">
                Explorar Colección
              </RouterLink>
            </div>
          </div>
        </swiper-slide>

        <swiper-slide>
          <div class="slide-content" :style="{ backgroundImage: `url(${img3})` }">
            <div class="hero-contenido">
              <span class="hero-subtitulo">Accesorios Destacados</span>
              <h1 class="hero-titulo">El toque final perfecto.</h1>
              <p class="hero-descripcion">
                Completá tu look con detalles que resaltan tu personalidad vayas donde vayas.
              </p>
              <RouterLink to="/catalogo" class="btn-hero-cta">
                Explorar Colección
              </RouterLink>
            </div>
          </div>
        </swiper-slide>
      </swiper>
    </section>

    <!-- 🏷️ 2. SECCIÓN DE CATEGORÍAS -->
    <section class="seccion-categorias">
      <h2 class="titulo-seccion">Comprá por Categoría</h2>
      <p class="subtitulo-seccion">Encontrá exactamente lo que estás buscando</p>
      
      <div class="grilla-categorias-home">
        <RouterLink 
          v-for="cat in categoriasDestacadas" 
          :key="cat.nombre"
          :to="{ path: '/catalogo', query: { categoria: cat.nombre } }"
          class="tarjeta-cat-home"
        >
          <img :src="cat.imagen" :alt="cat.nombre" class="cat-imagen-bg" />
          <div class="cat-overlay">
            <h3>{{ cat.nombre }}</h3>
            <span class="link-cat">Ver colección →</span>
          </div>
        </RouterLink>
      </div>
    </section>

    <!-- 🍂 3. SOBRE LA MARCA -->
    <section class="sobre-nosotros">
      <div class="bloque-texto-marca">
        <h2>Sobre María Urbana</h2>
        <p>
          Nacimos con el deseo de fusionar la comodidad urbana con un toque de elegancia clásica. Creemos en la moda lenta, en las prendas duraderas y en que vestirse bien todos los días debería ser un proceso simple y disfrutable.
        </p>
      </div>
    </section>

  </main>

  <ShopFooter />
</template>

<style scoped>
.home-container {
  min-height: 80vh;
}

/* --- Banner Principal (Desktop) --- */
.hero-banner-slider {
  height: 550px;
  background-color: #F3EFE9;
  position: relative;
}

.mySwiper {
  width: 100%;
  height: 100%;
}

.slide-content {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center 15%;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding: 0 5%;
  position: relative;
}

.slide-content::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to right, rgba(243, 239, 233, 0.95) 0%, rgba(243, 239, 233, 0.6) 45%, rgba(243, 239, 233, 0) 100%);
  z-index: 1;
}

.hero-contenido {
  max-width: 600px;
  position: relative;
  z-index: 2;
}

.swiper-fade .swiper-slide {
  transition-duration: 1200ms !important;
}

.hero-subtitulo {
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 2px;
  color: #8C7355;
  font-weight: bold;
}

.hero-titulo {
  font-size: 3rem;
  color: #333333;
  margin: 1rem 0;
  line-height: 1.2;
  font-family: 'Playfair Display', serif;
}

.hero-descripcion {
  color: #555555;
  font-size: 1.1rem;
  line-height: 1.6;
  margin-bottom: 2.5rem;
}

.btn-hero-cta {
  display: inline-block;
  background-color: #8C7355;
  color: #FFFFFF;
  text-decoration: none;
  padding: 1rem 2.5rem;
  border-radius: 4px;
  font-weight: bold;
  text-transform: uppercase;
  font-size: 0.9rem;
  transition: background-color 0.3s;
}

.btn-hero-cta:hover {
  background-color: #6E5941;
}

/* --- Categorías (Desktop) --- */
.seccion-categorias {
  padding: 5rem 5%;
  text-align: center;
}

.titulo-seccion {
  font-size: 2rem;
  color: #333333;
  margin-bottom: 0.5rem;
  font-family: 'Playfair Display', serif;
}

.subtitulo-seccion {
  color: #888888;
  margin-bottom: 3rem;
}

.grilla-categorias-home {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}

.tarjeta-cat-home {
  position: relative;
  height: 300px;
  border-radius: 8px;
  overflow: hidden;
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.tarjeta-cat-home:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.cat-imagen-bg {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.tarjeta-cat-home:hover .cat-imagen-bg {
  transform: scale(1.08);
}

.cat-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.1) 65%);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 1.5rem 1rem;
  text-align: center;
  transition: background 0.3s ease;
}

.tarjeta-cat-home:hover .cat-overlay {
  background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.2) 65%);
}

.cat-overlay h3 {
  font-size: 1.3rem;
  color: #FFFFFF;
  margin-bottom: 0.3rem;
  font-family: 'Playfair Display', serif;
}

.link-cat {
  color: #F3EFE9;
  font-size: 0.85rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* --- Sobre la marca (Desktop) --- */
.sobre-nosotros {
  background-color: #FFFFFF;
  padding: 6rem 5%;
  display: flex;
  justify-content: center;
  border-top: 1px solid #F0F0F0;
}

.bloque-texto-marca {
  max-width: 700px;
  text-align: center;
}

.bloque-texto-marca h2 {
  font-size: 1.8rem;
  color: #333333;
  margin-bottom: 1.5rem;
  font-family: 'Playfair Display', serif;
}

.bloque-texto-marca p {
  color: #666666;
  line-height: 1.8;
  font-size: 1.1rem;
}

/* ========================================================
   📱 ADAPTACIÓN RESPONSIVA PARA CELULARES Y TABLETS
   ======================================================== */
@media (max-width: 768px) {
  /* Banner Principal */
  .hero-banner-slider {
    height: 460px;
  }

  .slide-content {
    justify-content: center;
    text-align: center;
    padding: 0 1.2rem;
  }

  .slide-content::before {
    background: linear-gradient(to top, rgba(243, 239, 233, 0.95) 15%, rgba(243, 239, 233, 0.75) 100%);
  }

  .hero-subtitulo {
    font-size: 0.75rem;
    letter-spacing: 1.5px;
  }

  .hero-titulo {
    font-size: 1.8rem;
    margin: 0.6rem 0;
  }

  .hero-descripcion {
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
  }

  .btn-hero-cta {
    padding: 0.8rem 1.8rem;
    font-size: 0.8rem;
    width: 100%;
    max-width: 250px;
  }

  /* Categorías en 2 columnas estilo app de moda */
  .seccion-categorias {
    padding: 3rem 1rem;
  }

  .titulo-seccion {
    font-size: 1.5rem;
  }

  .subtitulo-seccion {
    font-size: 0.9rem;
    margin-bottom: 1.8rem;
  }

  .grilla-categorias-home {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.8rem;
  }

  .tarjeta-cat-home {
    height: 180px;
  }

  .cat-overlay {
    padding: 1rem 0.6rem;
  }

  .cat-overlay h3 {
    font-size: 1rem;
  }

  .link-cat {
    font-size: 0.75rem;
  }

  /* Sobre Nosotros */
  .sobre-nosotros {
    padding: 3rem 1.2rem;
  }

  .bloque-texto-marca h2 {
    font-size: 1.4rem;
    margin-bottom: 1rem;
  }

  .bloque-texto-marca p {
    font-size: 0.95rem;
    line-height: 1.6;
  }
}

@media (max-width: 480px) {
  .hero-banner-slider {
    height: 420px;
  }

  .hero-titulo {
    font-size: 1.5rem;
  }

  .grilla-categorias-home {
    gap: 0.6rem;
  }

  .tarjeta-cat-home {
    height: 160px;
  }
}
</style>