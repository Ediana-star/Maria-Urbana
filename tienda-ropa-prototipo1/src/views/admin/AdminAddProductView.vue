<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useTiendaStore } from '../../stores/useTiendaStore'

const router = useRouter()
const store = useTiendaStore()
// MAGIA APLICADA: Detecta automáticamente el servidor
const urlServidor = `http://${window.location.hostname}:8000`


const nuevoProducto = ref({
  nombre: '',
  precio: null,
  categoria: '',
  genero: '',
  stock: null,
  descripcion: '',
  talles: []
})

const imagenPreview = ref(null)
const archivoImagen = ref(null)

const mostrarNotificacion = ref(false)
const mensajeNotificacion = ref('')
const tipoNotificacion = ref('exito')

const mostrarAviso = (mensaje, tipo) => {
  mensajeNotificacion.value = mensaje
  tipoNotificacion.value = tipo
  mostrarNotificacion.value = true
  setTimeout(() => mostrarNotificacion.value = false, 4000)
}

const categorias = ['Remeras y Camisas', 'Camperas', 'Pantalones', 'Vestidos y Enteritos', 'Ropa Interior', 'Accesorios']
const generos = ['Mujer', 'Hombre', 'Unisex']
const tallesDisponibles = ['S', 'M', 'L', 'XL', 'Único']

const alSeleccionarImagen = (evento) => {
  const archivo = evento.target.files[0]
  if (archivo) {
    archivoImagen.value = archivo
    const urlTemporal = URL.createObjectURL(archivo)
    imagenPreview.value = urlTemporal
  }
}

// Función auxiliar para buscar el Pase VIP
const obtenerToken = () => localStorage.getItem('adminToken')

