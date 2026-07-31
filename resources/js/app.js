let html5QrCodeModulePromise;

// Load the scanner only when a user opens the QR scanner.
window.loadHtml5QrCode = () => {
    html5QrCodeModulePromise ??= import('html5-qrcode').then(module => module.Html5Qrcode);
    return html5QrCodeModulePromise;
};
