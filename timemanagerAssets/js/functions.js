function employeeTableClicked(action, employee_id, page_id) {
    switch (action) {
        case 'view':
            window.location = web_root + 'employee/view/' + employee_id;
            break;
        case 'edit':
            window.location = web_root + 'employee/edit/' + employee_id;
            break;
        case 'trash':
            window.location = web_root + 'employee/remove/' + page_id + '/' + employee_id;
            break;
        default:
            break;
    }
}

function updateTime(date, time_index, time_operation) {
    var multiplier;
    
    if (time_operation == 'in') {
        if (time_index > 2) {
            multiplier = (time_index+2)*2 - 1;
        } else {
            multiplier = (time_index+1)*2 - 1;
        }
    } else {
        if (time_index > 2) {
            multiplier = (time_index+2)*2;
        } else {
            multiplier = (time_index+1)*2;
        }
    }
    
    jQuery('.dialog_input[name=time]').attr('value', jQuery('table tbody tr:contains(\'' + date + '\') td').eq(multiplier).text());
    jQuery('.dialog_title').text(date);
    
    jQuery('.update_time_dialog input[name=date]').attr('value', date);
    jQuery('.update_time_dialog input[name=time_index]').attr('value', time_index);
    jQuery('.update_time_dialog input[name=time_operation]').attr('value', time_operation);
    jQuery('.update_time_dialog').dialog('open');
}

function loadPayPeriod(employee_id, pay_period) {
    window.location = web_root + 'employee/view/' + employee_id + '/' + pay_period;
}

function addDate(/*id, operation='pay_period'*/) {
    var id = arguments[0];
    var operation='pay_period';
    
    if (arguments.length > 1) {
        operation = arguments[1];
    }
    
    switch (operation) {
        case 'pay_period':
            jQuery('.date').datepicker('option', 'minDate', new Date(jQuery('.start_date').text()));
            jQuery('.date').datepicker('option', 'maxDate', new Date(jQuery('.end_date').text()));
            break;
        default:
            //Do nothing
    }
    
    jQuery('.add_date_dialog input[name=id]').attr('value', id);
    jQuery('.add_date_dialog').dialog('open');
}

function add_date_response(response) {
    jQuery('.add_date_response_text').text = response;
    jQuery('.add_date_response').dialog('open');
}

function client_operations(operation) {
    switch (operation) {
        case 'add':
            jQuery('.client_add_dialog').dialog('open');
            break;
        case 'edit':
            jQuery('.client_edit_dialog').dialog('open');
            jQuery('.client_name').text(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').text());
            jQuery('input[name=client_name]').val(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').text());
            jQuery('input[name=client_id]').val(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').val());
            break;
        case 'remove':
            jQuery('.client_remove_dialog').dialog('open');
            jQuery('.client_name').text(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').text());
            jQuery('input[name=client_id]').val(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').val());
            break;
        default:
            //None
    }
}

function department_operations(operation) {
    switch (operation) {
        case 'add':
            jQuery('.department_add_dialog').dialog('open');
            break;
        case 'edit':
            jQuery('.department_edit_dialog').dialog('open');
            jQuery('.department_name').text(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').text());
            jQuery('input[name=department_name]').val(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').text());
            jQuery('input[name=department_id]').val(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').val());
            break;
        case 'remove':
            jQuery('.department_remove_dialog').dialog('open');
            jQuery('.department_name').text(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').text());
            jQuery('input[name=department_id]').val(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').val());
            break;
        default:
            //None
    }
}