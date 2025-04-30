@extends('layouts.landlord-layout')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />
<style type="text/css">
.slider-wrapper {
  position: relative;
}

.slider-wrapper .slide-button {
  position: absolute;
  top: 50%;
  outline: none;
  border: none;
  height: 50px;
  width: 50px;
  z-index: 5;
  color: #fff;
  display: flex;
  cursor: pointer;
  font-size: 2.2rem;
  background: #000;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transform: translateY(-50%);
}

.slider-wrapper .slide-button:hover {
  background: #404040;
}

.slider-wrapper .slide-button#prev-slide {
  left: -25px;
  display: none;
}

.slider-wrapper .slide-button#next-slide {
  right: -25px;
}

.slider-wrapper .image-list {
  display: grid;
  grid-template-columns: repeat(10, 1fr);
  gap: 18px;
  font-size: 0;
  list-style: none;
  margin-bottom: 30px;
  overflow-x: auto;
  scrollbar-width: none;
}

.slider-wrapper .image-list::-webkit-scrollbar {
  display: none;
}

.slider-wrapper .image-list .image-item {
  width: 325px;
  height: 400px;
  object-fit: cover;
  margin-top: 20px;
}

.container .slider-scrollbar {
  height: 24px;
  width: 100%;
  display: flex;
  align-items: center;
}

.slider-scrollbar .scrollbar-track {
  background: #ccc;
  width: 100%;
  height: 2px;
  display: flex;
  align-items: center;
  border-radius: 4px;
  position: relative;
}

.slider-scrollbar:hover .scrollbar-track {
  height: 4px;
}

.slider-scrollbar .scrollbar-thumb {
  position: absolute;
  background: #000;
  top: 0;
  bottom: 0;
  width: 50%;
  height: 100%;
  cursor: grab;
  border-radius: inherit;
}

.slider-scrollbar .scrollbar-thumb:active {
  cursor: grabbing;
  height: 8px;
  top: -2px;
}

.slider-scrollbar .scrollbar-thumb::after {
  content: "";
  position: absolute;
  left: 0;
  right: 0;
  top: -10px;
  bottom: -10px;
}

/* Styles for mobile and tablets */
@media only screen and (max-width: 1023px) {
  .slider-wrapper .slide-button {
    display: none !important;
  }

  .slider-wrapper .image-list {
    gap: 10px;
    margin-bottom: 15px;
    scroll-snap-type: x mandatory;
  }

  .slider-wrapper .image-list .image-item {
    width: 280px;
    height: 380px;
  }

  .slider-scrollbar .scrollbar-thumb {
    width: 20%;
  }
}

/* CSS for enlarged image */
.enlarged-image {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
}

.enlarged-image img {
  max-width: 70%;
  max-height: 70%;
  object-fit: contain;
}

.close-enlarged {
  position: absolute;
  top: 18%;
  right: 26%;
  transform: translate(50%, -50%);
  color: white;
  font-size: 30px;
  cursor: pointer;
  width: 40px; /* Set width and height to maintain circular shape */
  height: 40px; /* Set width and height to maintain circular shape */
  padding: 5px; /* Add padding for better visibility */
  display: flex;
  justify-content: center;
  align-items: center;
}

