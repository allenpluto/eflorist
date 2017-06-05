/**
 * Created by User on 15/03/2017.
 */
var autocomplete,map,geocoder,marker,service;
var autocomplete_active = true;

function displayPlace(place, status)
{
    if (status != google.maps.places.PlacesServiceStatus.OK) {
console.log(status);
        resetMap();
        return false;
    }
    var google_place_row = [];
    var address_components_length = place['address_components'].length;
    for (var j=0; j<address_components_length; j++)
    {
        var type = place['address_components'][j]['types'][0];
        google_place_row[type] = place['address_components'][j]['long_name'];
        google_place_row[type+'_short'] = place['address_components'][j]['short_name'];
    }

    $('.form_row_street_address_display_container').html('<span class="form_row_street_address_display_row form_row_street_address_display_row_name">'+place['name']+'</span><span class="form_row_street_address_display_row form_row_street_address_display_row_suburb">'+google_place_row['locality']+', '+google_place_row['administrative_area_level_1_short']+' '+google_place_row['postal_code']+'</span><span class="form_row_street_address_display_row form_row_street_address_display_row_country">'+google_place_row['country']+'</span><a href="javascript:void(0);" class="reset_map font_icon">&#xf040;</a> ');
    $('#form_members_organization_street_address_place_id').val(place['place_id']);
    console.log(google_place_row);
    initialMap(place['geometry'].location);
}

function initialMap(map_center, map_zoom, map_mapTypeId)
{
    if (typeof map_zoom == 'undefined')
    {
        map_zoom = 14;
    }
    if (typeof map_mapTypeId == 'undefined')
    {
        map_mapTypeId = google.maps.MapTypeId.ROADMAP;
    }

    // Initial Map
    mapOptions = {
        center: map_center,
        zoom: map_zoom,
        mapTypeId: map_mapTypeId
    };
    if (!map)
    {
        map = new google.maps.Map(document.getElementById("form_members_organization_street_address_map"), mapOptions);

        // Initial Marker
        marker = new google.maps.Marker({
            draggable: false,
            map: map,
            position: map_center
        });
    }
    else
    {
        map.setCenter(map_center);
        marker.setPosition(map_center);
    }
    $(".form_row_organization_street_address_container").addClass('form_row_organization_street_address_container_show_map');
}

function resetMap()
{
    $(".form_row_organization_street_address_container").removeClass('form_row_organization_street_address_container_show_map');
    $('#form_members_organization_street_address').val('');
    $('#form_members_organization_street_address_place_id').val('');
    $('.form_row_street_address_display_container').html('');
}

$('.form_row_street_address_display_container').on('click', '.reset_map', function(){
    resetMap();
});

function fillInAddress()
{
    if (!autocomplete_active)
    {
        return false;
    }
    autocomplete_active = false;
    var mapOptions = null;
    var map = null;

    var place = autocomplete.getPlace();
//console.log(place);
    if (place['address_components'])
    {
        displayPlace(place,google.maps.places.PlacesServiceStatus.OK);
    }
    autocomplete_active = true;
}

function initialize_autocomplete()
{
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('form_members_organization_street_address')),
        {types: ['address'],componentRestrictions: {country: 'au'}});

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);

    $('#form_members_organization_street_address_place_id').trigger('change');
}

$('#form_members_organization_street_address_place_id').change(function(){
    var place_id = $(this).val();
//console.log(place_id);
    if (place_id)
    {
        var sydney_center = new google.maps.LatLng(-33.8736509,151.2068896);
        initialMap(sydney_center);

        var request = {'placeId':place_id};
        service = new google.maps.places.PlacesService(map);
        service.getDetails(request, displayPlace);
    }
    else
    {
        resetMap();
    }
});

$('.footer_action_button_reset').click(function(event){
    event.preventDefault();
    $(this).closest('.ajax_form_container').trigger('retrieve_form_data');
});

$('.footer_action_button_save').click(function(event){
    event.preventDefault();
    $(this).closest('.ajax_form_container').trigger('post_form_data');
});

function float_to_time(time){
    var result = {};
    result['hour'] = Math.floor(time*24);
    result['minute'] = Math.round((time*24-result['hour'])*60);
    if (result['minute'] >= 60)
    {
        result['hour']++;
        result['minute'] = 0;
    }
    if (result['hour'] >= 24)
    {
        result['hour'] = 23;
        result['minute'] = 59;
    }

    result['formatted'] = '';
    if (result['hour'] < 10) result['formatted'] += '0';
    result['formatted'] += result['hour'] + ':';
    if (result['minute'] < 10) result['formatted'] += '0';
    result['formatted'] += result['minute'];

    return result;
}

