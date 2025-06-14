<template>
    <section class="voices-header">
      <div class="overlay">
        <h2 class="title">Voices of the <span>Conference</span> !</h2>
        <p class="subtitle">
          Meet the experts leading our sessions and workshops. Their insights will spark ideas and meaningful conversations.
        </p>
      </div>
    </section>
  </template>
  
  <style scoped>
  .voices-header {
    background: url('../assets/i3.jpeg') center center / cover no-repeat;
    background-size: cover;
    background-position: center;
    color: white;
    position: relative;
    text-align: center;
    font-family: 'Segoe UI', sans-serif;
    
    
    
  }
  
  .overlay {
    background: linear-gradient(to right, rgba(0,0,0,0.6) 30%, rgba(255,255,255,0));
    padding:20px;
    
  }
 
  
  .title {
    font-size: 2.5rem;
    font-weight: 700;
  }
  
  .title span {
    color: #2a4b94; /* Couleur bleue */
  }
  
  .subtitle {
    font-size: 1.1rem;
    margin-top: 10px;
  }
  </style>
  