// Define config variables

const cfg = {
    selectors: {
        canvas: {
            nightSky: 'canvas.nightSky'
        },
        container: {
            animateTitlesFadeUp: '.container__animate--fade-up',
            inner: '.e-con-inner',
            nightSky: '.container__night-sky',
        },
        projects: {
            related: '.portfolio__list--related',
        },
        year: {
            current: '.current_year',
        },
        swiper: '.swiper-container',
    },
    classes: {
        nightSky: 'nightSky',
        active: 'active',
        flexRowInverted: 'flex__row--inverted'
    }
}

/**
 * Common functions
 */

function isCatalan() {
    return document.documentElement.lang == 'ca';
}

/**
 * Classes
 */

/** Basic class */
class BasicComponent {
    constructor(el) {
        this.el = el;
    }
}

/** Class for Night Sky Containers */
class NightSky extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        this.createCanvas();
        this.populateCanvas();
        this.addScrollButton();
    }

    createCanvas() {
        const canvas = document.createElement('canvas');
        canvas.className = cfg.classes.nightSky;
        this.el.appendChild(canvas);
    }

    populateCanvas() {
        const backgroundColor = "transparent";
        const maxStarRadius = 1.5;
        const canvas = this.el.querySelector(cfg.selectors.canvas.nightSky);
        const ctx = canvas.getContext("2d");
        const sizes = Math.max(this.el.offsetWidth, this.el.offsetHeight) * 1.2;
        const width = sizes;
        const height = sizes;
        canvas.width = width;
        canvas.height = height;

        function randomInt(max) {
            return Math.floor(Math.random() * max);
        }

        function createStars(width, height, spacing) {
            const stars = [];
            for (let x = 0; x < width; x += spacing) {
                for (let y = 0; y < height; y += spacing) {
                    const star = {
                        x: x + randomInt(spacing),
                        y: y + randomInt(spacing),
                        r: Math.random() * maxStarRadius
                    };
                    stars.push(star);
                }
            }
            return stars;
        }

        const stars = createStars(width, height, 30);

        function fillCircle(ctx, x, y, r, fillStyle) {
            ctx.beginPath();
            ctx.fillStyle = fillStyle;
            ctx.arc(x, y, r, 0, Math.PI * 2);
            ctx.fill();
        }

        function render() {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, width, height);
            stars.forEach(function(star) {
                const x = star.x;
                const y = star.y;
                const r = star.r;
                fillCircle(ctx, x, y, r, "rgb(255, 255, 255)");
            });
        }
        render();
    }

    addScrollButton() {
        const img = document.createElement('img');
        img.src = '/wp-content/uploads/2025/03/scroll_down-white.svg';
        img.setAttribute('alt', 'Scroll down icon');
        const container = document.createElement('a');
        container.classList.add('icon__scroll-down');
        container.href = '#about';
        container.appendChild(img);
        this.el.appendChild(container);
    }
}

/** Class for Animate Fade Up Containers */
class AnimateFadeUp extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        window.addEventListener('load', () => {
            if (!this.isEmpty()) {
                this.createActiveLoop();
                if (isCatalan()) {
                    this.el.parentNode.classList.add(cfg.classes.flexRowInverted);
                }
            }
        });
    }

    isEmpty() {
        return this.el.innerHTML === '';
    }

    createActiveLoop() {
        const elementList = this.el.querySelectorAll('.word');
        this.addActiveClass(elementList[0]);
        let indexCount = 1;

        setInterval(() => {
            this.removeActiveClass(elementList);
            this.addActiveClass(elementList[indexCount]);

            // Change to next index:
            indexCount++
            if (indexCount === elementList.length) {
                indexCount = 0;
            }
        }, 3000);
    }

    removeActiveClass(elementList) {
        elementList.forEach(element => {
            element.classList.remove(cfg.classes.active);
        });
    }

    addActiveClass(element) {
        element.classList.add(cfg.classes.active);
    }
}

/** Class for current year tags */
class CurrentYear extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        this.el.innerText = new Date().getFullYear();
    }
}

/** Swiper - Related Projects */
class RelatedProjects extends BasicComponent {
  constructor(el) {
    super(el);
    this.init();
}

init() {
    this.setRefs();
    this.initiateSwiper();
}

setRefs() {
    this.swiperContainer = this.el;
    this.params = {
        navigation: {
        prevEl: '.swiper-button-prev',
        nextEl: '.swiper-button-next',
        },
        mousewheel: true,
        pagination: {
            el: '.swiper-pagination',
            dynamicBullets: true,
        },
        scrollbar: {
            enabled: false,
        },
        keyboard: {
            enabled: true,
        },
        spaceBetween: 10,
        breakpoints: {
        0: {
            slidesPerView: 2,
        },
        600: {
            slidesPerView: 3,
        },
        992: {
            slidesPerView: 4,
        }
        },
    };
  }

  initiateSwiper() {
    this.swiper = new Swiper(this.swiperContainer, this.params);
  }
}



/**
 * Initialize first load class instances
 */

function initcustomClasses () {
    /* Find and initialize every instance of container with night sky bg */
    document.querySelectorAll(cfg.selectors.container.nightSky).forEach(nightSkyContainer => {
        const NIGHTSKY = new NightSky(nightSkyContainer);
    });

    /* Find and initialize every instance of container with Animate Fade Up */
    document.querySelectorAll(cfg.selectors.container.animateTitlesFadeUp).forEach(animateContainer => {
        const ANIMATEFADEUP = new AnimateFadeUp(animateContainer);
    });

    /* Find and initialize every instance of current_year class */
    document.querySelectorAll(cfg.selectors.year.current).forEach(yearSpan => {
        const CURRENTYEAR = new CurrentYear(yearSpan);
    });

    /* find and initiate carousel in Related Projects area */
    document.querySelectorAll(cfg.selectors.projects.related).forEach(relatedProjects => {
        const RELATEDPROJECTS = new RelatedProjects(relatedProjects);
    });
}

initcustomClasses();

/**
 * If Category or Tag page
 */

const isCatPage = document.body.classList.contains('category');
const isTagPage = document.body.classList.contains('tag');

if (isCatPage || isTagPage) {
    const name = document.head.querySelector('title').innerText.split('– eduvallve')[0].trim();
    // Know if "Category" or "Tag"
    const prefix = isCatPage ? 'Category' : 'Tag';
    // Create title element
    const title = document.createElement('h1');
    title.classList.add('entry-title');
    title.innerText = `${prefix}: "${name}"`;
    const box = document.createElement('div');
    box.classList.add('page-title');
    // Place title element in the DOM
    box.appendChild(title);
    document.querySelector('#content').prepend(box);
}