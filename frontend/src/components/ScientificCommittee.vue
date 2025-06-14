<template>
    <section class="committee-section">
      <h2 class="committee-title">scientific commity</h2>
  
      <!-- Chair seul en haut -->
      <div class="chair-wrapper">
        <div class="card">
          <div
            class="card-image"
            :style="{ backgroundImage: `url(${chair.image})` }"
          ></div>
          <div class="card-content">
            <h3 class="card-title">{{ chair.title }}</h3>
            <p class="card-text">{{ chair.description }}</p>
            <p class="card-name">{{ chair.name }}</p>
          </div>
        </div>
      </div>
  
      <!-- Autres membres (vice-chair + members) -->
      <div class="committee-grid">
        <div
          v-for="(member, index) in otherMembers"
          :key="index"
          class="card"
        >
          <div
            class="card-image"
            :style="{ backgroundImage: `url(${member.image})` }"
          ></div>
          <div class="card-content">
            <h3 class="card-title">{{ member.title }}</h3>
            <p class="card-text">{{ member.description }}</p>
            <p class="card-name">{{ member.name }}</p>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  const members = [
    {
      title: 'Dr. Aymen Chatti (Chair)',
      description: 'Expert in Smart Manufacturing and AI integration in Industry 4.0.',
      name: 'Université de Sfax',
      image: 'https://randomuser.me/api/portraits/men/10.jpg',
      role: 'chair',
    },
    {
      title: 'Prof. Salma Ben Rejeb (Vice Chair)',
      description: 'Researcher in Sustainable Energy and Renewable Technologies.',
      name: 'ENIT Tunis',
      image: 'https://randomuser.me/api/portraits/women/11.jpg',
      role: 'vice-chair',
    },
    {
      title: 'Dr. Fethi Ghozzi',
      description: 'Specialist in Cybersecurity and Industrial Data Protection.',
      name: 'INSAT Tunis',
      image: 'https://randomuser.me/api/portraits/men/12.jpg',
      role: 'member',
    },
    {
      title: 'Prof. Nada Mseddi',
      description: 'Focuses on Chemical Engineering and Smart Waste Management.',
      name: 'ISSTE Bizerte',
      image: 'https://randomuser.me/api/portraits/women/13.jpg',
      role: 'member',
    },
    {
      title: 'Dr. Karim Bouaziz',
      description: 'Blockchain and Digital Finance Innovator in Smart Economies.',
      name: 'IHEC Carthage',
      image: 'https://randomuser.me/api/portraits/men/14.jpg',
      role: 'member',
    },
    {
      title: 'Dr. Rania Trabelsi',
      description: 'Machine Learning expert in Agro-industry and Biotreatment.',
      name: 'INAT Tunis',
      image: 'https://randomuser.me/api/portraits/women/15.jpg',
      role: 'member',
    },
    {
      title: 'Prof. Mohamed Kallel',
      description: 'Robotics and Industrial Automation Leader.',
      name: 'ENISO Sousse',
      image: 'https://randomuser.me/api/portraits/men/16.jpg',
      role: 'member',
    },
    {
      title: 'Dr. Amira Jendoubi',
      description: 'Smart Environment & Climate Monitoring Systems Engineer.',
      name: 'ISG Tunis',
      image: 'https://randomuser.me/api/portraits/women/17.jpg',
      role: 'member',
    },
    {
      title: 'Dr. Mehdi Mansour',
      description: 'Cloud Computing Architect and Big Data Analyst.',
      name: 'FST Tunis',
      image: 'https://randomuser.me/api/portraits/men/18.jpg',
      role: 'member',
    },
  ];
  
  // Extraction du chair et tri des autres membres
  const chair = members.find((m) => m.role === 'chair');
  const otherMembers = members.filter((m) => m.role !== 'chair');
  </script>
  
  <style scoped>
  .committee-section {
    padding: 3rem 1rem;
    max-width: 1200px;
    margin: auto;
    font-family: 'Segoe UI', sans-serif;
  }
  
  .committee-title {
    text-align: center;
    font-size: 1.7rem;
    font-weight: 700;
    color: #1e3a8a;
    margin-bottom: 2rem;
    text-transform: lowercase;
  }
  
  .chair-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
  }
  
  .committee-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
  }
  
  .card {
    background: white;
    border-radius: 12px;
    box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-5px);
  }
  
  .card-image {
    height: 160px;
    background-size: cover;
    background-position: center;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
  }
  
  .card-content {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .card-title {
    color: #1e3a8a;
    font-size: 1rem;
    font-weight: 600;
  }
  
  .card-text {
    color: #4b5563;
    font-size: 0.875rem;
    line-height: 1.4;
  }
  
  .card-name {
    font-size: 0.875rem;
    color: #6b7280;
    border-top: 1px solid #e5e7eb;
    padding-top: 0.5rem;
  }
  </style>
  