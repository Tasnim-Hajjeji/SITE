<template>
    <section class="hero">
        <div class="content">
            <div class="text-content">
                <h1>Welcome to <span class="blue">SITE 2025</span></h1>
                <h2>Step into the future of sustainable innovation !</h2>
                <p>
                    The third edition of the international conference on Smart Industry, Technology and
                    Environment<br />
                    “SITE’2025” aims to bring together academicians, researchers, professionals and experts from various
                    fields to share their latest findings, innovations and best practices. It consists of keynotes, oral
                    sessions and poster presentations.
                </p>
                <div class="countdown">
                    <div><span>00 :</span><small>Days</small></div>
                    <div><span>00 :</span><small>Hours</small></div>
                    <div><span>00 :</span><small>Minutes</small></div>
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
            <div class="image-container">
                <img src="../assets/coeur.png" alt="Heart Image" class="hero-image" />
            </div>
            <div class="decorative-dots"></div>
        </div>
    </section>
</template>

<style scoped>
.hero {
    font-family: 'Segoe UI', sans-serif;
    background: white;
    padding: 2rem;
    position: relative;
    min-height: 40vh;
    display: flex;
    justify-content: flex-end;
    overflow: hidden;
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
    flex-wrap: wrap;
    /* optionnel : va à la ligne si trop étroit */
    justify-content: center;
    gap: 0.5rem;
    /* espace entre les boutons */
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


.content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    max-width: 1200px;
    position: relative;
    z-index: 2;
    transform: translateX(5%);
    /* Décalage léger à droite pour centrer visuellement */
}

.text-content {
    text-align: center;
    max-width: 70%;
}

.hero h1 {
    font-size: 3rem;
    /* Agrandi */
    font-weight: bold;
    font-weight: 700;
    /* Plus gras */
}

.hero .blue {
    color: #005a90;
}

.hero h2 {
    font-size: 2rem;
    /* Agrandi */
    font-weight: bold;
    font-weight: 700;
    /* Plus gras */
    margin-top: 0.5rem;
}

.hero p {
    margin-top: 1rem;
    font-size: 1rem;
    line-height: 1.6;
    color: #444;
}

.countdown {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
    font-size: 2rem;
    font-weight: bolder;
}

.countdown div {
    display: flex;
    flex-direction: column;
    align-items: right;
}

.countdown small {
    font-size: 0.75rem;
    font-weight: normal;
    align-items: left;
}



.image-container {
    max-width: 30%;
    margin-top: -5rem;
    /* Ajusté pour coller à la navbar */
    margin-right: 3rem;
    /* Ajusté pour coller à la navbar */

}

.hero-image {
    width: 100%;
    height: auto;
    display: block;
    animation: heartbeat 2s infinite;
    /* Animation de battement */
}

@keyframes heartbeat {
    0% {
        transform: scale(1);
    }

    20% {
        transform: scale(1.1);
        /* Léger agrandissement */
    }

    40% {
        transform: scale(1);
    }

    60% {
        transform: scale(1.1);
        /* Deuxième battement */
    }

    80% {
        transform: scale(1);
    }

    100% {
        transform: scale(1);
    }
}

.decorative-dots {
    position: absolute;
    top: 10%;
    left: -15%;
    width: 30%;
    height: 60%;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="10" r="2" fill="%23005a90"/><circle cx="30" cy="40" r="2" fill="%23005a90"/><circle cx="50" cy="70" r="2" fill="%23005a90"/><circle cx="70" cy="20" r="2" fill="%23005a90"/><circle cx="90" cy="50" r="2" fill="%23005a90"/></svg>') repeat;
    z-index: 1;
    opacity: 0.5;
    /* Rendu plus discret et loin de l'écriture */
}

@media (max-width: 768px) {
    .hero {
        justify-content: center;
        padding: 1rem;
    }

    .content {
        flex-direction: column;
        text-align: center;
        transform: translateX(0);
        /* Réinitialise le décalage sur mobile */
    }

    .text-content {
        max-width: 100%;
    }

    .image-container {
    display: none;
    }

    .decorative-dots {
        display: none;
        /* Masque les points sur mobile pour simplicité */
    }

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