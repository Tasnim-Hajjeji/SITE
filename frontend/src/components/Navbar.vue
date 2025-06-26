<template>
    <nav class="admin-navbar">
        <div class="navbar-left">
            <img src="@/assets/logosite.png" alt="Logo" class="logo" />
            <span class="site-title">SITE 202X</span>
        </div>

        <div class="navbar-center">
            <div class="search-container">
                <input v-model="searchQuery" @input="handleSearch" @focus="showResults = true" @blur="onBlur"
                    type="text" class="search-input" placeholder="Search for any person..." />
                <div v-if="searchLoading" class="search-loading">Searching...</div>
                <div v-if="showResults && searchResults.length > 0" class="search-results">
                    <div v-for="result in searchResults" :key="`${result.type}-${result.id}`" class="search-result-item"
                        @mousedown.prevent="navigateToResult(result)">
                        <div class="result-type-badge" :class="result.type">{{ getTypeLabel(result.type) }}</div>
                        <div class="result-content">
                            <div class="result-title">{{ result.name }}</div>
                            <div class="result-subtitle">{{ result.description }}</div>
                        </div>
                    </div>
                </div>
                <div v-if="showResults && searchResults.length === 0 && searchQuery.length > 1" class="search-empty">
                    No results found for "{{ searchQuery }}"
                </div>
            </div>
        </div>

        <div class="navbar-right">
            <img src="@/assets/avatar.png" alt="Notifications" class="notification-icon" />
            <span class="admin-name">Admin</span>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from '@/plugins/axios';

const router = useRouter();
const searchQuery = ref('');
const searchResults = ref([]);
const showResults = ref(false);
const searchLoading = ref(false);
let searchTimeout = null;

const getTypeLabel = (type) => {
    const labels = {
        speaker: 'Speaker',
        sponsor: 'Sponsor',
        participant: 'Participant',
        committee: 'Committee',
        partner: 'Partner'
    };
    return labels[type] || type;
};

const handleSearch = () => {
    clearTimeout(searchTimeout);

    if (searchQuery.value.length < 2) {
        searchResults.value = [];
        return;
    }

    searchTimeout = setTimeout(async () => {
        try {
            searchLoading.value = true;
            const editionId = localStorage.getItem('selectedEditionId');
            if (!editionId) {
                console.error('No edition ID found in localStorage');
                return;
            }

            const response = await axios.get('/global-search', {
                params: {
                    query: searchQuery.value,
                    edition_id: editionId
                }
            });

            searchResults.value = response.data;
        } catch (error) {
            console.error('Search error:', error);
            searchResults.value = [];
        } finally {
            searchLoading.value = false;
        }
    }, 300);
};

const navigateToResult = (result) => {
    showResults.value = false;
    router.push({
        path: result.url,
        query: {
            highlight: result.id,
            highlightType: result.type
        }
    });
};

const onBlur = () => {
    setTimeout(() => {
        showResults.value = false;
    }, 200);
};
</script>

<style scoped>
.admin-navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 60px;
    padding: 0 25px;
    background-color: white;
    border-bottom: 2px solid #e0e0e0;
    font-family: 'Segoe UI', sans-serif;
}

.navbar-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo {
    height: 30px;
    width: 30px;
}

.site-title {
    font-weight: 700;
    font-size: 18px;
    color: #002b45;
}

.navbar-center {
    flex: 1;
    display: flex;
    justify-content: center;
}

.search-container {
    position: relative;
    width: 300px;
}

.search-input {
    width: 100%;
    height: 32px;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-radius: 16px;
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #265985;
    box-shadow: 0 0 0 2px rgba(38, 89, 133, 0.2);
}

.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1001;
    max-height: 400px;
    overflow-y: auto;
    margin-top: 4px;
}

.search-result-item {
    padding: 12px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    display: flex;
    gap: 12px;
    align-items: center;
    transition: background-color 0.2s;
}

.search-result-item:hover {
    background-color: #f8f9fa;
}

.result-type-badge {
    font-size: 10px;
    text-transform: uppercase;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 600;
    color: white;
    flex-shrink: 0;
}

.result-type-badge.speaker {
    background-color: #4a6baf;
}

.result-type-badge.sponsor {
    background-color: #38a169;
}

.result-type-badge.participant {
    background-color: #9f7aea;
}

.result-type-badge.committee {
    background-color: #ed8936;
}

.result-type-badge.partner {
    background-color: #e14272;
}

.result-content {
    flex-grow: 1;
    overflow: hidden;
}

.result-title {
    font-weight: 500;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
}

.result-subtitle {
    font-size: 12px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.search-loading, .search-empty {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    padding: 12px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    color: #666;
    text-align: center;
    z-index: 1001;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.notification-icon {
    width: 22px;
    height: 22px;
}

.admin-name {
    font-size: 16px;
    color: #333;
}

@media (max-width: 768px) {
    .navbar-left {
        display: none;
    }

    .navbar-center {
        flex: 1;
        display: flex;
        justify-content: center;
    }

     .search-container {
        width: 200px;
    }

    .search-input {
        display: block;
    }
}
</style>