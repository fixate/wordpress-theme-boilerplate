jQuery(document).ready(function($) {
  var metaBox = $('form#post .theranch-imageupload-form'),
  clickSource = null;

  if (metaBox.length == 0)
    return;

	$('img#desat-img, img#colour-img', metaBox).click(function() {
    clickSource = this;
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		var fileurl = $('img',html).attr('src');
		$(clickSource).attr('src', fileurl);
		$(clickSource).siblings('input[type="hidden"]').val(fileurl);

		tb_remove();
	};
});