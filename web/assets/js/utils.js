export async function showSwal(title, text, icon = 'error') {
    // icons: warning, error, success, info, and question
    await Swal.fire({
        icon: icon,
        title: title,
        text: text,
        background: '#0c0c0c',
        color: '#fff',
    });

    return false;
}

export function toggleLoadingLine(show = true) {
    const loadingLine = document.querySelector('.swapper-loading-line');

    if (loadingLine) {
        if (show) {
            loadingLine.style.display = 'block';
        } else {
            loadingLine.style.display = 'none';
        }
    }
}

export function log(text, type = 'log', info = null) {
    if (!window['debugMode']) {
        return;
    }

    const date = new Date().timeNow();
    switch (type) {
        case 'info':
            console.info(date + ' - ' + text);
            break;
        case 'warn':
            console.warn(date + ' - ' + text);
            break;
        case 'error':
            console.error(date + ' - ' + text);
            break;
        default:
            console.log(date + ' - ' + text);
            break;
    }
}

Date.prototype.timeNow = function () {
    return ((this.getHours() < 10) ? "0" : "") + this.getHours() + ":" + ((this.getMinutes() < 10) ? "0" : "") + this.getMinutes() + ":" + ((this.getSeconds() < 10) ? "0" : "") + this.getSeconds();
}