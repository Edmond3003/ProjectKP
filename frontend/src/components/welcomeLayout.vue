<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <router-link :to="{ name: 'login' }" class="navbar-brand mr-auto" style="display: flex; align-items: center;">
        <img src="https://newarmadagroup.com/assets/img/logos/bu/atja.jpg" alt="Logo" height="30">
        <span style="margin-left: 5px;">MASTER ATK</span>
      </router-link>
    </div>
  </nav>
  <router-view />
</template>

<style>
.navbar-brand {
  display: flex;
  align-items: center;
}
</style>

<script>
export default {
  mounted() {
    if (!sessionStorage.getItem('visited')) {
      this.$router.push({ name: 'login' });
      sessionStorage.setItem('visited', 'true');
    }
  }
};
</script>

