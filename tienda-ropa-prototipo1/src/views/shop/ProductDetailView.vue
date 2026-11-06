<script setup>
import { useRoute, RouterLink } from 'vue-router'
import { ref, computed } from 'vue'
import ShopHeader from '../../components/shop/ShopHeader.vue'
import ShopFooter from '../../components/shop/ShopFooter.vue'
import { useTiendaStore } from '../../stores/useTiendaStore'

const route = useRoute()
const store = useTiendaStore()

const productoId = Number(route.params.id)

const producto = computed(() => {
  return store.productos.find(p => p.id === productoId)
})

const talleSeleccionado = ref('')
const cantidadSeleccionada = ref(1)

// Variable para controlar si el zoom de la imagen está abierto o cerrado
const zoomAbierto = ref(false)

const aumentarCantidad = () => {
  // Verificamos que haya producto y que la cantidad no supere el stock disponible
  if (producto.value && cantidadSeleccionada.value < producto.value.stock) {
    cantidadSeleccionada.value++
  } else {
    // Si ya llegamos al límite, mostramos el cartelito
    mostrarAviso('¡Ya alcanzaste el máximo de prendas disponibles!', 'error')
  }
}

const disminuirCantidad = () => {
  if (cantidadSeleccionada.value > 1) {
    cantidadSeleccionada.value--
  }
}

// Variables para controlar la notificación
const mostrarNotificacion = ref(false)
const mensajeNotificacion = ref('')
const tipoNotificacion = ref('exito') // 'exito' o 'error'

// Función auxiliar para mostrar el cartel
const mostrarAviso = (mensaje, tipo) => {
  mensajeNotificacion.value = mensaje
  tipoNotificacion.value = tipo
  mostrarNotificacion.value = true
  
  setTimeout(() => {
    mostrarNotificacion.value = false
  }, 3000)
}

const agregarAlCarritoReal = () => {
  if (!talleSeleccionado.value) {
    mostrarAviso('Por favor, seleccioná un talle antes de agregar al carrito.', 'error')
    return
  }
  
  store.agregarAlCarrito(producto.value, talleSeleccionado.value, cantidadSeleccionada.value)
  mostrarAviso(`¡Agregaste ${cantidadSeleccionada.value} prenda(s) al carrito!`, 'exito')
}
</script>

<template>
  <ShopHeader />
  
  <main class="detalle-container" v-if="producto">
    
    <RouterLink to="/catalogo" class="btn-volver">← Volver al Catálogo</RouterLink>

    <div class="wrapper-producto">
      <!-- Foto con clic para abrir el zoom -->
      <div class="foto-grande" @click="zoomAbierto = true" title="Hacé clic para ampliar">
        <img 
          :src="producto.imagen || 'https://via.placeholder.com/500?text=Sin+Foto'" 
          :alt="producto.nombre"
          class="imagen-detalle"
        />
        <span class="pista-zoom">🔍 Clic para ampliar</span>
      </div>

      <div class="info-compra">
        <span class="categoria">{{ producto.categoria }}</span>
        <h1 class="nombre">{{ producto.nombre }}</h1>
        <p class="precio">${{ producto.precio }}</p>
        <p class="descripcion">{{ producto.descripcion }}</p>

        <div class="controles-compra">
          <div class="seccion-talles">
            <h3>Talles disponibles</h3>
            <div class="lista-talles">
              <button 
                v-for="talle in producto.talles" 
                :key="talle"
                :class="['btn-talle', { activo: talleSeleccionado === talle }]"
                @click="talleSeleccionado = talle"
              >
                {{ talle }}
              </button>
            </div>
          </div>

          <div class="seccion-cantidad">
            <div class="selector-cantidad">
              <button class="btn-cant" @click="disminuirCantidad" :disabled="cantidadSeleccionada === 1">−</button>
              <span class="numero-cant">{{ cantidadSeleccionada }}</span>
              <!-- Aquí está el botón modificado para que se desactive al llegar al límite de stock -->
              <button class="btn-cant" @click="aumentarCantidad" :disabled="cantidadSeleccionada >= producto.stock">+</button>
            </div>
          </div>
        </div>

        <button class="btn-agregar" @click="agregarAlCarritoReal">
          AGREGAR AL CARRITO
        </button>
      </div>
    </div>

  </main>

  <main class="detalle-container error" v-else>
    <h2>Ups... El producto que buscás no existe.</h2>
    <RouterLink to="/catalogo" class="btn-volver">Volver al Catálogo</RouterLink>
  </main>

  <!-- Modal Flotante de Imagen Ampliada -->
  <div v-if="zoomAbierto" class="modal-zoom" @click="zoomAbierto = false">
    <button class="btn-cerrar-zoom" @click="zoomAbierto = false">✕</button>
    <img 
      :src="producto.imagen || 'https://via.placeholder.com/500?text=Sin+Foto'" 
      :alt="producto.nombre" 
      class="imagen-zoom-pantalla"
      @click.stop
    />
  </div>

  <!-- Cartelito flotante dinámico -->
  <div v-if="mostrarNotificacion" :class="['toast-notificacion', tipoNotificacion]">
    <span v-if="tipoNotificacion === 'exito'" class="icono-toast">✓</span>
    <span v-else class="icono-toast">!</span>
    <p>{{ mensajeNotificacion }}</p>
  </div>

  <ShopFooter />
</template>

<style scoped>
.detalle-container {
  padding: 2rem 5%;
  min-height: 70vh;
}

.btn-volver {
  display: inline-block;
  color: #8C7355;
  text-decoration: none;
  font-weight: 500;
  margin-bottom: 2rem;
  transition: color 0.3s;
}

