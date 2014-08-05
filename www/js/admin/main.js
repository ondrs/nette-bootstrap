$(function () {

  $('table.grido').grido();

  tinyMCE.init({
    mode : 'specific_textareas',
    editor_selector : "tinymce",
    theme : 'advanced',
    skin : 'o2k7',
    height : 400,
    width: '95%',
    plugins : 'pagebreak,paste',
    entity_encoding: 'raw',
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,

    theme_advanced_buttons1 : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist',
    theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,image,cleanup,help,code',
    theme_advanced_buttons3 : false,
    theme_advanced_buttons4 : false,
    theme_advanced_toolbar_location : 'top',
    theme_advanced_toolbar_align : 'left',
    theme_advanced_statusbar_location : false,
    theme_advanced_resizing : false
  });

});
