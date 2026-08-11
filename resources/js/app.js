
import Alpine from 'alpinejs';
import { Chart, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler } from 'chart.js';

Chart.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();
