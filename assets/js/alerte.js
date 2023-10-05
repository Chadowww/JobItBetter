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
