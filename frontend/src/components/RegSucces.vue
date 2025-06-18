<template>
  <div class="registration-container">
    <h1 class="title">
      Registration <span class="highlight">successful!</span>
    </h1>
    <p class="subtitle">
      Print and bring the generated registration form on the day of the SITE 2025 welcome to facilitate your admission
    </p>

    <div class="form-card">
      <div class="form-grid">
        <div class="form-field">
          <label>Full name</label>
          <input type="text" value="Puerto Rico" readonly />
        </div>
        <div class="form-field">
          <label>Email</label>
          <input type="text" value="ghada@gmail.com" readonly />
        </div>
        <div class="form-field">
          <label>Phone</label>
          <input type="text" value="24625506" readonly />
        </div>
        <div class="form-field flag">
          <label>Country</label>
          <div class="flag-field">
            <img src="https://flagcdn.com/tn.svg" alt="Tunisia flag" />
            <span>Tunisia</span>
          </div>
        </div>
        <div class="form-field">
          <label>Profession</label>
          <input type="text" value="Teacher" readonly />
        </div>
        <div class="form-field">
          <label>Institution</label>
          <input type="text" value="ensi" readonly />
        </div>
        <div class="form-field">
          <label>Participation</label>
          <input type="text" value="with article" readonly />
        </div>
        <div class="form-field">
          <label>Accommodation</label>
          <input type="text" value="yes" readonly />
        </div>
        <div class="form-field">
          <label>Number of accompanying adults</label>
          <input type="text" value="2" readonly />
        </div>
        <div class="form-field">
          <label>Number of accompanying children</label>
          <input type="text" value="2" readonly />
        </div>
        <div class="form-field">
          <label>Single supplement</label>
          <input type="text" value="yes" readonly />
        </div>
        <div class="form-field">
          <label>Extra night</label>
          <input type="text" value="1" readonly />
        </div>
        <div class="form-field">
          <label>Payment method</label>
          <input type="text" value="purchase order" readonly />
        </div>
        <div class="form-field">
          <label>Total amount</label>
          <input type="text" value="200 TND" readonly />
        </div>
      </div>
    </div><br>

    <div class="buttons">
      <button class="download">
        <i class="fas fa-download"></i> Download registration form
      </button>
      <button class="edit">Edit information</button>
      <router-link to="/"><button class="home">Return to Home</button></router-link>

    </div>
  </div>
</template>

<style scoped>
.registration-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 2rem;
  font-family: 'Segoe UI', sans-serif;
  color: #222;
  text-align: center;
}

.title {
  font-size: 2rem;
  font-weight: 700;
}

.title .highlight {
  color: #3b5998;
}

.subtitle {
  font-size: 1rem;
  color: #555;
  margin-top: 0.5rem;
  margin-bottom: 2rem;
}

.form-card {
  background-color: #ffffff;
  padding: 2rem;
  border-radius: 15px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.3s ease;
}

.form-card:hover {
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.form-field {
  text-align: left;
}

.form-field label {
  display: block;
  font-size: 0.9rem;
  margin-bottom: 0.3rem;
  color: #666;
}

.form-field input {
  width: 90%;
  padding: 0.6rem;
  border: 1px solid #dcdcdc;
  border-radius: 6px;
  background-color: #f5f9ff;
  color: #333;
}

.flag img {
  width: 22px;
  height: auto;
  margin-right: 0.5rem;
}

.flag-field {
  display: flex;
  align-items: center;
  padding: 0.6rem;
  border: 1px solid #dcdcdc;
  border-radius: 6px;
  background-color: #f5f9ff;
}

.buttons {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.download,
.edit,
.home {
  padding: 0.8rem 1.5rem;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
}

.download {
  background-color: #051d37;
  color: white;
  border: none;
}
.download:hover {
  background-color: #0d2b54;
  transform: scale(1.05);
}

.edit {
  background-color: white;
  color: #000;
  border: 2px solid #000;
}
.edit:hover {
  background-color: #f0f0f0;
  transform: scale(1.05);
}

.home {
  background-color: #265985;
  color: white;
  border: 2px solid #265985;
}
.home:hover {
  background-color: white;
  color: #265985;
  transform: scale(1.05);
}

@media (min-width: 600px) {
  .buttons {
    flex-direction: row;
    justify-content: center;
  }
}
</style>
