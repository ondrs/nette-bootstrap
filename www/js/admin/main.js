$(function () {

  // disable dropzone autodiscover
  Dropzone.autoDiscover = false;

  $('.grido').grido();

  tinyMCE.init({
    mode : 'specific_textareas',
    editor_selector : 'tinymce',
    height : 400,
    width: '95%',
    entity_encoding: 'raw',
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview'
  });

});
