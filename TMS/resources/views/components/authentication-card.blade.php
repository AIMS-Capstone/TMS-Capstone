  <div class="2xl:container h-screen m-auto bg-sky-50">  
      <div hidden class="fixed inset-0 w-3/5 lg:block bg-sky-50">
          <span class="absolute left-6 bottom-6 text-sm"></span>
          <div class="space-y-2 py-20 flex items-center sm:justify-center">
              {{ $logo }}
          </div>

          <div class="fixed lg:inset-20 w-full overflow-hidden h-5/6 lg:w-1/2" id="carousel-container">
            <div class="flex transition-transform duration-500 ease-out w-full h-full" id="carousel">
                <!-- Slide 1 -->
                <div class="min-w-full flex-shrink-0 p-12 flex flex-col items-center justify-center">
                    <img src="images/Visual data-pana.png" alt="Slide 1" class="object-contain h-60 lg:w-1/2" />
                    <div class="text-center mt-2">
                        <h2 class="text-2xl font-bold">Unlock Insightful Reports</h2>
                        <p class="taxuri-color text-sm mt-2">
                            Taxuri provides insightful reports that help <br>
                            you understand financial landscape
                        </p>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="min-w-full flex-shrink-0 p-12 flex flex-col items-center justify-center">
                    <img src="images/Audit-pana.png" alt="Slide 2" class="object-contain h-52 lg:w-1/2" />
                    <div class="text-center mt-2">
                        <h2 class="text-2xl font-bold text-center">Manage Taxes with Ease</h2>
                        <p class="text-center taxuri-color mt-2">
                            Taxuri ensures that managing taxes is straightforward and <br>
                            stress-free, so you can focus on what matters most
                        </p>
                    </div>
                </div>
                <!-- Slide 3 -->
                <div class="min-w-full flex-shrink-0 p-12 flex flex-col items-center justify-center">
                    <img src="images/Data extraction-amico.png" alt="Slide 3" class="object-contain h-52 lg:w-1/2" />
                    <div class="text-center mt-2">
                        <h2 class="text-2xl font-bold text-center mt-4">Smart Tax Solutions</h2>
                        <p class="text-center taxuri-color mt-2">
                            With built-in predictive analytics, Taxuri delivers smart <br>
                            solutions that adapt to your needs <br>
                            saving your time and effort
                        </p>
                    </div>
                </div>
            </div>
        
            <!-- Controls -->
            <div class="absolute inset-0 flex justify-between items-center">
                <button class="hidden text-gray-700 hover:text-gray-900" id="prev">
                    &#10094;
                </button>
                <button class="hidden text-gray-700 hover:text-gray-900" id="next">
                    &#10095;
                </button>
            </div>
        </div>
        
        <!-- Indicators -->
        <div class="flex justify-center items-center mt-96 lg:mt-96 xl:mt-80 space-x-3">
            <div class="w-2 h-2 md:w-3 md:h-3 bg-gray-400 rounded-xl" id="indicator-1"></div>
            <div class="w-2 h-2 md:w-3 md:h-3 bg-gray-400 rounded-xl" id="indicator-2"></div>
            <div class="w-2 h-2 md:w-3 md:h-3 bg-gray-400 rounded-xl" id="indicator-3"></div>
        </div>
      </div>

      <div hidden role="hidden" class="fixed inset-0 w-2/5 ml-auto bg-white lg:block sm:rounded-3xl overflow-hidden shadow-xl"></div>
          <div class="relative h-full ml-auto lg:w-2/5 px-40 py-6 items-center sm:justify-center">
              {{ $slot }}
          </div>

  </div>

<script>
    const carousel = document.getElementById('carousel');
    const slides = carousel.children;
    const indicators = Array.from(document.querySelectorAll('[id^=indicator-]'));

    let currentIndex = 0;
    const autoplayInterval = 3000; 
    let autoplay;

    document.getElementById('next').addEventListener('click', () => {
      moveToNextSlide();
      resetAutoplay(); 
    });

    document.getElementById('prev').addEventListener('click', () => {
      moveToPreviousSlide();
      resetAutoplay(); 
    });

    // Add click event listeners to each indicator
    indicators.forEach((indicator, index) => {
      indicator.addEventListener('click', () => {
        currentIndex = index;
        updateCarousel();
        resetAutoplay(); // Reset autoplay on indicator click
      });
    });

    function moveToNextSlide() {
      if (currentIndex < slides.length - 1) {
        currentIndex++;
      } else {
        currentIndex = 0;
      }
      updateCarousel();
    }

    function moveToPreviousSlide() {
      if (currentIndex > 0) {
        currentIndex--;
      } else {
        currentIndex = slides.length - 1;
      }
      updateCarousel();
    }

    function updateCarousel() {
      const offset = currentIndex * -100;
      carousel.style.transform = `translateX(${offset}%)`;

      indicators.forEach((indicator, index) => {
        if (index === currentIndex) {
          indicator.classList.add('bg-blue-900');
          indicator.classList.remove('bg-gray-400');
        } else {
          indicator.classList.add('bg-gray-400');
          indicator.classList.remove('bg-blue-900');
        }
      });
    }

    function startAutoplay() {
      autoplay = setInterval(() => {
        moveToNextSlide();
      }, autoplayInterval);
    }

    function resetAutoplay() {
      clearInterval(autoplay);
      startAutoplay();
    }

    startAutoplay();
  
</script>