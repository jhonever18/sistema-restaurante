<?php
$connect = include("../conexion/conectarBD.php");

$consulta = "SELECT plato_id, plato_nombre, plato_desc, plato_precio, plato_imagen_url FROM platos WHERE es_popular = 1";

$resultado = mysqli_query($connect, $consulta);
?>


<!DOCTYPE html>
 <html lang="es">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Pizzería JJJ'S - Menú y Pedidos Online</title>
     <script src="https://cdn.tailwindcss.com"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
      
     <script>
         tailwind.config = {
             theme: {
                 extend: {
                     colors: {
                         primary: '#e44d26', // Rojo anaranjado similar al del ejemplo
                         secondary: '#f7b731', // Un toque de naranja/amarillo
                         darkBg: '#efda9e', // Fondo oscuro principal
                         lightGray: '#2C3E50', // Texto claro
                         darkGray: '#333', // Texto oscuro (no muy usado en este tema)
                         mediumGray: '#b0b0b0', // Gris para textos secundarios
                         darkerBg: '#FAF3E0', // Fondo un poco más oscuro
                         topBarBg: '#0d0d0d', // Fondo de la barra superior
                     },
                     fontFamily: {
                         poppins: ['Poppins', 'sans-serif'],
                         oswald: ['Oswald', 'sans-serif'],
                     },
                     animation: {
                         float: 'floatImage 3s ease-in-out infinite',
                     },
                     keyframes: {
                         floatImage: {
                             '0%, 100%': { transform: 'translateY(0)' },
                             '50%': { transform: 'translateY(-20px)' },
                         }
                     }
                 }
             }
         }
     </script>
     <style>
         /* Estilos para el efecto de underline en los enlaces de navegación */
         .nav-links a.active,
         .nav-links a:hover {
             color: theme('colors.primary');
         }
         .nav-links a.active::after,
         .nav-links a:hover::after {
             width: 100%;
         }
 
         /* Estilo para el header sticky */
         .main-header.scrolled {
             @apply bg-darkBg shadow-lg;
             position: fixed;
             top: 0;
             width: 100%;
             animation: slideDown 0.3s ease-out;
             z-index: 50;
         }
 
         @keyframes slideDown {
             from { transform: translateY(-100%); }
             to { transform: translateY(0); }
         }
 
         /* Efectos de fuego/salsa en Hero Section (si quieres mantenerlos) */
         .hero-section::before,
         .hero-section::after {
             content: '';
             position: absolute;
             width: 100%;
             height: 150px;
             /* Si no tienes fire-effect.png, puedes quitar estas líneas o usar una imagen genérica */
             /* background: url('/imagenes/fire-effect.png') no-repeat center bottom / cover; */
             z-index: 1;
         }
 
         .hero-section::before {
             top: 0;
             transform: rotate(180deg);
         }
 
         .hero-section::after {
             bottom: 0;
         }
 
         /* Efectos de iconos en Popular Items Section */
         .popular-items-section::before,
         .popular-items-section::after {
             content: '';
             position: absolute;
             width: 80px;
             height: 80px;
             background-size: contain;
             background-repeat: no-repeat;
             opacity: 0.3;
             z-index: 0;
         }
 
         .popular-items-section::before {
             top: 20px;
             left: 20px;
             background-image: url('/imagenes/chili-pepper.png');
             transform: rotate(-20deg);
         }
 
         .popular-items-section::after {
             bottom: 20px;
             right: 20px;
             background-image: url('/imagenes/herbs.png');
             transform: rotate(30deg);
         }
 
         /* Dropdown para móvil */
         .nav-links.active {
             display: flex !important;
         }
         .user-dropdown.active .user-dropdown-content {
             display: block !important;
         }
 
         /* Estilos para el carrito flotante */
         .cart-sidebar {
             position: fixed;
             top: 0;
             right: -400px;
             width: 100%;
             max-width: 400px;
             height: 100%;
             background-color: theme('colors.darkBg');
             box-shadow: -5px 0 15px rgba(0,0,0,0.5);
             z-index: 1000;
             transition: right 0.3s ease-out;
             display: flex;
             flex-direction: column;
         }
 
         .cart-sidebar.open {
             right: 0;
         }
 
         .overlay {
             position: fixed;
             top: 0;
             left: 0;
             width: 100%;
             height: 100%;
             background-color: rgba(0, 0, 0, 0.7);
             z-index: 999;
             display: none;
         }
 
         .overlay.active {
             display: block;
         }
 
         /* Estilo para el fondo en la sección Hero */
         .hero-background-image { /* Cambié el nombre de la clase de hero-gif-background a hero-background-image */
             position: absolute;
             top: 0;
             left: 0;
             width: 100%;
             height: 100%;
             object-fit: cover; /* Asegura que la imagen cubra todo el espacio */
             z-index: -1; /* Envía la imagen detrás del contenido */
             filter: brightness(0.5) contrast(1.2); /* Ajusta brillo y contraste para que el texto sea legible */
             opacity: 0.8; /* Ajusta la opacidad para que no opaque demasiado el texto */
         }
     </style>
 </head>
 <body class="font-poppins text-lightGray bg-darkBg leading-relaxed">
 
     
 
    <section class="hero-section relative flex items-center justify-center py-24 md:py-40 min-h-[650px] text-center overflow-hidden">
         <img src="/imagenes/8603a2e5ef153a757308327dd154b7bc.jpg" alt="Fondo de pizza y ambiente italiano" class="hero-background-image">
         
         <div class="absolute inset-0 bg-black opacity-60 z-0"></div>
 
         <div class="hero-content relative z-10 px-5 md:px-0 max-w-2xl mx-auto">
             <p class="text-secondary text-lg md:text-xl mb-2 font-semibold">¡Bienvenido a JJJ's Pizzas!</p>
             <h1 class="font-oswald text-4xl md:text-6xl leading-tight mb-8 uppercase text-white">OBTÉN COMIDA DE LA MEJOR CALIDAD DE NOSOTROS</h1>
             <div class="flex justify-center"> <a href="menuPlatos.php" class="inline-block py-3 px-8 rounded-md bg-transparent text-white border-2 border-secondary font-semibold uppercase text-sm hover:bg-secondary hover:text-darkBg transition-all">EXPLORAR MENÚ</a>
             </div>
         </div>
    </section>
 
    <section id="popular-items" class="popular-items-section relative py-20 px-5 text-center bg-darkerBg overflow-hidden">
        <h2 class="font-oswald text-3xl md:text-4xl text-lightGray mb-12 uppercase relative">
            <span class="block text-secondary text-base md:text-lg font-normal mb-1">Lo más vendido</span> 
            Nuestros Productos Populares
        </h2>
        
        <div class="food-items-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
            <?php while ($plato = mysqli_fetch_assoc($resultado)) : ?>
                <div class="food-item bg-[#2C3E50] p-6 rounded-lg text-center transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-800">
                    <?php if (!empty($plato['plato_imagen_url'])) : ?>
                        <img src="<?= htmlspecialchars($plato['plato_imagen_url']) ?>" alt="Imagen del plato" class="w-44 h-44 object-cover rounded-full mx-auto mb-5 border-4 border-primary shadow-lg shadow-primary/50">
                    <?php else : ?>
                        <div class="w-full h-40 bg-gray-300 flex items-center justify-center rounded mb-4 text-gray-700">Sin imagen</div>
                    <?php endif; ?>

                    <h3 class="font-oswald text-2xl text-primary mb-2 uppercase"><?= htmlspecialchars($plato['plato_nombre']) ?></h3>
                    <p class="text-mediumGray text-sm mb-4"><?= htmlspecialchars($plato['plato_desc']) ?></p>
                    <p class="text-secondary text-2xl font-bold block mb-4">$<?= number_format($plato['plato_precio'], 0, ',', '.') ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </section>



 
     <section class="flex flex-col md:flex-row items-center gap-12 py-20 px-5 bg-darkBg max-w-7xl mx-auto">
         <div class="flex-1 relative">
             <img src="/imagenes/4f6d37b8954941c79f2ba4ea325b4aa5.jpg" alt="Plato de pasta y vino" class="rounded-lg shadow-lg hover:scale-[1.02] transition-transform duration-300">
         </div>
         <div class="flex-1 text-center md:text-left text-lightGray">
             <p class="text-secondary text-lg uppercase font-semibold mb-2">Acerca de la Empresa</p>
             <h2 class="font-oswald text-3xl md:text-4xl leading-tight mb-6 text-lightGray uppercase">Donde la Comida de Calidad se Encuentra con un Servicio Excelente.</h2>
             <p class="text-mediumGray text-base leading-relaxed mb-8">La experiencia gastronómica perfecta donde cada plato es elaborado con ingredientes frescos de alta calidad y servido por un personal amable y amigable que se esfuerza por ir más allá. En Cali, somos tu mejor opción para disfrutar de una verdadera pizza italiana.</p>
             <div class="flex flex-col sm:flex-row gap-5 mb-8">
                 <div class="bg-[#222] p-5 rounded-lg flex-1 text-center border-l-4 border-primary shadow-md">
                     <h3 class="font-oswald text-xl text-primary mb-1 uppercase">Comida Rápida</h3>
                     <p class="text-mediumGray text-sm">Nuestros alimentos son ricos en nutrientes.</p>
                 </div>
                 <div class="bg-[#222] p-5 rounded-lg flex-1 text-center border-l-4 border-primary shadow-md">
                     <h3 class="font-oswald text-xl text-primary mb-1 uppercase">Comida Rápida</h3>
                     <p class="text-mediumGray text-sm">Nuestros alimentos son ricos en nutrientes.</p>
                 </div>
             </div>
             <a href="#" class="inline-block bg-primary text-white py-3 px-8 rounded-md font-semibold uppercase text-sm hover:bg-red-700 transition-colors">SABER MÁS</a>
             
         </div>
     </section>
 
     <footer class="bg-darkerBg py-16 px-5 text-mediumGray text-sm">
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 max-w-6xl mx-auto mb-10">
             <div class="flex flex-col items-center md:items-start text-center md:text-left">
                 <img src="/imagenes/JJJ_s_Pizzas-removebg-preview (2).png" alt="Logo Blanco" class="h-16 mb-5">
                 <p class="mb-5">Tu pizzería favorita en Cali. Disfruta de la mejor pizza artesanal con ingredientes frescos y el toque italiano que nos caracteriza.</p>
                 <div class="flex space-x-4">
                     <a href="#" class="text-mediumGray text-xl hover:text-primary transition-colors"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="text-mediumGray text-xl hover:text-primary transition-colors"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="text-mediumGray text-xl hover:text-primary transition-colors"><i class="fab fa-instagram"></i></a>
                     <a href="#" class="text-mediumGray text-xl hover:text-primary transition-colors"><i class="fab fa-linkedin-in"></i></a>
                 </div>
             </div>
             <div class="text-center md:text-left">
                 <h3 class="font-oswald text-xl text-primary mb-5 uppercase">Enlaces Rápidos</h3>
                 <ul>
                     <li class="mb-2"><a href="#" class="text-mediumGray hover:text-primary transition-colors">Inicio</a></li>
                     <li class="mb-2"><a href="#popular-items" class="text-mediumGray hover:text-primary transition-colors">Menú</a></li>
                     <li class="mb-2"><a href="#" class="text-mediumGray hover:text-primary transition-colors">Acerca de Nosotros</a></li>
                     <li class="mb-2"><a href="#" class="text-mediumGray hover:text-primary transition-colors">Contacto</a></li>
                 </ul>
             </div>
             <div class="text-center md:text-left">
                 <h3 class="font-oswald text-xl text-primary mb-5 uppercase">Contacto</h3>
                 <p class="mb-2 flex items-start justify-center md:justify-start gap-2"><i class="fas fa-map-marker-alt text-primary mt-1"></i>Cali, Valle del Cauca</p>
                 <p class="mb-2 flex items-start justify-center md:justify-start gap-2"><i class="fas fa-phone-alt text-primary mt-1"></i> +57</p>
                 <p class="mb-2 flex items-start justify-center md:justify-start gap-2"><i class="fas fa-envelope text-primary mt-1"></i> JJJ'sPizzas@gmail.com</p>
                 <p class="mb-2 flex items-start justify-center md:justify-start gap-2"><i class="fas fa-clock text-primary mt-1"></i> Lun-Dom: 11:00 AM - 11:00 PM</p>
             </div>
             <div class="text-center md:text-left">
                 <h3 class="font-oswald text-xl text-primary mb-5 uppercase">Registrate</h3>
                 <p class="mb-4">No te pierdas nuestras últimas ofertas y noticias.</p>
                 <form action="#" class="flex mt-4 max-w-xs mx-auto md:mx-0">
                     <input type="email" placeholder="Tu correo electrónico" class="flex-grow p-3 border-none rounded-l-md bg-[#333] text-lightGray outline-none placeholder:text-mediumGray">
                     <button type="submit" class="bg-primary text-white p-3 rounded-r-md cursor-pointer hover:bg-red-700 transition-colors"><i class="fas fa-paper-plane"></i></button>
                 </form>
             </div>
         </div>
         
     </footer>
 
 
     <div class="overlay" id="cart-overlay"></div>
 
     <script src="/clientes/js/script.js"></script>
 </body>
 </html>