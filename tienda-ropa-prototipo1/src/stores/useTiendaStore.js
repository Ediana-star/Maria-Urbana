import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useTiendaStore = defineStore('tienda', () => {
  // MAGIA APLICADA: Detecta automáticamente desde dónde te conectas
  const urlServidor = `http://${window.location.hostname}:8000`

  // 1. ESTADO
  // Arrancamos con el array vacío, se va a llenar con los datos de Laravel
  const productos = ref([])
  const cargando = ref(false) // Agregamos esto para saber si está cargando de la base de datos
  const carrito = ref([])
  const pedidos = ref([])
  const configuracion = ref({
    nombreTienda: 'María Urbana',
    descripcionTienda: 'Tienda online de ropa y accesorios.'
  })

  // 2. ACCIONES (CONEXIÓN CON EL BACKEND)
  const cargarProductos = async () => {
    cargando.value = true
    try {
      // Usamos nuestra nueva variable urlServidor
      await fetch(`${urlServidor}/sanctum/csrf-cookie`, { credentials: 'include' }) // Esto es para que Laravel Sanctum nos deje hacer la petición
      // Esto es lo que estaba antes, pero ahora con la variable
      const respuesta = await fetch(`${urlServidor}/api/products`)
      const datos = await respuesta.json()

      // Adaptamos los nombres de inglés (Laravel) a español (tu Vue)
      productos.value = datos.map(prod => {
        
        //--- EL TRUCO PARA LAS IMÁGENES NUEVAS Y DE MUESTRA ---
        let rutaFoto = prod.image_path;
        
        if (rutaFoto) {
          // Si la ruta viene de la carpeta storage o de imagenes (seeders), le agregamos el servidor
          if (rutaFoto.startsWith('/storage/') || rutaFoto.startsWith('/imagenes/')) {
            // Usamos la variable para armar la ruta de la foto también
            rutaFoto = urlServidor + rutaFoto;
          }
        }

        return {
          id: prod.id,
          nombre: prod.name,
          categoria: prod.category,
          genero: prod.gender,
          precio: prod.price,
          stock: prod.stock,
          descripcion: prod.description,
          talles: prod.sizes,
          imagen: rutaFoto // Usamos la variable que arreglamos arriba
        }
      })
    } catch (error) {
      console.error("Hubo un error al conectar con el backend:", error)
    } finally {
      cargando.value = false
    }
  }

  // 3. ACCIONES DEL CARRITO Y PEDIDOS
  const agregarAlCarrito = (producto, talle, cantidad) => {
    const existe = carrito.value.find(item => item.id === producto.id && item.talle === talle)
    
    if (existe) {
      existe.cantidad += cantidad
    } else {
      carrito.value.push({
        ...producto,
        talle: talle,
        cantidad: cantidad
      })
    }
  }

  const eliminarDelCarrito = (id, talle) => {
    carrito.value = carrito.value.filter(item => !(item.id === id && item.talle === talle))
  }

  const agregarProducto = (nuevoProducto) => {
    const siguienteId = productos.value.length > 0 ? productos.value[productos.value.length - 1].id + 1 : 1
    productos.value.push({ id: siguienteId, ...nuevoProducto })
  }

  //--- GESTIÓN DE PEDIDOS Y STOCK ---
  const crearPedido = (clienteData, itemsCarrito, total) => {
    const nuevoPedido = {
      id: Date.now(),
      cliente: clienteData,
      items: JSON.parse(JSON.stringify(itemsCarrito)),
      total: total,
      estado: 'Pendiente',
      fecha: new Date().toLocaleDateString('es-UY', { hour: '2-digit', minute: '2-digit' })
    }
    pedidos.value.unshift(nuevoPedido)
  }

  const completarPedido = (pedidoId) => {
    const pedido = pedidos.value.find(p => p.id === pedidoId)
    if (pedido && pedido.estado === 'Pendiente') {
      pedido.items.forEach(itemPedido => {
        const prod = productos.value.find(p => p.id === itemPedido.id)
        if (prod && prod.stock >= itemPedido.cantidad) {
          prod.stock -= itemPedido.cantidad
        }
      })
      pedido.estado = 'Entregado'
    }
  }

  const cancelarPedido = (pedidoId) => {
    const pedido = pedidos.value.find(p => p.id === pedidoId)
    if (pedido && pedido.estado === 'Pendiente') {
      pedido.estado = 'Cancelado'
    }
  }

  // 4. GETTERS
  const totalArticulos = computed(() => {
    return carrito.value.reduce((suma, item) => suma + item.cantidad, 0)
  })

  const categorias = computed(() => {
    const unicas = Array.from(new Set(productos.value.map(p => p.categoria)))
    return ['Todos', ...unicas]
  })

  return {
    productos,
    cargando,
    categorias,
    carrito,
    pedidos,
    configuracion,
    totalArticulos,
    cargarProductos,
    agregarAlCarrito,
    eliminarDelCarrito,
    agregarProducto,
    crearPedido,
    completarPedido,
    cancelarPedido
  }
})