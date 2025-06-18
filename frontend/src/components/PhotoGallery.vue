<template>
    <section class="photo-gallery">
        <h2 class="title">Galerie Photo</h2>

        <div class="action-buttons">
            <button class="delete-btn">
                <i class="fas fa-trash"></i> Delete All
            </button>

            <button class="add-btn">
                <i class="fas fa-plus"></i> Add
            </button>
        </div><br><br>

        <div class="carousel-container">
            <button class="nav-btn" @click="prevSlide" :disabled="currentIndex === 0">
                <i class="fas fa-arrow-left"></i>
            </button>

            <div class="carousel-track">
                <div v-for="(image, index) in visibleImages" :key="index" class="image-card">
                    <img :src="image" alt="Gallery photo" />
                    <button class="hover-button delete-btn">Delete</button>
                </div>
            </div>

            <button class="nav-btn" @click="nextSlide" :disabled="currentIndex >= maxIndex">
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        <div class="carousel-dots">
            <span v-for="(dot, i) in totalDots" :key="i" :class="['dot', { active: i === currentIndex }]"></span>
        </div>
    </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const images = [
    '/gallery/1.webp',
    '/gallery/2.webp',
    '/gallery/3.jpeg',
    '/gallery/4.jpg',
    '/gallery/5.png',
]

const currentIndex = ref(0)
const visibleCount = ref(4)

const visibleImages = computed(() =>
    images.slice(currentIndex.value, currentIndex.value + visibleCount.value)
)

const maxIndex = computed(() =>
    Math.max(images.length - visibleCount.value, 0)
)

const totalDots = computed(() => images.length)

function prevSlide() {
    if (currentIndex.value > 0) {
        currentIndex.value--
    }
}

function nextSlide() {
    if (currentIndex.value < maxIndex.value) {
        currentIndex.value++
    }
}

function handleResize() {
    const width = window.innerWidth
    if (width < 640) {
        visibleCount.value = 1
    } else if (width < 1024) {
        visibleCount.value = 2
    } else if (width < 1280) {
        visibleCount.value = 3
    } else {
        visibleCount.value = 4
    }

    // Ajuster currentIndex si trop grand après resize
    if (currentIndex.value > maxIndex.value) {
        currentIndex.value = maxIndex.value
    }
}

onMounted(() => {
    handleResize()
    window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
    window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
.photo-gallery {
    padding: 2rem;
    max-width: 1200px;
    margin: auto;
    font-family: 'Poppins', sans-serif;
}

.title {

    font-size: 1.7rem;
    margin-bottom: 1rem;
    color: black;
    position: relative;
}

.title::after {
    content: "";
    width: 80px;
    height: 4px;
    background: #00a6a6;
    display: block;
    margin-top: 8px;
    border-radius: 2px;
}


.control-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}


.action-buttons button {
    margin-left: 1rem;
    padding: 8px 16px;
    border-radius: 25px;
    border: 1px solid;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.delete-btn {
    border-color: #e53935;
    color: #e53935;
    background-color: #fff;
}

.delete-btn:hover {
    background-color: #e53935;
    color: #fff;
}

.update-btn {
    border-color: #265985;
    color: #265985;
    background-color: #fff;
}

.update-btn:hover {
    background-color: #265985;
    color: #fff;
}

.add-btn {

    border-color: #268557;
    color: #268557;
    background-color: #fff;
}

.add-btn:hover {
    background-color: #268557;
    color: #fff;
}


.carousel-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    flex-wrap: nowrap;
}

.nav-btn {
    border: 2px solid #1c1c3c;
    background: white;
    color: #1c1c3c;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    font-size: 1.2rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.nav-btn:hover {
    background-color: #f6f6f6;
    transform: scale(1.1);
}

.nav-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.carousel-track {
    display: flex;
    gap: 1.2rem;
    flex-wrap: nowrap;
    overflow: hidden;
    width: 100%;
    max-width: 90%;
}

.image-card {
    position: relative;
    flex: 0 0 auto;
    width: 220px;
    height: auto;
    border-radius: 10px;
    box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
    background: white;
}

.image-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.image-card:hover img {
    transform: scale(1.05);
}

.carousel-dots {
    display: flex;
    justify-content: center;
    margin-top: 1.2rem;
    gap: 0.5rem;
}

.dot {
    width: 10px;
    height: 10px;
    background: #c4d6d6;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.dot.active {
    background: #00a6a6;
    transform: scale(1.2);
}

@media (max-width: 768px) {

    .nav-btn {
        width: 57px;
        height: 45px;
    }
}

.hover-button {
    position: absolute;
    bottom: 5%;
    left: 5%;
    z-index: 10;
    display: none;
    padding: 8px 16px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    /* Add any other button styles you want */
}

.image-card:hover .hover-button {
    display: block;
}

.image-card img {
    position: relative;
    z-index: 1;
    transition: opacity 0.3s ease;
}

.image-card:hover img {
    opacity: 0.8; /* Optional: slightly dim the image on hover */
}
</style>