<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzería JJJ's - Nuestro Menú Completo</title>
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
                        darkBg: '#efda9e', // Fondo oscuro principal (este es el "beige" que quieres)
                        lightGray: '#f0f0f0', // Texto claro
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
    
</head>
<body class="font-poppins text-lightGray bg-darkBg leading-relaxed">

    <header class="main-header bg-topBarBg text-lightGray py-4 px-5 z-50 w-full top-0">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.html" class="logo flex items-center gap-2">
                <img src="/imagenes/JJJ_s_Pizzas-removebg-preview (2).png" alt="Logo de JJJ's Pizzas" class="h-12">
            </a>

            <nav class="nav-links hidden md:flex flex-col md:flex-row items-center gap-6 font-semibold uppercase text-sm">
                <a href="menu.php" class="relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-primary after:transition-all after:duration-300">Inicio</a>
                
                <a href="Acerca.php" class="relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-primary after:transition-all after:duration-300">Acerca de</a>
                <a href="#contacto" class="relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-[2px] after:bg-primary after:transition-all after:duration-300">Contacto</a>
            </nav>

            <div class="header-icons flex items-center gap-6">
                <button class="text-lightGray text-xl hover:text-primary transition-colors hidden md:block"><i class="fas fa-search"></i></button>
                
                <div class="user-dropdown relative">
                 
                    
                   <?php session_start(); ?>
                    <div class="relative">
                        <?php if (!isset($_SESSION['cliente_id'])): ?>
                            <!-- Botón para abrir el modal de login -->
                            <button id="openLoginModal" class="text-lightGray text-xl hover:text-primary transition-colors">
                                <i class="fas fa-user"></i>
                            </button>

                            <!-- Contenedor donde se carga el loginModal -->
                            <div id="contenedorLoginModal"></div>

                            <!-- Script que carga el modal por AJAX -->
                            <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                const loginModal = document.getElementById("contenedorLoginModal");
                                fetch("../clientes/loginModal.php") // Asegúrate que esta ruta sea correcta
                                    .then(response => response.text())
                                    .then(html => {
                                        loginModal.innerHTML = html;
                                    });

                                document.getElementById("openLoginModal").addEventListener("click", () => {
                                    document.getElementById("loginModalFondo").classList.remove("hidden");
                                });
                            });
                            </script>
                        
                        <?php else: ?>
                            <!-- Botón para mostrar el menú del perfil -->
                            <button id="perfilBtn" class="text-lightGray text-xl hover:text-primary transition-colors">
                                <i class="fas fa-user"></i>
                            </button>

                            <!-- Menú desplegable del usuario -->
                            <div id="userDropdown" class="absolute right-0 mt-3 w-48 bg-[#222] rounded-md shadow-lg py-2 hidden z-50">
                                <div class="px-4 py-2 text-sm text-white font-semibold">
                                    <?php echo htmlspecialchars($_SESSION['cliente_nombre']); ?>
                                </div>
                                <hr class="border-gray-700 my-1">
                                <a href="perfil.php" class="block px-4 py-2 text-sm text-lightGray hover:bg-primary hover:text-darkBg">Mi Perfil</a>
                                <a href="editarPerfil.php" class="block px-4 py-2 text-sm text-lightGray hover:bg-primary hover:text-darkBg">Editar Perfil</a>
                                <a href="misPedidos.php" class="block px-4 py-2 text-sm text-lightGray hover:bg-primary hover:text-darkBg">Mis Pedidos</a>
                                <a href="cerrarSesion.php" class="block px-4 py-2 text-sm text-lightGray hover:bg-primary hover:text-darkBg">Cerrar Sesión</a>
                            </div>

                            <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                const perfilBtn = document.getElementById("perfilBtn");
                                const dropdown = document.getElementById("userDropdown");

                                if (perfilBtn && dropdown) {
                                    perfilBtn.addEventListener("click", (e) => {
                                        e.stopPropagation();
                                        dropdown.classList.toggle("hidden");
                                    });

                                    document.addEventListener("click", (e) => {
                                        if (!dropdown.contains(e.target) && e.target !== perfilBtn) {
                                            dropdown.classList.add("hidden");
                                        }
                                    });
                                }
                            });
                            </script>
                        <?php endif; ?>
                    </div>




                </div> 
                
                

               



                <button class="text-darkerBg text-xl hover:text-primary transition-colors relative" id="open-cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                </button> 

                <script>
                    document.getElementById('open-cart-btn').addEventListener('click', () => {
                        document.getElementById('cart-sidebar').classList.remove('translate-x-full');
                        document.getElementById('cart-overlay').classList.remove('hidden');
                    });
                    document.getElementById('close-cart-btn').addEventListener('click', () => {
                        document.getElementById('cart-sidebar').classList.add('translate-x-full');
                        document.getElementById('cart-overlay').classList.add('hidden');
                    });
                    document.getElementById('cart-overlay').addEventListener('click', () => {
                        document.getElementById('cart-sidebar').classList.add('translate-x-full');
                        document.getElementById('cart-overlay').classList.add('hidden');
                    });


                </script>

               
            </div>
        </div>
    </header>

    <main class="py-10">
        <section class="text-center py-10 bg-darkerBg">
            <h1 class="font-oswald text-4xl md:text-5xl leading-tight uppercase text-mediumGray">Nuestro Menú Completo</h1>
            <p class="text-secondary text-lg md:text-xl mt-2">Sabores que te encantarán de principio a fin</p>
        </section>

        <section class="py-10 bg-darkBg">
            <div class="container mx-auto flex flex-wrap justify-center gap-6 px-4">
                <a href="#pizzas-populares" class="category-nav-item text-darkGray">
                    <i class="fas fa-star"></i>
                    <span class="text-darkGray">Populares</span>
                </a>
                <a href="#pizzas-clasicas" class="category-nav-item text-darkGray">
                    <i class="fas fa-pizza-slice"></i>
                    <span class="text-darkGray">Clásicas</span>
                </a>
                <a href="#pizzas-especiales" class="category-nav-item text-darkGray">
                    <i class="fas fa-fire"></i> <span class="text-darkGray">Especiales</span>
                </a>
                <a href="#pastas" class="category-nav-item text-darkGray">
                    <i class="fas fa-bowl-food"></i> <span class="text-darkGray">Pastas</span>
                </a>
                <a href="#entradas" class="category-nav-item text-darkGray">
                    <i class="fas fa-cheese"></i> <span class="text-darkGray">Entradas</span>
                </a>
                <a href="#bebidas" class="category-nav-item text-darkGray">
                    <i class="fas fa-cocktail"></i>
                    <span class="text-darkGray">Bebidas</span>
                </a>
                <a href="#postres" class="category-nav-item text-darkGray">
                    <i class="fas fa-ice-cream"></i>
                    <span class="text-darkGray">Postres</span>
                </a>
            </div>
        </section>
        
            <!-- PIZZAS POPULARES -->
