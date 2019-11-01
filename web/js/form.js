$(document).ready(function () {
    var $inputs = $("input"); // get the collection of inputs

    $inputs.on('keypress', function (e) {
        if (e.which == 13) {
            var index = $inputs.index(this); // get the index of the current input
            $inputs.eq(index + 1).focus(); // focus the next input
        }
    });
});
