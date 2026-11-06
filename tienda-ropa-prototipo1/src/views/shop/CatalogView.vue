<script setup>
import { ref, computed, watch, onMounted } from 'vue' 
import { useRoute, RouterLink } from 'vue-router'
import ShopHeader from '../../components/shop/ShopHeader.vue'
import ShopFooter from '../../components/shop/ShopFooter.vue'
import { useTiendaStore } from '../../stores/useTiendaStore'

const store = useTiendaStore()
const route = useRoute()

// Pedimos los datos al cargar la pantalla
onMounted(() => {
  store.cargarProductos()
})

const textoBusqueda = ref('')
const talleSeleccionado = ref('Todos')
const generoSeleccionado = ref('Todos')
const ordenPrecio = ref('recientes') 

const categoriaSeleccionada = ref(route.query.categoria || 'Todos')

watch(
  () => route.query.categoria,
  (nuevaCategoria) => {
    categoriaSeleccionada.value = nuevaCategoria || 'Todos'
  }
)

const categorias = ['Todos', 'Remeras y Camisas', 'Camperas', 'Pantalones', 'Vestidos y Enteritos', 'Ropa Interior', 'Accesorios']
const generosDisponibles = ['Todos', 'Mujer', 'Hombre', 'Unisex']
const tallesDisponibles = ['Todos', 'S', 'M', 'L', 'XL', 'Único']

// Sistema de Favoritos
const favoritos = ref([])
const mostrarSoloFavoritos = ref(false) 

const toggleFavorito = (id) => {
  if (favoritos.value.includes(id)) {
    favoritos.value = favoritos.value.filter(favId => favId !== id)
  } else {
    favoritos.value.push(id)
  }
}

// AQUÍ ESTABA EL ERROR: Agregamos protecciones contra datos nulos
const productosFiltrados = computed(() => {
  let resultado = store.productos.filter(producto => {
    // 1. Protegemos los textos (si vienen null, usamos '')
    const nombreSeguro = producto.nombre || ''
    const descSegura = producto.descripcion || ''
    
    const coincideBusqueda = nombreSeguro.toLowerCase().includes(textoBusqueda.value.toLowerCase()) ||
                             descSegura.toLowerCase().includes(textoBusqueda.value.toLowerCase())
    
    const coincideCategoria = categoriaSeleccionada.value === 'Todos' || 
                              producto.categoria === categoriaSeleccionada.value

    const coincideGenero = generoSeleccionado.value === 'Todos' || 
                           producto.genero === generoSeleccionado.value ||
                           producto.genero === 'Unisex'

    // 2. Protegemos los talles (si vienen null, usamos [])
    const tallesSeguros = producto.talles || []
    const coincideTalle = talleSeleccionado.value === 'Todos' || 
                          tallesSeguros.includes(talleSeleccionado.value)

    const coincideFavorito = !mostrarSoloFavoritos.value || favoritos.value.includes(producto.id)

    return coincideBusqueda && coincideCategoria && coincideGenero && coincideTalle && coincideFavorito
  })

  // 3. Protegemos los precios al ordenar
  if (ordenPrecio.value === 'menor-mayor') {
    resultado.sort((a, b) => (a.precio || 0) - (b.precio || 0))
  } else if (ordenPrecio.value === 'mayor-menor') {
    resultado.sort((a, b) => (b.precio || 0) - (a.precio || 0))
  }

  return resultado
})
</script>

