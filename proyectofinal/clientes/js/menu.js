document.addEventListener('DOMContentLoaded', () => {
    // --- Funcionalidad del carrito ---
    const openCartBtn = document.getElementById('open-cart-btn');
    const closeCartBtn = document.getElementById('close-cart-btn');
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartOverlay = document.getElementById('cart-overlay');
    const addToCartButtons = document.querySelectorAll('.btn-add-to-cart');
    const cartItemsContainer = document.getElementById('cart-items-container');
    const cartSubtotal = document.getElementById('cart-subtotal');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const clearCartBtn = document.getElementById('clear-cart-btn');

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function renderCart() {
        cartItemsContainer.innerHTML = '';
        if (cart.length === 0) {
            emptyCartMessage.style.display = 'block';
            clearCartBtn.style.display = 'none';
        } else {
            emptyCartMessage.style.display = 'none';
            clearCartBtn.style.display = 'block';
            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.classList.add('flex', 'items-center', 'justify-between', 'py-3', 'border-b', 'border-gray-700');
                itemElement.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 rounded-full object-cover border border-primary">
                        <div>
                            <h4 class="text-lightGray font-semibold">${item.name}</h4>
                            <p class="text-mediumGray text-sm">$${item.price.toLocaleString('es-CO')}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="text-primary hover:text-secondary quantity-btn" data-id="${item.id}" data-action="decrease">-</button>
                        <span class="text-lightGray">${item.quantity}</span>
                        <button class="text-primary hover:text-secondary quantity-btn" data-id="${item.id}" data-action="increase">+</button>
                        <button class="text-red-500 hover:text-red-700 remove-from-cart-btn" data-id="${item.id}"><i class="fas fa-trash-alt"></i></button>
                    </div>
                `;
                cartItemsContainer.appendChild(itemElement);
            });
        }
        updateSubtotal();
    }

    function updateSubtotal() {
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartSubtotal.textContent = `$${total.toLocaleString('es-CO')}`;
    }

    function addItemToCart(id, name, price, image) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ id, name, price, image, quantity: 1 });
        }
        saveCart();
        renderCart();
    }

    function updateItemQuantity(id, action) {
        const itemIndex = cart.findIndex(item => item.id === id);
        if (itemIndex > -1) {
            if (action === 'increase') {
                cart[itemIndex].quantity++;
            } else if (action === 'decrease') {
                cart[itemIndex].quantity--;
                if (cart[itemIndex].quantity <= 0) {
                    cart.splice(itemIndex, 1); // Eliminar si la cantidad llega a 0
                }
            }
            saveCart();
            renderCart();
        }
    }

    function removeItemFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        saveCart();
        renderCart();
    }

    function clearCart() {
        cart = [];
        saveCart();
        renderCart();
    }

    openCartBtn.addEventListener('click', () => {
        cartSidebar.classList.add('open');
        cartOverlay.classList.add('active');
    });

    closeCartBtn.addEventListener('click', () => {
        cartSidebar.classList.remove('open');
        cartOverlay.classList.remove('active');
    });

    cartOverlay.addEventListener('click', () => {
        cartSidebar.classList.remove('open');
        cartOverlay.classList.remove('active');
    });

    addToCartButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const itemElement = e.target.closest('.food-item');
            const id = itemElement.dataset.id;
            const name = itemElement.dataset.name;
            const price = parseFloat(itemElement.dataset.price);
            const image = itemElement.querySelector('img').src; // Obtener la URL de la imagen

            addItemToCart(id, name, price, image);
        });
    });

    cartItemsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('quantity-btn')) {
            const id = e.target.dataset.id;
            const action = e.target.dataset.action;
            updateItemQuantity(id, action);
        } else if (e.target.closest('.remove-from-cart-btn')) {
            const id = e.target.closest('.remove-from-cart-btn').dataset.id;
            removeItemFromCart(id);
        }
    });

    clearCartBtn.addEventListener('click', clearCart);

    renderCart(); // Renderiza el carrito al cargar la página por primera vez

    // --- Funcionalidad del Header Sticky ---
    const header = document.querySelector('.main-header');
    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', () => {
        if (window.scrollY > header.offsetHeight && window.scrollY > lastScrollY) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        lastScrollY = window.scrollY;
    });

    // --- Funcionalidad del Menú Desplegable (Dropdown) ---
    const menuToggle = document.getElementById('menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('hidden'); // Oculta/muestra en móvil
        });
    }

    // Dropdown de usuario
    const userDropdownBtn = document.getElementById('user-dropdown-btn');
    const userDropdownContent = document.querySelector('.user-dropdown-content');

    if (userDropdownBtn && userDropdownContent) {
        userDropdownBtn.addEventListener('click', (event) => {
            event.stopPropagation(); // Evita que el clic en el botón cierre el dropdown inmediatamente
            userDropdownContent.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!userDropdownContent.contains(event.target) && !userDropdownBtn.contains(event.target)) {
                userDropdownContent.classList.add('hidden');
            }
        });
    }

    // --- Funcionalidad para el scroll horizontal de las secciones de productos (Carrusel) ---
    const scrollButtons = document.querySelectorAll('.scroll-button');

    scrollButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.dataset.scrollTarget;
            const scrollContainer = document.getElementById(targetId);
            const direction = button.dataset.scrollDirection;

            if (scrollContainer) {
                // Calcula cuánto desplazar basado en el ancho de un item y el gap
                const firstItem = scrollContainer.querySelector('.food-item');
                if (!firstItem) return;

                // El ancho total de un item incluyendo su margin-right (gap de space-x-8)
                // Se asume que space-x-8 es 2rem = 32px
                const itemWidth = firstItem.offsetWidth; 
                const gap = 32; // Ajusta este valor si cambias space-x-8 en Tailwind
                const scrollAmount = itemWidth + gap; // Desplaza una carta completa más el espacio

                if (direction === 'left') {
                    scrollContainer.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                } else {
                    scrollContainer.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Asegúrate de que los enlaces del menú de categorías en menuPlatos.php también hagan scroll suave
    document.querySelectorAll('.category-nav-item').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();
                const targetElement = document.querySelector(href);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
});