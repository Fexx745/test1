$(document).ready(function() {
    $("#search").keyup(function() {
        let searchText = $(this).val();
        if (searchText != "") {
            $.ajax({
                url: "action_search.php",
                method: "post",
                data: {
                    query: searchText
                },
                success: function(response) {
                    $("#show-list").html(response);
                }
            })
        } else {
            $("#show-list").html("");
        }
    })

    $(document).on('click', 'a#searchprd', function() {
        $("#search").val($(this).text())
        $("#show-list").html("");
    })
})