$('.form_hours_work_input_time').on('change',function(){
    var form_hours_work_container = $(this).closest('.form_hours_work_container');
    var form_hours_work_input_time_custom_container = form_hours_work_container.find('.form_hours_work_input_time_custom_container');
    var form_hours_work_input_time_custom_time_period_count = form_hours_work_container.find('.form_hours_work_input_time_custom_time_period_count');

    if ($(this).val() == 'custom')
    {
        form_hours_work_input_time_custom_container.addClass('form_hours_work_input_time_custom_container_show');
        form_hours_work_input_time_custom_time_period_count.trigger('change');
    }
    else
    {
        form_hours_work_input_time_custom_container.removeClass('form_hours_work_input_time_custom_container_show');
    }
});

$('.form_hours_work_input_time_custom_time_period_count').on('change',function(){
    var form_hours_work_container = $(this).closest('.form_hours_work_container');
    var form_hours_work_input_time_custom_time_period_count = $(this);
    var form_hours_work_input_time_custom_time_period_container = form_hours_work_container.find('.form_hours_work_input_time_custom_time_period_container');

    form_hours_work_input_time_custom_time_period_container.html('');

    var time_period_count = form_hours_work_input_time_custom_time_period_count.val();

    for (var i=0;i<time_period_count;i++)
    {
        var form_hours_work_input_time_custom_time_period = $('<div />',{
            'class':'form_hours_work_input_time_custom_time_period'
        });
        var form_hours_work_input_time_custom_open = $('<select />',{
            'class':'form_hours_work_input_time_custom_open'
        });
        var form_hours_work_input_time_custom_close = $('<select />',{
            'class':'form_hours_work_input_time_custom_close'
        });
        var time_division = 96;
        for (var j=0;j<=time_division;j++)
        {
            var time_value = 1.0/96*j;
            time_value = time_value.toFixed(10);
            var time_display = float_to_time(time_value);

            $('<option />',{
                'value': time_value
            }).html(time_display['formatted']).appendTo(form_hours_work_input_time_custom_open);
            $('<option />',{
                'value': time_value
            }).html(time_display['formatted']).appendTo(form_hours_work_input_time_custom_close);
        }


        $('<div />',{
            'class':'form_hours_work_input_label'
        }).html('Open Time '+(i+1)).appendTo(form_hours_work_input_time_custom_time_period);
        form_hours_work_input_time_custom_open.appendTo(form_hours_work_input_time_custom_time_period);
        $('<div />',{
            'class':'form_hours_work_input_label'
        }).html('Close Time '+(i+1)).appendTo(form_hours_work_input_time_custom_time_period);
        form_hours_work_input_time_custom_close.appendTo(form_hours_work_input_time_custom_time_period);

        form_hours_work_input_time_custom_time_period.appendTo(form_hours_work_input_time_custom_time_period_container);
    }

    switch (time_period_count)
    {
        case '2':
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_open:eq(0)').val('0.3750000000');
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_open:eq(1)').val('0.5625000000');
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_close:eq(0)').val('0.5208333333');
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_close:eq(1)').val('0.7291666667');
            break;
        case '3':
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_open:eq(0)').val('0.0000000000');
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_open:eq(1)').val('0.5625000000');
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_open:eq(2)').val('0.9166666667');
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_close:eq(0)').val('0.1041666667');
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_close:eq(1)').val('0.7291666667');
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_close:eq(2)').val('1.0000000000');
            break;
        default:
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_open:eq(0)').val('0.3750000000');
            form_hours_work_input_time_custom_time_period_container.find('.form_hours_work_input_time_custom_close:eq(0)').val('0.7083333333');
    }
});

$('.form_hours_work_input_time_custom_container').on('change', 'select',function(){
    var form_hours_work_container = $(this).closest('.form_hours_work_container');
    var form_hours_work_input_time_custom_result = form_hours_work_container.find('.form_hours_work_input_time_custom_result');
    var form_hours_work_input_time_custom_time_period_count = form_hours_work_container.find('.form_hours_work_input_time_custom_time_period_count');
    var form_hours_work_input_time_custom_time_period = form_hours_work_container.find('.form_hours_work_input_time_custom_time_period');

    var result_array = [];
    form_hours_work_input_time_custom_time_period.each(function(index,element){
        if (index >= form_hours_work_input_time_custom_time_period_count.val()) return false;
        var form_hours_work_input_time_custom_open = $(this).find('.form_hours_work_input_time_custom_open');
        var form_hours_work_input_time_custom_close = $(this).find('.form_hours_work_input_time_custom_close');

        result_array.push('['+form_hours_work_input_time_custom_open.val()+','+form_hours_work_input_time_custom_close.val()+']');
    });
    form_hours_work_input_time_custom_result.val('['+result_array.join()+']');
});

