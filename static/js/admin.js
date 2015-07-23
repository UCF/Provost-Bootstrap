// Adds filter method to array objects
// https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/filter

/* jshint ignore:start */
if (!Array.prototype.filter) {
  Array.prototype.filter = function(a) {
    "use strict";
    if (this === void 0 || this === null) throw new TypeError;
    var b = Object(this);
    var c = b.length >>> 0;
    if (typeof a !== "function") throw new TypeError;
    var d = [];
    var e = arguments[1];
    for (var f = 0; f < c; f++) {
      if (f in b) {
        var g = b[f];
        if (a.call(e, g, f, b)) d.push(g)
      }
    }
    return d
  }
}
/* jshint ignore:end */


var WebcomAdmin = {};


WebcomAdmin.__init__ = function($){
  // Allows forms with input fields of type file to upload files
  $('input[type="file"]').parents('form').attr('enctype','multipart/form-data');
  $('input[type="file"]').parents('form').attr('encoding','multipart/form-data');
};


WebcomAdmin.shortcodeTool = function($){
  cls         = this;
  cls.metabox = $('#shortcodes-metabox');
  if (cls.metabox.length < 1){console.log('no meta'); return;}

  cls.form     = cls.metabox.find('form');
  cls.search   = cls.metabox.find('#shortcode-search');
  cls.button   = cls.metabox.find('button');
  cls.results  = cls.metabox.find('#shortcode-results');
  cls.select   = cls.metabox.find('#shortcode-select');
  cls.form_url = cls.metabox.find("#shortcode-form").val();
  cls.text_url = cls.metabox.find("#shortcode-text").val();

  cls.shortcodes = (function(){
    var shortcodes = [];
    cls.select.children('.shortcode').each(function(){
      shortcodes.push($(this).val());
    });
    return shortcodes;
  })();

  cls.shortcodeAction = function(shortcode){
    var text = '[' + shortcode + ']';
    send_to_editor(text);
  };

  cls.searchAction = function(){
    cls.results.children().remove();

    var value = cls.search.val();

    if (value.length < 1){
      return;
    }

    var found = cls.shortcodes.filter(function(e, i, a){
      return e.match(value);
    });

    if (found.length > 1){
      cls.results.removeClass('empty');
    }

    $(found).each(function(){
      var item      = $("<li />");
      var link      = $("<a />");
      link.attr('href', '#');
      link.addClass('shortcode');
      link.text(this.valueOf());
      item.append(link);
      cls.results.append(item);
    });


    if (found.length > 1){
      cls.results.removeClass('empty');
    }else{
      cls.results.addClass('empty');
    }

  };

  cls.buttonAction = function(){
    cls.searchAction();
  };

  cls.itemAction = function(){
    var shortcode = $(this).text();
    cls.shortcodeAction(shortcode);
    return false;
  };

  cls.selectAction = function(){
    var selected = $(this).find(".shortcode:selected");
    if (selected.length < 1){return;}

    var value = selected.val();
    cls.shortcodeAction(value);
  };

  //Resize results list to match size of input
  cls.results.width(cls.search.outerWidth());

  // Disable enter key causing form submit on shortcode search field
  cls.search.keyup(function(e){
    cls.searchAction();

    if (e.keyCode == 13){
      return false;
    }
  });

  // Search button click action, cause search
  cls.button.click(cls.buttonAction);

  // Option change for select, cause action
  cls.select.change(cls.selectAction);

  // Results click actions
  cls.results.find('li a.shortcode').live('click', cls.itemAction);
};


WebcomAdmin.themeOptions = function($){
  var cls      = this;
  cls.active   = null;
  cls.parent   = $('.i-am-a-fancy-admin');
  cls.sections = $('.i-am-a-fancy-admin .fields .section');
  cls.buttons  = $('.i-am-a-fancy-admin .sections .section a');
  cls.buttonWrap = $('.i-am-a-fancy-admin .sections');
  cls.sectionLinks = $('.i-am-a-fancy-admin .fields .section a[href^="#"]');

  this.showSection = function(){
    var button  = $(this);
    var href    = button.attr('href');
    var section = $(href);

    if (cls.buttonWrap.find('.section a[href="'+href+'"]') && section.is(cls.sections)) {
      // Switch active styles
      cls.buttons.removeClass('active');
      button.addClass('active');

      cls.active.hide();
      cls.active = section;
      cls.active.show();

      history.pushState({}, '', button.attr('href'));
      var http_referrer = cls.parent.find('input[name="_wp_http_referer"]');
      http_referrer.val(window.location);
      return false;
    }
  };

  this.__init__ = function(){
    cls.active = cls.sections.first();
    cls.sections.not(cls.active).hide();
    cls.buttons.first().addClass('active');
    cls.buttons.click(this.showSection);
    cls.sectionLinks.click(this.showSection);

    if (window.location.hash){
      cls.buttons.filter('[href="' + window.location.hash + '"]').click();
    }

    var fadeTimer = setInterval(function(){
      $('.updated').fadeOut(1000);
      clearInterval(fadeTimer);
    }, 2000);
  };

  if (cls.parent.length > 0){
    cls.__init__();
  }
};


/**
 * Adds file uploader functionality to File fields.
 * Mostly copied from https://codex.wordpress.org/Javascript_Reference/wp.media
 **/
WebcomAdmin.fileUploader = function($) {
  $('.meta-file-wrap').each(function() {
    var frame,
        $container = $(this),
        $field = $container.find('.meta-file-field'),
        $uploadBtn = $container.find('.meta-file-upload'),
        $deleteBtn = $container.find('.meta-file-delete'),
        $previewContainer = $container.find('.meta-file-preview');

    // Add new btn click
    $uploadBtn.on('click', function(e) {
      e.preventDefault();

      // If the media frame already exists, reopen it.
      if (frame) {
        frame.open();
        return;
      }

      // Create a new media frame
      frame = wp.media({
        title: 'Select or Upload a File',
        button: {
          text: 'Use this file'
        },
        multiple: false  // Set to true to allow multiple files to be selected
      });

      // When an image is selected in the media frame...
      frame.on('select', function() {

        // Get media attachment details from the frame state
        var attachment = frame.state().get('selection').first().toJSON();

        // Send the attachment URL to our custom image input field.
        $previewContainer.html( '<img src="' + attachment.iconOrThumb + '"><br>' + attachment.filename );

        // Send the attachment id to our hidden input
        $field.val(attachment.id);

        // Hide the add image link
        $uploadBtn.addClass('hidden');

        // Unhide the remove image link
        $deleteBtn.removeClass('hidden');
      });

      // Finally, open the modal on click
      frame.open();
    });

    // Delete selected btn click
    $deleteBtn.on('click', function(e) {
      e.preventDefault();

      // Clear out the preview image
      $previewContainer.html('No file selected.');

      // Un-hide the add image link
      $uploadBtn.removeClass('hidden');

      // Hide the delete image link
      $deleteBtn.addClass('hidden');

      // Delete the image id from the hidden input
      $field.val('');
    });
  });
};


(function($){
  WebcomAdmin.__init__($);
  WebcomAdmin.themeOptions($);
  WebcomAdmin.shortcodeTool($);
  WebcomAdmin.fileUploader($);
})(jQuery);
