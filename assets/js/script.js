
document.addEventListener("DOMContentLoaded", function () {
    const mobileMenu = document.getElementById("mobileMenu");
    const mobileMenuBtn = document.getElementById("mobileMenuBtn");
    const closeMobileMenu = document.getElementById("closeMobileMenu");
    const overlay = document.getElementById("overlay");

    function openMenu() {
        mobileMenu.classList.remove("translate-x-full");
        overlay.classList.remove("hidden");
    }

    function closeMenu() {
        mobileMenu.classList.add("translate-x-full");
        overlay.classList.add("hidden");
    }

    mobileMenuBtn.addEventListener("click", openMenu);
    closeMobileMenu.addEventListener("click", closeMenu);
    overlay.addEventListener("click", closeMenu);
});

document.addEventListener("DOMContentLoaded", function () {
    const heroText = document.getElementById("heroText");
    if (heroText) {
        AOS.init({
            duration: 1000,
            once: true,
            mirror: false,
        });
        setTimeout(() => {
            heroText.style.transition = "all 1s ease";
            heroText.style.opacity = "1";
            heroText.style.transform = "translateY(0)";
        }, 100);
    }

    const canvas = document.getElementById("particleCanvas");
    if (canvas && canvas.getContext) {
        const ctx = canvas.getContext("2d");
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const particles = [];
        const particleCount = 100;

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 1;
                this.speedX = Math.random() * 2 - 1;
                this.speedY = Math.random() * 2 - 1;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x > canvas.width) this.x = 0;
                if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                if (this.y < 0) this.y = canvas.height;
            }
            draw() {
                ctx.fillStyle = "rgba(255, 255, 255, 0.5)";
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function init() {
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach((particle) => {
                particle.update();
                particle.draw();
            });
            requestAnimationFrame(animate);
        }

        init();
        animate();

        window.addEventListener("resize", () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    }
});

const carousel = document.querySelector('#carousel > div');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');

const itemCount = carousel.children.length;
const visibleCount = 6;
let currentIndex = 0;

function getItemWidth() {
    const item = carousel.children[0];
    return item.offsetWidth + parseInt(getComputedStyle(item).marginLeft) + parseInt(getComputedStyle(item).marginRight);
}

function updateCarousel() {
    const itemWidth = getItemWidth();
    const translateX = -(currentIndex * itemWidth);
    carousel.style.transform = `translateX(${translateX}px)`;

    prevBtn.disabled = currentIndex <= 0;
    nextBtn.disabled = currentIndex >= itemCount - visibleCount;
}

prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        updateCarousel();
    }
});

nextBtn.addEventListener('click', () => {
    if (currentIndex < itemCount - visibleCount) {
        currentIndex++;
        updateCarousel();
    }
});

window.addEventListener('resize', updateCarousel); // responsive davranış için
updateCarousel();