<section id="pizzas-populares" class="popular-items-section relative py-20 px-5 text-center bg-darkerBg overflow-hidden">
    <h2 class="font-oswald text-3xl md:text-4xl text-mediumGray mb-12 uppercase relative">
        <span class="block text-secondary text-base md:text-lg font-normal mb-1">Nuestras Favoritas</span> 
        Pizzas Populares
    </h2>

    <div class="relative overflow-hidden max-w-6xl mx-auto">
        <div id="scroll-pizzas-populares" class="flex gap-6 transition-transform duration-500 ease-in-out will-change-transform">
            <?php
                include("../conexion/conectarBD.php");
                $categoriaId = 5; // Pizzas populares
                $sql = "SELECT * FROM platos WHERE categoria_id = $categoriaId";
                $resultado = mysqli_query($connect, $sql);
            ?>

            <?php while ($plato = mysqli_fetch_assoc($resultado)) : ?>
                <div class="food-item bg-[#2C3E50] min-w-[260px] flex-shrink-0 p-6 rounded-lg text-center transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-800">
                    <?php if (!empty($plato['plato_imagen_url'])) : ?>
                        <img src="<?= htmlspecialchars($plato['plato_imagen_url']) ?>" alt="Imagen del plato" class="w-44 h-44 object-cover rounded-full mx-auto mb-5 border-4 border-primary shadow-lg shadow-primary/50">
                    <?php else : ?>
                        <div class="w-44 h-44 bg-gray-300 flex items-center justify-center rounded-full mx-auto mb-5 text-gray-700">Sin imagen</div>
                    <?php endif; ?>

                    <h3 class="font-oswald text-2xl text-primary mb-2 uppercase"><?= htmlspecialchars($plato['plato_nombre']) ?></h3>
                    <p class="text-mediumGray text-sm mb-4"><?= htmlspecialchars($plato['plato_desc']) ?></p>
                    <p class="text-secondary text-2xl font-bold block mb-4">$<?= number_format($plato['plato_precio'], 0, ',', '.') ?></p>
                        <div 
                    <button 
                        class="add-to-cart-btn mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-red-700 transition"
                        data-id="<?= $plato['plato_id'] ?>"
                        data-nombre="<?= htmlspecialchars($plato['plato_nombre']) ?>"
                        data-precio="<?= $plato['plato_precio'] ?>"
                        data-img="<?= htmlspecialchars($plato['plato_imagen_url']) ?>">
                        Agregar al carrito
                    </button>

                    <!-- Botón para mostrar ingredientes -->
                    <button 
                        onclick="mostrarIngredientes(<?= $plato['plato_id'] ?>, this)"
                        class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                        Ver Ingredientes
                    </button>

                    <!-- Contenedor oculto de ingredientes -->
                    <ul class="ingredientes-list hidden text-sm text-gray-300 mt-2 list-disc list-inside text-left"></ul>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Flechas -->
        <button class="scroll-button left absolute left-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-pizzas-populares" data-scroll-direction="left">
            <i class="fas fa-chevron-left text-white text-2xl"></i>
        </button>
        <button class="scroll-button right absolute right-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-pizzas-populares" data-scroll-direction="right">
            <i class="fas fa-chevron-right text-white text-2xl"></i>
        </button>
    </div>
