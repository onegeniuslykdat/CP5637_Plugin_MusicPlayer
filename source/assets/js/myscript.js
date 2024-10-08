jQuery(document).ready(function ($) {
  $("#auo-song-list li").on("click", function () {
    var src = $(this).data("src");
    $("#auo-source").attr("src", src);
    $("#auo-audio")[0].load();
    $("#auo-audio")[0].play();

    // Update number of times song has been played
    var songName = $(this).data("text");
    var songId = $(this).data("id");
    $.post(auo_ajax.url, {
      action: "auo_update_play_count",
      song_name: songName,
      song_id: songId,
    });
  });
});
