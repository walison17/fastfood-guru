var Local = (function () {
    var $cep, $lat, $lng, $city, $state;

    var init = function () {
        $cep = $('input[name=cep]');
        $lat = $('input[name=lat]');
        $lng = $('input[name=lng]');
        $city = $('input[name=city]');
        $state = $('input[name=state]');

        $cep.blur(function () {
            var cep = $(this).val().replace(/\D/g, '');
            
            if (/^[0-9]{8}$/.test(cep)) {
                getGeolationByCep(cep);
            }
        });
    }

    var getLocationByCep = function (cep) {
        return $.getJSON('https://viacep.com.br/ws/' + cep + '/json/')
            .then(function (response) { 
                return {
                    city: response.localidade,
                    state: response.uf, 
                    address: response.logradouro + ', ' + response.bairro + ', Brasil' 
                } 
            })
    }

    var getLatAndLngByAddress = function (address) {
        return $.get('https://maps.googleapis.com/maps/api/geocode/json?address=' + address + '&key=AIzaSyADIQLpr3-yrtwsVflaSp7zbWwYMpxNx6s')
            .then(function (response) { 
                var location =  response.results[0].geometry.location;
                return {
                    lat: location.lat,
                    lng: location.lng
                }
            });
    }

    var getGeolationByCep = function (cep) {
        getLocationByCep(cep)
            .then(function (location) {
                $city.val(location.city);
                $state.val(location.state);                
                
                getLatAndLngByAddress(location.address)
                    .then(function (geo) {
                        $lat.val(geo.lat);
                        $lng.val(geo.lng);  
                    });
            });
    }

    return {
        init: init
    }
})();

$(document).ready(Local.init);
