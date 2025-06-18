<template>
  <div class="important-dates">
    <h2 class="title">Important dates</h2>
    <div class="timeline">
      <div
        class="timeline-item"
        v-for="(item, index) in dates"
        :key="index"
        :class="{ left: index % 2 === 0, right: index % 2 !== 0 }"
      >
        <div class="content">
          <div class="date-circle">{{ item.date }}</div>
          <div class="text-box">{{ item.text }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const dates = [
  { date: '01 / 07', text: 'Submission Deadline (Extended abstract)' },
  { date: '02 / 07', text: 'Acceptance notification' },
  { date: '03 / 07', text: 'Registration Deadline' },
  { date: '04 / 07', text: 'Full paper submission deadline' }
];
</script>

<style scoped>
.important-dates {
  background-color: #245785;
  padding: 2rem 1rem;
  color: white;
  text-align: center;
  font-family: 'Segoe UI', sans-serif;
}

.title {
  font-size: 1.7rem;
  font-weight: bold;
  margin-bottom: 3rem;
}

.timeline {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  align-items: center;
  position: relative;
}

.timeline-item {
  width: 100%;
  display: flex;
  justify-content: flex-start;
  position: relative;
  max-width: 700px;
}

.timeline-item.right {
  justify-content: flex-end;
}

.content {
  display: flex;
  align-items: center;
  background: linear-gradient(145deg, #e0e0e0, #ffffff);
  border-radius: 50px;
  box-shadow: 8px 8px 16px #1b4468, -8px -8px 16px #2d6aa2;
  transition: transform 0.3s ease;
  overflow: hidden;
}

.content:hover {
  transform: scale(1.02);
}

.text-box {
  padding: 1rem;
  color: #333;
  font-size: 1rem;
  min-width: 280px;
  text-align: center;
}

.timeline-item.right .text-box {
  border-radius: 0 50px 50px 0;
  order: 2;
  text-align: center;
}

.date-circle {
  padding: 0.3rem;
  width: 40px;
  height: 35px;
  background-color: #bcd5e6;
  box-shadow: 0 0 25px rgba(255, 255, 255, 0.5);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.7rem;
  color: #333;
  margin: 0.5rem;
}

/* ✅ Responsive styling: date left, text right */
@media (max-width: 768px) {
  .timeline-item,
  .timeline-item.right {
    justify-content: center !important;
  }

  .content {
    flex-direction: row;
    align-items: center;
    text-align: left;
    border-radius: 30px;
    padding: 0.5rem;
    width: 90%;
    max-width: 350px;
    margin: 0.2rem;
    gap: 0.5rem;
  }

  .date-circle {
    order: 1;
    width: 40px;
    height: 40px;
    font-size: 0.6rem;
    margin: 0;
  }

  .text-box {
    order: 2;
    padding: 0.1rem;
    font-size: 0.9rem;
    color: #333;
    text-align: left;
    width: auto;
  }
}
</style>
