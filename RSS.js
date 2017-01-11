<script type="text/javascript">

$(document).ready(function () {
    $("#rssContent").text('Select your channel');
    $("#selChan").change(function () {
        $("#rssContent").empty();

        $("#channel_url").text($(this).val())

        let names = $(this).val();
        let arr = names.split('/');

        let rss_file ='xml/xml_'+arr[arr.length-2]+'.xml';

        let xhr = new XMLHttpRequest();
        xhr.open('GET', rss_file);
        xhr.onload = function () {

            let doc = xhr.responseText;

            console.log(xhr.responseXML.childNodes[0].children[0].lastElementChild.children[0].textContent);

            console.log(xhr.responseXML.childNodes[0].children[0].lastElementChild.children[1].textContent);

            let HTMLText = xhr.responseXML.childNodes[0].children[0];

            let arrItem = HTMLText.children;

            var text='';
            var list_articles = text;
            for (let i = 0; i < arrItem.length; i++) {

                if (arrItem[i].nodeName !== 'item') {
                    continue;
                }
                else {
                  if (i & 1){

                      list_articles = list_articles +'<div class="articles1"><table><tr><td><h3><a href="'+arrItem[i].children[1].textContent+'" target="_blank">'+arrItem[i].children[0].textContent+'</a></h3></td></tr>';
                      list_articles = list_articles +'<tr><td>'+arrItem[i].children[2].textContent+'</td></tr></table></div>';

                  }
                  else
                  {
                      list_articles = list_articles +'<div class="articles2"><table><tr><td><h3><a href="'+arrItem[i].children[1].textContent+'" target="_blank">'+arrItem[i].children[0].textContent+'</a></h3></td></tr>';
                      list_articles = list_articles +'<tr><td>'+arrItem[i].children[2].textContent+'</td></tr></table></div>';

                  }




                      //  let arr = arrItem[i].children;
                  //   let arr = arrItem[i].textContent;

                   // console.log(arr.textContent);

                    // for (let j=0; j<arr.length; j++){
                    //     if (arr[j].nodeName == 'title'){
                    //        let arr_title = arr[j].textContent;
                    //         console.log(arr[j].textContent);
                    //     }
                    //     if (arr[j].nodeName == 'link'){
                    //        let arr_link = arr[j].textContent;
                    //     }
                    //     if (arr[j].nodeName == 'description'){
                    //        let arr_descr = arr[j].textContent;
                    //     }
                    // }

                    //let arr_title = arrItem[i].children[0].textContent;





                   //  list_articles = list_articles +'<table><tr><h3 wight="65%"><h3></h3>'+arr_title+'</h3></hd><hd wight="35%">'+'<a href="'+arr_link+'" target="_blank">подробнее сюда</a></hd></tr><tr><td>'+arr_descr+'</td></tr></table><br><br>';



                }

                $("#rssContent").html(list_articles);

            }


        }
        xhr.send();

    });

});


</script>