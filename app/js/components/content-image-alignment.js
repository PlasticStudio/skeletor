/* 
In conjunction with .ss-htmleditorfield-file.image styling in /app/scss/global/_layout.scss 
*/

$(document).ready(function () {
        
    if ($('.ss-htmleditorfield-file.image').length > 0) {

        addClearFix();

        function addClearFix() {
            $('.ss-htmleditorfield-file.image').each(function () {

                if ($(this).parent().hasClass('captionImage')) {
                    console.log('yes');
                    if ($(this).parent().hasClass('leftAlone') || $(this).hasClass('center') || $(this).hasClass('rightAlone')) {
                        console.log('yes again');
                        $(this).parent().next().css('clear', 'both');
                    }
                } else {
                    console.log('no');
                    if ($(this).hasClass('leftAlone') || $(this).hasClass('center') || $(this).hasClass('rightAlone')) {
                        $(this).parent().next().css('clear', 'both');
                    }
                }
            }); 
        }
    }
});