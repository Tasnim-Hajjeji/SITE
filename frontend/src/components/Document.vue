<template>
  <section class="user-article-section">
    <h2 class="title">Articles</h2>

    <div class="article-preview" @click="toggleDropdown">
      <i class="fas fa-file-pdf"></i> {{ articles[0] ? 'Liste des Documents' : 'Aucun article disponible' }}
      <i :class="['fas', dropdownOpen ? 'fa-chevron-up' : 'fa-chevron-down']" class="toggle-icon"></i>
    </div>

    <div v-if="dropdownOpen && articles.length > 0" class="article-dropdown">
      <div
        v-for="(article, index) in articles"
        :key="index"
        class="dropdown-item"
        @click="openOrDownload(index)"
      >
        <i class="fas fa-file-pdf"></i> {{ file_name(article) }}
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Document',
  props: {
    editionId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      dropdownOpen: false,
      articles: []
    }
  },
  methods: {
    file_name(file) {
      return this.$i18n.locale === 'fr' ? file.name_fr : file.name_en
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen
    },
    async openOrDownload(i) {
      try {
        const pdfUrl = `${this.articles[i].url}`;
        const response = await fetch(pdfUrl, {
          method: 'GET',
          headers: {
            'Accept': 'application/pdf',
          },
        });
        if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
        const blob = await response.blob();
        if (blob.type !== 'application/pdf') throw new Error('Le fichier reçu n\'est pas un PDF');
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'Article_SITE.pdf';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
      } catch (error) {
        console.error('Erreur lors du téléchargement du modèle d\'article :', error.message);
        alert('Impossible de télécharger le modèle d\'article : ' + error.message + '. Veuillez vérifier votre connexion ou réessayer plus tard.');
      }
    },
  },
  mounted() {
    axios.get('http://localhost:8000/api/documents/edition/' + this.editionId)
      .then(response => {
        this.articles = response.data.map(file => ({
          name_fr: file.name_fr,
          name_en: file.name_en,
          url: `http://localhost:8000/storage/${file.url}`
        }))
      })
      .catch(error => {
        console.error('Failed to load articles:', error)
      })
  }
}
</script>

<style scoped>
.user-article-section {
  text-align: center;
  padding: 2rem 1rem;
  font-family: 'Segoe UI', sans-serif;
  max-width: 600px;
  margin: 2rem auto;
}

.title {
  font-size: 1.7rem;
  color: #265985;
  margin-bottom: 0.5rem;
  font-weight: bold;
}

.article-preview {
  background: #f3f4f2;
  border-radius: 20px;
  padding: 1rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: transform 0.3s ease;
}

.article-preview:hover {
  transform: translateY(-2px);
}

.toggle-icon {
  transition: transform 0.3s ease;
  margin-left: 0.8rem;
  color: #1c3d6e;
}

.article-dropdown {
  margin-top: 1rem;
  border-radius: 20px;
  overflow: hidden;
  background-color: #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
}

.dropdown-item {
  padding: 1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.6rem;
  transition: background-color 0.2s ease;
  border-bottom: 1px solid #eee;
}

.dropdown-item:last-child {
  border-bottom: none;
}

.dropdown-item:hover {
  background-color: #f0f9f9;
  color: #1c3d6e;
}

.fas.fa-file-pdf {
  color: #d32f2f;
}
</style>