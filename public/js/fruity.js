$(function () {
  $("body").on("click", ".delete", function (event) {
    event.stopImmediatePropagation();
    var id = $(this).attr("data-id");
    if (confirm("Are you sure you want to perform this action?")) {
      $('.loading').show();
      $.ajax({
        url: "/home/makeAction",
        data: { id: id, action: "delete" },
        type: "POST",
        success: function (res) {
          $('.loading').hide();
          alert("Fruit Deleted SuccessFully!");
          location.reload();
        },
      });
    }
  });
  $("body").on("click", ".fav_action", function (event) {
    event.stopImmediatePropagation();
    var id = $(this).attr("data-id");
    var action = $(this).attr("data-action");
    if (confirm("Are you sure you want to perform this action?")) {
      $('.loading').show();
      $.ajax({
        url: "/home/makeAction",
        data: { id: id, action: action },
        type: "POST",
        success: function (res) {
          $('.loading').hide();
          if (res == "reach_limit") {
            alert("You can't Add Fruits on Favorite Because Already 10 Fruits are exists!");
            return;
          }
          if (action == "add_to_fav") {
            alert("Fruit Added To Favorite List SuccessFully!");
          } else {
            alert("Fruit Removed From Favorite List SuccessFully!");
          } location.reload();
        },
      });
    }
  });
});
