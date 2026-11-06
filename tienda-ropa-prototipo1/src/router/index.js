import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // ==========================================
    // 🛒 RUTAS DEL COMPRADOR (TIENDA PÚBLICA)
    // ==========================================
    {
      path: '/',
      name: 'home',
      component: () => import('../views/shop/HomeView.vue')
    },
    {
      path: '/catalogo',
      name: 'catalogo',
      component: () => import('../views/shop/CatalogView.vue')
    },
    {
      path: '/tabla-de-talles',
      name: 'tabla-de-talles',
      component: () => import('../views/shop/TallesView.vue')
    },
    {
      path: '/producto/:id',
      name: 'producto',
      component: () => import('../views/shop/ProductDetailView.vue')
    },
    {
      path: '/carrito',
      name: 'carrito',
      component: () => import('../views/shop/CartView.vue')
    },
    {
      path: '/checkout',
      name: 'checkout',
      component: () => import('../views/shop/CheckoutView.vue')
    },

    // ==========================================
    // ⚙️ RUTAS INDEPENDIENTES (Sin el menú lateral)
    // ==========================================
    {
      path: '/setup',
      name: 'setup',
      component: () => import('../views/admin/SetupView.vue')
    },
    {
      path: '/admin/login', 
      name: 'admin-login',
      component: () => import('../views/admin/LoginView.vue') 
    },

    // ==========================================
    // ⚙️ RUTAS DEL ADMINISTRADOR
    // ==========================================
    {
      path: '/admin',
      component: () => import('../views/admin/AdminView.vue'),
      children: [
        {
          path: '',
          redirect: { name: 'admin-pedidos' } // Redirige por defecto a pedidos al entrar a /admin
        },
        {
          path: 'pedidos',
          name: 'admin-pedidos',
          component: () => import('../views/admin/AdminPedidosView.vue') // 👈 NUEVA RUTA DE PEDIDOS
        },
        {
          path: 'catalogo',
          name: 'admin-catalogo',
          component: () => import('../views/admin/AdminCatalogView.vue')
        },
        {
          path: 'agregar-producto',
          name: 'admin-agregar-producto',
          component: () => import('../views/admin/AdminAddProductView.vue')
        }
      ]
    }

  ]
})

// ==========================================
// 🛡️ EL "PORTERO" DE SEGURIDAD (Navigation Guard)
// ==========================================
router.beforeEach((to, from, next) => {
  const esRutaProtegida = to.path.startsWith('/admin');
  const esPaginaPublica = to.path === '/admin/login' || to.path === '/setup';
  
  // Ahora exigimos las DOS cosas: la bandera y la llave real
  const sesionIniciada = localStorage.getItem('sesionIniciada') === 'true';
  const tieneToken = localStorage.getItem('adminToken') !== null;

  if (esRutaProtegida && !esPaginaPublica && (!sesionIniciada || !tieneToken)) {
    next('/admin/login');
  } else {
    next();
  }
});

export default router