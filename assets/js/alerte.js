function notifManagment()
{
    let iconNotification = document.getElementById('icon-notification');
    let iconNotificationBtn = document.getElementById('icon-notification-button');
    let bodyAlerts = document.querySelector('.offcanvas-body');
    let linkAlerts = bodyAlerts.querySelectorAll('.fw-bold.link-alert');
    if (linkAlerts.length >= 1) {
        iconNotification.classList.remove('d-none');
        iconNotificationBtn.classList.remove('d-none');
    } else {
        iconNotification.classList.add('d-none');
        iconNotificationBtn.classList.add('d-none');
    }
}

function readAlert(event)
{
    event.preventDefault();

    const readlink = event.currentTarget;
    const link = readlink.href;
    try {
        fetch(link)
            .then(res => res.json())
            .then(data => {
                const alertIcon = readlink;
                if (data.state === false) {
                    const eyeIcon = document.getElementById('eye-' + data.id);
                    eyeIcon.classList.add("bi-eye-slash");
                    eyeIcon.classList.remove("bi-eye-fill");
                    alertIcon.classList.remove("fw-bold");
                    alertIcon.classList.add("fw-light");
                } else {
                    alertIcon.classList.remove("fw-light");
                    alertIcon.classList.add("fw-bold");
                }
                notifManagment();
            });
    } catch (err) {
        console.error(err);
    }
}

window.readAlert = readAlert;

function deleteAlert(event)
{
    event.preventDefault();

    const deleteLink = event.currentTarget;
    const link = deleteLink.href;

    try {
        fetch(link)
            .then(res => res.json())
            .then(data => {
                const alertRow = document.getElementById('alert-' + data.id);
                alertRow.remove();
            });
    } catch (err) {
        console.error(err);
    }
}

window.deleteAlert = deleteAlert;
notifManagment();
