/**
 * Created by JVengoechea on 29/09/2016.
 */
app.bapiModules.templates.makeBooking =
{
    init: function()
    {
        // Dismiss modal with updating dates.
        $(document).on('click', '.bapi-revisedates', function(e)
        {
            $('.modal').modal('hide');
        });
    },
    cond: function cond() {

        if(app.exists('.bapi-bookingform'))
        {
            this.init();
        }
    }
}