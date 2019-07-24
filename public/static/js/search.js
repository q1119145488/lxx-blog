$(document).ready(function(){
	

	$("#search input").keyup(function(){
    
        var width = $(window).width();
        if(width >= 768){
            var url = SCOPE.search,
                keyword = $("#search input").val(),
                postData = {'keyword':keyword};
                if( postData != ''){
                        $.post(url,postData,function(result){
                        //相关的业务处理
                        if(result.status == 1) {
                            $(".search-remind").css("display","block");
                            data = result.data;
                            search_html =  "";
                            $(data).each(function(i){
                                search_html += "<li><a href='http://"+window.location.host+"/post/id/"+data[i].id+"'>"+data[i].title+"</a></li>";
                            });
                            
                            $('.search-remind ul').html(search_html);
                        }else {
                            $(".search-remind").css("display","none");
                            search_html =  "";
                            $('.search-remind ul').html(search_html);
                        }
                    }, 'json');
                }
        }
    });
});