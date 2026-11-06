<script setup>
import { ref, computed } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import ShopHeader from '../../components/shop/ShopHeader.vue'
import ShopFooter from '../../components/shop/ShopFooter.vue'
import { useTiendaStore } from '../../stores/useTiendaStore'

const store = useTiendaStore()
const router = useRouter()
// MAGIA APLICADA: Detecta automáticamente el servidor
const urlServidor = `http://${window.location.hostname}:8000`

const nombre = ref('')
const telefono = ref('')
const direccion = ref('')

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

const confirmarPedido = async () => {
  // 1. Saneamiento: Eliminar espacios al inicio/final y colapsar múltiples espacios internos a uno solo
  const nombreLimpio = nombre.value.trim().replace(/\s+/g, ' ')
  // Remover espacios, guiones y paréntesis del teléfono para validar sólo los dígitos numéricos
  const telefonoLimpio = telefono.value.trim().replace(/[\s\-\(\)]/g, '')
  const direccionLimpia = direccion.value.trim().replace(/\s+/g, ' ')

  // 2. Validación de campos vacíos
  if (!nombreLimpio || !telefonoLimpio || !direccionLimpia) {
    mostrarAviso('Por favor, completá todos los campos obligatorios (*) para el envío.', 'error')
    return
  }

  // 3. Validación de Nombre
  const regexNombre = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{3,60}$/
  if (!regexNombre.test(nombreLimpio)) {
    mostrarAviso('Ingresá un nombre válido (sólo letras, mínimo 3 caracteres).', 'error')
    return
  }

  // 4. Validación de Teléfono 
  const regexTelefono = /^\+?[0-9]{8,15}$/
  if (!regexTelefono.test(telefonoLimpio)) {
    mostrarAviso('Ingresá un número de teléfono válido (sólo números, entre 8 y 15 dígitos).', 'error')
    return
  }

  // 5. Validación de Dirección 
  if (direccionLimpia.length < 5) {
    mostrarAviso('Ingresá una dirección de envío más específica (mínimo 5 caracteres).', 'error')
    return
  }

  // Asignar los valores ya limpios
  nombre.value = nombreLimpio
  telefono.value = telefonoLimpio
  direccion.value = direccionLimpia

  const numeroWhatsApp = "59898630403"

  const listaProductos = store.carrito
    .map(item => `• ${item.cantidad}x ${item.nombre} (Talle: ${item.talle}) - $${item.precio * item.cantidad}`)
    .join('\n')

  const mensaje = `Hola, soy *${nombre.value}*. Acabo de realizar un pedido:\n\n` +
                  `*Detalle del Pedido:*\n${listaProductos}\n\n` +
                  `*Total:* $${totalPagar.value}\n` +
                  `*Dirección:* ${direccion.value}\n` +
                  `*Teléfono:* ${telefono.value}`

  // --- NUEVA LÓGICA CON LARAVEL ---
  
  // A. Armamos el paquete de datos tal cual lo espera Laravel
  const payload = {
    customer_name: nombre.value,
    customer_phone: telefono.value,
    customer_address: direccion.value,
    total: totalPagar.value,
    items: store.carrito.map(item => ({
      id: item.id,
      talle: item.talle,
      cantidad: item.cantidad,
      precio: item.precio
    }))
  }

  try {
    mostrarAviso('Anotando tu pedido...', 'exito') // Un mensajito visual mientras carga

    // B. Mandamos la caja al backend
    const respuesta = await fetch(urlServidor+'/api/orders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    })

    if (respuesta.ok) {
      // C. Si todo salió bien, vaciamos el carrito local
      store.carrito = []

      // D. Redirigimos a WhatsApp e Inicio
      const mensajeCodificado = encodeURIComponent(mensaje)
      window.open(`https://wa.me/${numeroWhatsApp}?text=${mensajeCodificado}`, '_blank')
      router.push('/')
    } else {
      mostrarAviso('Hubo un error al procesar el pedido. Intentá de nuevo.', 'error')
    }
  } catch (error) {
    console.error("Error al enviar el pedido:", error)
    mostrarAviso('No se pudo conectar con el servidor. ¿El backend está prendido?', 'error')
  }
}
</script>

<template>
  <ShopHeader />

  <main class="checkout-container">
    <h1 class="titulo-checkout">Finalizar Compra</h1>

    <div v-if="store.carrito.length === 0" class="checkout-vacio">
      <p>No hay productos en el carrito para procesar un pedido.</p>
      <RouterLink to="/catalogo" class="btn-volver">Ir al Catálogo</RouterLink>
    </div>

    <div v-else class="wrapper-checkout">
      
      <!-- Columna Izquierda: Formulario de Envío -->
      <form @submit.prevent="confirmarPedido" class="formulario-envio">
        <h2>O completa tus datos para un nuevo pedido:</h2>
        
        <div class="campo">
          <label for="nombre">Nombre Completo *</label>
          <input 
            type="text" 
            id="nombre" 
            v-model="nombre" 
            placeholder="Ej: María García" 
            autocomplete="name"
            maxlength="60"
            required
          >
        </div>

        <div class="campo">
          <label for="telefono">Teléfono de contacto *</label>
          <input 
            type="tel" 
            id="telefono" 
            v-model="telefono" 
            placeholder="Ej: 099 123 456" 
            autocomplete="tel"
            maxlength="20"
            required
          >
        </div>

        <div class="campo">
          <label for="direccion">Dirección de envío *</label>
          <textarea 
            id="direccion" 
            v-model="direccion" 
            placeholder="Ej: Av. Principal 1234, Ap. 201 (Detallá piso o referencias)" 
            rows="3"
            maxlength="200"
            required
          ></textarea>
        </div>

        <p class="nota-pago">
          *No procesamos pagos online. Una vez confirmado, coordinaremos el pago y envío por WhatsApp.
        </p>
      </form>

      <!-- Columna Derecha: Resumen de Compra -->
      <div class="resumen-final">
        <h2>Resumen de Compra</h2>
        
        <div class="lista-resumen-checkout">
          <div class="item-checkout" v-for="item in store.carrito" :key="item.id + item.talle">
            <div class="item-mini-chico">
              <img 
                :src="item.imagen || item.producto?.imagen || 'https://via.placeholder.com/80?text=Sin+Foto'" 
                :alt="item.nombre"
                class="foto-mini-chica"
              />
            </div>
            <div class="detalles-item">
              <h4>{{ item.nombre }}</h4>
              <p>Talle: {{ item.talle }} | Cant: {{ item.cantidad }}</p>
            </div>
            <span class="precio-item">${{ item.precio * item.cantidad }}</span>
          </div>
        </div>

        <div class="totales-checkout">
          <div class="fila-total">
            <span>Subtotal</span>
            <span>${{ totalPagar }}</span>
          </div>
          <div class="fila-total">
            <span>Envío</span>
            <span class="gratis">Gratis</span>
          </div>
          <div class="fila-total final">
            <span>Total</span>
            <span>${{ totalPagar }}</span>
          </div>
        </div>

        <button type="button" @click="confirmarPedido" class="btn-confirmar">
          Confirmar Pedido
        </button>
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
   ESTILOS BASE (Computadora / Pantallas grandes)
   ========================================= */
.checkout-container {
  padding: 2rem 5%;
  min-height: 65vh;
}

.titulo-checkout {
  color: #333333;
  margin-bottom: 2.5rem;
  font-size: 1.8rem;
  text-align: center;
}

.checkout-vacio {
  text-align: center;
  padding: 3rem;
  background-color: #FFFFFF;
  border: 1px solid #EAEAEA;
  border-radius: 8px;
}

.btn-volver {
  display: inline-block;
  background-color: #8C7355;
  color: #FFFFFF;
  text-decoration: none;
  padding: 0.6rem 1.5rem;
  border-radius: 4px;
  margin-top: 1rem;
  font-weight: bold;
}

.wrapper-checkout {
  display: flex;
  gap: 3rem;
  align-items: flex-start;
}

.formulario-envio {
  flex: 1.5;
  background-color: #FDFCF7;
  border: 1px solid #EAEAEA;
  padding: 2rem;
  border-radius: 8px;
}

.formulario-envio h2, .resumen-final h2 {
  font-size: 1.2rem;
  color: #333333;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid #EAEAEA;
  padding-bottom: 0.5rem;
}

.campo {
  margin-bottom: 1.2rem;
}

.campo label {
  display: block;
  font-size: 0.9rem;
  color: #555555;
  margin-bottom: 0.4rem;
  font-weight: 500;
}

.campo input, .campo textarea {
  width: 100%;
  padding: 0.8rem;
  border: 1px solid #DDDDDD;
  border-radius: 4px;
  font-size: 1rem;
  color: #333333;
  background-color: #FFFFFF;
  transition: border-color 0.3s;
}

.campo input:focus, .campo textarea:focus {
  outline: none;
  border-color: #8C7355;
}

.resumen-final {
  flex: 1;
  background-color: #FDFCF7;
  border: 1px solid #EAEAEA;
  padding: 2rem;
  border-radius: 8px;
}

.lista-resumen-checkout {
  max-height: 260px;
  overflow-y: auto;
  margin-bottom: 1.5rem;
}

.item-checkout {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 0.8rem 0;
  border-bottom: 1px solid #EAEAEA;
}

.item-mini-chico {
  width: 50px;
  height: 50px;
  background-color: #F7F5F0;
  border-radius: 4px;
  overflow: hidden;
  border: 1px solid #EAEAEA;
  flex-shrink: 0;
}

.foto-mini-chica {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.detalles-item {
  flex: 1;
}

.detalles-item h4 {
  font-size: 0.9rem;
  color: #333333;
  margin: 0;
}

.detalles-item p {
  font-size: 0.75rem;
  color: #777777;
  margin: 0;
}

.precio-item {
  font-weight: bold;
  color: #333333;
  font-size: 0.95rem;
}

.totales-checkout {
  margin-bottom: 2rem;
}

.fila-total {
  display: flex;
  justify-content: space-between;
  color: #666666;
  margin-bottom: 0.6rem;
  font-size: 0.95rem;
}

.gratis {
  color: #2e7d32;
  font-weight: bold;
}

.final {
  border-top: 1px solid #DDDDDD;
  padding-top: 0.8rem;
  margin-top: 0.8rem;
  font-size: 1.2rem;
  font-weight: bold;
  color: #333333;
}

.final span:last-child {
  color: #8C7355;
}

.btn-confirmar {
  display: block;
  width: 100%;
  background-color: #C0955B;
  color: #FFFFFF;
  border: none;
  padding: 1rem;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  text-transform: uppercase;
  transition: background-color 0.3s;
}

.btn-confirmar:hover {
  background-color: #A37F4C;
}

.nota-pago {
  font-size: 0.85rem;
  color: #666666;
  margin-top: 1.5rem;
  line-height: 1.4;
}

/* Notificación flotante */
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
   ESTILOS RESPONSIVOS (Tablets y Celulares)
   ========================================= */
@media (max-width: 900px) {
  .checkout-container {
    padding: 1.5rem 1rem;
  }

  .wrapper-checkout {
    flex-direction: column;
    gap: 1.5rem;
  }

  .formulario-envio,
  .resumen-final {
    width: 100%;
    box-sizing: border-box;
    padding: 1.5rem 1.25rem;
  }

  .campo input, 
  .campo textarea {
    box-sizing: border-box;
  }
}

@media (max-width: 480px) {
  .titulo-checkout {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
  }

  .item-checkout {
    gap: 0.75rem;
  }

  .item-mini-chico {
    width: 42px;
    height: 42px;
  }

  .detalles-item h4 {
    font-size: 0.85rem;
  }

  .precio-item {
    font-size: 0.85rem;
  }

  .toast-notificacion {
    left: 1rem;
    right: 1rem;
    bottom: 1rem;
    justify-content: center;
    box-sizing: border-box;
  }
}
</style>