let slideIndex = 0;

const slides = document.querySelectorAll('.banner-slide');
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');

function showSlide(index) {
  if (index >= slides.length) {
    slideIndex = 0;
  } else if (index < 0) {
    slideIndex = slides.length - 1;
  }

  // Hide all slides
  slides.forEach(slide => slide.style.display = 'none');

  // Show the current slide
  slides[slideIndex].style.display = 'block';
}

function nextSlide() {
  slideIndex++;
  showSlide(slideIndex);
}

function prevSlide() {
  slideIndex--;
  showSlide(slideIndex);
}

// Initialize the carousel
showSlide(slideIndex);

// Event listeners for navigation
nextBtn.addEventListener('click', nextSlide);
prevBtn.addEventListener('click', prevSlide);

// Auto slide every 3 seconds
setInterval(nextSlide, 3000);
