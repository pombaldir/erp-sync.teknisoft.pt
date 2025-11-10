<script src="<?php echo URLBASE;?>/vendors/bootbox/bootbox.min.js"></script>

<script>
var openModalIframe = function(url) {
  var dialog = bootbox.dialog({
    message: '<iframe src="' + url + '&rnd='+Math.floor(Math.random() * 10000)+'"  class="border-0 h-100 w-100" frameBorder="0" style="width:100%;height:100%;"></iframe>',
    size: 'lg'
  });

dialog.on("shown.bs.modal", function() {
  
});

  // Remove whitespace inside dialog which is provided by the iframe content.
  dialog.find('.modal-body').addClass('p-0');

  // Maximize usable screen area inside modal.
  dialog.find('.modal-content').addClass('h-100');
  dialog.find('.bootbox-body').addClass('h-100');

  // Hide the close button instead of using `closeButton: false` in the bootbox config,
  // so we have a way to close the modal from within the iframe.
  dialog.find('.bootbox-close-button').addClass('d-none');
};
</script>