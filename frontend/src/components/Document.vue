<template>
    <section class="user-article-section">
      <h2 class="title">Articles</h2>
  
      <div class="article-preview" @click="toggleDropdown">
        <i class="fas fa-file-pdf"></i> {{ articles[0]?.name || 'No articles available' }}
        <i :class="['fas', dropdownOpen ? 'fa-chevron-up' : 'fa-chevron-down']" class="toggle-icon"></i>
      </div>
  
      <div v-if="dropdownOpen && articles.length > 0" class="article-dropdown">
        <div
          v-for="(article, index) in articles"
          :key="index"
          class="dropdown-item"
          @click="openOrDownload(article.url)"
        >
          <i class="fas fa-file-pdf"></i> {{ article.name }}
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  
  const dropdownOpen = ref(false)
  const articles = ref([])
  
  function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value
  }
  
  function openOrDownload(url) {
    window.open(url, '_blank')
  }
  
  onMounted(async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/user-articles')
      articles.value = response.data.map(file => ({
        name: file.name,
        url: `http://localhost:8000/storage/${file.path}`
      }))
    } catch (error) {
      console.error('Failed to load articles:', error)
    }
  })
  </script>
  
  <style scoped>
  .user-article-section {
    max-width: 600px;
    margin: 2rem auto;
    font-family: 'Poppins', sans-serif;
  }
  
  .title {
    font-size: 1.6rem;
    margin-bottom: 1rem;
    color: #000;
    position: relative;
  }
  
  .title::after {
    content: "";
    width: 70px;
    height: 3px;
    background: #00a6a6;
    display: block;
    margin-top: 8px;
    border-radius: 2px;
  }
  
  .article-preview {
    background-color: #f5f5f5;
    padding: 1rem 1.2rem;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background-color 0.3s;
  }
  
  .article-preview:hover {
    background-color: #e1f5f5;
  }
  
  .toggle-icon {
    transition: transform 0.3s ease;
    margin-left: 0.8rem;
  }
  
  .article-dropdown {
    margin-top: 1rem;
    border: 1px solid #ccc;
    border-radius: 10px;
    overflow: hidden;
    background-color: #fff;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
  }
  
  .dropdown-item {
    padding: 0.9rem 1.2rem;
    cursor: pointer;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    transition: background-color 0.2s ease;
  }
  
  .dropdown-item:last-child {
    border-bottom: none;
  }
  
  .dropdown-item:hover {
    background-color: #f0f9f9;
    color: #007777;
  }
  
  .fas.fa-file-pdf {
    color: #d32f2f;
  }
  </style>
  