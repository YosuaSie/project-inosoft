import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import { createApp } from 'vue'; // Import createApp dari Vue
import ExampleComponent from './components/ExampleComponent.vue'; // Import komponen Vue
import SearchComponent from './components/SearchComponent.vue';

const app = createApp({}); // Inisialisasi aplikasi Vue

app.component('example-component', ExampleComponent); // Daftarkan komponen Vue
app.component('search-component', SearchComponent);

app.mount('#app'); // Mount aplikasi Vue ke elemen dengan id "app"

