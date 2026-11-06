<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useTiendaStore } from '../../stores/useTiendaStore'

const store = useTiendaStore()
const router = useRouter()
// MAGIA APLICADA: Detecta automáticamente el servidor
const urlServidor = `http://${window.location.hostname}:8000`


onMounted(() => {
  store.cargarProductos()
})

const productoEditando = ref(null)
const busqueda = ref('')

const productosFiltrados = computed(() => {
  return store.productos.filter(producto =>
    producto.nombre.toLowerCase().includes(busqueda.value.toLowerCase())
  )
})

const mostrarNotificacion = ref(false)
const mensajeNotificacion = ref('')
const tipoNotificacion = ref('exito')

const mostrarAviso = (mensaje, tipo = 'exito') => {
  mensajeNotificacion.value = mensaje
  tipoNotificacion.value = tipo
  mostrarNotificacion.value = true
  setTimeout(() => mostrarNotificacion.value = false, 3000)
}

const abrirEdicion = (producto) => {
  productoEditando.value = { ...producto }
}

const obtenerToken = () => localStorage.getItem('adminToken')

const guardarCambios = async () => {
  try {
    mostrarAviso('Guardando...', 'exito')
    
    const token = obtenerToken()
    if (!token) {
        router.push('/admin/login')
        return
    }

    const respuesta = await fetch(urlServidor+`/api/products/${productoEditando.value.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}` // ¡Token enviado!
      },
      body: JSON.stringify({
        name: productoEditando.value.nombre,
        price: productoEditando.value.precio,
        stock: productoEditando.value.stock
      })
    })

    if (respuesta.status === 401) {
        localStorage.removeItem('adminToken')
        router.push('/admin/login')
        return
    }

    if (respuesta.ok) {
      await store.cargarProductos() 
      productoEditando.value = null
      mostrarAviso('¡Los cambios se guardaron correctamente!', 'exito')
    } else {
      mostrarAviso('Hubo un error al intentar editar.', 'error')
    }
  } catch (error) {
    mostrarAviso('No se pudo conectar con el servidor.', 'error')
  }
}

const eliminarProducto = async (id, nombre) => {
  const confirmar = confirm(`¿Estás seguro de que querés eliminar "${nombre}"? Esta acción no se puede deshacer.`)
  if (confirmar) {
    try {
      const token = obtenerToken()
      if (!token) {
          router.push('/admin/login')
          return
      }

      const respuesta = await fetch(`${urlServidor}/api/products/${id}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}` // ¡Token enviado!
        }
      })

      if (respuesta.status === 401) {
          localStorage.removeItem('adminToken')
          router.push('/admin/login')
          return
      }

      if (respuesta.ok) {
        await store.cargarProductos() 
        mostrarAviso(`El producto "${nombre}" fue eliminado.`, 'exito')
      } else {
        mostrarAviso('Hubo un error al intentar borrar.', 'error')
      }
    } catch (error) {
      mostrarAviso('No se pudo conectar con el servidor.', 'error')
    }
  }
}
</script>