<template>
  <ShopHeader />
  
  <main class="catalogo-container">
    <h1 class="titulo-catalogo">Colección María Urbana</h1>
    
    <div class="panel-filtros">
      
      <div class="fila-controles-superior">
        <div class="buscador-caja">
          <span class="lupa">🔍</span>
          <input type="text" v-model="textoBusqueda" placeholder="¿Qué estás buscando hoy?...">
        </div>

        <div class="controles-secundarios">
          <button 
            :class="['btn-filtro btn-favoritos', { activo: mostrarSoloFavoritos }]"
            @click="mostrarSoloFavoritos = !mostrarSoloFavoritos"
          >
            {{ mostrarSoloFavoritos ? '🖤 Favoritos' : '🤍 Ver Favoritos' }}
          </button>

          <div class="ordenar-caja">
            <select id="orden" v-model="ordenPrecio" class="select-orden">
              <option value="recientes">Más recientes</option>
              <option value="menor-mayor">Menor precio</option>
              <option value="mayor-menor">Mayor precio</option>
            </select>
          </div>
        </div>
      </div>

      <div class="bloque-filtro">
        <span class="etiqueta-filtro">Sección:</span>
        <div class="grupo-botones">
          <button 
            v-for="gen in generosDisponibles" :key="gen"
            :class="['btn-filtro', { activo: generoSeleccionado === gen }]"
            @click="generoSeleccionado = gen"
          >
            {{ gen }}
          </button>
        </div>
      </div>

      <div class="bloque-filtro">
        <span class="etiqueta-filtro">Categorías:</span>
        <div class="grupo-botones">
          <button 
            v-for="cat in categorias" :key="cat"
            :class="['btn-filtro', { activo: categoriaSeleccionada === cat }]"
            @click="categoriaSeleccionada = cat"
          >
            {{ cat }}
          </button>
        </div>
      </div>

      <div class="bloque-filtro">
        <span class="etiqueta-filtro">Talle:</span>
        <div class="grupo-botones">
          <button 
            v-for="talle in tallesDisponibles" :key="talle"
            :class="['btn-filtro talle', { activo: talleSeleccionado === talle }]"
            @click="talleSeleccionado = talle"
          >
            {{ talle }}
          </button>
        </div>
      </div>

    </div>

    <!-- Mensaje de carga mientras conecta con Laravel -->
    <div v-if="store.cargando" class="estado-cargando">
      <h3>Preparando la colección...</h3>
    </div>

    <!-- Si ya cargó, mostramos la grilla normal -->
    <div class="grilla-productos" v-else-if="productosFiltrados.length > 0">
      <div class="tarjeta-producto" v-for="producto in productosFiltrados" :key="producto.id">
        
        <div class="contenedor-foto">
          <button class="btn-favorito-tarjeta" @click.prevent="toggleFavorito(producto.id)">
             {{ favoritos.includes(producto.id) ? '🖤' : '🤍' }}
          </button>
          
          <img 
            loading="lazy" 
            :src="producto.imagen || 'https://via.placeholder.com/300?text=Sin+Foto'" 
            :alt="producto.nombre" 
            class="foto-prenda"
          />
        </div>
        
        <div class="info-producto">
          <span class="categoria">{{ producto.genero }} | {{ producto.categoria }}</span>
          <h3 class="nombre">{{ producto.nombre }}</h3>
          <p class="precio">${{ producto.precio }}</p>
          <!-- OTRO CAMBIO ACÁ: Protegemos el join de los talles -->
          <p class="talles-tarjeta">Talles: {{ (producto.talles || []).join(', ') }}</p>
        </div>

        <RouterLink :to="`/producto/${producto.id}`" class="btn-ver">
          Detalles
        </RouterLink>
      </div>
    </div>

    <div class="sin-resultados" v-else>
      <h3 v-if="mostrarSoloFavoritos && favoritos.length === 0">Aún no has guardado ningún producto en favoritos.</h3>
      <h3 v-else>No hay prendas que coincidan con todos estos filtros combinados.</h3>
      <p>Probá restableciendo algunos filtros para ver más opciones.</p>
    </div>

  </main>

  <ShopFooter />
</template>

<style scoped>
.estado-cargando {
  text-align: center;
  padding: 4rem 1rem;
  color: #8C7355;
  font-family: 'Playfair Display', serif;
}

.catalogo-container {
  padding: 2rem 5%;
  min-height: 60vh;
}

.titulo-catalogo {
  text-align: center;
  color: #333333;
  margin-bottom: 2.5rem;
  font-size: 2rem;
  font-family: 'Playfair Display', serif;
}