.btn-volver:hover {
  color: #333333;
}

.wrapper-producto {
  display: flex;
  gap: 4rem;
  align-items: flex-start;
}

.foto-grande {
  background-color: #F7F5F0;
  flex: 1;
  height: 500px;
  width: 100%; /* Asegura que tome el espacio adecuado */
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 8px;
  border: 1px solid #EAEAEA;
  overflow: hidden; 
  cursor: zoom-in;
  position: relative;
}

.imagen-detalle {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  display: block;
  transition: transform 0.3s ease;
}

.foto-grande:hover .imagen-detalle {
  transform: scale(1.02);
}

.pista-zoom {
  position: absolute;
  bottom: 12px;
  right: 12px;
  background-color: rgba(255, 255, 255, 0.9);
  color: #555555;
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 500;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  pointer-events: none;
}

.info-compra {
  flex: 1;
  width: 100%; /* Importante para dispositivos móviles */
}

.categoria {
  color: #999999;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 1px;
}

.nombre {
  font-size: 2.5rem;
  color: #333333;
  margin: 0.5rem 0 1rem 0;
}

.precio {
  font-size: 2rem;
  color: #8C7355;
  font-weight: bold;
  margin-bottom: 1.5rem;
}

.descripcion {
  color: #666666;
  line-height: 1.6;
  margin-bottom: 2rem;
}

.controles-compra {
  display: flex;
  align-items: flex-end;
  gap: 2rem;
  margin-bottom: 2.5rem;
}

.seccion-talles h3 {
  font-size: 0.9rem;
  color: #333333;
  margin-bottom: 0.8rem;
}

.lista-talles {
  display: flex;
  flex-wrap: wrap; /* Permite que los botones bajen de línea si no hay espacio */
  gap: 0.8rem;
}

.btn-talle {
  background-color: transparent;
  border: 1px solid #DDDDDD;
  padding: 0.6rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  font-weight: normal;
  transition: all 0.2s;
  min-width: 45px;
}

.btn-talle:hover {
  border-color: #8C7355;
}

.btn-talle.activo {
  border-color: #8C7355;
  background-color: transparent;
  font-weight: bold;
}

.selector-cantidad {
  display: flex;
  align-items: center;
  border: 1px solid #DDDDDD;
  border-radius: 4px;
  padding: 0.2rem;
}

.btn-cant {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  padding: 0.4rem 0.8rem;
  color: #555555;
}

.btn-cant:disabled {
  color: #CCCCCC;
  cursor: not-allowed;
}

.numero-cant {
  padding: 0 1rem;
  font-weight: bold;
  color: #333333;
}

.btn-agregar {
  background-color: #C0955B;
  color: #FFFFFF;
  border: none;
  padding: 1.2rem 2rem;
  width: 100%;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: bold;
  letter-spacing: 1px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-agregar:hover {
  background-color: #A37F4C;
}

.error {
  text-align: center;
  padding-top: 5rem;
}

.modal-zoom {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.85);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2000;
  cursor: zoom-out;
  backdrop-filter: blur(4px);
  animation: fadeIn 0.25s ease-out;
}

.imagen-zoom-pantalla {
  max-width: 90%;
  max-height: 90vh;
  object-fit: contain;
  border-radius: 8px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.5);
  cursor: default;
}

.btn-cerrar-zoom {
  position: absolute;
  top: 20px;
  right: 25px;
  background: transparent;
  border: none;
  color: #FFFFFF;
  font-size: 2rem;
  cursor: pointer;
  opacity: 0.8;
  transition: opacity 0.2s;
}

.btn-cerrar-zoom:hover {
  opacity: 1;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.toast-notificacion {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  padding: 1rem 1.5rem;
  border-radius: 4px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  display: flex;
  align-items: center;
  gap: 1rem;
  z-index: 1000;
  font-weight: 500;
  animation: aparecer 0.3s ease-out;
  background-color: #FDFCF7;
  color: #333333;
}

.toast-notificacion.exito {
  border-left: 5px solid #C0955B; 
}

.toast-notificacion.exito .icono-toast {
  background-color: #C0955B;
}

.toast-notificacion.error {
  border-left: 5px solid #C0392B; 
}

.toast-notificacion.error .icono-toast {
  background-color: #C0392B;
}

.icono-toast {
  color: white;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: bold;
}

@keyframes aparecer {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* =========================================
   NUEVO: ADAPTACIÓN PARA CELULARES
   ========================================= */
@media (max-width: 768px) {
  .wrapper-producto {
    flex-direction: column; /* Apila la imagen arriba y la información abajo */
    gap: 1.5rem; /* Reduce la separación gigante entre la foto y el texto */
  }

  .foto-grande {
    height: 350px; /* Evita que la foto sea excesivamente alta en celular */
  }

  .nombre {
    font-size: 1.8rem; /* Achica el título del producto */
    margin: 0.2rem 0 0.5rem 0;
  }

  .precio {
    font-size: 1.5rem; /* Achica un poco el precio */
    margin-bottom: 1rem;
  }

  .controles-compra {
    flex-direction: column; /* Apila los talles y la cantidad */
    align-items: flex-start;
    gap: 1.5rem;
  }

  .btn-agregar {
    padding: 1rem; /* Ajusta un poco el tamaño del botón para que no sea tan tosco */
  }

  /* Ajustamos el cartelito flotante para que no se salga de la pantalla */
  .toast-notificacion {
    bottom: 1rem;
    right: 1rem;
    left: 1rem;
    justify-content: center;
  }
}
</style>