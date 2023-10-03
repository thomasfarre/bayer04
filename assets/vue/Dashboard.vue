<!-- assets/js/components/Dashboard.vue -->

<template>
  <div class="p-4">
    <!-- Display user data here... -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      userData: null
    };
  },
  mounted() {
    // Fetch user data from Symfony backend and assign to userData
    this.fetchUserData();
  },
  methods: {
    async fetchUserData() {
      try {
        let response = await fetch('/dashboard');
        this.userData = await response.json();
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
    }
  }
};
</script>
