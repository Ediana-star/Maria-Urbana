<script setup>
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import ShopHeader from '../../components/shop/ShopHeader.vue'
import ShopFooter from '../../components/shop/ShopFooter.vue'
import { useTiendaStore } from '../../stores/useTiendaStore'

const store = useTiendaStore()

// Usamos un computed local para calcular el precio total a pagar en tiempo real
const totalPagar = computed(() => {
  return store.carrito.reduce((suma, item) => suma + (item.precio * item.cantidad), 0)
})

// Variables para controlar la notificación
const mostrarNotificacion = ref(false)
const mensajeNotificacion = ref('')
const tipoNotificacion = ref('error') // 'exito' o 'error'

// Función auxiliar para mostrar el cartel
const mostrarAviso = (mensaje, tipo = 'error') => {
  mensajeNotificacion.value = mensaje
  tipoNotificacion.value = tipo
  mostrarNotificacion.value = true
  
  setTimeout(() => {
    mostrarNotificacion.value = false
  }, 3000)
}

// Función para eliminar producto y mostrar la notificación
const eliminarProducto = (item) => {
  store.eliminarDelCarrito(item.id, item.talle)
  mostrarAviso(`Quitaste "${item.nombre}" del carrito`, 'error')
}
</script>

<template>
  <ShopHeader />

  <main class="carrito-container">
    <h1 class="titulo-carrito">Tu Carrito</h1>

    <div v-if="store.carrito.length === 0" class="carrito-vacio">
      <p>Tu carrito está vacío</p>
      <p class="subtexto">¿No sabés por dónde empezar? ¡Mirá nuestras prendas exclusivas!</p>
      <RouterLink to="/catalogo" class="btn-ir-catalogo">Ir al Catálogo</RouterLink>
    </div>

    <div v-else class="wrapper-carrito">
      
      <div class="lista-items">
        <div class="item-carrito" v-for="item in store.carrito" :key="item.id + item.talle">
          
          <div class="item-mini">
            <img 
              :src="item.imagen || item.producto?.imagen || 'https://via.placeholder.com/100?text=Sin+Foto'" 
              :alt="item.nombre"
              class="foto-mini"
            />
          </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         

          <div class="item-detalles">
            <span class="item-cat">{{ item.categoria }}</span>
            <h3>{{ item.nombre }}</h3>
            <p class="item-talle">Talle: <strong>{{ item.talle }}</strong></p>
          </div>

          <div class="item-cantidad">
            <button 
               @click="item.cantidad > 1 ? item.cantidad-- : null" 
               :disabled="item.cantidad === 1"
               class="btn-cant"
            >
              -
            </button>
            <span class="numero-cant">{{ item.cantidad }}</span>
            <button 
              @click="item.cantidad++" 
              class="btn-cant"
            >
              +
            </button>
          </div>

          <div class="item-subtotal">
            <p>${{ item.precio * item.cantidad }}</p>
          </div>

          <button 
            @click="eliminarProducto(item)" 
            class="btn-eliminar"
            title="Eliminar producto"
          >
            ✕
          </button>
        </div>
      </div>

      <div class="resumen-compra">
        <h3>Resumen del Pedido</h3>
        
        <div class="fila-resumen">
          <span>Productos ({{ store.totalArticulos }})</span>
          <span>${{ totalPagar }}</span>
        </div>
        
        <div class="fila-resumen envio">
          <span>Envío</span>
          <span class="gratis">Gratis</span>
        </div>

        <div class="fila-resumen total">
          <span>Total</span>
          <span>${{ totalPagar }}</span>
        </div>

        <RouterLink to="/checkout" class="btn-proceder">
          Finalizar Compra
        </RouterLink>
      </div>

    </div>
  </main>

  <!-- Cartelito flotante dinámico -->
  <div v-if="mostrarNotificacion" :class="['toast-notificacion', tipoNotificacion]">
    <span v-if="tipoNotificacion === 'exito'" class="icono-toast">✓</span>
    <span v-else class="icono-toast">!</span>
    <p>{{ mensajeNotificacion }}</p>
  </div>

  <ShopFooter />
</template>

<style scoped>
/* =========================================
   ESTILOS ORIGINALES (Computadora)
   ========================================= */
.carrito-container {
  padding: 2rem 5%;
  min-height: 65vh;
}

.titulo-carrito {
  color: #333333;
  margin-bottom: 2.5rem;
  font-size: 1.8rem;
}

/* Estilos de Carrito Vacío */
.carrito-vacio {
  text-align: center;
  padding: 4rem 2rem;
  background-color: #FFFFFF;
  border: 1px solid #EAEAEA;
  border-radius: 8px;
}

.carrito-vacio p {
  font-size: 1.3rem;
  color: #555555;
}

.carrito-vacio .subtexto {
  font-size: 1rem;
  color: #999999;
  margin: 0.5rem 0 2rem 0;
}

.btn-ir-catalogo {
  display: inline-block;
  background-color: #8C7355;
  color: #FFFFFF;
  text-decoration: none;
  padding: 0.8rem 2rem;
  border-radius: 4px;
  font-weight: bold;
  transition: background-color 0.3s;
}

