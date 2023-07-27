
// LIKE DISLIKE FUNCTIONALITY

function giveLike(randomNumber,id,upload_id,type=null){

    if ($('.like'+randomNumber).hasClass("active")) {
        $('.like'+randomNumber).removeClass("active");
        var isLike=0;
    }
    else {
        $('.like'+randomNumber).addClass("active");
        var isLike=1;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "modelLike",
        data: {"model_id":id,"is_like":isLike,"upload_id":upload_id,"like_type":type},
        dataType: 'json',
        success: function (data) {
        },
        error: function (data) {
        }
    });

}


