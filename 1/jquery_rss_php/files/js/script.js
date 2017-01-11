function getRSS(feedUrl) {
    $("#rssContent").empty();

    $.get('proxy.php?url=' + feedUrl, function(data) {
    $('#indicator').hide();
        $(data).find('item').each(function() {

            var title = $(this).find('title').text();
      
            var url = $(this).find('link').text();
      
            var description = $(this).find('description').text();
      
            var pubDate = $(this).find('pubDate').text();

            var html;
            html  = "<div class=\"entry\"><h2 class=\"postTitle\">" + "<a href=\"" + url + "\" target=\"_blank\">"+title+"<\/a><\/div>" + "<\/h2>";
            html += "<em class=\"date\">" + pubDate + "</em>";
            html += "<div class=\"description\">" + description + "</div>";

            $('#rssContent').append($(html));
        });
    });
}

$(document).ready(function() {
    $('#indicator').show();
    getRSS("http://feeds2.feedburner.com/jquery/");
})