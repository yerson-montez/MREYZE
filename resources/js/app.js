import './bootstrap';

import Alpine from 'alpinejs';
import { initHeaders } from './header';

window.Alpine = Alpine;

Alpine.start();

initHeaders();
