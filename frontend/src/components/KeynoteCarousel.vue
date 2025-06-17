<template>
    <section class="keynote-section">
        <h2 class="title">Keynote Session</h2>
        

        <div class="action-buttons">
            <button class="delete-btn">
                <i class="fas fa-trash"></i> Delete
            </button>
            
            <button class="add-btn">
                <i class="fas fa-plus"></i> Add
            </button>
            <button class="update-btn">
                <i class="fas fa-pen"></i> Update
            </button>
        </div><br><br>



        <div class="carousel">
            <button class="nav-btn" @click="prevSlide" :disabled="currentIndex === 0">
                <i class="fas fa-arrow-left"></i>
            </button>

            <div class="slides-wrapper">
                <div class="slide" v-for="(item, index) in visibleSlides" :key="index">
                    <div class="quote-icon">❝</div>
                    <p class="text">{{ item.text }}</p>
                    <div class="profile">
                        <img :src="item.image" alt="Speaker photo" class="avatar" />
                        <div>
                            <h3>{{ item.name }}</h3>
                            <p class="role">{{ item.role }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <button class="nav-btn" @click="nextSlide" :disabled="currentIndex === items.length - 1">
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        <div class="dots">
            <span v-for="(dot, i) in items.length" :key="i" :class="['dot', { active: i === currentIndex }]"></span>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue'

const currentIndex = ref(0)

const items = [
    {
        text: 'Sed eligendi facere repellendus. Ipsam ipsam incidunt minima harum tenetur. '.repeat(2),
        name: 'Samantha W.',
        role: 'Directeur ISET',
        image: 'https://i.pravatar.cc/50?img=12',
    },
    {
        text: 'Sed eligendi facere repellendus. Ipsam ipsam incidunt minima harum tenetur. '.repeat(2),
        name: 'John D.',
        role: 'Directeur ISET',
        image: 'https://i.pravatar.cc/50?img=32',
    },
    {
        text: 'Sed eligendi facere repellendus. Ipsam ipsam incidunt minima harum tenetur. '.repeat(2),
        name: 'Alice K.',
        role: 'Directeur ISET',
        image: 'https://i.pravatar.cc/50?img=21',
    },
]

const visibleSlides = computed(() => [items[currentIndex.value]])

function nextSlide() {
    if (currentIndex.value < items.length - 1) currentIndex.value++
}

function prevSlide() {
    if (currentIndex.value > 0) currentIndex.value--
}
</script>

<style scoped>
.keynote-section {
    padding: 2rem;
    max-width: 1200px;
    margin: auto;
    font-family: 'Poppins', sans-serif;
    animation: fadeInUp 0.6s ease;
}

@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(40px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
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
    background-color:#268557;
    color: #fff;
}

.carousel {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    flex-wrap: nowrap;
    transition: all 0.5s ease;
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
    opacity: 0.5;
    cursor: not-allowed;
}

.slides-wrapper {
    display: flex;
    justify-content: center;
    width: 100%;
    max-width: 600px;
    position: relative;
}

.slide {
    background: white;
    border-radius: 20px;
    box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    animation: slideIn 0.4s ease;
    transition: transform 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.quote-icon {
    font-size: 2rem;
    color: #f6b10c;
    margin-bottom: 1rem;
    transform: rotate(-10deg);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: rotate(-10deg) scale(1);
    }

    50% {
        transform: rotate(-10deg) scale(1.05);
    }

    100% {
        transform: rotate(-10deg) scale(1);
    }
}

.text {
    font-size: 0.95rem;
    color: #333;
    flex-grow: 1;
    margin-bottom: 1rem;
    line-height: 1.6;
    transition: color 0.3s ease;
}

.profile {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.avatar:hover {
    transform: scale(1.1);
}

.role {
    font-size: 0.85rem;
    color: gray;
}

.dots {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
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
    width: 12px;
    height: 12px;
    animation: bounce 0.4s ease;
}

@keyframes bounce {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.4);
    }

    100% {
        transform: scale(1);
    }
}

@media (max-width: 768px) {

    .nav-btn {
        width: 55px;
        height: 45px;
    }
}
</style>