.panel-filtros {
  background-color: #F7F5F0;
  padding: 1.5rem;
  border-radius: 8px;
  border: 1px solid #EAEAEA;
  margin-bottom: 3rem;
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.fila-controles-superior {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1.5rem;
  flex-wrap: wrap;
}

.buscador-caja {
  position: relative;
  flex: 1;
  min-width: 280px;
}

.buscador-caja input {
  width: 100%;
  padding: 0.7rem 1rem 0.7rem 2.5rem;
  border: 1px solid #DDDDDD;
  border-radius: 4px;
  font-size: 0.95rem;
  outline: none;
  transition: border-color 0.3s;
}

.buscador-caja input:focus {
  border-color: #8C7355;
}

.lupa {
  position: absolute;
  left: 0.8rem;
  top: 50%;
  transform: translateY(-50%);
}

.controles-secundarios {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.ordenar-caja {
  display: flex;
  align-items: center;
}

.select-orden {
  padding: 0.6rem 1rem;
  border: 1px solid #DDDDDD;
  border-radius: 4px;
  background-color: #FFFFFF;
  color: #333333;
  cursor: pointer;
  outline: none;
}

.bloque-filtro {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.etiqueta-filtro {
  font-weight: bold;
  color: #555555;
  font-size: 0.9rem;
  min-width: 90px;
}

.grupo-botones {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.btn-filtro {
  background-color: #FFFFFF;
  border: 1px solid #DDDDDD;
  padding: 0.4rem 1.2rem;
  border-radius: 4px;
  cursor: pointer;
  color: #555555;
  font-size: 0.85rem;
  font-weight: 500;
  transition: all 0.2s;
  white-space: nowrap;
}

.btn-favoritos {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1rem;
}

.btn-filtro.talle {
  padding: 0.4rem 0.8rem;
  min-width: 40px;
  text-align: center;
}

.btn-filtro:hover {
  border-color: #8C7355;
  color: #8C7355;
}

.btn-filtro.activo {
  background-color: #8C7355;
  border-color: #8C7355;
  color: #FFFFFF;
}

.grilla-productos {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 2.5rem;
}

.tarjeta-producto {
  background-color: #FFFFFF;
  border: 1px solid #EAEAEA;
  border-radius: 8px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: transform 0.3s, box-shadow 0.3s;
}

.tarjeta-producto:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
}

.contenedor-foto {
  height: 320px;
  width: 100%;
  background-color: #F7F5F0;
  overflow: hidden; 
  position: relative; 
}

.btn-favorito-tarjeta {
  position: absolute;
  top: 10px;
  right: 10px;
  background: white;
  border: none;
  border-radius: 50%;
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  z-index: 10;
  transition: transform 0.2s;
}

.btn-favorito-tarjeta:hover {
  transform: scale(1.1);
}

.foto-prenda {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  transition: transform 0.4s ease; 
}

.tarjeta-producto:hover .foto-prenda {
  transform: scale(1.05);
}

.info-producto {
  padding: 1.2rem;
  text-align: center;
  flex-grow: 1;
}

.categoria {
  font-size: 0.7rem;
  color: #999999;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.nombre {
  font-size: 1rem;
  color: #333333;
  margin: 0.4rem 0;
}

.precio {
  font-size: 1.2rem;
  color: #8C7355;
  font-weight: bold;
}

.talles-tarjeta {
  font-size: 0.75rem;
  color: #888888;
  margin-top: 0.5rem;
}

.btn-ver {
  display: block;
  text-align: center;
  text-decoration: none;
  background-color: transparent;
  color: #8C7355;
  border: 1px solid #8C7355;
  padding: 0.6rem;
  margin: 0 1rem 1rem 1rem;
  border-radius: 4px;
  font-weight: bold;
  text-transform: uppercase;
  font-size: 0.75rem;
  transition: all 0.3s;
}

.btn-ver:hover {
  background-color: #8C7355;
  color: #FFFFFF;
}

.sin-resultados {
  text-align: center;
  padding: 4rem 1rem;
  color: #666666;
}

@media (max-width: 768px) {
  .catalogo-container {
    padding: 1rem;
  }
  .titulo-catalogo {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
  }
  .panel-filtros {
    padding: 1rem;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  .fila-controles-superior {
    flex-direction: column;
    align-items: stretch;
    gap: 0.8rem;
  }
  .buscador-caja {
    min-width: 100%;
  }
  .controles-secundarios {
    justify-content: space-between;
    gap: 0.5rem;
  }
  .btn-favoritos {
    flex: 1;
    justify-content: center;
    font-size: 0.8rem;
    padding: 0.5rem;
  }
  .ordenar-caja {
    flex: 1;
  }
  .select-orden {
    width: 100%;
    font-size: 0.8rem;
    padding: 0.5rem;
  }
  .bloque-filtro {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.4rem;
  }
  .etiqueta-filtro {
    font-size: 0.8rem;
    min-width: auto;
  }
  .grupo-botones {
    flex-wrap: nowrap;
    overflow-x: auto;
    width: 100%;
    padding-bottom: 0.5rem;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none; 
  }
  .grupo-botones::-webkit-scrollbar {
    display: none; 
  }
  .btn-filtro {
    font-size: 0.75rem;
    padding: 0.35rem 0.8rem;
  }
  .grilla-productos {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.6rem;
  }
  .tarjeta-producto {
    border-radius: 6px;
  }
  .contenedor-foto {
    height: 220px;
  }
  .btn-favorito-tarjeta {
    width: 30px;
    height: 30px;
    top: 6px;
    right: 6px;
    font-size: 0.8rem;
  }
  .info-producto {
    padding: 0.8rem 0.5rem;
  }
  .nombre {
    font-size: 0.85rem;
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .precio {
    font-size: 1rem;
  }
  .categoria, .talles-tarjeta {
    font-size: 0.65rem;
  }
  .btn-ver {
    padding: 0.5rem;
    margin: 0 0.5rem 0.8rem 0.5rem;
    font-size: 0.7rem;
  }
}

@media (max-width: 380px) {
  .contenedor-foto {
    height: 180px;
  }
  .controles-secundarios {
    flex-direction: column;
  }
}
</style>