<template>
    <nav class="admin-navbar">
        <div class="navbar-left">
            <img src="@/assets/logo.png" alt="Logo" class="logo" />
            <span class="site-title">SITE 202X</span>
        </div>

        <div class="navbar-center">
            <input type="text" class="search-input" placeholder="Search for anything..." />
        </div>

        <div class="navbar-right">
            <img src="@/assets/logo.png" alt="Notifications" class="notification-icon" />
            <span class="admin-name">Admin</span>
        </div>
    </nav>
</template>

<script setup>
// Pas besoin de script pour l’instant
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
    height: 50px;
    padding: 0 20px;
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
    height: 24px;
    width: 24px;
}

.site-title {
    font-weight: 700;
    font-size: 16px;
    color: #002b45;
}

.navbar-center {
    flex: 1;
    display: flex;
    justify-content: center;
}

.search-input {
    width: 250px;
    height: 28px;
    padding: 4px 10px;
    border: 1px solid #ccc;
    border-radius: 14px;
    font-size: 14px;
    outline: none;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.notification-icon {
    width: 18px;
    height: 18px;
}

.admin-name {
    font-size: 14px;
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

    .search-input {
        display: none;
    }
}
</style>