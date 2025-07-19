import './bootstrap';
import '../css/app.css';

import * as bootstrap from 'bootstrap';

import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';

const app = createApp({});
app.component('example-component', ExampleComponent);
app.mount('#app');

