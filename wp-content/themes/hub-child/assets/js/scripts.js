(function ($) {
    
    var owl = $("#mission-carousel");
    var owl_tabs = $(".team-tabs .e-n-tabs-heading");
    var owl_tabs_2 = $(".team-tabs-2 .e-n-tabs-content");
    var owl_portfolio = $(".portfolio-flex-grid");
    var breakpoint = 768; // Définissez votre breakpoint mobile (ex: 768px pour les tablettes en portrait)

    function initializeOwlCarousel() {
        if (window.innerWidth < breakpoint && !owl.hasClass("owl-loaded")) {
            // Initialiser Owl Carousel si l\'écran est mobile et qu\'il n\'est pas déjà initialisé
            owl.owlCarousel({
                loop: true,
                margin: 22,
                nav: false,
                dots: false,
                responsive:{
                    0:{
                        items:1.5
                    },
                    // Vous pouvez affiner les breakpoints ici pour mobile si nécessaire
                    // Par exemple, si vous voulez 2 items sur des mobiles plus larges
                    480:{
                        items:2.5
                    }
                    // Pas besoin de définir des items pour les écrans > breakpoint ici,
                    // car le carrousel sera détruit
                }
            });
        } else if (window.innerWidth >= breakpoint && owl.hasClass("owl-loaded")) {
            // Détruire Owl Carousel si l\'écran est desktop et qu\'il est initialisé
            owl.owlCarousel("destroy");
        }


        if (window.innerWidth < breakpoint && !owl_tabs.hasClass("owl-loaded")) {
            owl_tabs.addClass('owl-carousel');
            // Initialiser Owl Carousel si l\'écran est mobile et qu\'il n\'est pas déjà initialisé
            owl_tabs.owlCarousel({
                loop: true,
                margin: 0,
                nav: true,
                navText: ["<img src='/wp-content/themes/hub-child/assets/img/arrow-left-rounded.svg'>", "<img src='/wp-content/themes/hub-child/assets/img/arrow-right-rounded.svg'>"],
                dots: false,
                responsive:{
                    0:{
                        items:1
                    }
                }
            });
            owl_tabs.on('changed.owl.carousel', function(event) {
                //owl_tabs.find('.owl-item.active>button').trigger( "click" );
                var currentSlideIndex = event.item.index;
                var activeSlide = $(event.target).find('.owl-item').eq(currentSlideIndex);
                var buttonInsideActiveSlide = activeSlide.find('.e-n-tab-title'); // Replace with your button's class or ID
                
                // Trigger the click event on the button
                if (buttonInsideActiveSlide.length) { // Check if the button exists
                    buttonInsideActiveSlide.trigger('click');
                }
            })
        } else if (window.innerWidth >= breakpoint && owl_tabs.hasClass("owl-loaded")) {
            // Détruire Owl Carousel si l\'écran est desktop et qu\'il est 
            owl_tabs.removeClass('owl-carousel');
            owl_tabs.owlCarousel("destroy");
        }

        if (window.innerWidth < breakpoint && !owl_tabs_2.hasClass("owl-loaded")) {
            owl_tabs_2.addClass('owl-carousel');
            // Initialiser Owl Carousel si l\'écran est mobile et qu\'il n\'est pas déjà initialisé
            owl_tabs_2.owlCarousel({
                loop: true,
                margin: 0,
                nav: true,
                navText: ["<img src='/wp-content/themes/hub-child/assets/img/arrow-left-rounded.svg'>", "<img src='/wp-content/themes/hub-child/assets/img/arrow-right-rounded.svg'>"],
                dots: false,
                responsive:{
                    0:{
                        items:1
                    }
                }
            });
            
        } else if (window.innerWidth >= breakpoint && owl_tabs_2.hasClass("owl-loaded")) {
            // Détruire Owl Carousel si l\'écran est desktop et qu\'il est 
            owl_tabs_2.removeClass('owl-carousel');
            owl_tabs_2.owlCarousel("destroy");
        }

        if (window.innerWidth < breakpoint && !owl_portfolio.hasClass("owl-loaded")) {
            owl_portfolio.addClass('owl-carousel');
            // Initialiser Owl Carousel si l\'écran est mobile et qu\'il n\'est pas déjà initialisé
            owl_portfolio.owlCarousel({
                loop: true,
                margin: 0,
                nav: false,
                dots: false,
                autoHeight:true,
                responsive:{
                    0:{
                        items:1
                    }
                }
            });
        } else if (window.innerWidth >= breakpoint && owl_portfolio.hasClass("owl-loaded")) {
            // Détruire Owl Carousel si l\'écran est desktop et qu\'il est 
            owl_portfolio.removeClass('owl-carousel');
            owl_portfolio.owlCarousel("destroy");
        }
    }

    // Appeler la fonction au chargement de la page
    initializeOwlCarousel();

    // Appeler la fonction lors du redimensionnement de la fenêtre
    $(window).on("resize", function() {
        initializeOwlCarousel();
    });
    
})(jQuery);