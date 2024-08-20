<div class="2xl:container h-screen m-auto bg-sky-50">  
    <div hidden class="fixed inset-0 w-3/5 lg:block bg-sky-50">
        <span class="absolute left-6 bottom-6 text-sm"></span>
        <div class="space-y-2 py-20 flex items-center sm:justify-center">
            {{ $logo }}
        </div>

        <div class="fixed inset-0 w-full overflow-hidden">
            <div class="flex transition-transform duration-500 ease-out" id="carousel">
              <div class="min-w-full flex-shrink-0 p-8">
                <div class="items-center justify-center w-2/6 h-2/6">
                  <div class="flex items-center justify-center">
                    <img src="images/Visual data-pana.png" alt="Slide 1" />
                  </div>
                  <div class="text-center mt-4">
                    <h2 class="text-2xl font-semibold">Unlock Insightful Reports</h2>
                    <p class="taxuri-color mt-2">
                      Taxuri provides insightful reports that help you understand financial landscape.
                    </p>
                  </div>
                </div>
              </div>
          
              <div class="min-w-full flex-shrink-0 p-8">
                <div class="items-center justify-center w-2/6 h-2/6">
                  <div class="flex items-center justify-center">
                    <img src="images/Audit-pana.png" alt="Slide 2" />
                  </div>
                  <h2 class="text-2xl font-semibold text-center mt-4">Manage Taxes with Ease</h2>
                  <p class="text-center taxuri-color mt-2">
                    Taxuri ensures that managing taxes is straightforward and stress-free, so you can focus on what matters most.
                  </p>
                </div>
              </div>

              <div class="min-w-full flex-shrink-0 p-8">
                <div class="items-center justify-center w-2/6 h-2/6">
                  <div class="flex items-center justify-center">
                    <img src="images/Data extraction-pana.png" alt="Slide 3" />
                  </div>
                  <h2 class="text-2xl font-semibold text-center mt-4">Smart Tax Solutions</h2>
                  <p class="text-center taxuri-color mt-2">
                    With built-in predictive analytics, Taxuri delivers smart solutions that adaprt to your needs, saving your time and effort.
                  </p>
                </div>
              </div>
          
              <!-- Add more slides as needed -->
            </div>
          
            <!-- Controls -->
            {{-- <div class="absolute inset-0 flex justify-between items-center pointer-events-none">
              <button class="text-gray-700 hover:text-gray-900 pointer-events-auto" id="prev">
                &#10094;
              </button>
              <button class="text-gray-700 hover:text-gray-900 pointer-events-auto" id="next">
                &#10095;
              </button>
            </div> --}}
          </div>
          
          <!-- Indicators -->
          <div class="flex justify-center mt-4 space-x-2">
            <div class="w-2 h-2 bg-gray-400 rounded-full" id="indicator-1"></div>
            <div class="w-2 h-2 bg-gray-400 rounded-full" id="indicator-2"></div>
            <div class="w-2 h-2 bg-gray-400 rounded-full" id="indicator-3"></div>
          </div>

        {{-- <div x-data="{            
            autoplayIntervalTime: 4000,
            slides: [      
                {                    
                    imgSrc: 'images/Visual data-pana.png',                    
                    imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',  
                    title: 'Unlock Insightful Reports',
                    description: 'Taxuri Provides insightful reports that help you understand financial landscape.',            
                },        
                {                    
                    imgSrc: 'images/Audit-pana.png',                    
                    imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',  
                    title: 'Manage Taxes with Ease',
                    description: 'Taxuri ensures that managing taxes is straightforward and stress-free, so you can focus on what matters most.',            
                },                
                {                    
                    imgSrc: 'images/Data extraction-amico.png',                    
                    imgAlt: 'Vibrant abstract painting with swirling blue and purple hues on a canvas.',    
                    title: 'Smart Tax Solutions',
                    description: 'Where built-in predictive analytics, Taxuri delivers smart solutions that adapt to your needs, saving your time and effort.',       
                },            
            ],            
            currentSlideIndex: 1,
            isPaused: false,
            autoplayInterval: null,
            previous() {                
                if (this.currentSlideIndex > 1) {                    
                    this.currentSlideIndex = this.currentSlideIndex - 1                
                } else {           
                    this.currentSlideIndex = this.slides.length                
                }            
            },            
            next() {                
                if (this.currentSlideIndex < this.slides.length) {                    
                    this.currentSlideIndex = this.currentSlideIndex + 1                
                } else {                   
                    this.currentSlideIndex = 1                
                }            
            },    
            autoplay() {
                this.autoplayInterval = setInterval(() => {
                    if (! this.isPaused) {
                        this.next()
                    }
                }, this.autoplayIntervalTime)
            },
            // Updates interval time   
            setAutoplayInterval(newIntervalTime) {
                clearInterval(this.autoplayInterval)
                this.autoplayIntervalTime = newIntervalTime
                this.autoplay()
            },    
         }" x-init="autoplay" class="relative w-full overflow-hidden ">
           
            <!-- slides -->
            <div class=" min-h-[70svh] w-40 align-middle carousel-size">
                <template x-for="(slide, index) in slides">
                    <div x-cloak x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.1000ms>
                        
                        <!-- Title and description -->
                        <div class="lg:px-32 lg:py-14 absolute inset-0 z-10 flex flex-col items-center justify-end gap-2 bg-gradient-to-t from-slate-900/85 to-transparent px-16 py-12 text-center">
                            <h3 class="w-full lg:w-[80%] text-balance text-2xl lg:text-3xl font-bold text-white" x-text="slide.title" x-bind:aria-describedby="'slide' + (index + 1) + 'Description'"></h3>
                            <p class="lg:w-1/2 w-full text-pretty text-sm text-slate-300" x-text="slide.description" x-bind:id="'slide' + (index + 1) + 'Description'"></p>
                        </div>
        
                        <img class="absolute carousel-size w-full h-full inset-0 object-cover text-slate-700 dark:text-slate-300" x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt" />
                    </div>
                </template>
            </div>
            
            <!-- Pause/Play Button -->
            <button type="button" class="absolute bottom-5 right-5 z-20 rounded-full text-slate-300 opacity-50 transition hover:opacity-80 focus-visible:opacity-80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 active:outline-offset-0" aria-label="pause carousel" x-on:click="(isPaused = !isPaused), setAutoplayInterval(autoplayIntervalTime)" x-bind:aria-pressed="isPaused">
                <svg x-cloak x-show="isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-7">
                    <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm6.39-2.908a.75.75 0 0 1 .766.027l3.5 2.25a.75.75 0 0 1 0 1.262l-3.5 2.25A.75.75 0 0 1 8 12.25v-4.5a.75.75 0 0 1 .39-.658Z" clip-rule="evenodd">
                </svg>
                <svg x-cloak x-show="!isPaused" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-7">
                    <path fill-rule="evenodd" d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm5-2.25A.75.75 0 0 1 7.75 7h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Zm4 0a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1-.75-.75v-4.5Z" clip-rule="evenodd">
                </svg>
            </button>
            
            <!-- indicators -->
            <div class="absolute rounded-xl bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 px-1.5 py-1 md:px-2" role="group" aria-label="slides" >
                <template x-for="(slide, index) in slides">
                    <button class="size-2 cursor-pointer rounded-full transition" x-on:click="(currentSlideIndex = index + 1), setAutoplayInterval(autoplayIntervalTime)" x-bind:class="[currentSlideIndex === index + 1 ? 'bg-slate-300' : 'bg-slate-300/50']" x-bind:aria-label="'slide ' + (index + 1)"></button>
                </template>
            </div>
        </div> --}}
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
      resetAutoplay(); n
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
          indicator.classList.add('bg-blue-600');
          indicator.classList.remove('bg-gray-400');
        } else {
          indicator.classList.add('bg-gray-400');
          indicator.classList.remove('bg-blue-600');
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