</section>




     <section id="pizzas-clasicas" class="popular-items-section relative py-20 px-5 text-center bg-darkBg overflow-hidden">
    <h2 class="font-oswald text-3xl md:text-4xl text-mediumGray mb-12 uppercase relative">
        <span class="block text-secondary text-base md:text-lg font-normal mb-1">Los Sabores de Siempre</span> 
        Pizzas Clásicas
    </h2>

    <div class="relative overflow-hidden max-w-6xl mx-auto">
        <div id="scroll-pizzas-clasicas" class="flex gap-6 transition-transform duration-500 ease-in-out will-change-transform">
            <?php
                $categoriaId = 6; // Pizzas clásicas
                $sql = "SELECT * FROM platos WHERE categoria_id = $categoriaId";
                $resultado = mysqli_query($connect, $sql);
            ?>

            <?php while ($plato = mysqli_fetch_assoc($resultado)) : ?>
                <div class="food-item bg-[#2C3E50] min-w-[260px] flex-shrink-0 p-6 rounded-lg text-center transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-800">
                    <?php if (!empty($plato['plato_imagen_url'])) : ?>
                        <img src="<?= htmlspecialchars($plato['plato_imagen_url']) ?>" alt="Imagen del plato" class="w-44 h-44 object-cover rounded-full mx-auto mb-5 border-4 border-primary shadow-lg shadow-primary/50">
                    <?php else : ?>
                        <div class="w-44 h-44 bg-gray-300 flex items-center justify-center rounded-full mx-auto mb-5 text-gray-700">Sin imagen</div>
                    <?php endif; ?>

                    <h3 class="font-oswald text-2xl text-primary mb-2 uppercase"><?= htmlspecialchars($plato['plato_nombre']) ?></h3>
                    <p class="text-mediumGray text-sm mb-4"><?= htmlspecialchars($plato['plato_desc']) ?></p>
                    <p class="text-secondary text-2xl font-bold block mb-4">$<?= number_format($plato['plato_precio'], 0, ',', '.') ?></p>

                    <button 
                        class="add-to-cart-btn mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-red-700 transition"
                        data-id="<?= $plato['plato_id'] ?>"
                        data-nombre="<?= htmlspecialchars($plato['plato_nombre']) ?>"
                        data-precio="<?= $plato['plato_precio'] ?>"
                        data-img="<?= htmlspecialchars($plato['plato_imagen_url']) ?>">
                        Agregar al carrito
                    </button>

                    <!-- Botón para mostrar ingredientes -->
                    <button 
                        onclick="mostrarIngredientes(<?= $plato['plato_id'] ?>, this)"
                        class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                        Ver Ingredientes
                    </button>

                    <!-- Contenedor oculto de ingredientes -->
                    <ul class="ingredientes-list hidden text-sm text-gray-300 mt-2 list-disc list-inside text-left"></ul>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Flechas -->
        <button class="scroll-button left absolute left-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-pizzas-clasicas" data-scroll-direction="left">
            <i class="fas fa-chevron-left text-white text-2xl"></i>
        </button>
        <button class="scroll-button right absolute right-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-pizzas-clasicas" data-scroll-direction="right">
            <i class="fas fa-chevron-right text-white text-2xl"></i>
        </button>
    </div>
