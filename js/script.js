// Global Constants
const words = [
    'zero',
    'one',
    'two',
    'three',
    'four',
    'five',
    'six',
    'seven',
    'eight',
    'nine',
    'ten'
];

// Clear the form on reload or back button
$(window).bind("pageshow", function() {
    // reset form
    element = document.getElementById('subForm');
    element.reset();
});

// Final Verification 
function fv19() {
    $('input[name="hv"]').val("verified bitch");
}