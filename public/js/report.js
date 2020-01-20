$('#from_date').datepicker({
    format: 'yyyy-mm-dd',
    autoHide: true
});
$('#to_date').datepicker({
    format: 'yyyy-mm-dd',
    autoHide:true,
    startDate: $('#from_date').datepicker('getDate')
});
$("#from_date").on("change", function (e) {
    $('#to_date').datepicker('setStartDate', $('#from_date').datepicker('getDate'));
    $('#to_date').val('');
});

$("#from_date, #to_date").keypress(function(event) {
    return false;
});