</section>





      <section id="pizzas-especiales" class="popular-items-section relative py-20 px-5 text-center bg-darkerBg overflow-hidden">
    <h2 class="font-oswald text-3xl md:text-4xl text-mediumGray mb-12 uppercase relative">
        <span class="block text-secondary text-base md:text-lg font-normal mb-1">Creaciones Únicas</span> 
        Pizzas Especiales
    </h2>

    <div class="relative overflow-hidden max-w-6xl mx-auto">
        <div id="scroll-pizzas-especiales" class="flex gap-6 transition-transform duration-500 ease-in-out will-change-transform">
            <?php
                $categoriaId = 7; // Pizzas especiales
                $sql = "SELECT * FROM platos WHERE categoria_id = $categoriaId";
                $resultado = mysqli_query($connect, $sql);
            ?>

            <?php while ($plato = mysqli_fetch_assoc($resultado)) : ?>
                <div class="food-item bg-[#2C3E50] min-w-[260px] flex-shrink-0 p-6 rounded-lg text-center transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-800">
                    <?php if (!empty($plato['plato_imagen_url'])) : ?>
                        <img src="<?= htmlspecialchars($plato['plato_imagen_url']) ?>" alt="Imagen del plato" class="w-44 h-44 object-cover rounded-full mx-auto mb-5 border-4 border-primary shadow-lg shadow-primary/50">
                    <?php else : ?>
                        <div class="w-44 h-44 bg-gray-300 flex items-center justify-center rounded-full mx-auto mb-5 text-gray-700">Sin imagen</div>
                    <?php endif; ?>

                    <h3 class="font-oswald text-2xl text-primary mb-2 uppercase"><?= htmlspecialchars($plato['plato_nombre']) ?></h3>
                    <p class="text-mediumGray text-sm mb-4"><?= htmlspecialchars($plato['plato_desc']) ?></p>
                    <p class="text-secondary text-2xl font-bold block mb-4">$<?= number_format($plato['plato_precio'], 0, ',', '.') ?></p>

                    <button 
                        class="add-to-cart-btn mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-red-700 transition"
                        data-id="<?= $plato['plato_id'] ?>"
                        data-nombre="<?= htmlspecialchars($plato['plato_nombre']) ?>"
                        data-precio="<?= $plato['plato_precio'] ?>"
                        data-img="<?= htmlspecialchars($plato['plato_imagen_url']) ?>">
                        Agregar al carrito
                    </button>

                    <!-- Botón para mostrar ingredientes -->
                    <button 
                        onclick="mostrarIngredientes(<?= $plato['plato_id'] ?>, this)"
                        class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                        Ver Ingredientes
                    </button>

                    <!-- Contenedor oculto de ingredientes -->
                    <ul class="ingredientes-list hidden text-sm text-gray-300 mt-2 list-disc list-inside text-left"></ul>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Flechas funcionales -->
        <button class="scroll-button left absolute left-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-pizzas-especiales" data-scroll-direction="left">
            <i class="fas fa-chevron-left text-white text-2xl"></i>
        </button>
        <button class="scroll-button right absolute right-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-pizzas-especiales" data-scroll-direction="right">
            <i class="fas fa-chevron-right text-white text-2xl"></i>
        </button>
    </div>
