<template>
    <div class="topics-carousel">
        <h1 class="title">Topics</h1>
        <div class="topics">
            <div v-for="(topic, index) in visibleTopics" :key="topic.id"
                :class="['topic-card', { active: index === 1 }]">
                <div class="number">{{ topic.id }}</div>
                <h2 style="font-size: 1.1rem;">{{ topic.title }}</h2>
                <p style="font-size: 0.8rem;">{{ topic.description }}</p>
            </div>
        </div>
        <div class="controls">
            <button @click="prev">&#8592;</button>
            <button @click="next">&#8594;</button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const topics = [
    {
        id: '01',
        title: 'Economy, management and Entrepreneurship',
        description: 'Marketing digital, Finance, Management, Block chain, E-commerce …',
    },
    {
        id: '02',
        title: 'Engineering innovation',
        description: 'Smart factory, Robotics, Cybersecurity, AI, Materials, Energy management, Cloud computing, Big Data, Additive manufacturing, Machine Learning, Embedded systems …',
    },
    {
        id: '03',
        title: 'Processes, Environment and Sustainable Development',
        description: 'Agro-industry 4.0, Chemical process, Renewable energy, Smart Waste Management, Biotreatment, Sensors in Bio-industry, Pharmaceutic process …',
    },
    {
        id: '04',
        title: 'New Technologies',
        description: 'AR/VR, IoT, Quantum computing, Edge computing, Web3…',
    },
    {
        id: '05',
        title: 'Social Impact',
        description: 'Tech for good, Education, HealthTech, CivicTech…',
    }
];

const activeIndex = ref(1); // le milieu

// t7ott 3 topics: el active + eli 9ablou + eli baadou
const visibleTopics = computed(() => {
    const start = (activeIndex.value - 1 + topics.length) % topics.length;
    return [
        topics[start],
        topics[(start + 1) % topics.length],
        topics[(start + 2) % topics.length],
    ];
});

const next = () => {
    activeIndex.value = (activeIndex.value + 1) % topics.length;
};

const prev = () => {
    activeIndex.value = (activeIndex.value - 1 + topics.length) % topics.length;
};
</script>

<style scoped>
.topics-carousel {
    text-align: center;
    margin: 2rem 0;
    font-family: 'Segoe UI', sans-serif;
}

.title {
    font-size: 1.7rem;
    font-weight: bold;
}


.number {
    font-size: 2rem;
    color: #4590c7;
    font-weight: bold;
}

.controls {
    margin-top: 1rem;
    display: flex;
    justify-content: center;
    gap: 1rem;
}

button {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 2px solid #4590c7;
    background-color: transparent;
    color: #4590c7;
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

button:hover {
    background-color: #4590c7;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(69, 144, 199, 0.3);
}

.topics {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: nowrap;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    gap: 1rem;
    padding: 1rem 0;
}

.topic-card {
    flex: 0 0 80%;
    max-width: 300px;
    min-width: 250px;
    scroll-snap-align: center;
    opacity: 0.5;
    transition: all 0.3s ease;
    margin: 0 0.5rem;
    padding: 1rem;
    border-radius: 10px;
    background: #f4f4f4;
}

.topic-card.active {
    opacity: 1;
    transform: scale(1.05);
    background: #fff;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.topics::-webkit-scrollbar {
    display: none;
}

.topics {
    -ms-overflow-style: none;
    /* IE and Edge */
    scrollbar-width: none;
    /* Firefox */
}

@media (max-width: 768px) {
    .topics-carousel {
        padding: 0 1rem;
    }

    .topic-card {
        flex: 0 0 90%;
        min-width: 220px;
    }

    .controls {
        margin-top: 0.5rem;
    }

    button {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
}
</style>