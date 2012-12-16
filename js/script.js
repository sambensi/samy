window.addEvent('domready', function() {

    // Affichage de la tooltip sur les images
    var SimpleTip = new Tips($$('.simple_tip'), {
        showDelay: 0,
        hideDelay: 0,
        offsets: {x: 4, y: 4},
        fixed: false
    });

});