.btn-ir-catalogo:hover {
  background-color: #6E5941;
}

/* Distribución del Carrito Lleno */
.wrapper-carrito {
  display: flex;
  gap: 2.5rem;
  align-items: flex-start;
}

.lista-items {
  flex: 2;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.item-carrito {
  display: flex;
  align-items: center;
  background-color: #FFFFFF;
  border: 1px solid #EAEAEA;
  padding: 1.5rem;
  border-radius: 8px;
  gap: 1.5rem;
}

.item-mini {
  background-color: #F7F5F0;
  width: 70px;
  height: 70px;
  border-radius: 6px;
  border: 1px solid #EAEAEA;
  overflow: hidden; 
  flex-shrink: 0;   
}

.foto-mini {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  display: block;
}

.item-detalles {
  flex: 2;
}

.item-cat {
  font-size: 0.75rem;
  color: #999999;
  text-transform: uppercase;
}

.item-detalles h3 {
  font-size: 1.1rem;
  color: #333333;
  margin: 0.2rem 0;
}

.item-talle {
  font-size: 0.9rem;
  color: #666666;
}

.item-cantidad {
  display: flex;
  align-items: center;
  border: 1px solid #DDDDDD;
  border-radius: 4px;
  overflow: hidden;
}

.btn-cant {
  background-color: #FFFFFF;
  border: none;
  width: 32px;
  height: 32px;
  cursor: pointer;
  font-size: 1.1rem;
  font-weight: bold;
  color: #555555;
  transition: background-color 0.2s;
}

.btn-cant:hover:not(:disabled) {
  background-color: #F0F0F0;
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

.item-subtotal {
  flex: 1;
  text-align: right;
  font-size: 1.2rem;
  font-weight: bold;
  color: #8C7355;
}

.btn-eliminar {
  background: none;
  border: none;
  font-size: 1.1rem;
  cursor: pointer;
  padding: 0.5rem;
  color: #333333;
  transition: transform 0.2s, color 0.2s;
}

.btn-eliminar:hover {
  transform: scale(1.15);
  color: #C0392B;
}

.resumen-compra {
  flex: 1;
  background-color: #FFFFFF;
  border: 1px solid #EAEAEA;
  padding: 2rem;
  border-radius: 8px;
}

.resumen-compra h3 {
  font-size: 1.3rem;
  color: #333333;
  border-bottom: 1px solid #F0F0F0;
  padding-bottom: 1rem;
  margin-bottom: 1.5rem;
}

.fila-resumen {
  display: flex;
  justify-content: space-between;
  color: #666666;
  margin-bottom: 1rem;
}

.envio .gratis {
  color: #2e7d32;
  font-weight: bold;
}

.total {
  border-top: 1px solid #F0F0F0;
  padding-top: 1rem;
  margin-top: 1.5rem;
  font-size: 1.3rem;
  font-weight: bold;
  color: #333333;
}

.total span:last-child {
  color: #8C7355;
}

.btn-proceder {
  display: block;
  text-align: center;
  background-color: #C0955B;
  color: #FFFFFF;
  text-decoration: none;
  padding: 1rem;
  margin-top: 2rem;
  border-radius: 4px;
  font-weight: bold;
  text-transform: uppercase;
  font-size: 0.95rem;
  transition: background-color 0.3s;
}

.btn-proceder:hover {
  background-color: #A37F4C;
}

/* Estilos de la notificación flotante */
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
   NUEVO: ESTILOS RESPONSIVOS (Celulares)
   ========================================= */
@media (max-width: 768px) {
  
  /* 1. Apilamos la lista de productos y el resumen de compra */
  .wrapper-carrito {
    flex-direction: column;
    gap: 1.5rem;
  }

  /* 2. Hacemos que ocupen todo el ancho de la pantalla */
  .lista-items, .resumen-compra {
    width: 100%;
    box-sizing: border-box;
  }

  /* 3. Reorganizamos cada producto individual */
  .item-carrito {
    flex-wrap: wrap; /* Permite que los elementos bajen de línea si no caben */
    position: relative; /* Para poder ubicar la "X" libremente */
    padding: 1rem;
  }

  /* 4. La imagen y los detalles se quedan arriba */
  .item-detalles {
    flex: 1 1 50%;
    padding-right: 2rem; /* Deja espacio para que el texto no pise la "X" */
  }

  /* 5. Ubicamos la "X" (eliminar) arriba a la derecha */
  .btn-eliminar {
    position: absolute;
    top: 1rem;
    right: 1rem;
  }

  /* 6. Los botones de cantidad y el precio bajan a una nueva "fila" */
  .item-cantidad {
    margin-top: 1rem;
  }

  .item-subtotal {
    margin-top: 1rem;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: flex-end; /* Empuja el precio hacia la derecha */
  }

  /* 7. Centramos el cartelito de notificación en celulares */
  .toast-notificacion {
    right: 1rem;
    left: 1rem;
    bottom: 1rem;
    justify-content: center;
  }
}
</style>