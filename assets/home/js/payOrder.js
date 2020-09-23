'use strict';
function toogleElements(showId, hideId, className) {
    document.getElementById(showId).classList.toggle(className)
    document.getElementById(hideId).classList.toggle(className)
}

function redirect(url) {
    window.location.href = url;
}

function voucherPay(codeId) {    
    let codeElement = document.getElementById(codeId);
    let code = codeElement.value;

    if (code.trim()) {
        let post = {
            'code' : code,
            'amount' : codeElement.dataset.total
        }
        let url = globalVariables.ajax + 'voucherPay';
        sendAjaxPostRequest(post, url, 'voucherPay', voucherResponse);
    } else {
        alertify.error('Code is required');
    }
}

function voucherResponse(data) {
    if (data['status'] === '0') {
        alertify.error(data['message']);
        return;
    } else if (data['status'] === '2') {
        alertify.success(data['message']);
        $('.voucher').css('display', 'block');
        document.getElementById('voucherAmount').innerHTML = data['voucherAmount'];
        document.getElementById('leftAmount').innerHTML = data['leftAmount'];
        return;
    }
    redirect(data['redirect']);
}
