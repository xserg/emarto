
function count_chars(input, output)
{
  $(input).on("input", function() {
      var maxlength = $(this).attr("maxlength");
      var currentLength = $(this).val().length;
      $(output).html(maxlength - currentLength);

  });
}
