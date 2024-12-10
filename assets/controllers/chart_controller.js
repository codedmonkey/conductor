import { Controller } from '@hotwired/stimulus';
import Chart from 'chart.js/auto'; // todo limit size

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    'canvas',
  ]

  static values = {
    data: Object,
  }

  connect() {
    const data = this.dataValue;

    new Chart(
      this.canvasTarget,
      {
        type: 'line',
        data: {
          labels: Object.keys(data).map(),
          datasets: [
            {
              label: 'Installations per day',
              data: Object.values(data),
            },
          ],
        },
      },
    );
  }
}