</section>




 <section id="pastas" class="popular-items-section relative py-20 px-5 text-center bg-darkBg overflow-hidden">
    <h2 class="font-oswald text-3xl md:text-4xl text-mediumGray mb-12 uppercase relative">
        <span class="block text-secondary text-base md:text-lg font-normal mb-1">Delicias Italianas</span> 
        Pastas
    </h2>

    <div class="relative overflow-hidden max-w-6xl mx-auto">
        <div id="scroll-pastas" class="flex gap-6 transition-transform duration-500 ease-in-out will-change-transform">
            <?php
                $categoriaId = 2; // Pastas
                $sql = "SELECT * FROM platos WHERE categoria_id = $categoriaId";
                $resultado = mysqli_query($connect, $sql);
            ?>
            
            <?php while ($plato = mysqli_fetch_assoc($resultado)) : ?>
                <div class="food-item bg-[#2C3E50] min-w-[260px] flex-shrink-0 p-6 rounded-lg text-center transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-800">
                    <?php if (!empty($plato['plato_imagen_url'])) : ?>
                        <img src="<?= htmlspecialchars($plato['plato_imagen_url']) ?>" alt="Imagen del plato" class="w-44 h-44 object-cover rounded-full mx-auto mb-5 border-4 border-primary shadow-lg shadow-primary/50">
                    <?php else : ?>
                        <div class="w-44 h-44 bg-gray-300 flex items-center justify-center rounded-full mx-auto mb-5 text-gray-700">Sin imagen</div>
                    <?php endif; ?>

                    <h3 class="font-oswald text-2xl text-primary mb-2 uppercase"><?= htmlspecialchars($plato['plato_nombre']) ?></h3>
                    <p class="text-mediumGray text-sm mb-4"><?= htmlspecialchars($plato['plato_desc']) ?></p>
                    <p class="text-secondary text-2xl font-bold block mb-4">$<?= number_format($plato['plato_precio'], 0, ',', '.') ?></p>

                    <button 
                        class="add-to-cart-btn mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-red-700 transition"
                        data-id="<?= $plato['plato_id'] ?>"
                        data-nombre="<?= htmlspecialchars($plato['plato_nombre']) ?>"
                        data-precio="<?= $plato['plato_precio'] ?>"
                        data-img="<?= htmlspecialchars($plato['plato_imagen_url']) ?>">
                        Agregar al carrito
                    </button>

                    <button 
                        onclick="mostrarIngredientes(<?= $plato['plato_id'] ?>, this)"
                        class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                        Ver Ingredientes
                    </button>

                    <ul class="ingredientes-list hidden text-sm text-gray-300 mt-2 list-disc list-inside text-left"></ul>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Flechas -->
        <button class="scroll-button left absolute left-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-pastas" data-scroll-direction="left">
            <i class="fas fa-chevron-left text-white text-2xl"></i>
        </button>
        <button class="scroll-button right absolute right-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-pastas" data-scroll-direction="right">
            <i class="fas fa-chevron-right text-white text-2xl"></i>
        </button>
    </div>
