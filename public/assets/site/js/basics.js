export async function toggleLoadingLine(show) {
    if (show) {
        await $('.loading-line').show();
    } else {
        await $('.loading-line').hide();
    }
}

// return the current url of the project
export function getUrl() {
    return siteUrl;
}

export async function sendPost(data, returnResponse = false, sendSwal = false, redirectOnSuccess = false, url = null) {
    // default values
    let status = 'error';
    let content = 'Something went wrong.';
    let response = [];

    // do ajax post
    try {
        await $.ajax({
            url: '_action.php',
            type: 'post',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (result) {
                status = result['status'];
                content = result['content'];
                response = result;
            },
            error: function (req, err) {
                console.log(err);
            }
        });

    } catch (error) {
        // log error
        console.warn(error);
    }

    // should send swal?
    if (sendSwal) {
        await showSwal(status, content);
    }

    // is success and redirect on success?
    if (status === 'success' && redirectOnSuccess) {
        if (url) {
            window.location.href = url;
        }
    }

    if (returnResponse) {
        return response;
    }

    // return true when success
    return status === 'success';
}

/** fires a new swal */
export async function showSwal(icon, title) {
    await Swal.fire({
        icon: icon,
        title: title,
    })
}

/**
 * @returns {*[]}
 */
export function getCurrentPage() {
    // get urls
    const paths = window.location.pathname.split("/");
    const params = window.location.href.split("?");

    // declare values
    const response = [];
    let page = null;
    let param = null;

    // default values
    response['page'] = 'index';
    response['params'] = ''

    // page not empty?
    if ((page = paths[paths.length - 1]) != null) {
        response['page'] = page;
    }

    // params not empty?
    if ((param = params[params.length - 1]) != null && params.length - 1 !== 0) {
        response['params'] = "&" + param;
    }

    // return array
    return response;
}