<script setup>
import ShopHeader from '../../components/shop/ShopHeader.vue'
import ShopFooter from '../../components/shop/ShopFooter.vue'

// Datos de la tabla de medidas (extraídos de tu diseño)
const medidas = [
  { talle: '34/XS', busto: '80-84', cintura: '62-66', cadera: '88-92', largo: '100' },
  { talle: '36/S',  busto: '84-88', cintura: '66-70', cadera: '92-96', largo: '101' },
  { talle: '38/M',  busto: '88-92', cintura: '70-74', cadera: '96-100', largo: '102' },
  { talle: '40/L',  busto: '92-96', cintura: '74-78', cadera: '100-104', largo: '103' },
  { talle: '42/XL', busto: '96-100', cintura: '78-82', cadera: '104-108', largo: '104' },
  { talle: '44/XXL', busto: '100-104', cintura: '82-86', cadera: '108-112', largo: '105' }
]
</script>

<template>
  <ShopHeader />

  <main class="talles-page">
    
    <!-- Encabezado de sección -->
    <section class="encabezado">
      <h1>Tabla de Talles</h1>
      <p>Encontrá la medida ideal para cada prenda</p>
    </section>

    <!-- Contenido en dos tarjetas -->
    <div class="grid-talles">
      
      <!-- Bloque Izquierdo: Tabla de Valores -->
      <div class="card-seccion tabla-card">
        <h2>TABLA DE VALORES (CM)</h2>
        <div class="tabla-responsive">
          <table>
            <thead>
              <tr>
                <th>TALLES</th>
                <th>BUSTO (CM)</th>
                <th>CINTURA (CM)</th>
                <th>CADERA (CM)</th>
                <th>LARGO DE PIERNA (CM)</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="fila in medidas" :key="fila.talle">
                <td class="col-talle">{{ fila.talle }}</td>
                <td>{{ fila.busto }}</td>
                <td>{{ fila.cintura }}</td>
                <td>{{ fila.cadera }}</td>
                <td>{{ fila.largo }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Bloque Derecho: Guía de Medidas con Ilustración -->
      <div class="card-seccion guia-card">
        <h2>GUÍA DE MEDIDAS</h2>
        
        <div class="contenedor-ilustracion">
          <svg viewBox="0 0 200 320" class="svg-silueta" xmlns="http://www.w3.org/2000/svg">
            <!-- Cabeza -->
            <ellipse cx="100" cy="30" rx="14" ry="18" fill="none" stroke="#4A3E3D" stroke-width="1.8" />
            
            <!-- Cuello y Hombros -->
            <path d="M 93 47 L 90 58 L 75 68 L 70 100 L 78 160 L 80 200 L 78 280 L 88 280 L 96 200 L 100 170 L 104 200 L 112 280 L 122 280 L 120 200 L 122 160 L 130 100 L 125 68 L 110 58 L 107 47 Z" 
                  fill="none" stroke="#4A3E3D" stroke-width="1.8" />
            
            <!-- Brazos -->
            <path d="M 75 68 L 65 110 L 62 165 L 68 165 L 72 118" fill="none" stroke="#4A3E3D" stroke-width="1.6" />
            <path d="M 125 68 L 135 110 L 138 165 L 132 165 L 128 118" fill="none" stroke="#4A3E3D" stroke-width="1.6" />

            <!-- Línea 1: Busto -->
            <line x1="68" y1="92" x2="132" y2="92" stroke="#8C7355" stroke-dasharray="3 3" stroke-width="1.5" />
            <line x1="132" y1="92" x2="160" y2="92" stroke="#8C7355" stroke-width="1" />
            <text x="163" y="95" class="label-svg">Busto</text>

            <!-- Línea 2: Cintura -->
            <line x1="72" y1="120" x2="128" y2="120" stroke="#8C7355" stroke-dasharray="3 3" stroke-width="1.5" />
            <line x1="128" y1="120" x2="160" y2="120" stroke="#8C7355" stroke-width="1" />
            <text x="163" y="123" class="label-svg">Cintura</text>

            <!-- Línea 3: Cadera -->
            <line x1="70" y1="145" x2="130" y2="145" stroke="#8C7355" stroke-dasharray="3 3" stroke-width="1.5" />
            <line x1="130" y1="145" x2="160" y2="145" stroke="#8C7355" stroke-width="1" />
            <text x="163" y="148" class="label-svg">Cadera</text>

            <!-- Línea 4: Largo de Pierna -->
            <line x1="100" y1="160" x2="100" y2="280" stroke="#8C7355" stroke-dasharray="2 2" stroke-width="1" />
            <line x1="112" y1="160" x2="160" y2="160" stroke="#8C7355" stroke-width="1" />
            <line x1="112" y1="280" x2="160" y2="280" stroke="#8C7355" stroke-width="1" />
            <line x1="150" y1="160" x2="150" y2="280" stroke="#8C7355" stroke-width="1" />
            <text x="155" y="215" class="label-svg-multiline">Largo de</text>
            <text x="155" y="228" class="label-svg-multiline">Pierna</text>
          </svg>
        </div>

        <!-- Lista explicativa numerada -->
        <ol class="lista-guia">
          <li><strong>Busto:</strong> Medir sobre la parte más prominente del pecho.</li>
          <li><strong>Cintura:</strong> Medir alrededor de la parte más angosta del torso.</li>
          <li><strong>Cadera:</strong> Medir la parte más ancha de la cadera estando de pie.</li>
          <li><strong>Largo de Pierna (Inseam):</strong> Medir desde la entrepierna hasta el tobillo.</li>
        </ol>

      </div>

    </div>

  </main>

  <ShopFooter />
</template>

<style scoped>
.talles-page {
  padding: 3rem 6%;
  background-color: #FAF8F5; /* Fondo cálido característico de María Urbana */
  min-height: 80vh;
}

.encabezado {
  text-align: center;
  margin-bottom: 2.5rem;
}

.encabezado h1 {
  font-family: 'Playfair Display', Georgia, serif;
  font-size: 2.4rem;
  color: #2C2623;
  margin-bottom: 0.4rem;
}

.encabezado p {
  color: #7A6E65;
  font-size: 1rem;
}

/* Grilla responsive de dos tarjetas */
.grid-talles {
  display: grid;
  grid-template-columns: 1.8fr 1fr;
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.card-seccion {
  background-color: #FFFFFF;
  border: 1px solid #EAE5DF;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}

.card-seccion h2 {
  font-size: 1.1rem;
  letter-spacing: 1px;
  color: #2C2623;
  margin-bottom: 1.5rem;
  font-weight: 700;
}

/* Tabla de valores */
.tabla-responsive {
  overflow-x: auto;
  /* Oculta la barra de scroll en navegadores webkit para un diseño más limpio, pero permite el swipe */
  -webkit-overflow-scrolling: touch; 
}

table {
  width: 100%;
  border-collapse: collapse;
  text-align: center;
}

th {
  font-size: 0.85rem;
  font-weight: 700;
  color: #4A3E3D;
  padding: 1rem 0.6rem;
  border-bottom: 2px solid #EAE5DF;
  letter-spacing: 0.5px;
  /* Evita que los títulos se corten en varias líneas */
  white-space: nowrap; 
}

td {
  padding: 1.1rem 0.6rem;
  border-bottom: 1px solid #F3EFEA;
  color: #554A46;
  font-size: 0.95rem;
  /* Mantiene los números en una sola línea */
  white-space: nowrap;
}

.col-talle {
  font-weight: bold;
  color: #2C2623;
}

/* Guía ilustrada */
.contenedor-ilustracion {
  display: flex;
  justify-content: center;
  padding: 1rem 0;
}

.svg-silueta {
  width: 100%;
  max-width: 220px;
  height: auto;
}

.label-svg {
  font-size: 11px;
  fill: #4A3E3D;
  font-weight: 600;
}

.label-svg-multiline {
  font-size: 10px;
  fill: #4A3E3D;
  font-weight: 600;
}

.lista-guia {
  margin-top: 1.5rem;
  padding-left: 1.2rem;
  border-top: 1px solid #F3EFEA;
  padding-top: 1.2rem;
}

.lista-guia li {
  font-size: 0.85rem;
  color: #665B55;
  margin-bottom: 0.5rem;
  line-height: 1.4;
}

/* --- ADAPTACIÓN A DISPOSITIVOS MÓVILES --- */

/* Tablets y pantallas medianas */
@media (max-width: 900px) {
  .grid-talles {
    grid-template-columns: 1fr; /* Pasa todo a una sola columna */
  }
}

/* Celulares y pantallas pequeñas */
@media (max-width: 600px) {
  .talles-page {
    padding: 1.5rem 1rem; /* Reduce el espacio a los costados de la pantalla */
  }

  .encabezado h1 {
    font-size: 1.8rem; /* Achica el título principal */
  }

  .encabezado p {
    font-size: 0.9rem;
  }

  .card-seccion {
    padding: 1.2rem; /* Reduce el espacio interior de las tarjetas blancas */
    gap: 1rem;
  }

  th {
    padding: 0.8rem 0.5rem;
    font-size: 0.75rem; /* Achica un poco la letra de la cabecera */
  }

  td {
    padding: 0.8rem 0.5rem;
    font-size: 0.85rem; /* Achica un poco los números */
  }

  .svg-silueta {
    max-width: 180px; /* Evita que el dibujo ocupe demasiada pantalla */
  }
}
</style>