</section>






       <section id="entradas" class="popular-items-section relative py-20 px-5 text-center bg-darkerBg overflow-hidden">
    <h2 class="font-oswald text-3xl md:text-4xl text-mediumGray mb-12 uppercase relative">
        <span class="block text-secondary text-base md:text-lg font-normal mb-1">Para Comenzar</span> Entradas
    </h2>

    <div class="relative overflow-hidden max-w-6xl mx-auto">
        <div id="scroll-entradas" class="flex gap-6 transition-transform duration-500 ease-in-out will-change-transform">
            <?php
                $categoriaId = 1; // Entradas
                $sql = "SELECT * FROM platos WHERE categoria_id = $categoriaId";
                $resultado = mysqli_query($connect, $sql);
            ?>
            
            <?php while ($plato = mysqli_fetch_assoc($resultado)) : ?>
                <div class="food-item bg-[#2C3E50] min-w-[260px] flex-shrink-0 p-6 rounded-lg text-center transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-800">
                    <?php if (!empty($plato['plato_imagen_url'])) : ?>
                        <img src="<?= htmlspecialchars($plato['plato_imagen_url']) ?>" alt="Imagen del plato" class="w-44 h-44 object-cover rounded-full mx-auto mb-5 border-4 border-primary shadow-lg shadow-primary/50">
                    <?php else : ?>
                        <div class="w-44 h-44 bg-gray-300 flex items-center justify-center rounded-full mx-auto mb-5 text-gray-700">Sin imagen</div>
                    <?php endif; ?>

                    <h3 class="font-oswald text-2xl text-primary mb-2 uppercase"><?= htmlspecialchars($plato['plato_nombre']) ?></h3>
                    <p class="text-mediumGray text-sm mb-4"><?= htmlspecialchars($plato['plato_desc']) ?></p>
                    <p class="text-secondary text-2xl font-bold block mb-4">$<?= number_format($plato['plato_precio'], 0, ',', '.') ?></p>

                    <button 
                        class="add-to-cart-btn mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-red-700 transition"
                        data-id="<?= $plato['plato_id'] ?>"
                        data-nombre="<?= htmlspecialchars($plato['plato_nombre']) ?>"
                        data-precio="<?= $plato['plato_precio'] ?>"
                        data-img="<?= htmlspecialchars($plato['plato_imagen_url']) ?>">
                        Agregar al carrito
                    </button>

                    <!-- Botón para mostrar ingredientes -->
                    <button 
                        onclick="mostrarIngredientes(<?= $plato['plato_id'] ?>, this)"
                        class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                        Ver Ingredientes
                    </button>

                    <!-- Contenedor oculto de ingredientes -->
                    <ul class="ingredientes-list hidden text-sm text-gray-300 mt-2 list-disc list-inside text-left"></ul>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Flechas -->
        <button class="scroll-button left absolute left-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-entradas" data-scroll-direction="left">
            <i class="fas fa-chevron-left text-white text-2xl"></i>
        </button>
        <button class="scroll-button right absolute right-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-entradas" data-scroll-direction="right">
            <i class="fas fa-chevron-right text-white text-2xl"></i>
        </button>
    </div>
</section>





        <section id="postres" class="popular-items-section relative py-20 px-5 text-center bg-darkerBg overflow-hidden">
    <h2 class="font-oswald text-3xl md:text-4xl text-mediumGray mb-12 uppercase relative">
        <span class="block text-secondary text-base md:text-lg font-normal mb-1">El Toque Final</span> Postres
    </h2>

    <div class="relative overflow-hidden max-w-6xl mx-auto">
        <div id="scroll-postres" class="flex gap-6 transition-transform duration-500 ease-in-out will-change-transform">
            <?php
                $categoriaId = 4; // Postres
                $sql = "SELECT * FROM platos WHERE categoria_id = $categoriaId";
                $resultado = mysqli_query($connect, $sql);
            ?>

            <?php while ($plato = mysqli_fetch_assoc($resultado)) : ?>
                <div class="food-item bg-[#2C3E50] min-w-[260px] flex-shrink-0 p-6 rounded-lg text-center transition-transform duration-300 hover:scale-105 hover:shadow-xl border border-gray-800">
                    <?php if (!empty($plato['plato_imagen_url'])) : ?>
                        <img src="<?= htmlspecialchars($plato['plato_imagen_url']) ?>" alt="Imagen del plato" class="w-44 h-44 object-cover rounded-full mx-auto mb-5 border-4 border-primary shadow-lg shadow-primary/50">
                    <?php else : ?>
                        <div class="w-44 h-44 bg-gray-300 flex items-center justify-center rounded-full mx-auto mb-5 text-gray-700">Sin imagen</div>
                    <?php endif; ?>

                    <h3 class="font-oswald text-2xl text-primary mb-2 uppercase"><?= htmlspecialchars($plato['plato_nombre']) ?></h3>
                    <p class="text-mediumGray text-sm mb-4"><?= htmlspecialchars($plato['plato_desc']) ?></p>
                    <p class="text-secondary text-2xl font-bold block mb-4">$<?= number_format($plato['plato_precio'], 0, ',', '.') ?></p>

                    <button 
                        class="add-to-cart-btn mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-red-700 transition"
                        data-id="<?= $plato['plato_id'] ?>"
                        data-nombre="<?= htmlspecialchars($plato['plato_nombre']) ?>"
                        data-precio="<?= $plato['plato_precio'] ?>"
                        data-img="<?= htmlspecialchars($plato['plato_imagen_url']) ?>">
                        Agregar al carrito
                    </button>

                    <!-- Botón para ver ingredientes -->
                    <button 
                        onclick="mostrarIngredientes(<?= $plato['plato_id'] ?>, this)"
                        class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                        Ver Ingredientes
                    </button>

                    <!-- Contenedor de ingredientes oculto -->
                    <ul class="ingredientes-list hidden text-sm text-gray-300 mt-2 list-disc list-inside text-left"></ul>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Flechas funcionales -->
        <button class="scroll-button left absolute left-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-postres" data-scroll-direction="left">
            <i class="fas fa-chevron-left text-white text-2xl"></i>
        </button>
        <button class="scroll-button right absolute right-4 top-1/2 transform -translate-y-1/2 z-10" data-scroll-target="scroll-postres" data-scroll-direction="right">
            <i class="fas fa-chevron-right text-white text-2xl"></i>
        </button>
    </div>
