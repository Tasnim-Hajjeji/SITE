<template>
    <section class="site-header">
      <div class="header-overlay">
        <div class="header-content">
          <h1>SITE 2024</h1>
          <p>
            The second edition of the international conference on Smart Industry, Technology and Environment
          </p>
          <div class="header-info">
            <div class="info-item">
              <span class="material-icons">location_on</span>
              <span>Hammamet, Tunisia</span>
            </div>
            <div class="info-item">
              <span class="material-icons">event</span>
              <span>October 24–26, 2024</span>
            </div>
          </div>
        </div>
      </div>
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </section>
  </template>
  
  <script setup>
  // No script needed for static data
  </script>
  
  <style scoped>
  .site-header {
    background: url('../assets/i3.jpeg') center center / cover no-repeat;
    height: 10vh;
    min-height: 600px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    color: white;
    font-family: 'Segoe UI', sans-serif;
  }
  
  .header-overlay {
    background-color: rgba(0, 0, 0, 0.65); /* تخفيف الخلفية */
    width: 100%;
    height: 100%;
    padding-left: 5vw;
    padding-right: 5vw;
    display: flex;
    align-items: center;
    justify-content: flex-start;
  }
  
  .header-content {
    max-width: 700px;
    text-align: left;
  }
  
  .header-content h1 {
    font-size: 3.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
    line-height: 1.2;
  }
  
  .header-content p {
    font-size: 1.25rem;
    font-weight: 400;
    margin-bottom: 2rem;
    line-height: 1.6;
  }
  
  .header-info {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
  }
  
  .info-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    background-color: rgba(255, 255, 255, 0.15);
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 500;
    backdrop-filter: blur(4px);
  }
  
  .material-icons {
    font-size: 1.5rem;
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .header-content {
      text-align: center;
      align-items: center;
      margin: 0 auto;
    }
  
    .header-content h1 {
      font-size: 2.25rem;
    }
  
    .header-content p {
      font-size: 1.1rem;
    }
  
    .header-info {
      flex-direction: column;
      align-items: center;
    }
  
    .info-item {
      justify-content: center;
    }
  }
  </style>
  