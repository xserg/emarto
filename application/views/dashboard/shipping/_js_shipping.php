<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
    //select continen
    $(document).on("change", "#select_continents", function () {
        if ($(this).val() == 'WW') {
          $('#select_countries option').empty();
          $('#select_states option').empty();
          $('#form_group_countries').hide();
          $('#form_group_states').hide();
          return;
        }
        $("#btn_select_region_container").show();
        get_countries_by_continent($(this).val(), "<?= trans("all_countries"); ?>");
        if ($(this).val() != '' && $(this).val() != 0) {
            $("#form_group_countries").show();
        } else {
            $("#form_group_countries").hide();
        }
    });
    //select country
    $(document).on("change", "#select_countries", function () {
        get_states_by_country($(this).val(), "<?= trans("all_states"); ?>");
        $("#form_group_states").show();
    });
    //select region
    $(document).on("click", "#btn_select_region", function () {

        var continent = $('#select_continents').val();
        var continent_text = $('#select_continents option:selected').text();

        if (continent == 'WW') {
            region_id == 'WW';
            region_id = continent;
            region_text = continent_text;
            input_name = 'continent';
            //$('#select_continents').empty();
            $('#form_group_continents').hide();
        } else {

        var country = $('#select_countries').val();
        var country_text = $('#select_countries option:selected').text();
        var state = $('#select_states').val();
        var state_text = $('#select_states option:selected').text();

        var region_id = state;
        var region_text = country_text + '/' + state_text;
        var input_name = 'state';
        if (region_id == '' || region_id == 0 || region_id == null) {
            region_id = country;
            region_text = country_text;
            input_name = 'country';
        }
        if (region_id == '' || region_id == 0 || region_id == null) {
            region_id = continent;
            region_text = continent_text;
            input_name = 'continent';
        }
        }
        if (region_id) {
            if (!$('#lc-' + input_name + '-' + region_id).length) {
                $("#selected_regions_container").append('<div id="lc-' + input_name + '-' + region_id + '" class="region">' + region_text + '<a href="javascript:void(0)"><i class="fa fa-times"></i></a><input type="hidden" value="' + region_id + '" name="' + input_name + '[]"></div>');
            }
        }

        if (region_id == ' ') {
            //console.log('world');
            $('#form_group_continents').hide();
            $('#form_group_countries').hide();
            $('#form_group_states').hide();
            $("#btn_select_region_container").hide();
        }
        //return;
        //reset
        //$('#select_continents').val(null).trigger('change');
        //$('#select_countries option').empty();
        //$('#select_states option').empty();
        //$('#select_countries').hide();
        //$('#form_group_states').hide();
    });
    //delete location
    $(document).on("click", ".region a", function () {
        $(this).parent().remove();
        $('#form_group_continents').show();
    });

    //delete location database
    function delete_shipping_location(id) {
        var data = {
            "id": id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            type: "POST",
            url: base_url + "dashboard_controller/delete_shipping_location_post",
            data: data,
            success: function (response) {
            }
        });
    }

    //shipping methods
    $(document).on("click", "#btn_select_shipping_method", function () {
        var data = {
            "selected_option": $('#select_shipping_methods').val()
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        data["sys_lang_id"] = sys_lang_id;

        $.ajax({
            type: "POST",
            url: base_url + "dashboard_controller/select_shipping_method",
            data: data,
            success: function (response) {
                var obj = JSON.parse(response);
                if (obj.result == 1) {
                    $("#selected_shipping_methods").append(obj.html_content);
                }
            }
        });
    });

    //delete shipping method
    $(document).on("click", ".btn-delete-shipping-method", function () {
        var id = $(this).attr('data-id');
        $("#row_shipping_method_" + id).remove();
    });

    //delete shipping method database
    function delete_shipping_method(id, message) {
        swal({
            text: message,
            icon: "warning",
            buttons: true,
            buttons: [sweetalert_cancel, sweetalert_ok],
            dangerMode: true,
        }).then(function (willDelete) {
            if (willDelete) {
                var data = {
                    "id": id
                };
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                    type: "POST",
                    url: base_url + "dashboard_controller/delete_shipping_method_post",
                    data: data,
                    success: function (response) {
                        $("#row_shipping_method_" + id).remove();
                    }
                });
            }
        });
    }

    function showError(error) {
      swal({
          text: error,
          icon: "warning",
          buttons: sweetalert_ok,
          dangerMode: true,
      });
    }

    $(document).ready(function () {
      var error_message = '';
        $('#shipping-zone').submit(function (e) {

            if (!$('#zone_name').val()
            && !$('[name=zone_name_lang_1]').val()
            && !$('[name=zone_name_lang_2]').val()
            && !$('[name=zone_id]').val()) {
              showError(shipping_name_requiired);
              e.preventDefault();
              return;
            }

            if (
              ($('#flat_rate_cost_class_1').val() == 0 && $('input[name="status_1"]:checked').val() == 1) ||
              ($('#flat_rate_cost_class_2').val() == 0 && $('input[name="status_2"]:checked').val() == 1) ||
              ($('#flat_rate_cost_class_3').val() == 0 && $('input[name="status_3"]:checked').val() == 1)
            ) {
              showError(shipping_not_null);
              e.preventDefault();
              return;
            }

            if (!$('#flat_rate_cost_class_1').val() && !$('#flat_rate_cost_class_2').val() && !$('#flat_rate_cost_class_3').val()) {
              showError(shipping_method_requiired);
              e.preventDefault();
              return;
            }

            if (
              ($('#flat_rate_cost_class_1').val() &&  !$('select[name="time_1"]').val()) ||
              ($('#flat_rate_cost_class_2').val() &&  !$('select[name="time_2"]').val()) ||
              ($('#flat_rate_cost_class_3').val() &&  !$('select[name="time_3"]').val())
            ) {
              showError(shipping_time_requiired);
              e.preventDefault();
              return;
            }



            if (!$("#selected_regions_container").text()) {
              showError(select_shipping_destinations);
              e.preventDefault();
              return;
            }

        });
    });

    //set economy 0 for free

    $(document).on("click", "#status_1_3", function () {
        $("#flat_rate_cost_class_1").val('0.00').attr('disabled', 'disabled');
    });
    $(document).on("click", "#status_2_3", function () {
        $("#flat_rate_cost_class_2").val('0.00').attr('disabled', 'disabled');
    });
    $(document).on("click", "#status_3_3", function () {
        $("#flat_rate_cost_class_3").val('0.00').attr('disabled', 'disabled');
    });

    $(document).on("click", "#status_1_1", function () {
        $("#flat_rate_cost_class_1").val('0.00').removeAttr("disabled");
    });
    $(document).on("click", "#status_2_1", function () {
        $("#flat_rate_cost_class_2").val('0.00').removeAttr("disabled");
    });
    $(document).on("click", "#status_3_1", function () {
        $("#flat_rate_cost_class_3").val('0.00').removeAttr("disabled");
    });

    $(document).on("change", "#zone_name", function (e) {
        if ($('#zone_name').val() == 'domestic') {
          $("#selected_regions_container").empty();
          //$("#selected_regions_container").hide();
            console.log($('#zone_name').val());
            var country;
            $("#select_continents").val('EU');
            var data = {
                "key": 'EU',
                "lang": sys_lang_id
            };

            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "ajax_controller/get_default_country",
                data: data,
                success: function (response) {
                    $('#select_countries option').remove();
                    $("#select_countries").append(response);
                    //country = $("#select_countries").val();
                    $("#select_countries").attr('disabled', 'disabled');
                    //$("#btn_select_region").trigger("click");
                }
            });
            $('#form_group_continents').hide();
            //$('#select_continents').val(null).trigger('change');
            $("#form_group_countries").show();

            console.log(default_country);
            get_states_by_country(default_country, "<?= trans("all_states"); ?>");
            $("#form_group_states").show();
            $("#btn_select_region_container").show();

            $("#select_countries").val(default_country);
            //$("#btn_select_region").trigger("click");
        } else {
          $("#selected_regions_container").empty();
          $('#form_group_continents').show();
          $('#select_continents').val(null).trigger('change');

          $('#select_countries option').empty();
          $("#select_countries").removeAttr("disabled");
          $('#select_states option').empty();
          $('#select_countries').hide();
          $('#form_group_states').hide();
        }
    });

</script>
