$(document).ready(function(){


  $("#tagsSubmit").click(function(){
    
     var tags = $("#tags").val();
     url = SCOPE.tagsAdd;

     postData = {'tags':tags};
     $.post(url,postData,function(result){
        //相关的业务处理
        if(result.status == 1) {
            // 将信息填充到html中
            data = result.data;
            
            tags_html = "";
            tags_list = "";
            $(data).each(function(i){
                tags_list +='<input name="tag[]" type="hidden"  value="'+data.tags+'"/>';
                tags_html += "<a class='btn btn-success'>"+data.tags+"</a>";
            });
            $('.tags_id').html(tags_html);
            $('.tagsList').html(tags_list);
        }else if(result.status == 2) {
            // 将信息填充到html中
            data = result.data;
            
            tags_html = "";
            tags_list = "";
            $(data).each(function(i){
                tags_list +='<input name="tag[]" type="hidden"  value="'+data[i].tags+'"/>';
                tags_html += "<a class='btn btn-success'>"+data[i].tags+"</a>";
            });
            $('.tags_id').html(tags_html);
            $('.tagsList').html(tags_list);
        }
        else if(result.status == 0) {
            $('.tags_id').html('添加失败');
        }
    }, 'json');


  });


    $("#tagsRemove").click(function(){
    

     url = SCOPE.tagsRemove;

     postData = {'status':1};
     $.post(url,postData,function(result){
        //相关的业务处理
        if(result.status == 1) {
            
            tags_html = "";
            $('.tags_id').html(tags_html);
        }else {
            $('.tags_id').html('添加失败');
        }
    }, 'json');


  });

    $("#like").click(function(){
    

     url = SCOPE.like;
     articleId = SCOPE.articleId;
     

     postData = {'articleId':articleId};
     $.post(url,postData,function(result){
        //相关的业务处理
        if(result.status == 1) {
            data = result.data;
            like_html = "Thanks For Your Rating!";
          
            $('#like span').html(data);
            $('#remind').html(like_html);
        }else if(result.status == 2){
            like_html = "You had clicked on like,Please don't click repeatly!";
            $('#remind').html(like_html);
        }
    }, 'json');
  });


    //日志添加
    //
    $("#submit").click(function(){
    

     url = SCOPE.diary;
     var diary = $("#addDiary").val();

     postData = {'diary':diary};
     $.post(url,postData,function(result){
        //相关的业务处理
        if(result.status == 1) {
            data = result.data;
            diary_list ='';
            $(data).each(function(i){
                time = data[i].time ;
                diary_list +='<li><span>'+time+'</span><span>'+data[i].diary+'&nbsp;<a class="diary-delete">删除</a></span></li>';
            });
            $('.update-tree ul').html( diary_list );

            $(" #addDiary").val("");
        }else if(result.status == 2){

            like_html = "";
            $('#remind').html(like_html);
        }
    }, 'json');
  });

    //验证码校验
    $("#captchaInput").blur(function(){
        var url = SCOPE.captcha;
        code = $("#captchaInput").val();
        postData = {'code':code};
          $.post(url,postData,function(result){
                if(result.status == 1) {
                      $('#verifyRight').css("display","block");   
                }else{
                       $('#verifyWrong').css("display","block"); 
                }
            }, 'json');
          }).focus(function(){
              $('#verifyRight').css("display","none"); 
              $('#verifyWrong').css("display","none"); 
          });           

});