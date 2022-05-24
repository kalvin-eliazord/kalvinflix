<?php
include_once("includes/header.php");
?>
<div class="textboxContainer">
    <input type="text" class="searchInput" placeholder="Search for something">
    <div class="searchMode">
        <input type="radio" name="searchMode" value="entity" checked>
        <label>Entity</label>

        <input type="radio" name="searchMode" value="actor">
        <label>Actor</label>

        <input type="radio" name="searchMode" value="producer">
        <label>Producer</label>
    </form>
</div>

<div class="results"></div>

<script>

$(function() {
    var username = '<?php echo $userLoggedIn; ?>';
    var timer;
    
    $(".searchInput").keyup(function() {
        clearTimeout(timer);

        timer = setTimeout(function() {
            var val = $(".searchInput").val();
            var searchMode = $('input[type=radio]:checked').val();

            if(val != "") {
                $.post("ajax/getSearchResults.php", { term: val, username: username, searchMode: searchMode },
                 function(data) {
                    $(".results").html(data);
                })
            }
            else {
                $(".results").html("");
            }

        }, 500);
    })

})

</script>