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
            if (data.getAlert) {
                alertIcon.classList.remove("font-weight-bold"); // Remove the .bi-heart (empty heart) from classes in <i> element
                alertIcon.classList.add("font-weight-light"); // Add the .bi-heart-fill (full heart) from classes in <i>
            } else {
                alertIcon.classList.remove("font-weight-light"); // Remove the .bi-heart-fill (full heart) from classes
                alertIcon.classList.add("font-weight-bold"); // Add the .bi-heart (empty heart) from classes in <i> element
            }
        });
    } catch (err) {
      // eslint-disable-next-line no-console
        console.error(err);
    }
}

window.readAlert(readAlert)
