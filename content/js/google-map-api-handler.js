/**
 * Created by User on 29/06/2016.
 */
var search_location = document.getElementById('search_location');
var search_location_place_id = document.getElementById('search_location_place_id');
var search_location_autocomplete;

function search_location_change()
{
    var mapOptions = null;
    var map = null;

    var place = search_location_autocomplete.getPlace();
    //console.log(place);
    //document.getElementById('map-options').innerHTML = place['formatted_address']+'('+place['types'][0]+':'+place['geometry'].location+')';
    //initialMap(place['geometry'].location);
    if (typeof place['formatted_address'] !== 'undefined')
    {
        search_location.value = place['formatted_address'];
        search_location_place_id.value = place.place_id;
    }
    else
    {
        search_location_place_id.value = '';
    }
}

function google_map_api_callback()
{
    search_location_autocomplete = new google.maps.places.Autocomplete((search_location),{types: ['(cities)'], componentRestrictions: {country: 'au'}});

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    search_location_autocomplete.addListener('place_changed', search_location_change);
}
