require('./bootstrap');

// Set up Vue
import { createApp } from 'vue'

// Component imports
import Booking from './components/Booking.vue';

const app = createApp({});

// Register components
app.component('booking', Booking);

// Mount app to DOM
app.mount('#app');