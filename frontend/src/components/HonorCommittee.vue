<template>
    <section class="honor-section">
      <div class="honor-header">
        <h2>
          Discover Our <span class="highlight">Honor Committee !</span>
        </h2>
        <p>
          The Honorary Committee brings together influential figures who share our vision and support our mission. <br />
          Their presence reflects the quality and credibility of this conference.
        </p>
      </div>
  
      <div class="cards-container">
        <div
          v-for="(member, index) in members"
          :key="index"
          class="honor-card"
        >
          <div
            class="image-placeholder"
            :style="member.image ? { backgroundImage: `url(${member.image})`, backgroundSize: 'cover' } : {}"
            :class="{ filled: !member.image && index === 0 }"
          ></div>
          <h3 class="card-title">{{ member.name }}</h3>
          <p class="card-description">{{ member.description }}</p>
          <hr />
          <p class="card-footer">{{ member.footer }}</p>
        </div>
      </div>
    </section>
</template>
  
<script setup>
  const members = [
    {
      name: "John Doe",
      description: "Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer",
      footer: "President, Tech Org",
      image: "",
    },
    {
      name: "Dr. Sarah Smith",
      description: "Expert in AI and machine learning with over 20 years of experience in the field.",
      footer: "AI Researcher",
      image: "https://via.placeholder.com/300x150",
    },
    {
      name: "Ahmed Ben Ali",
      description: "Renowned innovator and public speaker in North Africa.",
      footer: "Founder, InnovateTN",
      image: "",
    },
    {
      name: "Meryem Khalil",
      description: "Digital strategist and youth mentor, passionate about tech & change.",
      footer: "CEO, YouthRise",
      image: "https://via.placeholder.com/300x150",
    },
    {
      name: "Fatma Lamine",
      description: "Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer",
      footer: "Director, FutureLab",
      image: "",
    },
    {
      name: "Ali Mezni",
      description: "Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer",
      footer: "Professor, Engineering School",
      image: "",
    },
    {
      name: "Yasmine Trabelsi",
      description: "Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer",
      footer: "VP, Youth Alliance",
      image: "https://via.placeholder.com/300x150",
    },
    {
      name: "Oussama Kacem",
      description: "Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer",
      footer: "Entrepreneur",
      image: "",
    },
  ];
</script>
  
<style scoped>
  .honor-section {
    padding: 4rem 2rem;
    max-width: 1200px;
    margin: auto;
    text-align: center;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f9f9f9; /* ✅ fond clair */
  }
  
  .honor-header h2 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
  }
  
  .honor-header .highlight {
    color: #1e518f;
  }
  
  .honor-header p {
    font-size: 0.95rem;
    color: #333;
    margin-bottom: 2rem;
  }
  
  .cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 2.5rem 1.5rem; /* ✅ séparation entre cards */
    justify-items: center;
  }
  
  .honor-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    padding: 1rem;
    width: 100%;
    max-width: 300px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .honor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  }
  
  .image-placeholder {
    background-color: #ccc;
    width: 100%;
    height: 150px;
    border-radius: 10px;
    margin-bottom: 1rem;
    background-position: center;
    background-repeat: no-repeat;
  }
  
  .image-placeholder.filled {
    background-color: #1e518f;
  }
  
  .card-title {
    font-weight: bold;
    color: #1e518f;
    font-size: 1rem;
    text-align: left;
    margin-bottom: 0.5rem;
  }
  
  .card-description {
    font-size: 0.85rem;
    text-align: left;
    margin-bottom: 1rem;
    color: #555;
  }
  
  hr {
    width: 100%;
    border: none;
    border-top: 1px solid #ccc;
    margin-bottom: 0.5rem;
  }
  
  .card-footer {
    font-size: 0.85rem;
    color: #444;
    text-align: left;
  }
</style>
  