<template>
  <div class="admin-view-container">
    <div class="header-pantalla-admin">
      <div>
        <h1>Control de Inventario</h1>
        <p class="subtitulo">Gestioná los productos visibles en la tienda.</p>
      </div>
      <RouterLink to="/admin/agregar-producto" class="btn-agregar-nuevo">
        + Agregar Nuevo Producto
      </RouterLink>
    </div>

    <div style="margin-bottom: 1rem;">
      <input
        type="text"
        v-model="busqueda"
        placeholder="Buscar por nombre..."
        class="input-simple"
      />
    </div>

    <div v-if="store.cargando" style="text-align: center; padding: 2rem; color: #888;">
      Cargando inventario...
    </div>

    <div v-else class="contenedor-tabla card-admin">
      <table class="tabla-admin">
        <thead>
          <tr>
            <th>ID</th>
            <th>Prenda</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Disponibles</th>
            <th>Talles Activos</th>
            <th class="texto-derecha">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="producto in productosFiltrados" :key="producto.id">
            <td class="col-id" data-label="ID">
              <span>#{{ producto.id }}</span>
            </td>
            <td class="col-prenda" data-label="Prenda">
              <div class="info-prenda-tabla">
                <div class="contenedor-foto-tabla">
                  <img :src="producto.imagen || 'https://via.placeholder.com/50'" alt="Foto prenda" class="foto-miniatura-tabla" />
                </div>
                <div>
                  <span class="nombre-prenda">{{ producto.nombre }}</span>
                  <span class="descripcion-corta">{{ producto.descripcion ? producto.descripcion.substring(0, 45) : '' }}...</span>
                </div>
              </div>
            </td>
            <td data-label="Categoría"><span class="tag-categoria-admin">{{ producto.categoria }}</span></td>
            <td class="precio-admin" data-label="Precio">${{ producto.precio }}</td>
            <td class="stock-unidades" data-label="Stock">
              <span><strong>{{ producto.stock ?? 0 }}</strong> u.</span>
            </td>
            <td data-label="Talles">
              <div class="lista-talles-admin">
                <span v-for="talle in producto.talles" :key="talle" class="badge-talle">{{ talle }}</span>
              </div>
            </td>
            <td class="texto-derecha" data-label="Acciones">
              <div class="acciones-grupo">
                <button class="btn-accion editar" @click="abrirEdicion(producto)">✏️ Editar</button>
                <button class="btn-accion eliminar" @click="eliminarProducto(producto.id, producto.nombre)">🗑️ Borrar</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- MODAL EDICIÓN -->
    <div v-if="productoEditando" class="overlay-modal">
      <div class="contenido-modal">
        <h3>Editar Prenda #{{ productoEditando.id }}</h3>
        
        <label>Nombre:</label>
        <input v-model="productoEditando.nombre" type="text" class="input-modal" />
        
        <label>Precio ($):</label>
        <input v-model.number="productoEditando.precio" type="number" class="input-modal" />
        
        <label>Stock disponible:</label>
        <input v-model.number="productoEditando.stock" type="number" class="input-modal" />
        
        <div class="botones-modal">
          <button @click="guardarCambios" class="btn-guardar">Guardar</button>
          <button @click="productoEditando = null" class="btn-cancelar">Cancelar</button>
        </div>
      </div>
    </div>

    <!-- TOAST -->
    <div v-if="mostrarNotificacion" :class="['toast-notificacion', tipoNotificacion]">
      <span v-if="tipoNotificacion === 'exito'" class="icono-toast">✓</span>
      <span v-else class="icono-toast">!</span>
      <p>{{ mensajeNotificacion }}</p>
    </div>
  </div>
</template>

