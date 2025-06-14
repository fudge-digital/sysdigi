import './bootstrap';

import Alpine from 'alpinejs';

import 'cropperjs/dist/cropper.css';

import { initCropper } from './cropper-global.js';

window.Alpine = Alpine;

Alpine.start();