const guardarProducto = async () => {
  // Validaciones
  if (!nuevoProducto.value.nombre || !nuevoProducto.value.precio || !nuevoProducto.value.categoria || !nuevoProducto.value.genero || nuevoProducto.value.stock === null) {
    mostrarAviso('Por favor, completá todos los campos obligatorios.', 'error')
    return
  }

  const formData = new FormData()
  formData.append('name', nuevoProducto.value.nombre)
  formData.append('price', Number(nuevoProducto.value.precio))
  formData.append('category', nuevoProducto.value.categoria)
  formData.append('gender', nuevoProducto.value.genero)
  formData.append('stock', Number(nuevoProducto.value.stock))
  formData.append('description', nuevoProducto.value.descripcion || 'Sin descripción.')

  nuevoProducto.value.talles.forEach((talle, index) => {
    formData.append(`sizes[${index}]`, talle)
  })

  if (archivoImagen.value) {
    formData.append('image', archivoImagen.value)
  }

  try {
    mostrarAviso('Enviando datos al servidor...', 'exito')
    
    // Verificamos que haya sesión iniciada
    const token = obtenerToken()
    if (!token) {
       router.push('/admin/login')
       return
    }

    const respuesta = await fetch(urlServidor+'/api/products', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}` // ¡Acá mandamos el Token!
      },
      body: formData
    })

    if (respuesta.status === 401) {
        localStorage.removeItem('adminToken')
        router.push('/admin/login')
        return
    }

    if (respuesta.status === 422) {
      const errores = await respuesta.json()
      console.log("Errores de validación de Laravel:", errores)
      mostrarAviso('Laravel rechazó los datos. Revisá la consola (F12) para ver por qué.', 'error')
      return
    }

    if (respuesta.ok) {
      mostrarAviso(`¡"${nuevoProducto.value.nombre}" publicado con éxito!`, 'exito')
      await store.cargarProductos()
      setTimeout(() => router.push('/admin/catalogo'), 2000)
    } else {
      mostrarAviso(`Error en el servidor: código ${respuesta.status}`, 'error')
    }
  } catch (error) {
    console.error("Error de conexión:", error)
    mostrarAviso('No se pudo conectar. ¿El servidor de Laravel está prendido?', 'error')
  }
}
</script>

<template>
  <div class="agregar-producto-container">
    <div class="header-formulario">
      <h1>Agregar Nueva Prenda</h1>
      <p>Cargá los detalles, el stock y la foto para dar de alta el producto.</p>
    </div>

    <div class="card-formulario">
      <form @submit.prevent="guardarProducto">
        <div class="seccion-foto-carga">
          <label class="label-titulo">Foto de la prenda</label>
          <div class="contenedor-uploader">
            <div class="recuadro-preview">
              <img v-if="imagenPreview" :src="imagenPreview" alt="Vista previa" class="foto-previsualizada" />
              <span v-else class="emoji-placeholder">📷</span>
            </div>
            <div class="controles-foto">
              <label for="input-foto" class="btn-subir-foto">📁 Seleccionar Imagen</label>
              <input type="file" id="input-foto" accept="image/*" @change="alSeleccionarImagen" style="display: none;" />
              <p class="ayuda-foto">Sugerencia: Usar fotos en formato JPG o PNG verticales.</p>
            </div>
          </div>
        </div>

        <div class="grupo-input">
          <label for="nombre">Nombre de la prenda *</label>
          <input v-model="nuevoProducto.nombre" type="text" id="nombre" placeholder="Ej: Camisa Lino Beige" required />
        </div>

        <div class="fila-datos">
          <div class="grupo-input">
            <label for="precio">Precio ($) *</label>
            <input v-model="nuevoProducto.precio" type="number" id="precio" placeholder="Ej: 4500" min="0" required />
          </div>
          <div class="grupo-input">
            <label for="categoria">Categoría *</label>
            <select v-model="nuevoProducto.categoria" id="categoria" required>
              <option value="" disabled>Seleccionar...</option>
              <option v-for="cat in categorias" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </div>
          <div class="grupo-input">
            <label for="genero">Género *</label>
            <select v-model="nuevoProducto.genero" id="genero" required>
              <option value="" disabled>Seleccionar...</option>
              <option v-for="gen in generos" :key="gen" :value="gen">{{ gen }}</option>
            </select>
          </div>
          <div class="grupo-input">
            <label for="stock">Cantidad / Stock *</label>
            <input v-model="nuevoProducto.stock" type="number" id="stock" placeholder="Ej: 30" min="0" required />
          </div>
        </div>

        <div class="grupo-input">
          <label for="descripcion">Descripción o detalles de la prenda</label>
          <textarea v-model="nuevoProducto.descripcion" id="descripcion" rows="3" placeholder="Contale a tus clientas sobre la tela, el corte, etc..."></textarea>
        </div>

        <div class="grupo-input">
          <label>Talles habilitados</label>
          <div class="grid-checkboxes">
            <label v-for="talle in tallesDisponibles" :key="talle" class="checkbox-label">
              <input type="checkbox" :value="talle" v-model="nuevoProducto.talles" />
              <span class="custom-box">{{ talle }}</span>
            </label>
          </div>
        </div>

        <div class="bloque-botones">
          <RouterLink to="/admin/catalogo" class="btn-cancelar">Cancelar</RouterLink>
          <button type="submit" class="btn-guardar">✨ Publicar Prenda</button>
        </div>
      </form>
    </div>

    <div v-if="mostrarNotificacion" :class="['toast-notificacion', tipoNotificacion]">
      <span v-if="tipoNotificacion === 'exito'" class="icono-toast">✓</span>
      <span v-else class="icono-toast">!</span>
      <p>{{ mensajeNotificacion }}</p>
    </div>
  </div>
</template>

<style scoped>
.header-formulario { margin-bottom: 2rem; }
.header-formulario h1 { font-size: 1.8rem; color: #333; margin: 0 0 0.3rem 0; }
.header-formulario p { color: #777; font-size: 0.95rem; margin: 0; }
.card-formulario { background-color: #FFFFFF; border-radius: 12px; padding: 2rem; border: 1px solid #EAE5DF; box-shadow: 0 4px 10px rgba(0,0,0,0.01); max-width: 800px; }
.seccion-foto-carga { margin-bottom: 1.8rem; background-color: #FAF9F6; padding: 1.2rem; border-radius: 8px; border: 1px dashed #D2B9A1; }
.label-titulo { display: block; font-size: 0.9rem; font-weight: 600; color: #4A3E3D; margin-bottom: 0.8rem; }
.contenedor-uploader { display: flex; align-items: center; gap: 1.5rem; }
.recuadro-preview { width: 90px; height: 90px; border-radius: 8px; background-color: #EFECE8; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #E2DDD7; flex-shrink: 0;}
.foto-previsualizada { width: 100%; height: 100%; object-fit: cover; }
.emoji-placeholder { font-size: 2rem; color: #888; }
.btn-subir-foto { background-color: #FFFFFF; color: #8C7355; border: 1px solid #8C7355; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; display: inline-block; }
.btn-subir-foto:hover { background-color: #8C7355; color: #FFFFFF; }
.ayuda-foto { font-size: 0.75rem; color: #888; margin: 0.4rem 0 0; }
.grupo-input { display: flex; flex-direction: column; margin-bottom: 1.5rem; }
.grupo-input label { font-size: 0.9rem; font-weight: 600; color: #4A3E3D; margin-bottom: 0.5rem; }
.grupo-input input, .grupo-input select, .grupo-input textarea { padding: 0.75rem 1rem; border: 1px solid #D2B9A1; border-radius: 6px; background-color: #FAF9F6; font-size: 0.95rem; color: #333; outline: none; }
.grupo-input input:focus, .grupo-input select:focus, .grupo-input textarea:focus { border-color: #8C7355; background-color: #FFFFFF; }
.fila-datos { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1.2rem; }
.grid-checkboxes { display: flex; gap: 0.8rem; flex-wrap: wrap; }
.checkbox-label { cursor: pointer; position: relative; }
.checkbox-label input { position: absolute; opacity: 0; width: 0; height: 0; }
.custom-box { display: inline-block; padding: 0.5rem 1rem; border: 1px solid #D2B9A1; border-radius: 6px; background-color: #FFFFFF; color: #555; font-weight: 600; min-width: 45px; text-align: center; transition: all 0.2s;}
.checkbox-label input:checked ~ .custom-box { background-color: #8C7355; border-color: #8C7355; color: #FFFFFF; }
.bloque-botones { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; border-top: 1px solid #F0EAE4; padding-top: 1.5rem; }
.btn-cancelar { color: #777; padding: 0.75rem 1.5rem; text-decoration: none; font-size: 0.95rem; font-weight: 500; display: flex; align-items: center; }
.btn-guardar { background-color: #8C7355; color: #FFFFFF; border: none; padding: 0.75rem 1.8rem; border-radius: 6px; font-size: 0.95rem; font-weight: bold; cursor: pointer; transition: 0.2s;}
.btn-guardar:hover { background-color: #735D43; }
.toast-notificacion { position: fixed; bottom: 2rem; right: 2rem; padding: 1rem 1.5rem; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 1rem; z-index: 1000; font-weight: 500; animation: aparecer 0.3s ease-out; background-color: #FDFCF7; color: #333333; }
.toast-notificacion.exito { border-left: 5px solid #C0955B; }
.toast-notificacion.exito .icono-toast { background-color: #C0955B; }
.toast-notificacion.error { border-left: 5px solid #C0392B; }
.toast-notificacion.error .icono-toast { background-color: #C0392B; }
.icono-toast { color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; justify-content: center; align-items: center; font-weight: bold; }
@keyframes aparecer { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

@media (max-width: 768px) {
  .card-formulario { padding: 1.2rem; }
  .contenedor-uploader { flex-direction: column; align-items: flex-start; gap: 1rem; }
  .fila-datos { grid-template-columns: 1fr; }
  .bloque-botones { flex-direction: column-reverse; gap: 0.5rem; }
  .btn-cancelar, .btn-guardar { width: 100%; justify-content: center; text-align: center; padding: 0.9rem; }
  .toast-notificacion { left: 1rem; right: 1rem; bottom: 1rem; justify-content: center; }
}
</style>