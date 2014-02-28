//使用方法: class="editor" , lang="javascript",没有.editor不会解析
$(".editor").each(function(index){
    var id = "editor_"+index;
    $(this).attr("id",id);
    var editor = ace.edit(id);
   editor.setTheme("ace/theme/visual_studio");
   editor.getSession().setMode("ace/mode/" + $(this).attr("lang"));
   editor.resize();
})

//生成导航
var nav_html = "";
$(".wk-page-doc h3 a").each(function(index){
    nav_html += "<li><a href='#" + $(this).attr("name") + "'>"+ $(this).text() +"</a></li>";
});
$(".wk-menu").append(nav_html);