<style scoped>
.input-simple { width: 100%; max-width: 300px; padding: 0.6rem 1rem; border: 1px solid #D2B9A1; border-radius: 6px; font-size: 0.95rem; outline: none; }
.input-simple:focus { border-color: #8C7355; }
.contenedor-foto-tabla { width: 45px; height: 45px; border-radius: 6px; overflow: hidden; border: 1px solid #EAE5DF; background-color: #FAF9F6; flex-shrink: 0; }
.foto-miniatura-tabla { width: 100%; height: 100%; object-fit: cover; }
.header-pantalla-admin { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; gap: 1rem; flex-wrap: wrap; }
.header-pantalla-admin h1 { font-size: 1.8rem; color: #333333; margin: 0 0 0.3rem 0; }
.subtitulo { color: #777777; font-size: 0.95rem; margin: 0; }
.btn-agregar-nuevo { background-color: #8C7355; color: #FFFFFF; text-decoration: none; padding: 0.7rem 1.2rem; border-radius: 4px; font-weight: bold; font-size: 0.9rem; transition: background-color 0.2s; white-space: nowrap;}
.btn-agregar-nuevo:hover { background-color: #735D43; }
.contenedor-tabla { padding: 0; background-color: #FFFFFF; border-radius: 12px; border: 1px solid #EAE5DF; box-shadow: 0 4px 10px rgba(0,0,0,0.01); overflow: hidden; }
.tabla-admin { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; }
.tabla-admin th { background-color: #F7F5F0; color: #555555; padding: 1rem 1.5rem; font-weight: bold; border-bottom: 2px solid #EAEAEA; }
.tabla-admin td { padding: 1.2rem 1.5rem; border-bottom: 1px solid #F0F0F0; color: #444444; vertical-align: middle; }
.col-id { font-family: monospace; color: #888888; font-size: 0.9rem; }
.info-prenda-tabla { display: flex; align-items: center; gap: 0.8rem; }
.nombre-prenda { display: block; font-weight: 600; color: #333333; line-height: 1.2;}
.descripcion-corta { display: block; font-size: 0.8rem; color: #888888; margin-top: 0.2rem; }
.tag-categoria-admin { background-color: #EFECE8; color: #555555; font-size: 0.8rem; padding: 0.2rem 0.6rem; border-radius: 4px; }
.precio-admin { font-weight: bold; color: #8C7355; }
.lista-talles-admin { display: flex; gap: 0.3rem; flex-wrap: wrap; justify-content: flex-end; }
.badge-talle { background-color: #FFFFFF; border: 1px solid #DDDDDD; color: #555555; font-size: 0.75rem; padding: 0.1rem 0.4rem; border-radius: 3px; }
.texto-derecha { text-align: right; }
.acciones-grupo { display: flex; gap: 0.5rem; justify-content: flex-end; }
.btn-accion { padding: 0.4rem 0.8rem; border-radius: 4px; font-size: 0.85rem; cursor: pointer; border: 1px solid transparent; transition: all 0.2s; }
.btn-accion.editar { background-color: #FFFFFF; border-color: #D2B9A1; color: #8C7355; }
.btn-accion.editar:hover { background-color: #FAF9F6; }
.btn-accion.eliminar { background-color: #FFF0F0; color: #C62828; }
.btn-accion.eliminar:hover { background-color: #FFE5E5; }

/* MODAL */
.overlay-modal { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.4); display: flex; justify-content: center; align-items: center; z-index: 100; backdrop-filter: blur(2px); padding: 1rem;}
.contenido-modal { background: #FFFFFF; padding: 2rem; border-radius: 12px; width: 100%; max-width: 380px; display: flex; flex-direction: column; gap: 0.8rem; border: 1px solid #EAE5DF; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
.contenido-modal h3 { color: #333333; margin-top: 0; font-size: 1.3rem; }
.contenido-modal label { font-size: 0.85rem; font-weight: 600; color: #4A3E3D; margin-top: 0.3rem; }
.input-modal { padding: 0.65rem 0.9rem; border: 1px solid #D2B9A1; border-radius: 6px; width: 100%; background-color: #FAF9F6; font-size: 0.95rem; color: #333; outline: none; transition: all 0.2s; }
.input-modal:focus { border-color: #8C7355; background-color: #FFFFFF; }
.botones-modal { display: flex; gap: 0.8rem; margin-top: 1.2rem; }
.btn-guardar { background: #8C7355; color: white; border: none; padding: 0.65rem 1rem; border-radius: 6px; cursor: pointer; flex: 1; font-weight: bold; transition: background-color 0.2s; }
.btn-cancelar { background: #EFECE8; color: #555555; border: none; padding: 0.65rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background-color 0.2s; }

/* TOAST */
.toast-notificacion { position: fixed; bottom: 2rem; right: 2rem; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12); display: flex; align-items: center; gap: 1rem; z-index: 1000; font-weight: 500; font-size: 0.95rem; animation: aparecer 0.3s cubic-bezier(0.16, 1, 0.3, 1); background-color: #FDFCF7; color: #333333; border: 1px solid #EAE5DF; }
.toast-notificacion.exito { border-left: 5px solid #8C7355; }
.toast-notificacion.exito .icono-toast { background-color: #8C7355; }
.toast-notificacion.error { border-left: 5px solid #C0392B; }
.toast-notificacion.error .icono-toast { background-color: #C0392B; }
.icono-toast { color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 0.85rem; flex-shrink: 0; }

@keyframes aparecer { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }

@media (max-width: 768px) {
  .header-pantalla-admin { flex-direction: column; align-items: stretch; }
  .input-simple { max-width: 100%; }
  .btn-agregar-nuevo { text-align: center; }
  .tabla-admin thead { display: none; }
  .tabla-admin tr { display: flex; flex-direction: column; border-bottom: 2px solid #EAE5DF; padding: 1rem; background-color: #FFF; }
  .tabla-admin td { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid #F9F7F5; text-align: right; }
  .tabla-admin td:last-child { border-bottom: none; }
  .tabla-admin td::before { content: attr(data-label); font-weight: bold; color: #888; margin-right: 1rem; text-align: left; }
  .col-id span { display: inline-block; }
  .col-prenda.info-prenda-tabla { text-align: right; justify-content: flex-end; }
  .col-prenda { flex-direction: column; align-items: flex-end; }
  .col-prenda::before { margin-bottom: 0.5rem; width: 100%; }
  .acciones-grupo { width: 100%; justify-content: flex-end; }
  .toast-notificacion { left: 1rem; right: 1rem; bottom: 1rem; justify-content: center; }
}
</style>