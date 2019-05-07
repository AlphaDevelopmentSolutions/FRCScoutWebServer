$(document).ready(function ()
{
    $.post('/ajax/getOprStats.php',
        {
            eventId: $('#eventId').val()
        },
        function (data)
        {
            var jsonData = JSON.parse(data);

            $('#opr').html(Math.round(jsonData['oprs']['frc' + $('#teamId').val()] * 100.00) / 100.00);
            $('#dpr').html(Math.round(jsonData['dprs']['frc' + $('#teamId').val()] * 100.00) / 100.00);
        }
    );
});