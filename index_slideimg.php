<?php
include('condb.php');
include('script-css.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
    $sql = "SELECT * FROM banners";
    $result = mysqli_query($conn, $sql);
    ?>

        <!-- Slideshow -->
        <div class="slideshow-container">
            <?php
            $sql = "SELECT * FROM banners";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <div class="mySlides">
                    <img src="assets/images/banner/<?php echo $row['image']; ?>">
                </div>
                <?php
            }
            ?>
            <a class="prev" onclick="plusSlides(-1)"><i class='bx bxs-chevron-left'></i></a>
            <a class="next" onclick="plusSlides(1)"><i class='bx bxs-chevron-right'></i></a>
        </div>

</body>

</html>

<script>
    let slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");
        if (n > slides.length) { slideIndex = 1 }
        if (n < 1) { slideIndex = slides.length }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
    }
</script>