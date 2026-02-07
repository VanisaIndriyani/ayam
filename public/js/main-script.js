 // Dark to red
 window.addEventListener("scroll", function() {
     const navbar = document.querySelector(".navbar");
    const hero = document.querySelector(".hero");

    if (!hero) return;

    if (window.scrollY > hero.offsetHeight - 30) {
         navbar.classList.add("red-navbar");
     } else {
         navbar.classList.remove("red-navbar");
     }
 });

 // Produk Carousel Logic
 (function() {
     const track = document.getElementById('track');
     if (!track) return;

     let cards = Array.from(track.children);
     const prevBtn = document.getElementById('prev');
     const nextBtn = document.getElementById('next');
     const dotsWrap = document.getElementById('dots');
     const qtyInput = document.getElementById('quantity');
     const plusBtn = document.getElementById('plus');
     const minusBtn = document.getElementById('minus');
     const nameBox = document.getElementById('product-name');
     const descBox = document.getElementById('product-description');
     const priceBox = document.getElementById('product-price');
     const orderBtn = document.getElementById('order-btn');

     if (cards.length === 0) return;

     // Clone for looping
     const firstClone = cards[0].cloneNode(true);
     const lastClone = cards[cards.length - 1].cloneNode(true);
     
     // Remove potential duplicate IDs or special classes from clones
     firstClone.classList.remove('active');
     lastClone.classList.remove('active');

     track.appendChild(firstClone);
     track.insertBefore(lastClone, cards[0]);

     // Refresh cards array after cloning
     const allCards = Array.from(track.children);
     const totalRealCards = cards.length;
     
     let index = 1; // Start at the first real card
     let isTransitioning = false;

     // Initialize dots
     if (dotsWrap) {
         dotsWrap.innerHTML = '';
         for (let i = 0; i < totalRealCards; i++) {
             const d = document.createElement('div');
             d.className = 'dot';
             d.dataset.idx = i + 1; // map to track index
             dotsWrap.appendChild(d);
         }
     }
     const dots = dotsWrap ? Array.from(dotsWrap.children) : [];

     function update(animate = true) {
         const style = getComputedStyle(document.documentElement);
         const cardW = parseFloat(style.getPropertyValue('--card-w')) || 320;
         const gap = parseFloat(style.getPropertyValue('--gap')) || 24;
         
         if (animate) {
             track.style.transition = 'transform 500ms cubic-bezier(.22, .9, .32, 1)';
         } else {
             track.style.transition = 'none';
         }

         const translate = -(index * (cardW + gap));
         track.style.transform = `translateX(${translate}px)`;

         // Classes & Content Sync
         allCards.forEach((c, i) => {
             c.classList.remove('active', 'near');
             if (i === index) {
                 c.classList.add('active');
                 
                 // Sync Text (Task 2)
                 if (nameBox) nameBox.innerText = c.dataset.name || '';
                 if (descBox) descBox.innerText = c.dataset.description || '';
                 if (priceBox) priceBox.innerText = c.dataset.priceDisplay || '';
             }
             else if (i === index - 1 || i === index + 1) c.classList.add('near');
         });

         // Dots sync (map index safely)
         let activeDotIndex = index - 1;
         if (index === 0) activeDotIndex = totalRealCards - 1;
         if (index === totalRealCards + 1) activeDotIndex = 0;
         
         dots.forEach((d, i) => d.classList.toggle('active', i === activeDotIndex));
     }

     function handleTransitionEnd() {
         isTransitioning = false;
         if (index === 0) {
             index = totalRealCards;
             update(false);
         } else if (index === totalRealCards + 1) {
             index = 1;
             update(false);
         }
     }

     track.addEventListener('transitionend', handleTransitionEnd);

     // Controls
     prevBtn?.addEventListener('click', () => {
         if (isTransitioning) return;
         isTransitioning = true;
         index--;
         update();
     });

     nextBtn?.addEventListener('click', () => {
         if (isTransitioning) return;
         isTransitioning = true;
         index++;
         update();
     });

     dotsWrap?.addEventListener('click', e => {
         if (isTransitioning) return;
         if (e.target.classList.contains('dot')) {
             isTransitioning = true;
             index = Number(e.target.dataset.idx);
             update();
         }
     });

     // Card Click
     allCards.forEach((c, i) => {
         c.addEventListener('click', () => {
             if (isTransitioning || i === index) return;
             isTransitioning = true;
             index = i;
             update();
         });
     });

     // Quantity
     plusBtn?.addEventListener('click', () => {
         qtyInput.value = parseInt(qtyInput.value) + 1;
     });
     minusBtn?.addEventListener('click', () => {
         if (parseInt(qtyInput.value) > 1) {
             qtyInput.value = parseInt(qtyInput.value) - 1;
         }
     });

     // Order Button
     orderBtn?.addEventListener('click', async (e) => {
         e.preventDefault();
         const activeCard = allCards[index];
         if (!activeCard) return;

         const productId = activeCard.dataset.id;
         const qty = qtyInput.value;
         const loggedIn = document.querySelector('meta[name="user-logged-in"]')?.content === '1';

         if (!loggedIn) {
             const modalEl = document.getElementById('mustLoginModal');
             if (modalEl) {
                 new bootstrap.Modal(modalEl).show();
             } else {
                 const loginUrl = activeCard.closest('section').dataset.loginRoute || '/login';
                 window.location.href = loginUrl;
             }
             return;
         }

         orderBtn.disabled = true;
         const originalText = orderBtn.innerText;
         orderBtn.innerText = 'Processing...';

         try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            // ROBUST BASE URL DETECTION
            let baseUrl = document.querySelector('meta[name="base-url"]')?.content;

            // FORCE CHECK: If current URL contains /public, ensure baseUrl includes it
            const path = window.location.pathname;
            const pos = path.indexOf('/public');

            if (pos > -1) {
                 // Found /public in URL. Construct base URL from window.location
                 // This overrides the meta tag which might be wrong (pointing to domain root)
                 baseUrl = window.location.origin + path.substring(0, pos + 7);
            } else if (!baseUrl) {
                 baseUrl = '';
            }
            // Ensure no trailing slash
            baseUrl = baseUrl ? baseUrl.replace(/\/$/, '') : '';

            console.log('Using Base URL:', baseUrl); // Debugging

            const res = await fetch(`${baseUrl}/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': csrfToken,
                     'Accept': 'application/json'
                 },
                 body: JSON.stringify({ quantity: qty })
             });

             const data = await res.json();
            if (data.success) {
                if (data.redirect) {
                     window.location.href = data.redirect;
                } else {
                     // Fallback using baseUrl
                     window.location.href = `${baseUrl}/checkout`;
                }
            } else {
                 alert(data.message || 'Gagal menambahkan ke keranjang');
                 orderBtn.disabled = false;
                 orderBtn.innerText = originalText;
             }
         } catch (error) {
             console.error('Error:', error);
             alert('Terjadi kesalahan. Silakan coba lagi.');
             orderBtn.disabled = false;
             orderBtn.innerText = originalText;
         }
     });

     window.addEventListener('load', () => update(false));
     window.addEventListener('resize', () => update(false));
 })();

 //efek active
 document.addEventListener("DOMContentLoaded", function() {
     const sections = document.querySelectorAll("section[id]");
     const navLinks1 = document.querySelectorAll(".tempe");
     const navLinks2 = document.querySelectorAll(".tempe2");

     function setActiveLink() {
         let current = "";

         sections.forEach(section => {
             const sectionTop = section.offsetTop - 150; // tambah offset biar bagian atas kebaca
             const sectionHeight = section.clientHeight;
             if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                 current = section.getAttribute("id");
             }
         });

         // Untuk link teks
        navLinks1.forEach(link => {
            link.classList.remove("active-tempe");
            if (current && link.getAttribute("href").includes("#" + current)) {
                link.classList.add("active-tempe");
            }
        });

        // Untuk ikon (tempe2)
        navLinks2.forEach(link => {
            link.classList.remove("active-tempe2");

            // khusus ikon rumah aktif hanya saat di #home
            if (current === "home" && link.getAttribute("href").includes("#home")) {
                link.classList.add("active-tempe2");
            }
        });
     }

     window.addEventListener("scroll", setActiveLink);
     setActiveLink();
 });