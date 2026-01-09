<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Slider</title>
    <link rel="stylesheet" href="include/styles.css">  
</head>
<body>
<div class="slider-container">
    <div class="slider">
        <div class="slide"><img src="uploads/1.png" alt="Slide 1"></div>
        <div class="slide"><img src="uploads/2.png" alt="Slide 2"></div>
        <div class="slide"><img src="uploads/3.png" alt="Slide 3"></div>
    </div>
    <div class="prev" onclick="moveSlide(-1)">&#10094;</div>
    <div class="next" onclick="moveSlide(1)">&#10095;</div>
</div>

<script>
let slideIndex = 0;
const slides = document.querySelectorAll('.slide');

function moveSlide(direction) {
    slideIndex += direction;
    if (slideIndex < 0) slideIndex = slides.length - 1;
    if (slideIndex >= slides.length) slideIndex = 0;
    document.querySelector('.slider').style.transform = `translateX(-${slideIndex * 100}%)`;
}

setInterval(() => moveSlide(1), 3000); // Auto-slide every 3 seconds
</script>
</body>
</html>
