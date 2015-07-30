// Define globals for JSHint validation:
/* global console */


function mobileNavToggle($) {
  $('#header-pulldown-toggle').on('click', function(e) {
    e.preventDefault();
    $(this).add('#header-menu').toggleClass('active');
  });
}


if (typeof jQuery != 'undefined'){
  jQuery(document).ready(function($) {

    mobileNavToggle($);

  });
} else {
  console.log('jQuery dependency failed to load');
}