</section>
    </main>






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
                    <li class="mb-2"><a href="index.html" class="text-mediumGray hover:text-primary transition-colors">Inicio</a></li>
                    <li class="mb-2"><a href="#pizzas-populares" class="text-mediumGray hover:text-primary transition-colors">Menú</a></li>
                    <li class="mb-2"><a href="index.html#about" class="text-mediumGray hover:text-primary transition-colors">Acerca de Nosotros</a></li>
                    <li class="mb-2"><a href="index.html#contact" id="contacto" class="text-mediumGray hover:text-primary transition-colors">Contacto</a></li>
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
                <h3 class="font-oswald text-xl text-primary mb-5 uppercase">Regístrate</h3>
                <p class="mb-4">No te pierdas nuestras últimas ofertas y noticias.</p>
                <form action="#" class="flex mt-4 max-w-xs mx-auto md:mx-0">
                    <input type="email" placeholder="Tu correo electrónico" class="flex-grow p-3 border-none rounded-l-md bg-[#333] text-lightGray outline-none placeholder:text-mediumGray">
                    <button type="submit" class="bg-primary text-white p-3 rounded-r-md cursor-pointer hover:bg-red-700 transition-colors"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </footer>

<!-- Sidebar del carrito -->
<div class="cart-sidebar fixed top-0 right-0 w-full max-w-md h-full bg-darkBg z-[1000] transition-transform duration-300 transform translate-x-full flex flex-col" id="cart-sidebar">
    <div class="flex justify-between items-center p-5 border-b border-gray-700">
        <h2 class="text-2xl font-oswald text-primary uppercase">Tu Carrito</h2>
        <button class="text-lightGray text-2xl hover:text-primary transition-colors" id="close-cart-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="cart-items flex-grow overflow-y-auto p-5" id="cart-items-container">
        <p class="text-mediumGray text-center mt-10" id="empty-cart-message">Tu carrito está vacío.</p>
    </div>
    <div class="p-5 border-t border-gray-700">
        <div class="flex justify-between items-center text-lg font-bold mb-4">
            <span>Subtotal:</span>
            <span id="cart-subtotal">$0.00</span>
        </div>
        <button id="btn-proceder-pago" class="bg-secondary text-darkBg py-3 w-full rounded-md font-semibold uppercase text-lg hover:bg-yellow-600 transition-colors">
            Proceder al Pago
        </button>

        <button class="bg-gray-700 text-lightGray py-3 w-full rounded-md font-semibold uppercase text-lg mt-3 hover:bg-gray-600 transition-colors" id="clear-cart-btn">Vaciar Carrito</button>
    </div>
    
   

</div>

<?php include('../metodo_pago/metodoPago.php'); ?>




