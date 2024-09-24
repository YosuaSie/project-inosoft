<template>
    <div>
        <h1>Hello from Vue 3 in Laravel 10!</h1>
        <ul>
            <li v-for="item in items" :key="item.id">{{ item.name }}</li>
        </ul>
  </div>
</template>
  
<script>
    import axios from 'axios';

    export default {
        name: 'ExampleComponent',
        data() {
            return {
                items: [],
            };
        },
        mounted() {
            // Menggunakan Axios untuk mengambil data dari Laravel API
            axios.get('/api/items').then(response => {
                this.items = response.data;
            }).catch(error => {
                console.error('Error fetching items:', error);
            });
        },
    };
</script>
  
<style scoped>
    h1 {
      color: blue;
    }
</style>
  