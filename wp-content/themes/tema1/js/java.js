jQuery(document).ready(function () {
    console.log("ready!123");

                                  /*  ADD BORDER */
    jQuery("body").on("click", ".form__controlmy", function () {
        var name = jQuery(this).text();
        console.log(name);
        /* if (jQuery( ".form__controlmy").hasClass( "img_cat" )) {
             jQuery(".form__controlmy").removeClass('img_cat');
         }else{
             jQuery(".form__controlmy").addClass('img_cat');
         }*/
        jQuery(".wr").toggleClass("img_border")

    });


                                /* REMOVE BORDER */

    jQuery("body").on("click", ".img_border", function () {
        var name = jQuery(this).text();
        console.log(name);
        jQuery(this).removeClass('img_border');

    });

                                  /* ADD PHOTO */

    jQuery("body").on("click", ".form__link", function () {
        var name = jQuery(this).text();
        console.log(name);


        jQuery('.img_cat').each(function () {
            var src = jQuery(this).attr('src');
            jQuery('#position_button').append('<img src="'+src+'">');
        });


    });
                                 /* TABS  */
    jQuery("body").on("click", ".tabs", function () {
        var name = jQuery(this).text();
        console.log(name);


    });

});



