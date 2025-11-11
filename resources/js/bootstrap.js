import axios from 'axios';
window.axios = axios;
import './bootstrap';

// Importamos el JS de Bootstrap (y Popper.js que viene incluido)
import 'bootstrap';

// Importamos la lógica de nuestro formulario
import './formulario.js';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
