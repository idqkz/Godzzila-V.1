<div id='map' class='detailed-order-map'></div>
<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){

	ymaps.ready(init);
	function init() {
		var coords = $('#map').attr('data-location');
		var myPlacemark,
		    myMap = new ymaps.Map('map', {
		        center: coords,
		        zoom: 12
		    });

		// Создание метки
		function createPlacemark(coords) {
		    return new ymaps.Placemark(coords, {
                iconContent: firstGeoObject.properties.get('name'),
                balloonContent: firstGeoObject.properties.get('text')
		    }, {
		        preset: 'islands#violetStretchyIcon',
		        draggable: true
		    });
		}

		// Определяем адрес по координатам (обратное геокодирование)
		// function getAddress(coords) {
		//     myPlacemark.properties.set('iconContent', 'поиск...');
		//     ymaps.geocode(coords).then(function (res) {
		//         var firstGeoObject = res.geoObjects.get(0);

		//         myPlacemark.properties
		//             .set({
		
		
		//             });
		//     });
		// }

	}
})
</script>