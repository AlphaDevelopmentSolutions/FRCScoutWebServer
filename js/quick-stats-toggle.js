function showQuickStats()
{

    if($('#quick-stats').attr('hidden'))
    {
        $('#show-stats-btn').html('Show Less');
        $('#quick-stats').removeAttr('hidden');
    }

    else
    {
        $('#show-stats-btn').html('Show More');
        $('#quick-stats').attr('hidden', 'hidden');
    }

}