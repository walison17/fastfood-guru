$(document).ready(function () {

    $(".form-ajax").on('submit',function (event){
        event.preventDefault();
        let form = $(this);
        let data = $(this).serializeArray();
        let action = $(this).data("action");
        
        if (action === undefined || action == ""){
            alert("Erro inesperado, recarregue a página e tente novamente");
            return false;
        } 

        $(".loading-content").addClass("show"); //Adiciona Loading
        $.ajax({
            type: "POST",
            url: action,
            data: data,
            dataType: "json",
            success: function (response) {
                switch (response.acao){
                    case "redirect":
                        alert(response.message);
                        if (response.url != undefined && response.url != ""){
                            window.location.href = response.url;
                        }else{
                            window.location.reload();
                        }
                        break;
                    default:
                        $(form).trigger("reset");
                        $(".modal").modal("hide");
                        alert(response.message);
                }
            },
            error : function (response){
                console.log(response.responseText);
                alert(response.responseText);
            }
        }).always(function (){
            $(".loading-content").removeClass("show"); //Remove Loading
        });
    });

    $(".desce-efeito").click(function (event) {
        event.preventDefault();
        $('body,html').animate({
            scrollTop: $($(this).attr("href")).offset().top - 40
        }, 700);
    });

    // browser window scroll (in pixels) after which the "back to top" link is shown
    var offset = 300,
    //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
    offset_opacity = 1200,
    //duration of the top scrolling animation (in ms)
    scroll_top_duration = 700,
    //grab the "back to top" link
    $back_to_top = $('.cd-top');
    //hide or show the "back to top" link
    $(window).scroll(function () {
    ($(this).scrollTop() > offset) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
    if ($(this).scrollTop() > offset_opacity) {
        $back_to_top.addClass('cd-fade-out');
    }
    });
    //smooth scroll to top
    $back_to_top.on('click', function (event) {
    event.preventDefault();
    $('body,html').animate({
        scrollTop: 0,
    }, scroll_top_duration
            );
    });

});