$('.form_hours_work_input_submit').click(function(){
    var form_hours_work_container = $(this).closest('.form_hours_work_container');
    var form_hours_work_result = form_hours_work_container.find('.form_hours_work_result');
    var form_hours_work_input_time = form_hours_work_container.find('.form_hours_work_input_time');
    var form_hours_work_input_time_custom_container = form_hours_work_container.find('.form_hours_work_input_time_custom_container');
    var form_hours_work_input_time_custom_result = form_hours_work_container.find('.form_hours_work_input_time_custom_result');
    var form_hours_work_input_weekday = form_hours_work_container.find('.form_hours_work_input_weekday');

    var result = {};
    try {
        result = $.parseJSON(form_hours_work_result.val());
    } catch (e) {
        console.log('Illegal Json format 1');
        console.log(form_hours_work_result.val());
        return;
    }

    var time_period = form_hours_work_input_time.val();
    var weekday = form_hours_work_input_weekday.val();
    weekday = weekday.split(',');

    if (time_period == 'closed')
    {
        weekday.forEach(function(item,index){
            if (result[item])
            {
                delete result[item];
            }
        });
    }
    else
    {
        if (time_period == 'custom')
        {
            time_period = form_hours_work_input_time_custom_result.val();
        }

        weekday.forEach(function(item,index){
            try {
                result[item] = $.parseJSON(time_period);
            } catch (e) {
                console.log('Illegal Json format 2');
                console.log(time_period);
                return;
            }
        });
    }
    var result_array = [];
    for (var key in result)
    {
        var weekday_time = [];
        result[key].forEach(function(item,index){
            weekday_time.push('['+item.join()+']');
        });
        result_array.push('"'+key+'":['+weekday_time.join()+']');
    }
    var result_string = '';
    if (result_array.length > 0)
    {
        result_string = '{'+result_array.join()+'}';
    }
    console.log(result_string);
    form_hours_work_result.val(result_string).trigger('change');
});

$('.form_hours_work_input_cancel').click(function(){
    var form_hours_work_container = $(this).closest('.form_hours_work_container');
    var form_hours_work_input = form_hours_work_container.find('.form_hours_work_input');
    form_hours_work_input.removeClass('form_hours_work_input_show');
});

$('.form_hours_work_display').on('click','.edit_hours_work',function(){
    var form_hours_work_container = $(this).closest('.form_hours_work_container');
    var form_hours_work_input = form_hours_work_container.find('.form_hours_work_input');
    form_hours_work_input.toggleClass('form_hours_work_input_show');
});

$('.form_hours_work_result').on('change',function(){
    var form_hours_work_container = $(this).closest('.form_hours_work_container');
    var form_hours_work_result = $(this);
    var form_hours_work_input = form_hours_work_container.find('.form_hours_work_input');
    var form_hours_work_display = form_hours_work_container.find('.form_hours_work_display');

    var result = $.parseJSON(form_hours_work_result.val());
    if (!result)
    {
        result = {};
    }
    form_hours_work_display.html('');
    var week_day_name = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    for (var i=0; i<7; i++)
    {
        var weekday_row = $('<div />',{
            'class':'weekday_row'
        }).html('<div class="weekday_name">'+week_day_name[i]+'</div>')
        var weekday_content = $('<div />',{
            'class':'weekday_content hour_row_container'
        });

        var working_hour = result[(i+1)%7];
        if (working_hour)
        {
            working_hour.forEach(function(working_hour_row,index){
                var hour_row = $('<div />',{
                    'class':'hour_row'
                });
                if (working_hour_row[1] <= working_hour_row[0])
                {
                    hour_row.addClass('hour_row_error');
                }
                var open_time = float_to_time(working_hour_row[0]);
                var close_time = float_to_time(working_hour_row[1]);

                var hour_row_content = '<span>'+open_time['formatted']+'</span> to <span>'+close_time['formatted']+'</span>';;
                hour_row.html(hour_row_content).appendTo(weekday_content);
            });

        }
        else
        {
            weekday_content.html('Closed');
        }
        weekday_row.append(weekday_content).appendTo(form_hours_work_display);
    }
    if (form_hours_work_display.html())
    {
        $('<a href="javascript:void(0);" class="edit_hours_work font_icon">&#xf040;</a>').appendTo(form_hours_work_display);
        form_hours_work_input.removeClass('form_hours_work_input_show');
    }
    else
    {
        form_hours_work_input.addClass('form_hours_work_input_show');
    }
});

$(document).ready(function(){
    if ($('.form_hours_work_result').val())
    {
        $('.form_hours_work_result').trigger('change');
    }
});