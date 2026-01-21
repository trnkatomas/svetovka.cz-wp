(function() {
    // Find the "balíček" button by its href or text content
    const balicekLink = document.querySelector('a[href*="balicek-zeny-bezpravi"]');

    if (!balicekLink) {
        console.error('Balíček button not found');
        return;
    }

    // Find the burst-tray
    const burstTray = document.querySelector('.burst-tray');

    if (!burstTray) {
        console.error('Burst tray not found');
        return;
    }

    // Store reference to original parent (the li element) to remove later
    const originalContainer = balicekLink.closest('li.product');

    // Clone the link and modify it for the burst-tray
    const newButton = balicekLink.cloneNode(true);

    // Add burst-btn class, keep existing classes
    newButton.classList.add('burst-btn');

    // Shorten the text for better fit in the tray
    newButton.textContent = 'koupit v balíčku';

    // Insert as first child (will appear as furthest/3rd button due to RTL layout)
    burstTray.insertBefore(newButton, burstTray.firstChild);

    // Update transition delay for the new button order
    const buttons = burstTray.querySelectorAll('.burst-btn');
    buttons.forEach((btn, index) => {
        btn.style.transitionDelay = (0.05 * (buttons.length - 1 - index)) + 's';
    });

    // Increase max-width to fit all 3 buttons
    const style = document.createElement('style');
    style.textContent = '.burst-widget:hover .burst-tray { max-width: 700px; }';
    document.head.appendChild(style);

    // Hide the original container
    if (originalContainer) {
        originalContainer.style.display = 'none';
    }

    console.log('Balíček button moved to burst-tray successfully');
})();
