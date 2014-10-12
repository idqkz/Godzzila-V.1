ymaps.ready(init);

function init() {
	coords  = document.getElementById('coords').getAttribute('data-coords').split(',');
    var myPlacemark,
        myMap = new ymaps.Map('map', {
            center: [parseFloat(coords[0]) + 0.001, parseFloat(coords[1])],
            zoom: 16,
            controls: ['zoomControl']
        });

    myMap.behaviors.disable(['scrollZoom']);

    // Создание метки
    function createPlacemark(coords) {
        return new ymaps.Placemark(coords, {
        }, {
            iconLayout: 'default#image',
            iconImageHref: 'http://opt.chulochki.kz/img/theme/map-pin.svg',
            iconImageSize: [60, 44],
            iconImageOffset: [-60, -1],
            draggable: false
        });
    }


	coords  = document.getElementById('coords').getAttribute('data-coords').split(',');

    myPlacemark = createPlacemark(coords);
        myMap.geoObjects.add(myPlacemark);

}