.close-enlarged:hover {
  background-color: rgba(0, 0, 0, 0.5); /* Add a semi-transparent black background */
  border-radius: 50%; /* Make the background circular */
  position: absolute;
  top: 18%;
  right: 26%;
  transform: translate(50%, -50%);
}
</style>
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-3">
                @include('landlord.property.success-message')
                <div class="card-header">
                    <h4>Your Property
                        <a href="{{ route('landlord-create-property') }}" class="btn btn-primary float-end">New Property</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <div class="container">
                            <div class="slider-wrapper">
                              <button id="prev-slide" class="slide-button material-symbols-rounded">
                                chevron_left
                              </button>
                              <ul class="image-list">
                                @php
                            $n = 1;
                        @endphp

                        @foreach ($images as $image)
                            <img class="image-item" src="{{ asset($image->image) }}" alt="img-{{ $n }}" />
                            @php
                                $n++;
                            @endphp
                        @endforeach
                              </ul>
                              <button id="next-slide" class="slide-button material-symbols-rounded">
                                chevron_right
                              </button>
                            </div>
                            <div class="slider-scrollbar">
                              <div class="scrollbar-track">
                                <div class="scrollbar-thumb"></div>
                              </div>
                            </div>
                          </div>
                          <hr>
                          <div>
                            content
                          </div>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        const initSlider = () => {
    const imageList = document.querySelector(".slider-wrapper .image-list");
    const slideButtons = document.querySelectorAll(".slider-wrapper .slide-button");
    const sliderScrollbar = document.querySelector(".container .slider-scrollbar");
    const scrollbarThumb = sliderScrollbar.querySelector(".scrollbar-thumb");
    const maxScrollLeft = imageList.scrollWidth - imageList.clientWidth;

    // Handle scrollbar thumb drag
    scrollbarThumb.addEventListener("mousedown", (e) => {
        const startX = e.clientX;
        const thumbPosition = scrollbarThumb.offsetLeft;
        const maxThumbPosition = sliderScrollbar.getBoundingClientRect().width - scrollbarThumb.offsetWidth;

        // Update thumb position on mouse move
        const handleMouseMove = (e) => {
            const deltaX = e.clientX - startX;
            const newThumbPosition = thumbPosition + deltaX;

            // Ensure the scrollbar thumb stays within bounds
            const boundedPosition = Math.max(0, Math.min(maxThumbPosition, newThumbPosition));
            const scrollPosition = (boundedPosition / maxThumbPosition) * maxScrollLeft;

            scrollbarThumb.style.left = `${boundedPosition}px`;
            imageList.scrollLeft = scrollPosition;
        }

        // Remove event listeners on mouse up
        const handleMouseUp = () => {
            document.removeEventListener("mousemove", handleMouseMove);
            document.removeEventListener("mouseup", handleMouseUp);
        }

        // Add event listeners for drag interaction
        document.addEventListener("mousemove", handleMouseMove);
        document.addEventListener("mouseup", handleMouseUp);
    });

    // Slide images according to the slide button clicks
    slideButtons.forEach(button => {
        button.addEventListener("click", () => {
            const direction = button.id === "prev-slide" ? -1 : 1;
            const scrollAmount = imageList.clientWidth * direction;
            imageList.scrollBy({ left: scrollAmount, behavior: "smooth" });
        });
    });

     // Show or hide slide buttons based on scroll position
    const handleSlideButtons = () => {
        slideButtons[0].style.display = imageList.scrollLeft <= 0 ? "none" : "flex";
        slideButtons[1].style.display = imageList.scrollLeft >= maxScrollLeft ? "none" : "flex";
    }

    // Update scrollbar thumb position based on image scroll
    const updateScrollThumbPosition = () => {
        const scrollPosition = imageList.scrollLeft;
        const thumbPosition = (scrollPosition / maxScrollLeft) * (sliderScrollbar.clientWidth - scrollbarThumb.offsetWidth);
        scrollbarThumb.style.left = `${thumbPosition}px`;
    }

    // Call these two functions when image list scrolls
    imageList.addEventListener("scroll", () => {
        updateScrollThumbPosition();
        handleSlideButtons();
    });

    // Function to enlarge the clicked image
    const enlargeImage = (src) => {
      const enlargedContainer = document.createElement('div');
      enlargedContainer.classList.add('enlarged-image');
      enlargedContainer.innerHTML = `
        <span class="close-enlarged">&times;</span>
        <img src="${src}" alt="Enlarged Image">
      `;
      document.body.appendChild(enlargedContainer);

      // Close enlarged image when clicking on close button
      const closeButton = enlargedContainer.querySelector('.close-enlarged');
      closeButton.addEventListener('click', () => {
        enlargedContainer.remove();
      });
    };

    // Add click event listener to each image
    imageList.querySelectorAll('.image-item').forEach(image => {
      image.addEventListener('click', () => {
        enlargeImage(image.src);
      });
    });
    }

    window.addEventListener("resize", initSlider);
    window.addEventListener("load", initSlider);
      </script>

@endsection
