import axios from 'axios';
window.axios = axios;
import './bootstrap';

// Importar el js de Bootstrap
import 'bootstrap';

import './formulario.js';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
