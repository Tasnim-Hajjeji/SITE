<!-- HeroSection.vue -->
<template>
    <section class="hero">
        <div class="content">
            <h1>Welcome to <span class="blue">SITE 2025</span></h1>
            <h2>Step into the future of sustainable innovation !</h2>
            <p>
                The third edition of the international conference on Smart Industry, Technology and Environment<br />
                “SITE’2025” aims to bring together academicians,researchers, professionals and experts from
                various fields to share their latest findings, innovations and best practices. It consists of keynotes,
                oral sessions and poster presentations.
            </p>
            <div class="countdown">
                <div><span>00</span><small>Days</small></div>
                <div><span>00</span><small>Hours</small></div>
                <div><span>00</span><small>Minutes</small></div>
                <div><span>00</span><small>Seconds</small></div>
            </div>
            <br>
            <div class="buttons">

                <a href="#date" class="btn-blue">Important dates</a>

                <button class="btn-white">Article submission</button>
                <button class="btn-whit">Article template</button>
                <button class="btn-green">Registration</button>
            </div>
        </div>
    </section>
</template>

<style scoped>
.hero {
    font-family: 'Segoe UI', sans-serif;
    background: white;
    padding: 1rem 2rem;
    text-align: center;
}

.btn-blue {
    background-color: #005a90;
}

a {
    color: white;
    text-decoration: none;
    margin: 0.5rem;
    padding: 0.4rem 1rem;
    font-size: 0.9rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}
.buttons {
  display: flex;
  flex-wrap: wrap; /* optionnel : va à la ligne si trop étroit */
  justify-content: center;
  gap: 0.5rem; /* espace entre les boutons */
}

.hero h1 {
    font-size: 2.5rem;
    font-weight: bold;
}

.hero .blue {
    color: #265985;
}

.hero h2 {
    font-size: 1.5rem;
    margin-top: 0.5rem;
}

.hero p {
    margin-top: 1rem;
    font-size: 1rem;
    line-height: 1.6;
    color: #444;
}

.timer {
    margin: 2rem 0;
    font-size: 1.5rem;
    font-weight: bold;
}

.timer span {
    font-size: 1rem;
    font-weight: normal;
    margin-left: 0.5rem;
}

.buttons button {
    margin: 0.5rem;
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

.buttons .btn-white {
    background-color: #163654;
    color: white;
}

.buttons .btn-whit {
    background-color: #476784;
    color: white;
}

.buttons .btn-green {
    background-color: #3c7547;
    color: white;
}


.buttons :hover {
    background-color: rgb(237, 230, 230);
    color: black;
    transform: translateY(-2px);
}

.countdown {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
    font-size: 1.5rem;
    font-weight: bold;
}

.countdown div {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.countdown small {
    font-size: 0.75rem;
    font-weight: normal;
}

/* .content {
    background-image: url('../assets/image.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

}
*/

@media (max-width: 768px) {
    .hero h1 {
        font-size: 2rem;
    }

    .hero h2 {
        font-size: 1.1rem;
    }

    .hero p {
        font-size: 0.8rem;
    }

}
</style>