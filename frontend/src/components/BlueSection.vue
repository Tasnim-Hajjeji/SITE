<template>
    <section class="blue-section">
        <div class="top-curve"></div>
        <br><br><br><br>
        <h2 class="section-title">About SITE 2025</h2><br>
        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-calendar-alt"></i>
                <p>October 24–26, 2025</p>
            </div>
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <p>Hammamet, Tunisia</p>
            </div>
            <div class="info-item">
                <i class="fas fa-users"></i>
                <p>Academics, researchers, professionals, experts</p>
            </div>
            <div class="info-item">
                <i class="fas fa-lightbulb"></i>
                <p>Share findings, innovations and best practices</p>
            </div>
            <div class="info-item">
                <i class="fas fa-microphone"></i>
                <p>Keynotes, oral sessions, poster presentations</p>
            </div>
            <div class="info-item">
                <i class="fas fa-globe"></i>
                <p>Promote sustainable development & interdisciplinary</p>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'BlueSection'
}
</script>

<style scoped>
.blue-section {
    background-color: #265985;
    font-family: 'Segoe UI', sans-serif;
    color: white;
    text-align: center;
    padding-bottom: 1rem;
    position: relative;
    overflow: hidden;
}

.top-curve {
    position: absolute;
    top: -100px;
    left: 0;
    width: 100%;
    height: 200px;
    background-color: white;
    border-bottom-left-radius: 100% 100px;
    border-bottom-right-radius: 100% 100px;
    z-index: 1;
}

.section-title {
    position: relative;
    z-index: 2;
    font-size: 1.7rem;
    font-weight: bold;
    margin: 3rem 0 2rem;
}

.info-grid {
    position: relative;
    z-index: 2;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1.7rem;
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 1rem;
}

.info-item {
    width: 160px;
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 0.95rem;
    text-align: center;
}

.info-item i {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    background-color: #0f3b72;
    padding: 1rem;
    border-radius: 10px;
}
</style>