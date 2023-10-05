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
            console.log(data);
            if (data.state === false) {
                alertIcon.classList.remove("fw-bold"); // Remove the .bi-heart (empty heart) from classes in <i> element
                alertIcon.classList.add("fw-light"); // Add the .bi-heart-fill (full heart) from classes in <i>
            } else {
                alertIcon.classList.remove("fw-light"); // Remove the .bi-heart (empty heart) from classes in <i> element
                alertIcon.classList.add("fw-bold"); // Add the .bi-heart-fill (full heart) from classes in <i>
            }
        });
    } catch (err) {
      // eslint-disable-next-line no-console
        console.error(err);
    }
}

window.readAlert = readAlert;
