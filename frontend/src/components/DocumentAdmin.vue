<template>
    <section class="article-section">
      <h2 class="title">Articles</h2>
      <div class="action-buttons">
        <button class="add-btn" @click="showModal = true">
          <i class="fas fa-plus"></i> Add
        </button>
      </div><br><br>
  
      <div v-if="isLoading" class="loading-message">Loading articles...</div>
      <div v-else-if="articles.length === 0" class="empty-message">No articles available.</div>
      <div v-else class="article-list">
        <div class="article-card" v-for="(article, index) in articles" :key="index">
          <div class="article-name" @click="openPDF(article.url)">
            <i class="fas fa-file-pdf"></i> {{ article.name }}
          </div>
          <button class="delete-btn" @click="deleteArticle(index)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
  
      <!-- Add PDF Modal -->
      <div v-if="showModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="modal-title">Add Article (PDF)</h3>
          <input type="file" accept="application/pdf" @change="handlePDFUpload" />
          <div class="modal-actions">
            <button class="cancel-btn" @click="showModal = false">Cancel</button>
            <button class="confirm-btn" @click="addArticle">Add</button>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const showModal = ref(false)
  const articles = ref([])
  const isLoading = ref(false)
  const uploadedPDF = ref(null)
  
  function handlePDFUpload(event) {
    const file = event.target.files[0]
    if (file && file.type === 'application/pdf') {
      uploadedPDF.value = file
    } else {
      alert('Please select a PDF file.')
    }
  }
  
  function addArticle() {
    if (!uploadedPDF.value) return
    const url = URL.createObjectURL(uploadedPDF.value)
    articles.value.push({ name: uploadedPDF.value.name, url })
    uploadedPDF.value = null
    showModal.value = false
  }
  
  function openPDF(url) {
    window.open(url, '_blank')
  }
  
  function deleteArticle(index) {
    articles.value.splice(index, 1)
  }
  </script>
  
  <style scoped>
  .article-section {
    padding: 2rem;
    font-family: 'Poppins', sans-serif;
    animation: fadeInUp 0.6s ease;
  }
  
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(40px);
    }
    to {
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
  
  .action-buttons .add-btn {
    border: 1px solid #268557;
    color: #268557;
    background-color: #fff;
    border-radius: 25px;
    padding: 8px 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .action-buttons .add-btn:hover {
    background-color: #268557;
    color: #fff;
  }
  
  .article-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  .article-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .article-name {
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #1c1c3c;
  }
  
  .article-name:hover {
    text-decoration: underline;
  }
  
  .delete-btn {
    background: #f8d7da;
    color: #721c24;
    border: none;
    padding: 8px;
    border-radius: 50%;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  
  .delete-btn:hover {
    background: #f5c6cb;
  }
  
  .modal-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 50;
  }
  
  .modal-content {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    width: 90%;
    max-width: 400px;
    font-family: 'Poppins', sans-serif;
  }
  
  .modal-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    text-align: center;
  }
  
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
  }
  
  .cancel-btn,
  .confirm-btn {
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    border: none;
    font-weight: 500;
  }
  
  .cancel-btn {
    background: #e0e0e0;
    color: #333;
  }
  
  .confirm-btn {
    background: #268557;
    color: white;
  }
  
  .confirm-btn:hover {
    background: #1d6b46;
  }
  </style>
  