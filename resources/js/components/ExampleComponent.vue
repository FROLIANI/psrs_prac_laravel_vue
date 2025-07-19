
<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const error = ref('')

const handleLogin = async () => {
  try {
    const response = await fetch('/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ email: email.value, password: password.value }),
    })

    if (!response.ok) {
      throw new Error('Invalid credentials')
    }

    window.location.href = '/dashboard'
  } catch (err) {
    error.value = err.message
  }
}
</script>

<template>

  <div class="login-container">
    <h2>Login</h2>
    <form @submit.prevent="handleLogin">
      <div class="form-group">
        <label>Email:</label>
        <input v-model="email" type="email" required />
      </div>

      <div class="form-group">
        <label>Password:</label>
        <input v-model="password" type="password" required />
      </div>

      <button type="submit">Login</button>

      <p v-if="error" class="error">{{ error }}</p>
    </form>
  </div>
</template>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}
.form-group {
  margin-bottom: 15px;
}
input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}
button {
  padding: 10px 20px;
}
.error {
  color: red;
  margin-top: 10px;
}
</style>
