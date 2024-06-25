
function count_chars(input, output)
{
  console.log(input, output);
  $(input).on("input", function() {
      var maxlength = $(this).attr("maxlength");
      var currentLength = $(this).val().length;
      $(output).html(maxlength - currentLength);

      console.log(maxlength - currentLength);
  });
}
