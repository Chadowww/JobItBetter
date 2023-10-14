
import * as noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.css';

let slider = document.getElementById('salary-slider');

if (slider) {
    const min = document.getElementById('joboffer_filter_minSalary');
    const max = document.getElementById('joboffer_filter_maxSalary');
    const minValue = Math.floor(parseInt(slider.dataset.min, 10) / 10) * 10;
    const maxValue = Math.ceil(parseInt(slider.dataset.max, 10) / 10) * 10;
    const range = noUiSlider.create(slider, {
        start: [min.value || minValue, max.value || maxValue],
        connect: true,
        step: 10,
        range: {
            'min': minValue,
            'max': maxValue,
        },
    });
console.log(slider)


    range.on('slide', function (values, handle) {

        if (handle === 0) {
            min.value = Math.round(values[0]);
        }
        if (handle === 1) {
            max.value = Math.round(values[1]);
        }
        console.log(values, handle)
    });
}

