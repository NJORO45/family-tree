// ========================== GLOBAL APP OBJECT ==========================
window.myVoucherApp = window.myVoucherApp || {};
const app = window.myVoucherApp;

// ========================== DYNAMIC MODAL HTML ==========================
if(!document.getElementById('voucherModalContainer')) {
    const modalHtml = `
    <div id="voucherModalContainer" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;">
        <div style="background:#fff;padding:20px;border-radius:10px;max-width:400px;width:100%;position:relative;">
            <span id="closeVoucherModal" style="position:absolute;top:10px;right:15px;cursor:pointer;font-weight:bold;">&times;</span>
            <h2>Scan or Upload Voucher</h2>
            <div id="reader" style="width:250px;height:250px;margin:auto;"></div>
            <div style="margin-top:10px;">
                <input type="file" id="uploadVoucherFile" accept="image/*" />
            </div>
            <div id="voucherErrorMessage" style="color:red;margin-top:10px;text-align:center;display:none;"></div>
        </div>
    </div>`;
    const container = document.createElement('div');
    container.innerHTML = modalHtml;
    document.body.appendChild(container);
}

// ========================== DOM REFERENCES ==========================
app.voucherModalContainer = document.getElementById('voucherModalContainer');
app.voucherErrorMessage = document.getElementById('voucherErrorMessage');
app.closeVoucherModal = document.getElementById('closeVoucherModal');
app.uploadVoucherFile = document.getElementById('uploadVoucherFile');
app.html5QrCode = app.html5QrCode || null;

// ========================== UTILITY FUNCTIONS ==========================
window.postData = function(voucher) {
    const endpointUrl = '/applyvoucher'; // Replace with your API
    const body = JSON.stringify({ voucherId: voucher, ticketID: 'KCC236V' });

    fetch(endpointUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: body
    })
    .then(res => res.json())
    .then(data => {
        if(data.errorCode == 0){
            app.voucherErrorMessage.style.color = 'green';
            app.voucherErrorMessage.textContent = "Voucher Applied Successfully!";
        } else {
            app.voucherErrorMessage.style.color = 'red';
            app.voucherErrorMessage.textContent = "Voucher is not valid. (" + data.errorCode + ")";
        }
        app.voucherErrorMessage.style.display = 'block';
    })
    .catch(err => {
        app.voucherErrorMessage.style.color = 'red';
        app.voucherErrorMessage.textContent = 'Error validating voucher.';
        app.voucherErrorMessage.style.display = 'block';
        console.error(err);
    });
};

// ========================== QR CODE FUNCTIONS ==========================
window.startCamera = function() {
    if(app.html5QrCode) app.html5QrCode.clear().catch(() => {});
    app.html5QrCode = new Html5Qrcode("reader");

    app.html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 }, supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA, Html5QrcodeScanType.SCAN_TYPE_FILE] },
        (decodedText, decodedResult) => {
            app.voucherErrorMessage.style.display = 'block';
            app.voucherErrorMessage.style.color = 'green';
            app.voucherErrorMessage.textContent = "Validating voucher...";
            stopCamera();
            postData(decodedText);
        },
        errorMessage => console.log("QR Error:", errorMessage)
    ).catch(err => console.error("Unable to start camera:", err));
};

window.stopCamera = function() {
    if(app.html5QrCode) {
        app.html5QrCode.stop().then(() => console.log("Camera stopped")).catch(err => console.error(err));
    }
};

// ========================== EVENT LISTENERS ==========================
app.closeVoucherModal.addEventListener('click', () => {
    stopCamera();
    app.voucherModalContainer.style.display = 'none';
});

app.uploadVoucherFile.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if(file) {
        if(app.html5QrCode) app.html5QrCode.clear().catch(() => {});
        app.html5QrCode = new Html5Qrcode("reader");
        app.html5QrCode.scanFile(file, true)
        .then(decodedText => {
            app.voucherErrorMessage.style.display = 'block';
            app.voucherErrorMessage.style.color = 'green';
            app.voucherErrorMessage.textContent = "Validating voucher...";
            postData(decodedText);
        })
        .catch(err => {
            app.voucherErrorMessage.style.display = 'block';
            app.voucherErrorMessage.style.color = 'red';
            app.voucherErrorMessage.textContent = "Unable to decode QR from file.";
            console.error(err);
        });
    }
});

// ========================== SHOW MODAL ==========================
window.showVoucherModal = function() {
    app.voucherModalContainer.style.display = 'flex';
    app.voucherErrorMessage.style.display = 'none';
    startCamera();
};

// ========================== USAGE ==========================
// Call showVoucherModal() to open the modal
// Example: showVoucherModal();