<!-- Script del carrito -->
<script>
    let carrito = [];

    const overlay = document.getElementById("cart-overlay");
    const sidebar = document.getElementById("cart-sidebar");
    const closeBtn = document.getElementById("close-cart-btn");
    const cartItemsContainer = document.getElementById("cart-items-container");
    const cartSubtotal = document.getElementById("cart-subtotal");
    const emptyMessage = document.getElementById("empty-cart-message");

    document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre;
            const precio = parseFloat(btn.dataset.precio);
            const img = btn.dataset.img;

            const item = carrito.find(p => p.id === id);
            if (item) {
                item.cantidad += 1;
            } else {
                carrito.push({ id, nombre, precio, cantidad: 1, img });
            }

            renderCarrito();
            abrirCarrito();
        });
    });

    function renderCarrito() {
        cartItemsContainer.innerHTML = "";
        let total = 0;

        if (carrito.length === 0) {
            emptyMessage.classList.remove("hidden");
            cartSubtotal.textContent = "$0.00";
            return;
        }

        emptyMessage.classList.add("hidden");

        carrito.forEach(producto => {
            const item = document.createElement("div");
            item.className = "flex items-center justify-between mb-4";
            item.innerHTML = `
                <div class="flex items-center gap-3">
                    
                        <h4 class="text-white text-sm">${producto.nombre}</h4>
                        <p class="text-gray-400 text-xs">x${producto.cantidad}</p>
                    </div>
                </div>
                <div class="text-white font-bold">$${(producto.precio * producto.cantidad).toLocaleString()}</div>
            `;
            cartItemsContainer.appendChild(item);
            total += producto.precio * producto.cantidad;
        });

        cartSubtotal.textContent = `$${total.toLocaleString()}`;
    }

    function abrirCarrito() {
        sidebar.classList.remove("translate-x-full");
        overlay.classList.remove("hidden");
    }

    function cerrarCarrito() {
        sidebar.classList.add("translate-x-full");
        overlay.classList.add("hidden");
    }

    closeBtn.addEventListener("click", cerrarCarrito);
    overlay.addEventListener("click", cerrarCarrito);

    document.getElementById("clear-cart-btn").addEventListener("click", () => {
        carrito = [];
        renderCarrito();
    });
</script>

<script>
    // Mostrar modal de método de pago
    document.getElementById("btn-proceder-pago").addEventListener("click", () => {
        document.getElementById("modal-metodo-pago").classList.remove("hidden");
    });

    // Cerrar el modal
    document.getElementById("cerrar-modal-metodo-pago").addEventListener("click", () => {
        document.getElementById("modal-metodo-pago").classList.add("hidden");
    });

    // También puedes cerrar haciendo clic fuera del contenido
    document.getElementById("modal-metodo-pago").addEventListener("click", (e) => {
        if (e.target.id === "modal-metodo-pago") {
            e.target.classList.add("hidden");
        }
    });
</script>


   
<script src="../clientes/js/modalLogin.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const tarjetaWidth = 280; // ancho estimado de cada plato (ajústalo si usas más padding)

    // seleccionar todos los botones de scroll
    document.querySelectorAll(".scroll-button").forEach(boton => {
        const targetId = boton.dataset.scrollTarget;
        const container = document.getElementById(targetId);
        let scrollX = 0;

        // calcular tarjetas visibles y desplazamiento máximo
        const calcularMaxScroll = () => {
            const totalTarjetas = container.children.length;
            const visibles = Math.floor(container.parentElement.offsetWidth / tarjetaWidth);
            const maxScroll = (totalTarjetas - visibles) * tarjetaWidth;
            return Math.max(0, maxScroll);
        };

        boton.addEventListener("click", () => {
            const direccion = boton.dataset.scrollDirection;
            const maxScroll = calcularMaxScroll();

            if (direccion === "left") {
                scrollX = Math.max(0, scrollX - tarjetaWidth);
            } else {
                scrollX = Math.min(maxScroll, scrollX + tarjetaWidth);
            }

            container.style.transform = `translateX(-${scrollX}px)`;
        });
    });
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function mostrarIngredientes(idPlato, boton) {
    $.ajax({
        url: "verIngredientes.php", // Asegúrate de tener este archivo
        method: "POST",
        data: { id_plato: idPlato },
        success: function (respuesta) {
            $("#contenidoIngredientes").html('<button onclick="cerrarModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black">✖</button>' + respuesta);
            $("#modalIngredientes").removeClass("hidden").addClass("flex");
        },
        error: function () {
            alert("Error al cargar los ingredientes.");
        }
    });
}

function cerrarModal() {
    $("#modalIngredientes").removeClass("flex").addClass("hidden");
}
</script>

<div id="modalIngredientes" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-4 rounded shadow-lg w-1/2 relative" id="contenidoIngredientes">
        <button onclick="cerrarModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black">✖</button>
        <!-- Aquí se carga el contenido -->
    </div>
</div>

</body>
</html>